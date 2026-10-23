<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Listing;
use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CrmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.crm.index');
    }

    private function businessColumn($name, $listingUrl, $createdAt)
    {
        $html = '<a href="' . $listingUrl . '">' . e($name) . '</a>';
        if ($createdAt) {
            $html .= '<br><small class="text-muted">' . __('Created') . ': ' . $createdAt->format('d M Y, h:i A') . '</small>';
        }
        return $html;
    }

    private function phoneColumn($userPhone, $listingPhone)
    {
        $html = '<div>' . __('User ph') . ': ' . ($userPhone ?: '-') . '</div>';
        $html .= '<div>' . __('Listing ph') . ': ' . ($listingPhone ?: '-') . '</div>';
        return $html;
    }

    /**
     * Non-paid customers who have not yet been called / have no status set.
     */
    public function toCallDatatables(Request $request)
    {
        $query = Listing::query()
            ->with('user')
            ->where(function ($q) {
                $q->whereNull('plan_id')
                    ->orWhereHas('plan', function ($q2) {
                        $q2->where('plan_type', 'free');
                    });
            })
            ->whereHas('user')
            ->whereDoesntHave('leadFollowup', function ($q) {
                $q->whereNotNull('status');
            })
            ->orderBy('listings.id', 'desc');

        return Datatables::of($query)
            ->addColumn('user_id', function (Listing $l) {
                return $l->user->id ?? null;
            })
            ->addColumn('user_name', function (Listing $l) {
                return $l->user->name ?? 'N/A';
            })
            ->addColumn('customer_link', function (Listing $l) {
                return $l->user ? route('admin-user-show', $l->user->id) : '#';
            })
            ->addColumn('business_name', function (Listing $l) {
                return $this->businessColumn($l->name, route('admin.listing.view', $l->id), $l->created_at);
            })
            ->addColumn('phone', function (Listing $l) {
                return $this->phoneColumn($l->user->phone ?? null, $l->phone_number ?? null);
            })
            ->addColumn('listing_id', function (Listing $l) {
                return $l->id;
            })
            ->addColumn('action', function (Listing $l) {
                return '<button type="button" class="btn btn-primary btn-sm set-status-btn" data-listing="' . $l->id . '">' . __('Set Status') . '</button>';
            })
            ->rawColumns(['action', 'business_name', 'phone'])
            ->toJson();
    }

    /**
     * Leads still being nurtured: Interested / Next Day Recall.
     */
    public function followUpDatatables(Request $request)
    {
        return $this->leadFollowupDatatables($request, ['interested', 'next_day_recall'], true);
    }

    /**
     * Leads whose journey has ended: Paid (converted) / Not Interested (rejected).
     */
    public function endJourneyDatatables(Request $request)
    {
        return $this->leadFollowupDatatables($request, ['paid', 'not_interested'], false);
    }

    private function leadFollowupDatatables(Request $request, array $allowedStatuses, bool $showRecall)
    {
        $query = LeadFollowup::query()
            ->with(['user', 'listing'])
            ->whereIn('status', $allowedStatuses);

        if ($request->filled('status_filter') && $request->status_filter !== 'all' && in_array($request->status_filter, $allowedStatuses, true)) {
            $query->where('status', $request->status_filter);
        }

        $query->orderBy('updated_at', 'desc');

        $table = Datatables::of($query)
            ->addColumn('user_id', function (LeadFollowup $f) {
                return $f->user->id ?? null;
            })
            ->addColumn('user_name', function (LeadFollowup $f) {
                return $f->user->name ?? 'N/A';
            })
            ->addColumn('customer_link', function (LeadFollowup $f) {
                return $f->user ? route('admin-user-show', $f->user->id) : '#';
            })
            ->addColumn('business_name', function (LeadFollowup $f) {
                if (!$f->listing) {
                    return 'N/A';
                }
                return $this->businessColumn($f->listing->name, route('admin.listing.view', $f->listing->id), $f->listing->created_at);
            })
            ->addColumn('phone', function (LeadFollowup $f) {
                return $this->phoneColumn($f->user->phone ?? null, $f->listing->phone_number ?? null);
            })
            ->addColumn('listing_id', function (LeadFollowup $f) {
                return $f->listing_id;
            })
            ->addColumn('status_badge', function (LeadFollowup $f) {
                $map = [
                    'interested'       => ['label' => __('Interested'), 'class' => 'info'],
                    'not_interested'   => ['label' => __('Not Interested'), 'class' => 'danger'],
                    'next_day_recall'  => ['label' => __('Next Day Recall'), 'class' => 'warning'],
                    'paid'             => ['label' => __('Paid'), 'class' => 'success'],
                ];
                $s = $map[$f->status] ?? ['label' => $f->status, 'class' => 'secondary'];

                return '<span class="badge badge-' . $s['class'] . '">' . $s['label'] . '</span>';
            })
            ->addColumn('comment', function (LeadFollowup $f) {
                return $f->comment ? e($f->comment) : '-';
            })
            ->addColumn('last_called', function (LeadFollowup $f) {
                return $f->last_called_at ? $f->last_called_at->format('d M Y, h:i A') : '-';
            })
            ->addColumn('next_recall', function (LeadFollowup $f) {
                return $f->next_recall_date ? $f->next_recall_date->format('d M Y') : '-';
            })
            ->addColumn('action', function (LeadFollowup $f) {
                return '<button type="button" class="btn btn-primary btn-sm set-status-btn"'
                    . ' data-listing="' . $f->listing_id . '"'
                    . ' data-status="' . e($f->status) . '"'
                    . ' data-comment="' . e($f->comment) . '"'
                    . ' data-recall="' . e($f->next_recall_date ? $f->next_recall_date->format('Y-m-d') : '') . '"'
                    . '>' . __('Update') . '</button>';
            })
            ->rawColumns(['action', 'status_badge', 'business_name', 'phone']);

        return $table->toJson();
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'listing_id'        => 'required|exists:listings,id',
            'status'            => 'required|in:interested,not_interested,next_day_recall,paid',
            'comment'           => 'nullable|string',
            'next_recall_date'  => 'nullable|date',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        LeadFollowup::updateOrCreate(
            ['listing_id' => $listing->id],
            [
                'user_id'            => $listing->user_id,
                'status'             => $request->status,
                'comment'            => $request->comment,
                'next_recall_date'   => $request->status === 'next_day_recall' ? $request->next_recall_date : null,
                'last_called_at'     => now(),
                'called_by_admin_id' => Auth::guard('admin')->id(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => __('Lead status updated successfully.'),
        ]);
    }
}
