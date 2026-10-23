@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Website Contents') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('General Settings') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gs.contents') }}">{{ __('Website Contents') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="card mb-4 mt-3">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Website Contents Form') }}</h6>
        </div>

        <div class="card-body">
            <div class="gocover"
                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>
            <form class="geniusform" action="{{ route('admin.gs.update') }}" method="POST" enctype="multipart/form-data">

                @include('includes.admin.form-both')

                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inp-title">{{ __('Login Email Verification') }}</label>
                            <div class="frm-btn btn-group mb-1">
                                <button type="button"
                                    class="btn btn-sm btn-rounded dropdown-toggle btn-{{ $gs->is_verification_email == 1 ? 'success' : 'danger' }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $gs->is_verification_email == 1 ? __('Activated') : __('Deactivated') }}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="1"
                                        data-val="{{ __('Activated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_verification_email', 1]) }}">{{ __('Activate') }}</a>
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="0"
                                        data-val="{{ __('Deactivated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_verification_email', 0]) }}">{{ __('Deactivate') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inp-title">{{ __('Enable Force SSL Redirection') }}</label>
                            <div class="frm-btn btn-group mb-1">
                                <button type="button"
                                    class="btn btn-sm btn-rounded dropdown-toggle btn-{{ $gs->is_ssl == 1 ? 'success' : 'danger' }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $gs->is_ssl == 1 ? __('Activated') : __('Deactivated') }}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="1"
                                        data-val="{{ __('Activated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_ssl', 1]) }}">{{ __('Activate') }}</a>
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="0"
                                        data-val="{{ __('Deactivated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_ssl', 0]) }}">{{ __('Deactivate') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inp-title">{{ __('Disqus') }}</label>
                            <div class="frm-btn btn-group mb-1">
                                <button type="button"
                                    class="btn btn-sm btn-rounded dropdown-toggle btn-{{ $gs->is_disqus == 1 ? 'success' : 'danger' }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $gs->is_disqus == 1 ? __('Activated') : __('Deactivated') }}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="1"
                                        data-val="{{ __('Activated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_disqus', 1]) }}">{{ __('Activate') }}</a>
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="0"
                                        data-val="{{ __('Deactivated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_disqus', 0]) }}">{{ __('Deactivate') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inp-title">{{ __('Tawk.to') }}</label>
                            <div class="frm-btn btn-group mb-1">
                                <button type="button"
                                    class="btn btn-sm btn-rounded dropdown-toggle btn-{{ $gs->is_talkto == 1 ? 'success' : 'danger' }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $gs->is_talkto == 1 ? __('Activated') : __('Deactivated') }}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="1"
                                        data-val="{{ __('Activated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_talkto', 1]) }}">{{ __('Activate') }}</a>
                                    <a class="dropdown-item drop-change" href="javascript:;" data-status="0"
                                        data-val="{{ __('Deactivated') }}"
                                        data-href="{{ route('admin.gs.status', ['is_talkto', 0]) }}">{{ __('Deactivate') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" name="app_mode" type="checkbox" value="1" {{ $gs->app_mode == 1 ? 'checked' : '' }} id="app_mode">
                            <label class="form-check-label" for="app_mode">
                               Uncheck to remove no indexing
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" name="is_active_ai_assistant" type="checkbox" value="1" {{ $gs->app_mode == 1 ? 'checked' : '' }} id="app_mode">
                            <label class="form-check-label" for="is_active_ai_assistant">
                               show ai assistant
                            </label>
                        </div>
                    </div>
                    


                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="cp-container" id="cp3-container">
                                <div class="input-group" title="Using input value">
                                    <input type="color" name="colors" class="form-control"
                                        value="{{ $gs->colors }}" id="exampleInputPassword1">
                                </div>
                            </div>
                        </div>
                    </div>
 

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inp-name">{{ __('Time Zone') }}</label>
                            @php
                                $timezone_identifiers = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

                                echo "<select name='time_zone' class='form-control mb-3' required>";

                                echo "<option disabled selected>
                          Please Select Timezone
                        </option>";

                                for ($i = 0; $i < count($timezone_identifiers); $i++) {
                                    if ($gs->time_zone == $timezone_identifiers[$i]) {
                                        $msg = 'selected';
                                    } else {
                                        $msg = '';
                                    }
                                    echo "<option value='" . $timezone_identifiers[$i] . "' " . $msg . '>' . $timezone_identifiers[$i] . '</option>';
                                }

                                echo '</select>';
                            @endphp
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inp-phone_code">{{ __('Tawk.to ID') }}</label>
                            <input type="text" class="form-control" id="inp-phone_code" name="talkto"
                                placeholder="{{ __('Enter phone code') }}" value="{{ $gs->talkto }}" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inp-title">{{ __('Website Title') }}</label>
                            <input type="text" class="form-control" id="inp-title" name="title"
                                placeholder="{{ __('Enter Website Title') }}" value="{{ $gs->title }}" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('OG Image') }}</label> <br>
                            @if (isset($gs->og_image))
                                <img src="{{ asset('assets/images/' . $gs->og_image ?? 'default.png') }}" width="100"
                                    height="100" class="mb-2" alt="{{ $gs->og_image }}">
                            @else
                                <img src="{{ asset('assets/images/default.png') }}" width="100" height="100"
                                    class="mb-2" alt="no image">
                            @endif


                            <input type="file" class="form-control" name="og_image">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('Meta Keywords') }}</label> <br>
                            <img src="" width="100" alt="meta Keywords">
                            <input type="text" class="form-control" name="meta_keyword"
                                placeholder="{{ __('Enter Meta Keywords') }}" value="{{ $gs->meta_keyword }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('Meta Description') }}</label>
                            <textarea class="form-control summernote" name="meta_description" rows="3"
                                placeholder="{{ __('Enter Error Text') }}">{{ $gs->meta_description }}</textarea>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inp-disqus">{{ __('Disqus Website Short Name') }}</label>
                            <input type="text" class="form-control" id="inp-disqus" name="disqus"
                                placeholder="{{ __('Disqus Website Short Name') }}" value="{{ $gs->disqus }}"
                                required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inp-name">{{ __('Currency Format') }}</label>
                            <select class="form-control mb-3" name="currency_format">
                                <option value="" selected>{{ __('Select Category') }}</option>
                                <option value="0" {{ $gs->currency_format == 0 ? 'selected' : '' }}>
                                    {{ __('Before Price') }}</option>
                                <option value="1" {{ $gs->currency_format == 1 ? 'selected' : '' }}>
                                    {{ __('After Price') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- <div class="col-12 mb-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="" class="form-label">Google client id</label>
                                <input type="text" name="g_client_id" value="{{ $gs->g_client_id }}"
                                    class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label for="" class="form-label">Google client secret</label>
                                <input type="text" name="g_secret_key" value="{{ $gs->g_secret_key }}"
                                    class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label for="" class="form-label">Redirect URL</label>
                                <div class="input-group">
                                    <input type="text" name="redirect_url" id="textToCopy"
                                        value="http://localhost/talika/user/login/google/callback" readonly
                                        class="form-control">
                                    <a href="javascript:void(0)" id="copyButton" class="input-group-text rounded-start-0">Copy</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>

                <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#copyButton').click(function() {
                console.log('click');
                $('#textToCopy').select();
                document.execCommand("copy");
                $('#textToCopy').blur();
                $('#copyButton').text('Copied!');
                setTimeout(function() {
                    $('#copyButton').text('Copy');
                }, 1000);
            });
        });
    </script>
@endsection
