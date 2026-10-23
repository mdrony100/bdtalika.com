@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Listing By Views') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Listing By Views') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            {{-- @include('includes.admin.form-success') --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Filter Directories</h5>
                    <form action="{{ route('admin.listing.byviews') }}" id="listingForm" method="get">
                        <div class="row d-flex align-items-center">
                            <div class="col-sm-5 col-xl-4">
                                <label for="plan" class="form-label">Subscription Plan</label>
                                <select name="plan" id="plan" class="form-control">
                                    <option value="" class="d-none">Select</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            {{ (string) Request::get('plan') === (string) $plan->id ? 'selected' : '' }}>
                                            {{ $plan->title }} ({{ ucfirst($plan->plan_type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2 col-xl-4">
                                @if (Request::get('plan') !== null)
                                    <div class="align-self-center mt-3">
                                        <a href="{{ route('admin.listing.byviews') }}" class="btn btn-primary">Refresh</a>
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
                                    <th>{{ __('Views') }}</th>
                                    <th>{{ __('Reviews') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    @if ($seoOffer && $seoOffer->status)
                                        <th>{{ __('SEO Offer') }}</th>
                                    @endif
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listings as $listing)
                                    <tr>
                                        <td>
                                            <img src="{{ $listing->photo ? url('assets/images/' . $listing->photo) : url('assets/images/default.png') }}"
                                                alt="Image" style="max-width: 100px; height: auto;">
                                        </td>
                                        <td>{{ $listing->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.listing.byviews.analytics', $listing->id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="{{ __('View Analytics') }}">
                                                <i class="fas fa-chart-bar"></i> {{ $listing->total_views ?? 0 }}
                                            </a>
                                        </td>
                                        <td>{{ $listing->total_review ?? 0 }}</td>
                                        <td>
                                            @if ($listing->user)
                                                <a
                                                    href="{{ route('admin-user-show', $listing->user->id) }}">{{ $listing->user->name }}</a>
                                            @else
                                                {{ 'Bdtalika' }}
                                            @endif
                                        </td>
                                        <td>{{ $listing->category ? $listing->category->title : '' }}</td>
                                        <td>{{ $listing->plan ? $listing->plan->plan_type : '' }}</td>
                                        @if ($seoOffer && $seoOffer->status)
                                            <td class="text-center">
                                                @if ($listing->plan && $listing->plan->plan_type === 'lifetime')
                                                    @php
                                                        $whatsappPhone = $listing->whatsapp_number ?: $listing->phone_number;
                                                        $seoMessage = $seoOffer->buildMessage($listing, $listing->total_views ?? 0);
                                                    @endphp
                                                    <button type="button"
                                                        class="btn btn-link p-0 copy-whatsapp-message"
                                                        data-message="{{ base64_encode($seoMessage) }}"
                                                        title="Copy SEO Offer Message">
                                                        <i class="fas fa-copy fa-lg text-success"></i>
                                                    </button>
                                                    <div>
                                                        <small class="text-muted">{{ __('Whatsapp') }}:</small>
                                                        {{ $whatsappPhone ?: '-' }}
                                                    </div>
                                                @else
                                                    {{ '—' }}
                                                @endif
                                            </td>
                                        @endif
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
                    <div class="d-flex justify-content-between align-items-center px-3 py-3">
                        <div class="text-muted">
                            {{ __('Showing') }} {{ $listings->firstItem() ?? 0 }}-{{ $listings->lastItem() ?? 0 }}
                            {{ __('of') }} {{ $listings->total() }} {{ __('directories') }}
                        </div>
                        <div>
                            {{ $listings->links() }}
                        </div>
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
				<button type="submit" class="btn btn-success btn-ok disabled">
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					{{ __('Submit') }}
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
        function copyText(text) {
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            }

            let textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);

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

        $(document).ready(function() {
            $('body').on('click', '.copy-whatsapp-message', function(event) {
                event.preventDefault();
                let $button = $(this);
                let message = decodeBase64Message($button.attr('data-message'));
                let originalHtml = $button.html();

                copyText(message).then(function() {
                    $button.html('<i class="fas fa-check fa-lg text-success"></i>');

                    if (typeof toastr !== 'undefined') {
                        toastr.success('SEO offer message copied.');
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
        $(function() {
            $(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">' +
                '<a class="btn btn-info mr-2" href="{{ route('admin.listing.seo.offer.settings') }}">' +
                '<i class="fas fa-cog"></i> {{ __('SEO Offer Settings') }}' +
                '</a>' +
                '</div>');
        });
    </script>
@endsection
