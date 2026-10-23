@extends('layouts.admin')
@section('styles')
    <style>
        .sub-categori .modal .modal-dialog {
            position: fixed !important;
            bottom: 0 !important;
            right: 0;
            width: 600px !important;
            left: 0;
            margin: 0 auto !important;
        }

        .note-editable.card-block {
            height: 322px;
        }

        .note-toolbar.card-header {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Directories') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('All Directories') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            {{-- @include('includes.admin.form-success') --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Filter Directories</h5>
                    <form action="{{ route('admin.listing.pending') }}" id="listingForm" method="get">
                        <div class="row d-flex align-items-center">
                            <div class="col-sm-5 col-xl-4">
                                <label for="plan" class="form-label">Plan</label>
                                <select name="plan" id="plan" class="form-control">
                                    <option value="" class="d-none">Select</option>
                                    <option value="free" {{ Request::get('plan') == 'free' ? 'selected' : '' }}>Free
                                    </option>
                                    <option value="lifetime"{{ Request::get('plan') == 'lifetime' ? 'selected' : '' }}>
                                        LifeTime
                                    </option>
                                </select>
                            </div>

                            <div class="col-sm-2 col-xl-4">
                                @if (Request::get('plan') !== null || Request::get('status') !== null)
                                    <div class="align-self-center mt-3">
                                        <a href="{{ route('admin.listing.pending') }}" class="btn btn-primary">Refresh</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="dataTables" class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Featured Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Sendmail') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Whatsapp') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Plan prices are pulled live from the plans table.
                                    $publicPaidPlans = \App\Models\Plan::where('status', 1)
                                        ->where('is_private', 0)
                                        ->where('plan_type', '!=', 'free')
                                        ->orderBy('price', 'asc')
                                        ->get();
                                    $fmt = function ($price) {
                                        $f = number_format((float) $price, 2, '.', '');
                                        $f = rtrim(rtrim($f, '0'), '.');
                                        return $f === '' ? '0' : $f;
                                    };
                                    $planLines = [];
                                    foreach ($publicPaidPlans as $plan) {
                                        if ($plan->plan_type === 'monthly') {
                                            $planLines[] = '- ' . $plan->title . ' — ৳' . $fmt($plan->price)
                                                . '/month, billed yearly (৳' . $fmt((float) $plan->price * 12) . '/year)';
                                        } elseif ($plan->plan_type === 'lifetime') {
                                            $planLines[] = '- ' . $plan->title . ' — ৳' . $fmt($plan->price) . ' one-time';
                                        } else {
                                            $planLines[] = '- ' . $plan->title . ' — ৳' . $fmt($plan->price);
                                        }
                                    }
                                    $planListText = implode("\n", $planLines);
                                @endphp
                                @foreach ($listings as $listing)
                                    <tr>
                                        <td>
                                            <img src="{{ $listing->photo ? url('assets/images/' . $listing->photo) : url('assets/images/default.png') }}"
                                                alt="Image" style="max-width: 100px; height: auto;">
                                        </td>
                                        <td>{{ $listing->name }}</td>
                                        <td>
                                            @if ($listing->user)
                                                <a
                                                    href="{{ route('admin-user-show', $listing->user->id) }}">{{ $listing->user->name }}</a>
                                            @else
                                                {{ 'Bdtalika' }}
                                            @endif
                                        </td>
                                        <td>{{ $listing->category ? $listing->category->title : '' }}</td>
                                        <td>{{ $listing->created_at->format('l, d M Y - h:i A') }}</td>
                                        <td><a onclick="return confirm('are you sure ?')" href="{{ route('admin.listing.mail.page', $listing->id) }}" class="dropdown-item send">Send Mail</a></td>

                                        <td>
                                            <div class="btn-group mb-1">
                                                <button type="button"
                                                    class="btn btn-{{ $listing->status == 1 ? 'success' : ($listing->status == 2 ? 'danger' : ($listing->status == 3 ? 'info' : 'warning')) }} btn-sm btn-rounded dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $listing->status == 1 ? __('Approved') : ($listing->status == 2 ? __('Rejected') : ($listing->status == 3 ? __('Requires Review') : __('Draft'))) }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if ($listing->status != 1)
                                                        <a class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to approve?')"
                                                            href="{{ route('admin.listing.status', ['id1' => $listing->id, 'id2' => 1]) }}">
                                                            {{ __('Approve') }}
                                                        </a>
                                                    @endif
                                                    @if ($listing->status != 0)
                                                        <a class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to change to Draft?')"
                                                            href="{{ route('admin.listing.status', ['id1' => $listing->id, 'id2' => 0]) }}">
                                                            {{ __('Draft') }}
                                                        </a>
                                                    @endif
                                                    @if ($listing->status != 2)
                                                        <a class="dropdown-item rejectListing " data-toggle="modal"
                                                            data-target="#rejectStatusModal"
                                                            href="{{ route('admin.listing.reject', $listing->id) }}">
                                                            {{ __('Reject') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $whatsappPhone = $listing->whatsapp_number ?: $listing->phone_number;
                                                $userPhone = optional($listing->user)->phone;
                                                $customerName = optional($listing->user)->name ?: 'Customer';
                                                $paymentUrl = route('user.listing.plan', $listing->id);
                                                $whatsappMessage = "Dear {$customerName},\n"
                                                    . "Your business \"{$listing->name}\" is still in Draft on BDTalika, so customers cannot see it yet.\n\n"
                                                    . "Activate your listing and get better visibility + SEO benefits.\n\n"
                                                    . "Plans:\n"
                                                    . $planListText . "\n\n"
                                                    . "Complete payment:\n"
                                                    . "{$paymentUrl}\n\n"
                                                    . "Need help? WhatsApp/Call: +8801738411242\n\n"
                                                    . "Thank you,\n"
                                                    . "BDTalika Team";
                                            @endphp

                                            <button type="button"
                                                class="btn btn-link p-0 copy-whatsapp-message"
                                                data-message="{{ base64_encode($whatsappMessage) }}"
                                                title="Copy WhatsApp Message">
                                                <i class="fas fa-copy fa-lg text-success"></i>
                                            </button>
                                            <div>
                                                <small class="text-muted">{{ __('Whatsapp') }}:</small>
                                                {{ $whatsappPhone ?: '-' }}
                                            </div>
                                            <div>
                                                <small class="text-muted">{{ __('User Phone') }}:</small>
                                                {{ $userPhone ?: '-' }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="btn-group mb-1">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm btn-rounded dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('admin.listing.view', [$listing->id]) }}"
                                                        class="dropdown-item">{{ __('View') }}</a>
                                                    <a href="{{ route('admin.listing.edit.type', [$listing->id]) }}"
                                                        class="dropdown-item">{{ __('Edit') }}</a>
                                                    <a href="{{ route('admin.listing.review', [$listing->id]) }}"
                                                        class="dropdown-item">{{ __('Get Reviews') }}</a>
                                                    <a href="{{ route('admin.listing.mail.page', $listing->id) }}"
                                                        class="dropdown-item">{{ __('Send Draft Mail') }}</a>
                                                    <a href="javascript:;" data-toggle="modal"
                                                        data-target="#whatsappLogsModal"
                                                        class="dropdown-item whatsappLogs"
                                                        data-href="{{ route('admin.listing.whatsapp.logs', $listing->id) }}">{{ __('WhatsApp Logs') }}</a>
                                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal"
                                                        class="dropdown-item"
                                                        data-href="{{ route('admin.listing.delete', $listing->id) }}">{{ __('Delete') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade confirm-modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        {{ __('You are about to delete this Directory. Every informtation under this category will be deleted.') }}
                    </p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</a>
                    <a href="javascript:;" class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
                </div>
            </div>
        </div>
    </div>


    {{-- status modal --}}
    <div class="modal fade confirm-modal" id="rejectStatusModal" tabindex="-1" role="dialog"
        aria-labelledby="statusModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Reject Directories') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="dataHtml"></div>
            </div>
        </div>
    </div>

    {{-- whatsapp logs modal --}}
    <div class="modal fade" id="whatsappLogsModal" tabindex="-1" role="dialog"
        aria-labelledby="whatsappLogsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('WhatsApp Reminder History') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3" id="whatsappLogsListingName"></h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Type') }}</th>
                                </tr>
                            </thead>
                            <tbody id="whatsappLogsBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">' +
                '<a class="btn btn-primary" href="{{ route('admin.listing.type') }}">' +
                '<i class="fas fa-plus"></i> {{ __('Add New') }}' +
                '</a>' +
                '</div>');
        });
    </script>
    <script>
        $(document).ready(function() {
            $("body").on('click', '.rejectListing', function() {
                let url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(data) {
                        $('#dataHtml').html(data);
                    }
                })
            });
        });
    </script>
    <script>
        $(document).on('submit', 'form', function() {
            var submitBtn = $(this).find('#submitBtn');
            submitBtn.prop('disabled', true);
            submitBtn.html(`
				<button type="submit" class="submit-btn btn btn-primary text-center disabled">
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					{{ __('Loading...') }}
				</button>
			  `);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#plan, #status').change(function() {
                $('#listingForm').submit();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function copyText(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }

                let textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();

                return new Promise(function(resolve, reject) {
                    try {
                        document.execCommand('copy') ? resolve() : reject();
                    } catch (error) {
                        reject(error);
                    } finally {
                        document.body.removeChild(textarea);
                    }
                });
            }

            function decodeBase64Message(value) {
                let binary = atob(value);
                let bytes = Uint8Array.from(binary, function(char) {
                    return char.charCodeAt(0);
                });

                return new TextDecoder('utf-8').decode(bytes);
            }

            $('body').on('click', '.copy-whatsapp-message', function(event) {
                event.preventDefault();
                let $button = $(this);
                let message = decodeBase64Message($button.attr('data-message'));
                let originalHtml = $button.html();

                copyText(message).then(function() {
                    $button.html('<i class="fas fa-check fa-lg text-success"></i>');

                    if (typeof toastr !== 'undefined') {
                        toastr.success('WhatsApp message copied.');
                    }

                    setTimeout(function() {
                        $button.html(originalHtml);
                    }, 1500);
                }).catch(function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Could not copy message.');
                    } else {
                        alert('Could not copy message.');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '.whatsappLogs', function() {
                let url = $(this).data('href');
                let $body = $('#whatsappLogsBody');
                $body.html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
                $('#whatsappLogsListingName').text('');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#whatsappLogsListingName').text(data.listing);
                        $body.empty();
                        if (!data.logs || data.logs.length === 0) {
                            $body.html(
                                '<tr><td colspan="4" class="text-center text-muted">No WhatsApp reminders sent yet.</td></tr>'
                            );
                            return;
                        }
                        $.each(data.logs, function(i, log) {
                            $body.append(
                                '<tr>' +
                                '<td>' + (i + 1) + '</td>' +
                                '<td>' + log.datetime + '</td>' +
                                '<td>' + (log.phone || '') + '</td>' +
                                '<td>' + log.type + '</td>' +
                                '</tr>'
                            );
                        });
                    },
                    error: function() {
                        $body.html(
                            '<tr><td colspan="4" class="text-center text-danger">Failed to load logs.</td></tr>'
                        );
                    }
                });
            });
        });
    </script>
@endsection
