@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Manage Support Tickets') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.index') }}">{{ __('Tickets') }}</a></li>
            </ol>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            @include('includes.admin.form-success')
            <div class="card mb-4">
                <div class="table-responsive">
                    <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th style="width:10%;">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td><a href="{{ route('admin-user-show', $ticket->user->id) }}">{{ $ticket->user->name  }}</a></td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>
                                        <span class="px-3 py-1 badge badge-{{ $ticket->status == '1' ? 'success' : 'danger' }}">
                                            {{ $ticket->status == '1' ? 'Open' : 'Closed' }}</span>
                                    </td>
                                    <td>{{ date('d M, Y', strtotime($ticket->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route('admin.ticket.view', $ticket->id) }}"
                                            class="btn btn-primary btn-xs rounded py-1 px-2">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">Not Found Ticket</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mr-4 mt-3">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('partials.admin.status')
@endsection
