@extends('layouts.admin')
@section('contact', 'active')
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Contact View') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <a href="{{ route('admin.contact.index') }}" class="btn-sm btn btn-primary">{{ __('Back') }}</a>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <div class="row mt-3">
        <!-- Datatables -->
        <div class="col-lg-12">

            @include('includes.admin.form-success')

            <div class="card mb-4">
                <table class="table table-bordered">
                    <tr>
                        <td>Name: {{ $contact->name }}</td>
                    </tr>
                    <tr>
                        <td>Email: {{ $contact->email }}</td>
                    </tr>
                    <tr>
                        <td>Phone: {{ $contact->phone }}</td>
                    </tr>
                    <tr>
                        <td>Subject: {{ $contact->subject }}</td>
                    </tr>
                    <tr>
                        <td>Message: {{ $contact->message }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- DataTable with Hover -->

    </div>
    <!--Row-->
@endsection
