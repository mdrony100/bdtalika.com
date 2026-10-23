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
                                    <option value="free" {{ Request::get('plan') == 'free' ? 'selected' : '' }}>Free
                                    </option>
                                    <option value="lifetime" {{ Request::get('plan') == 'lifetime' ? 'selected' : '' }}>
                                        LifeTime</option>
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
                <div class="table-responsive p-3">
                    <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Featured Image') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Reviews') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Plan') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
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
        "use strict";

        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            searching: true,
            // ajax: "{{ route('admin.listing.datatables', ['status' => 'all']) }}",
            ajax: {
                data: function(d) {
                    d.plan = $('#plan').val();
                    d.status = $('#status').val();
                },
                url: "{{ route('admin.listing.datatables', ['status' => 'all']) }}"
            },
            columns: [

                {
                    data: 'photo',
                    name: 'photo',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'total_review',
                    name: 'total_review'
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'category_id',
                    name: 'category_id'

                },
                {
                    data: function(row) {
                        return row.plan ? row.plan.plan_type : '';
                    },
                    name: 'plan_type'
                },

                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }

            ],
            language: {
                processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
            }
        });

        $(function() {
            $(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">' +
                '<a class="btn btn-primary" href="{{ route('admin.listing.type') }}">' +
                '<i class="fas fa-plus"></i> {{ __('Add New') }}' +
                '</a>' +
                '</div>');
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
