@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Email Logs') }} - {{ $user->name }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('User List') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Email Logs') }}</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('To') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th class="text-center">{{ __('View') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    <td>{{ $log->email }}</td>
                                    <td>
                                        <a href="javascript:;" class="view-email"
                                            data-toggle="modal" data-target="#emailBodyModal"
                                            data-subject="{{ e($log->subject) }}"
                                            data-date="{{ $log->created_at->format('d M Y, h:i A') }}"
                                            data-body-id="email-body-{{ $log->id }}">
                                            {{ $log->subject ?? __('(No subject)') }}
                                        </a>
                                        <div id="email-body-{{ $log->id }}" style="display:none;">{!! $log->body !!}</div>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:;" class="btn btn-sm btn-primary view-email"
                                            data-toggle="modal" data-target="#emailBodyModal"
                                            data-subject="{{ e($log->subject) }}"
                                            data-date="{{ $log->created_at->format('d M Y, h:i A') }}"
                                            data-body-id="email-body-{{ $log->id }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ __('No emails found for this user.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 pb-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- EMAIL BODY MODAL --}}
    <div class="modal fade" id="emailBodyModal" tabindex="-1" role="dialog" aria-labelledby="emailBodyModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1" id="emailModalSubject"></h5>
                        <small class="text-muted" id="emailModalDate"></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="emailModalBody" style="max-height: 65vh; overflow-y: auto;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- EMAIL BODY MODAL ENDS --}}
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.view-email', function () {
            var subject = $(this).data('subject');
            var date = $(this).data('date');
            var bodyId = $(this).data('body-id');

            $('#emailModalSubject').text(subject || '(No subject)');
            $('#emailModalDate').text(date);
            $('#emailModalBody').html($('#' + bodyId).html());
        });
    </script>
@endsection
