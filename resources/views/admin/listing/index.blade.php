@extends('layouts.admin')

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
                    <form action="{{ route('admin.listing.index') }}" id="listingForm" method="get">
                        <div class="row d-flex align-items-center">
                            <div class="col-sm-5 col-xl-4">
                                <label for="plan" class="form-label">Plan</label>
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

                            <div class="col-sm-5 col-xl-4">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" class="d-none">Select</option>
                                    <option value="0" {{ Request::get('status') == '0' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="2" {{ Request::get('status') == '2' ? 'selected' : '' }}>Reject
                                    </option>
                                    <option value="3" {{ Request::get('status') == '3' ? 'selected' : '' }}>Requires
                                        review</option>
                                </select>
                            </div>

                            <div class="col-sm-2 col-xl-4">
                                @if (Request::get('plan') !== null || Request::get('status') !== null)
                                    <div class="align-self-center mt-3">
                                        <a href="{{ route('admin.listing.index') }}" class="btn btn-primary">Refresh</a>
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
                                    <th>{{ __('Reviews') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created') }}</th>
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
                                        <td>{{ $listing->plan ? $listing->plan->title : '' }}</td>
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
                                        <td>{{ $listing->created_at->format('l, d M Y - h:i A') }}</td>
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
@endsection
