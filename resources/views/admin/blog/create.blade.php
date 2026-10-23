@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add New Post') }} <a class="btn btn-primary btn-rounded btn-sm"
                    href="{{ route('admin.blog.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Blog') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">{{ __('Post') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.blog.create') }}">{{ __('Add New Post') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Add New Post Form') }}</h6>
                </div>

                <div class="card-body">
                    <div class="gocover" style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                    </div>
                    <form action="{{ route('admin.blog.create') }}" method="POST" enctype="multipart/form-data">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="m-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                    
                        {{ csrf_field() }}
                    
                        <div class="form-group">
                            <label for="title">{{ __('Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="{{ __('Enter Title') }}" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title">{{ __('Slug') }}</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                placeholder="{{ __('Enter Slug') }}" value="{{ old('slug') }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>{{ __('Published') }}</option>
                                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>{{ __('Draft') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inp-name">{{ __('Category') }}</label>
                            <select class="form-control mb-3" name="category_id">
                                <option value="" selected>{{ __('Select Category') }}</option>
                                @foreach ($cats as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label>{{ __('Set Picture') }} </label>
                            <div class="wrapper-image-preview">
                                <div class="box">
                                    <div class="back-preview-image"
                                        style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
                                    <div class="upload-options">
                                        <label class="img-upload-label" for="img-upload"> <i class="fas fa-camera"></i>
                                            {{ __('Upload Picture') }} </label>
                                        <input id="img-upload" type="file" class="image-upload" name="photo" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="details">{{ __('Description ') }}</label>
                            <textarea class="form-control summernote" id="details" name="details" rows="3"
                                placeholder="{{ __('Description') }}">{{ old('details') }}</textarea>
                        </div>
                    
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="secheck" class="custom-control-input" id="seo" 
                                    {{ old('secheck') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="seo"> {{ __('Allow Blog SEO') }}</label>
                            </div>
                        </div>
                    
                        <div class="showbox {{ old('secheck') ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label for="meta_tag">{{ __('Meta Tags') }}</label>
                                <input type="text" class="mytags" name="meta_tag" placeholder="{{ __('Meta Tags') }}"
                                    value="{{ old('meta_tag') }}">
                            </div>
                    
                            <div class="form-group">
                                <label for="meta_description">{{ __('Meta Description') }}</label>
                                <textarea class="form-control summernote" id="meta_description" name="meta_description" rows="3"
                                    placeholder="{{ __('Meta Description') }}">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    
                        <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
