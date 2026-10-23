@extends('layouts.admin')
@section('styles')
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <style>
        input[type="search"] {
            outline: none !important;
            border: 1px solid #ddd !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Contacts') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contact.index') }}">{{ __('Contacts') }}</a></li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <div class="row mt-3">
        <!-- Datatables -->
        <div class="col-lg-12">

            @include('includes.admin.form-success')

            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table id="contactTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ date('M d, y', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route('admin.contact.view', $item->id) }}"
                                            class="btn-sm btn btn-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- DataTable with Hover -->

    </div>
    <!--Row-->
@endsection
@section('scripts')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactTable').DataTable();
        });
    </script>
@endsection
