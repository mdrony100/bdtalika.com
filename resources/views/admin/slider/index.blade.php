@extends('layouts.admin')
@section('styles')
    <style>
        td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Manage Slider') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('Slider') }}</a></li>
            </ol>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            @include('includes.admin.form-success')
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table id="sliderTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Link') }}</th>
                                <th style="width:15%;">{{ __('Order Number') }}</th>
                                <th>{{ __('Impressions') }}</th>
                                <th>{{ __('Clicks') }}</th>
                                <th>{{ __('CTR') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th style="width:15%;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
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
                        {{ __('You are about to delete this Category. Every informtation under this category will be deleted.') }}
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

{{-- Email PDF report modal --}}
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="reportForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Email PDF Report - <span id="reportSliderName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="customer@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Report Period</label>
                        <select name="range" class="form-control">
                            <option value="monthly">This Month</option>
                            <option value="yearly">This Year</option>
                            <option value="all" selected>All Time</option>
                        </select>
                    </div>
                    <small class="text-muted">The report is sent as a PDF attachment and logged in the recipient's email history.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Send PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



@section('scripts')
    <script>
        "use strict";
        var baseUrl = "{{ asset('/') }}";
        var table = $('#sliderTable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('admin.slider.index') }}",
            columns: [{
                    data: 'image',
                    name: 'image',
                    render: function(data, type, full, meta) {
                        return '<img src="' + baseUrl + data + '" height="100">';
                    }
                },
                {
                    data: 'link',
                    name: 'link'
                },
                {
                    data: 'order_number',
                    name: 'order_number'
                },
                {
                    data: 'impressions',
                    name: 'impressions'
                },
                {
                    data: 'clicks',
                    name: 'clicks'
                },
                {
                    data: 'ctr',
                    name: 'ctr'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<span class="badge badge-success">Active</span>';
                        } else {
                            return '<span class="badge badge-danger">Inactive</span>';
                        }
                    }

                },
                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }

            ]
        });
        $(function() {
            $(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">' +
                '<a class="btn btn-primary" href="{{ route('admin.slider.create') }}">' +
                '<i class="fas fa-plus"></i> {{ __('Add New') }}' +
                '</a>' +
                '</div>');
        });

        // PDF report modal: fill slider id + reset form on open
        $("body").on('click', '.open-report-modal', function () {
            var sliderId = $(this).data('slider-id');
            var sliderName = $(this).data('slider-name') || ('Slider #' + sliderId);
            $('#reportForm').attr('action', '{{ url('admin/slider/send-report') }}/' + sliderId);
            $('#reportSliderName').text(sliderName);
            $('#reportModal').modal('show');
        });
    </script>
@endsection
