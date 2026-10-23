<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Datatables;

class PlanController extends Controller
{
    public $service;
    public function __construct(PlanService $planService)
    {
        $this->middleware('auth:admin');
        $this->service = $planService;
    }

    public function datatables()
    {
        $datas = Plan::orderBy('id', 'desc')->get();

        return Datatables::of($datas)
            ->editColumn('price', function (Plan $data) {
                return showAdminAmount($data->price);
            })
            ->editColumn('post_duration', function (Plan $data) {
                return $data->post_duration . ' Days';
            })
            ->addColumn('visibility', function (Plan $data) {
                return $data->is_private ? __('Private') : __('Public');
            })
            ->addColumn('status', function (Plan $data) {
                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-' . $status_sign . ' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ' . $status . '
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.plans.status', ['id1' => $data->id, 'id2' => 1]) . '">' . __("Activate") . '</a>
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.plans.status', ['id1' => $data->id, 'id2' => 0]) . '">' . __("Deactivate") . '</a>
                                </div>
                            </div>';
            })
            ->addColumn('action', function (Plan $data) {

                return '<div class="btn-group mb-1">
                                  <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ' . 'Actions' . '
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start">
                                    <a href="' . route('admin.plans.edit', $data->id) . '"  class="dropdown-item">' . __("Edit") . '</a>
                                  </div>
                                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.plans.index');
    }

    public function create()
    {
        $data['currency'] = defaultCurrency();
        return view('admin.plans.create', $data);
    }

    public function store(PlanRequest $request)
    {
        $plan                   = new Plan();
        $plan->title            = $request->title;
        $plan->price            = adminCurrencyAmount($request['price']);
        $plan->regular_price    = $request->filled('regular_price') ? adminCurrencyAmount($request['regular_price']) : null;
        $plan->sorting          = $request->sorting ?? 0;
        $plan->plan_type        = $request->plan_type;
        $plan->status           = $request->status; 
        $plan->is_private       = $request->is_private ?? 0;
        $plan->post_duration    = $request->post_duration;
        $plan->google_review    = $request->google_review ?? 0;
        $plan->ai_assistant     = $request->ai_assistant ?? 0;
        $plan->number_of_photos = $request->number_of_photos;
        $plan->video_url        = $request->video_url ?? 0;
        $plan->google_map       = $request->google_map ?? 0;
        $plan->keywords         = $request->keywords;
        $plan->features         = $request->features ?? 0;
        $plan->listing          = $request->listing ?? 0;
        $plan->home             = $request->home ?? 0;
        $plan->follow           = $request->follow ?? 0;
        $plan->analytics        = $request->analytics ?? 0;
        $plan->indexing         = $request->indexing ?? 0;
        $plan->notification     = $request->notification ?? 0;
        $plan->public_profile   = $request->public ?? 0;
        if ($request['attribute']) {
            $plan->attribute = json_encode($request['attribute'], true);
        }
        $plan->save();
        $msg = 'New Data Added Successfully.' . ' ' . '<a href="' . route('admin.plans.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data['data'] = Plan::findOrFail($id);
        $data['currency'] = defaultCurrency();
        $data['attributes'] = $data['data']->attribute != NULL ? json_decode($data['data']->attribute, true) : [];

        return view('admin.plans.edit', $data);
    }

    public function update(PlanRequest $request, $id)
    {
        $plan                   = Plan::findOrFail($id);
        $plan->title            = $request->title; 
        $plan->price            = adminCurrencyAmount($request->price);
        $plan->regular_price    = $request->filled('regular_price') ? adminCurrencyAmount($request->regular_price) : null;
        $plan->sorting          = $request->sorting ?? 0;
        $plan->status           = $request->status;
        $plan->is_private       = $request->is_private ?? 0;
        $plan->plan_type        = $request->plan_type;
        $plan->post_duration    = $request->post_duration;
        $plan->google_review    = $request->google_review ?? 0;
        $plan->ai_assistant     = $request->ai_assistant ?? 0;
        $plan->number_of_photos = $request->number_of_photos;
        $plan->video_url        = $request->video_url ?? 0;
        $plan->google_map       = $request->google_map ?? 0;
        $plan->keywords         = $request->keywords;
        $plan->features         = $request->features ?? 0;
        $plan->listing          = $request->listing ?? 0;
        $plan->home             = $request->home ?? 0;
        $plan->follow           = $request->follow ?? 0;
        $plan->analytics        = $request->analytics ?? 0;
        $plan->indexing         = $request->indexing ?? 0;
        $plan->notification     = $request->notification ?? 0;
        $plan->public_profile   = $request->public ?? 0;
        if($request->attribute){
            $plan->attribute = json_encode($request->attribute,true);
        }
        $plan->update();
        $msg = 'Data Updated Successfully.' . ' ' . '<a href="' . route('admin.plans.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function status($id1, $id2)
    {
        $this->service->status($id1, $id1);

        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function bulkdelete(Request $request)
    {
        $this->service->bulkdelete($request->bulk_id);

        return response()->json("Data Deleted Successfully");
    }

    public function destroy($id)
    {
        $this->service->destroy($id);

        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}
