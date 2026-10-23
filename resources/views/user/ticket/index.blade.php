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
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}"
                                    class="color_link">@lang('Home')</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('user.listing.index') }}"
                                    class="">@lang('Manage Support Tickets')</a></li>
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
                                                    @lang('All Support Tickets')
                                                </h4>
                                            </div>
                                            <div class="">
                                                <a href="{{ route('user.ticket.create') }}" class="btn btn-primary btn-sm py-2 px-3">Create Ticket</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="dashboard-list-wraps-body py-3">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th style="width:10%;">View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($tickets as $ticket) 
                                                    <tr>
                                                        <td>{{ $ticket->subject }}</td>
                                                        <td>
                                                            <span class="badge bg-{{$ticket->status == '1' ? 'success' : 'danger'}}">
                                                            {{$ticket->status == '1' ? 'Open' : 'Closed'}}</span>
                                                        </td>
                                                        <td>{{ date('d M, Y', strtotime($ticket->created_at)) }}</td>
                                                        <td>
                                                            <a href="{{ route('user.ticket.view',$ticket->id) }}" class="btn btn-primary btn-xs rounded py-1 px-2">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty 
                                                    <tr class="text-center">
                                                        <td colspan="4">Not Found Support Ticket</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>


                                    {{ $tickets->links() }}


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
