@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center py-3 justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Custom Pages') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Custom Pages') }}</a></li>
            </ol>
        </div>
    </div>


    <!-- Row -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table id="sliderTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th style="width:10%;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($custom_pages as $page) 
                             <tr>
                                 <td>{{ $page->name }}</td>
                                 <td>
                                    <span class="badge badge-{{ $page->status == 'active' ? 'success' : 'danger' }}">{{ Str::ucfirst($page->status) }}</span>
                                 </td>
                                 <td>
                                     <a href="{{ route('admin.custom.page.edit', $page->id) }}" class="btn btn-primary">
                                         Edit
                                     </a>
                                 </td>
                             </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Row-->
@endsection
