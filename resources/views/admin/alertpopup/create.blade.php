@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add Alert Popup') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Add Alert Popup') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-lg-12">
            <!-- Form Basic -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <div class="">
                            <h6 class="m-0 font-weight-bold text-primary">{{ __('Add Alert Popup') }}</h6>
                        </div>
                        <div class="">
                            <a href="{{ route('admin.alertpopup.index') }}" class="btn btn-sm btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <form action="{{ route('admin.alertpopup.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('includes.admin.form-both')
                    
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="1">Image</option>
                                        <option value="2" {{ $errors->has('iframe_video') ? 'selected' : '' }}>Iframe Video</option>
                                        <option value="3" {{ $errors->has('video_file') ? 'selected' : '' }}>Upload Video File</option>
                                    </select>
                                </div>
                                <div class="imageField">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Image <span class="text-danger">*</span> <span class="text-primary">(Recommended Size: 350 X 220)</span> </label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group iframeVideo" style="display :{{ $errors->has('iframe_video') ? 'block' : 'none' }}">
                                    <label for="" class="form-label">Iframe Video <span class="text-danger">*</span>
                                        <span class="text-info">(Use only iframe code)</span></label>
                                    <textarea name="iframe_video" id="iframe_video" cols="30" class="form-control" rows="6"></textarea>
                                    @error('iframe_video')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="videoFile" style="display :{{ $errors->has('video_file') ? 'block' : 'none' }}">
                                    <div class="form-group mb-3">
                                        <label for="" class="form-label">Upload File <span class="text-danger">*</span></label>
                                        <input type="file" name="video_file" class="form-control">
                                    </div>
                                    @error('video_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Link <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="link" class="form-control">
                                </div>
                                @error('link')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                
                                <div class="form-group">
                                    <label for="" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
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
    <script>
        $(document).ready(function() {
            $('#type').on('change', function() {
                var type = $(this).val();
                if (type == 2) {
                    $('.iframeVideo').show();
                    $('.imageField').hide();
                    $('.videoFile').hide();
                }else if(type == 3){
                    $('.iframeVideo').hide();
                    $('.imageField').hide();
                    $('.videoFile').show();
                }
                else {
                    $('.imageField').show();
                    $('.iframeVideo').hide();
                    $('.videoFile').hide();
                }
            });
            $('#type').trigger('change');
        });
    </script>
@endsection
