@extends('layouts.front')
@section('ticket', 'active')
@push('css')
    <style>
        .card-header {
            background: #fafafa;
            padding: 15px 12px;
            border-color: #ebebeb;
        }

        .card {
            border: 1px solid #EEE;
        }
    </style>
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
                                                    @lang('View Support Ticket')
                                                </h4>
                                            </div>
                                            <div class="">
                                                <a href="{{ route('user.ticket.index') }}"
                                                    class="btn btn-primary btn-sm px-3 py-1">Back</a>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <ul class="m-0 p-0">
                                        <li class="py-2">Subject: {{ $ticket->subject }}</li>
                                        <li class="py-2">Status: <span
                                                class="badge bg-{{ $ticket->status == '1' ? 'success' : 'danger' }}">{{ $ticket->status == '1' ? 'Open' : 'Closed' }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title m-0">Message</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('user.ticket.message', $ticket->id) }}"  method="post">
                                                @csrf
                                                <div class="bg-light message_box rounded p-3 mb-3"
                                                    style="max-height: 500px; overflow: hidden; overflow-y: auto;">
                                                    @foreach ($ticket_messages as $item)
                                                        <div class="mb-2 overflow-hidden">
                                                            <div
                                                                class="border {{ $item->user_id != '0' ? 'float-end' : '' }} p-3 bg-white rounded float-start">
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
                                                @if ($ticket->status != '0')
                                                    <div class="">
                                                        <textarea name="message" class="form-control mb-2" cols="30" placeholder="Write..." rows="3" required></textarea>
                                                        @error('message')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        <div style="float:right;">
                                                            <button type="submit"
                                                                class="btn btn-success px-5 py-2">Send</button>
                                                        </div>
                                                    </div>
                                                @endif
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
@endsection

@push('js')
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
@endpush
