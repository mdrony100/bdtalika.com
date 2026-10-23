@extends('layouts.front')
@section('ticket', 'active')
@push('css')
@endpush

@section('content')
    <!-- ======================= dashboard Detail ======================== -->
    <div class="bg_light px-4 py-2">
        <div class="dashboard-tlbar d-block">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    {{-- <h1 class="ft-medium">@lang('Listings Analytics')</h1> --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}"
                                    class="color_link">@lang('Home')</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('user.listing.index') }}"
                                    class="">@lang('Manage Tickets')</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
        <a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false"
            aria-controls="MobNav">
            <i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
        </a>
        <div class="collapse" id="MobNav">
            @includeIf('partials.user.sidebar')
        </div>
        <div class="dashboard-content">
            <div class="">
                <div class="dashboard-widg-bar d-block">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
                                <div class="dashboard-list-wraps-head br-bottom py-3 px-3">
                                    <div class="dashboard-list-wraps-flx">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="">
                                                <h4 class="mb-0 ft-medium fs-md">
                                                    <i class="lni lni-ticket me-2 theme-cl fs-sm"></i>
                                                    @lang('Add Support Ticket')
                                                </h4>
                                            </div>
                                            <div class="">
                                                <a href="{{ route('user.ticket.index') }}"
                                                    class="btn btn-primary btn-sm px-3 py-1">Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="dashboard-list-wraps-body py-3 px-3">
                                    <form action="{{ route('user.ticket.store') }}" method="post">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Subject <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="subject" class="form-control"
                                                placeholder="Enter your subject" value="{{ old('subject') }}" required>
                                            @error('subject')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Priority <span
                                                    class="text-danger">*</span></label>
                                            <select name="priority" id="priority" class="form-control">
                                                <option value="" class="d-none">Choose your priority</option>
                                                <option value="high">High</option>
                                                <option value="low">Low</option>
                                                <option value="urgent">Urgent</option>
                                            </select>
                                            @error('priority')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Message <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="message" id="message" class="form-control" cols="30" rows="5" required>{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success px-5 py-2">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
