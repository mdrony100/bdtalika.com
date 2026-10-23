@extends('layouts.admin')

@section('styles')
    <style>
        .card-header {
            background: #fafafa;
            padding: 15px 12px;
            border-color: #ebebeb;
        }

        .card {
            border: 1px solid #EEE;
        }

        ul {
            list-style: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('View Support Ticket') }}</h5>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <h6 class="card-title m-0">Message</h4>
                                    </div>
                                    <div class="">
                                        <a href="{{ route('admin.ticket.index') }}" class="btn btn-primary">Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="m-0 p-0 px-3">
                                    <li class="py-2">Subject: {{ $ticket->subject }}</li>
                                    <li class="py-2">Status:
                                        <span
                                            class="px-3 py-1 badge badge-{{ $ticket->status == '1' ? 'success' : 'danger' }}">{{ $ticket->status == '1' ? 'Open' : 'Closed' }}
                                        </span>
                                    </li>
                                </ul>

                                <form action="{{ route('admin.ticket.status', $ticket->id) }}" method="post">
                                    @csrf
                                    <div class="row px-3">
                                        <div class="col-lg-4 col-xl-3">
                                            <div class="input-group">
                                                <select name="status" class="form-control mr-2" id="">
                                                    <option value="1" {{ $ticket->status == '1' ? 'selected' : '' }}>
                                                        Open</option>
                                                    <option value="0" {{ $ticket->status == '0' ? 'selected' : '' }}>
                                                        Close</option>
                                                </select>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <hr>
                                <form action="{{ route('admin.ticket.message', $ticket->id) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $ticket->status }}">
                                    <div class="bg-gray p-3 mb-3 message_box"
                                        style="max-height: 500px; overflow: hidden; overflow-y: auto;background:#f5f5f5 !important">
                                        @foreach ($ticket_messages as $item)
                                            <div class="mb-2 overflow-hidden">
                                                <div
                                                    class="border {{ $item->user_id != '0' ? 'float-left' : 'float-right' }} p-3 bg-white rounded float-start">
                                                    <div class="name mb-1">
                                                        <strong>{{ $item->user->name ?? 'Admin' }}</strong>
                                                    </div>
                                                    <div class="message mb-2">
                                                        {{ $item->message }}
                                                    </div>
                                                    <div class="float-end">
                                                        <small
                                                            class="text-muted">{{ date('d M , Y', strtotime($item->created_at)) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="px-3">
                                        <textarea name="message" class="form-control mb-2" cols="30" placeholder="Write..." rows="3" required></textarea>
                                        @error('message')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div style="float:right;">
                                            <button type="submit" class="btn btn-success px-5 py-2 mb-4">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('partials.admin.status')
    <script>
        function scrollToBottom() {
            var messageContainer = document.querySelector(
                ".message_box");
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        function scrollPageToBottom() {
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: "smooth"
            });
        }
        scrollToBottom();
        scrollPageToBottom();
    </script>
@endsection
