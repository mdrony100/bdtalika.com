@extends('layouts.admin')

@section('content')
    @php
        $user = $data;
    @endphp

    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ $user->name }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('User') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('Users') }}</a></li>
            </ol>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-12">

            @include('includes.admin.form-success')

            <div class="card mb-3">
                <div class="card-body d-flex flex-wrap align-items-center gap-2" style="gap: 8px;">
                    <a href="{{ route('admin.user.login.as.user', $user->id) }}"
                        class="btn btn-sm btn-primary"
                        onclick="return confirm('Login as this user?')">
                        {{ __('Login as User') }}
                    </a>
                    <a href="{{ route('admin.user.logs', $user->id) }}" class="btn btn-sm btn-info">
                        {{ __('User Logs') }}
                    </a>
                    <a href="{{ route('admin.user.email.logs', $user->id) }}" class="btn btn-sm btn-info">
                        {{ __('Email Logs') }}
                    </a>
                    <a href="{{ route('admin-user-edit', $user->id) }}" class="btn btn-sm btn-warning">
                        {{ __('Edit') }}
                    </a>
                    <a href="javascript:;" class="btn btn-sm btn-secondary send"
                        data-email="{{ $user->email }}"
                        data-toggle="modal"
                        data-target="#vendorform">
                        {{ __('Send') }}
                    </a>
                    <a href="{{ route('admin-user-verify', $user->id) }}"
                        onclick="return confirm('Are you sure to Verify Email?')"
                        class="btn btn-sm btn-success">
                        {{ __('Verify Email') }}
                    </a>
                    <div class="btn-group">
                        <button type="button"
                            class="btn btn-sm btn-{{ $user->is_banned == 1 ? 'danger' : 'success' }} dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                            {{ $user->is_banned == 1 ? __('Block') : __('Unblock') }}
                        </button>
                        <div class="dropdown-menu">
                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal"
                                class="dropdown-item"
                                data-href="{{ route('admin-user-ban', ['id1' => $user->id, 'id2' => 0]) }}">
                                {{ __('Unblock') }}
                            </a>
                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal"
                                class="dropdown-item"
                                data-href="{{ route('admin-user-ban', ['id1' => $user->id, 'id2' => 1]) }}">
                                {{ __('Block') }}
                            </a>
                        </div>
                    </div>
                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal"
                        class="btn btn-sm btn-danger"
                        data-href="{{ route('admin-user-delete', $user->id) }}">
                        {{ __('Delete') }}
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="row my-5">
                    <div class="col-md-2">
                        <div class="user-image">
                            @if ($user->is_provider == 1)
                                <img src="{{ $user->photo ? asset($user->photo) : asset('assets/images/default-user.png') }}"
                                    alt="No Image">
                            @else
                                <img class=""
                                    src="{{ $user->photo ? asset('assets/images/' . $user->photo) : asset('assets/images/default-user.png') }}"
                                    alt="No Image">
                            @endif
                            <a class="mybtn1 btn btn-primary send" data-email="{{ $user->email }}" data-toggle="modal"
                                data-target="#vendorform" href="javascript:;">{{ __('Send Message') }}</a>

                        </div>
                    </div>
                    <div class="col-md-10 mt-5">
                        <div class="table-responsive show-table">
                            <table class="table">
                                <tr>
                                    <th>{{ __('ID#') }}</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Username') }}</th>
                                    <td>{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Phone') }}</th>
                                    <td>{{ $user->phone ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Password') }}</th>
                                    <td>{{ $user->user_password }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Address') }}</th>
                                    <td>{{ $user->address }}</td>
                                </tr>

                                <tr>
                                    <th>{{ __('City') }}</th>
                                    <td>{{ $user->city }}</td>
                                </tr>

                                <tr>
                                    <th>{{ __('Zip Code') }}</th>
                                    <td>{{ $user->zip }}</td>
                                </tr>

                                <tr>
                                    <th>{{ __('Join Date') }}</th>
                                    <td>{{ date('d M, Y', strtotime($user->created_at)) }}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- DataTable with Hover -->

    </div>

    <div class="row my-3">
        <div class="col-xl-12 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">@lang('User Directory')</h6>
                </div>
                @if (count($listings) > 0)
                    <div class="table-responsive table--mobile-lg">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>@lang('Serial No')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Ratting')</th>
                                    <th>@lang('Review')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listings as $key => $data)
                                    <tr>
                                        <td data-label="@lang('Serial No')">{{ $loop->iteration }}</td>
                                        <td data-label="@lang('Name')">
                                          <a href="{{ route('admin.listing.view', $data->id) }}">{{ $data->name }}</a>
                                        </td>
                                        <td data-label="@lang('Ratting')">
                                            {{ $data->reviews->count() > 0 ? $data->directoryRatting($data->id) : 'N/A' }}
                                        </td>
                                        <td data-label="@lang('Ratting')">{{ $data->reviews->count() }}</td>
                                        <td data-label="@lang('Status')">
                                            <span
                                                class="badge badge-{{ $data->status == 0 ? 'warning' : ($data->status == 1 ? 'success' : 'danger') }}">{{ $data->status == 0 ? 'pending' : ($data->status == 1 ? 'approved' : 'rejected') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                @else
                    <p class="text-center">@lang('NO DATA FOUND')</p>
                @endif
            </div>
        </div>
    </div>


    {{-- STATUS MODAL --}}

    <div class="modal fade confirm-modal" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update Status') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to change the status.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</a>
                    <a href="javascript:;" class="btn btn-success btn-ok">{{ __('Update') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS MODAL ENDS --}}


    {{-- MESSAGE MODAL --}}
    <div class="sub-categori">
        <div class="modal fade confirm-modal" id="vendorform" tabindex="-1" role="dialog"
            aria-labelledby="vendorformLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel">{{ __('Send Message') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply1">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <input type="email" class="form-control" id="eml1" name="to"
                                                    placeholder="{{ __('Email') }}" readonly value="{{ $user->email }}"
                                                    required="">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="subj1" name="subject"
                                                    placeholder="{{ __('Subject') }}" value="" required="">
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" name="message" id="msg1" cols="20" rows="6"
                                                    placeholder="{{ __('Your Message') }} "required=""></textarea>
                                            </div>                                           <button class="submit-btn btn btn-primary text-center" id="emlsub1"
                                                type="submit">{{ __('Send Message') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    {{-- MESSAGE MODAL ENDS --}}

    {{-- DELETE MODAL --}}

    <div class="modal fade confirm-modal" id="deleteModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to delete this User.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</a>
                    <a href="javascript:;" class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL ENDS --}}

@endsection
