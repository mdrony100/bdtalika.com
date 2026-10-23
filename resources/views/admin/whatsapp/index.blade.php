@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('WhatsApp Messaging') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('WhatsApp Messaging') }}</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'pending' ? 'active' : '' }}"
                                href="{{ route('admin.whatsapp.index', ['tab' => 'pending']) }}">
                                {{ __('Pending / Eligible') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'history' ? 'active' : '' }}"
                                href="{{ route('admin.whatsapp.index', ['tab' => 'history']) }}">
                                {{ __('Message History') }}
                            </a>
                        </li>
                    </ul>

                    @if ($tab === 'pending')
                        <div class="mb-3">
                            <strong>{{ __('Automatic cutoff') }}:</strong>
                            {{ config('services.meta_whatsapp.abandoned_start_at', '2026-09-15 00:00:00') }}
                            <span class="text-muted ml-2">{{ __('Cron runs every 30 minutes and sends within 29-60 minutes.') }}</span>
                        </div>
                        <p class="alert alert-info mb-3">
                            <strong>{{ __('Eligibility Logic') }}:</strong>
                            {{ __('Automatic reminders send only when') }}
                            <code>listing.created_at &gt;= {{ config('services.meta_whatsapp.abandoned_start_at', '2026-09-15 00:00:00') }}</code>,
                            {{ __('the listing is at least') }} {{ config('services.meta_whatsapp.abandoned_min_age_minutes', 30) }} {{ __('minutes old') }},
                            <code>status = 0</code>,
                            {{ __('there is no completed bKash payment, no successful transaction, the plan is not free, the user has WhatsApp opt-in, and a valid phone exists') }}
                            ({{ __('preferring') }} <code>users.phone</code>).
                            {{ __('A successful automatic send is checked from') }} <code>whatsapp_messages</code>
                            {{ __('using') }} <code>listing_id</code>, <code>purpose = abandoned_payment_reminder</code>,
                            <code>source = automatic</code>, {{ __('and') }} <code>status = sent</code>.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Listing ID') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Business') }}</th>
                                        <th>{{ __('WhatsApp') }}</th>
                                        <th>{{ __('Created') }}</th>
                                        <th>{{ __('Age') }}</th>
                                        <th>{{ __('Payment') }}</th>
                                        <th>{{ __('Consent') }}</th>
                                        <th>{{ __('Reminder') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pendingListings as $listing)
                                        @php
                                            $messages = $listing->whatsAppMessages
                                                ->where('purpose', \App\Services\WhatsAppAbandonedPaymentReminderService::PURPOSE);
                                            $automaticSent = $messages->where('source', 'automatic')->where('status', 'sent')->isNotEmpty();
                                            $manualSent = $messages->where('source', 'manual')->where('status', 'sent')->isNotEmpty();
                                            $latestMessage = $messages->sortByDesc('created_at')->first();
                                            $phone = \App\Services\BangladeshPhoneService::normalize(optional($listing->user)->phone)
                                                ?: \App\Services\BangladeshPhoneService::normalize($listing->whatsapp_number);
                                        @endphp
                                        <tr>
                                            <td>#{{ $listing->id }}</td>
                                            <td>{{ optional($listing->user)->name ?: '-' }}</td>
                                            <td>{{ $listing->name }}</td>
                                            <td>{{ $phone ?: '-' }}</td>
                                            <td>{{ optional($listing->created_at)->format('d M Y, h:i A') }}</td>
                                            <td>{{ optional($listing->created_at)->diffForHumans(null, true) }}</td>
                                            <td><span class="badge badge-warning">{{ __('Unpaid') }}</span></td>
                                            <td>
                                                @if (optional($listing->user)->whatsapp_opt_in)
                                                    <span class="badge badge-success">{{ __('Yes') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('No') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($automaticSent)
                                                    <span class="badge badge-success">{{ __('Automatic Sent') }}</span>
                                                @elseif ($manualSent)
                                                    <span class="badge badge-success">{{ __('Manual Sent') }}</span>
                                                @elseif ($latestMessage)
                                                    <span class="badge badge-secondary">{{ ucfirst($latestMessage->status) }}</span>
                                                @else
                                                    <span class="badge badge-light">{{ __('None') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.whatsapp.send', $listing->id) }}"
                                                    onsubmit="return confirm('{{ $automaticSent ? __('An automatic reminder was already sent. Send another manual WhatsApp reminder intentionally?') : __('Send WhatsApp payment reminder now?') }}');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fab fa-whatsapp"></i> {{ __('Send WhatsApp Reminder') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">{{ __('No eligible abandoned-payment listings found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="px-3 pb-3">
                            {{ $pendingListings->links() }}
                        </div>
                    @else
                        <form method="GET" action="{{ route('admin.whatsapp.index') }}" class="form-inline mb-3">
                            <input type="hidden" name="tab" value="history">
                            <input type="text" name="q" class="form-control mr-2 mb-2" value="{{ request('q') }}"
                                placeholder="{{ __('Search customer, listing, phone, Meta ID') }}">
                            <select name="source" class="form-control mr-2 mb-2">
                                <option value="">{{ __('All Sources') }}</option>
                                <option value="automatic" {{ request('source') === 'automatic' ? 'selected' : '' }}>{{ __('Automatic') }}</option>
                                <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>{{ __('Manual') }}</option>
                            </select>
                            <select name="status" class="form-control mr-2 mb-2">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>{{ __('Sent') }}</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            </select>
                            <button type="submit" class="btn btn-primary mb-2">{{ __('Filter') }}</button>
                            <a href="{{ route('admin.whatsapp.index', ['tab' => 'history']) }}" class="btn btn-secondary ml-2 mb-2">{{ __('Reset') }}</a>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Business') }}</th>
                                        <th>{{ __('Recipient') }}</th>
                                        <th>{{ __('Template') }}</th>
                                        <th>{{ __('Purpose') }}</th>
                                        <th>{{ __('Source') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Meta Message ID') }}</th>
                                        <th>{{ __('Error') }}</th>
                                        <th>{{ __('Admin') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($history as $message)
                                        <tr>
                                            <td>{{ $message->created_at->format('d M Y, h:i A') }}</td>
                                            <td>{{ optional($message->user)->name ?: '-' }}</td>
                                            <td>
                                                @if ($message->listing_id)
                                                    #{{ $message->listing_id }} - {{ optional($message->listing)->name ?: '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $message->recipient_phone ?: '-' }}</td>
                                            <td>{{ $message->template_name }}</td>
                                            <td>{{ ucwords(str_replace('_', ' ', $message->purpose)) }}</td>
                                            <td>{{ ucfirst($message->source) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $message->status === 'sent' ? 'success' : ($message->status === 'failed' ? 'danger' : 'secondary') }}">
                                                    {{ ucfirst($message->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $message->meta_message_id ?: '-' }}</td>
                                            <td>{{ $message->error_message ?: '-' }}</td>
                                            <td>{{ $message->admin_id ? optional($message->admin)->name : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">{{ __('No WhatsApp message history found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="px-3 pb-3">
                            {{ $history->links() }}
                        </div>
                    @endif

                    @if ($cronLog)
                        <div class="alert alert-info mt-3 mb-0">
                            <strong>{{ __('WhatsApp Abandoned Payment Reminder') }}:</strong>
                            {{ __('Last daily row') }} {{ optional($cronLog->run_date)->format('d M Y') ?: '-' }},
                            {{ __('runs') }} {{ (int) $cronLog->run_count }},
                            {{ __('sent') }} {{ (int) $cronLog->total_sent }},
                            {{ __('failed') }} {{ (int) $cronLog->total_failed }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
