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
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Manage Alert Popup') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('Alert Popup') }}</a></li>
            </ol>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            @include('includes.admin.form-success')
            <div class="card mb-4">
                <div class="card-header">
                    <div class=""></div>
                </div>
                <div class="table-responsive p-3">
                    <table id="dataTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:20%;">{{ __('Image') }}</th>
                                <th style="width:20%;">{{ __('Link') }}</th>
                                <th style="width:20%;">{{ __('Views') }}</th>
                                <th style="width:20%;">{{ __('Status') }}</th>
                                <th style="width:20%;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        "use strict";
        var baseUrl = "{{ asset('assets/images') }}/";
        var table = $('#dataTable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('admin.alertpopup.index') }}",
            columns: [{
                    data: 'file_type',
                    name: 'file_type',
                    render: function(data, type, row, meta) {
                        var content = '';
                        if (row.type == 1) {
                            content = '<img src="' + baseUrl + row.image + '" height="100">';
                        } else if (row.type == 2) {
                            content = row.iframe_video;
                        } else if (row.type == 3) {
                            content = '<video controls width="280" height="180" id="cst_video">' +
                                '<source src="' + baseUrl + row.video_file + '">' +
                                '</video>';
                        }
                        return content;
                    }
                },
                {
                    data: 'link',
                    name: 'link'
                },
                {
                    data: 'views',
                    name: 'views'
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
                '<a class="btn btn-primary" href="{{ route('admin.alertpopup.create') }}">' +
                '<i class="fas fa-plus"></i> {{ __('Add New') }}' +
                '</a>' +
                '</div>');
        });
    </script>
@endsection
