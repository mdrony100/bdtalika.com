@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center py-3 justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">
                {{ __('Edit Custom Page') }}
                <a class="btn btn-primary btn-rounded btn-sm" href="{{ route('admin.custom.page.index') }}"><i
                        class="fas fa-arrow-left"></i> Back</a>
            </h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Edit Custom Page') }}</a></li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="geniusform" action="{{ route('admin.custom.page.update', $page->id) }}" method="POST">
                        @include('includes.admin.form-both')
                        @if ($errors->any())
                            <div class="text-danger">{{ $errors->first() }}</div>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label for="inp-name">{{ __('Title') }}</label>
                            <input type="text" class="form-control" name="title" placeholder="{{ __('Title') }}"
                                value="{{ $page->title }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inp-name">{{ __('Sub Title') }}</label>
                            <input type="text" class="form-control" name="sub_title" placeholder="{{ __('Sub Title') }}"
                                value="{{ $page->sub_title }}" required>
                            @error('sub_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inp-name">{{ __('Meta Title') }}</label>
                            <textarea name="meta_title" id="meta_title" class="form-control" cols="30" rows="5" required>{{ $page->meta_title }}</textarea>
                            @error('meta_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inp-name">{{ __('Meta Description') }}</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" cols="30" rows="5" required>{{ $page->meta_description }}</textarea>
                            @error('meta_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inp-name">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ $page->status == 'actove' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $page->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Row-->
@endsection
