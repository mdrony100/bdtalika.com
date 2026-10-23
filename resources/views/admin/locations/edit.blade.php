@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Divisions') }} <a class="btn btn-primary btn-rounded btn-sm"
                    href="{{ route('admin.locations.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Divisions') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Post Form') }}</h6>
                </div>

                <div class="card-body">
                    <div class="gocover"
                        style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                    </div>
                    <form class="geniusform" action="{{ route('admin.locations.update', $data->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @include('includes.admin.form-both')

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="title">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="title" name="name"
                                placeholder="{{ __('Enter Title') }}" value="{{ $data->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="inp-name">{{ __('Parent') }}</label>
                            <select class="form-control mb-3" name="parent_id">
                                <option value="" selected>{{ __('Select Parent') }}</option>
                                @foreach ($locations as $key => $location)
                                    <option value="{{ $location->id }}"
                                        {{ $data->parent_id == $location->id ? 'selected' : '' }}>{{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Order Number') }}</label>
                            <input type="text" class="form-control" name="order_number"
                                placeholder="{{ __('Enter order number') }}" value="{{ $data->order_number }}"
                                >
                        </div>

                        <div class="form-group">
                            <label>{{ __('Set Picture') }} </label>
                            <div class="wrapper-image-preview">
                                <div class="box">
                                    <div class="back-preview-image"
                                        style="background-image: url({{ $data->photo ? asset('assets/images/' . $data->photo) : asset('assets/images/placeholder.jpg') }});">
                                    </div>
                                    <div class="upload-options">
                                        <label class="img-upload-label" for="img-upload"> <i class="fas fa-camera"></i>
                                            {{ __('Upload Picture') }} </label>
                                        <input id="img-upload" type="file" class="image-upload" name="photo"
                                            accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label for="meta_title">{{ __('Meta Title') }}</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                placeholder="{{ __('Enter Meta Title') }}" value="{{ $data->meta_title }}" required>
                        </div>

                        <div class="form-group">
                            <label for="inp-slug">{{ __('Meta Description') }}</label>
                             <textarea name="meta_description" id="meta_description" class="form-control" cols="30" rows="5"  placeholder="Enter Meta Description" required>{{ $data->meta_description }}</textarea>
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
