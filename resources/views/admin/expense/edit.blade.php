@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">
                {{ __('Edit Expense') }}
                <a class="btn btn-primary btn-rounded btn-sm" href="{{ route('admin.expenses.index') }}">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>
            </h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">{{ __('Expenses') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') }}</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
                        @include('admin.expense._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
