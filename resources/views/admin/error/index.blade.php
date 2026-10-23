@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('500 Errors') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('500 Errors') }}</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">{{ __('Server errors your visitors have faced.') }}</span>
                        @if ($logs->total() > 0)
                            <a href="{{ route('admin.error.logs.clear') }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('{{ __('Clear all error logs?') }}')">
                                <i class="fas fa-trash"></i> {{ __('Clear All') }}
                            </a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('URL') }}</th>
                                    <th>{{ __('Message') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('IP') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                        <td><span class="badge badge-danger">{{ $log->status_code }}</span></td>
                                        <td style="max-width: 220px; word-break: break-all;">
                                            <span title="{{ $log->url }}">{{ \Illuminate\Support\Str::limit($log->url, 60) }}</span>
                                        </td>
                                        <td style="max-width: 260px; word-break: break-word;">
                                            {{ \Illuminate\Support\Str::limit($log->message, 90) }}
                                        </td>
                                        <td>{{ $log->user_id ? ($log->user->name ?? '#' . $log->user_id) : __('Guest') }}</td>
                                        <td>{{ $log->ip_address ?? '-' }}</td>
                                        <td class="text-center" style="white-space: nowrap;">
                                            <a href="javascript:;" class="btn btn-sm btn-primary view-error"
                                                data-toggle="modal" data-target="#errorModal"
                                                data-code="{{ $log->status_code }}"
                                                data-date="{{ $log->created_at->format('d M Y, h:i A') }}"
                                                data-exception="{{ e($log->exception) }}"
                                                data-message="{{ e($log->message) }}"
                                                data-location="{{ e($log->file . ':' . $log->line) }}"
                                                data-url="{{ e($log->url) }}"
                                                data-method="{{ e($log->method) }}"
                                                data-agent="{{ e($log->user_agent) }}"
                                                data-trace-id="error-trace-{{ $log->id }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.error.logs.delete', $log->id) }}"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('{{ __('Delete this error log?') }}')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <pre id="error-trace-{{ $log->id }}" style="display:none;">{{ $log->trace }}</pre>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('No errors logged. Everything is running smoothly.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-3 pb-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- ERROR DETAIL MODAL --}}
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="badge badge-danger" id="errModalCode"></span> {{ __('Error Detail') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-3">
                        <tr><th style="width: 130px;">{{ __('Date') }}</th><td id="errModalDate"></td></tr>
                        <tr><th>{{ __('Exception') }}</th><td id="errModalException"></td></tr>
                        <tr><th>{{ __('Message') }}</th><td id="errModalMessage"></td></tr>
                        <tr><th>{{ __('Location') }}</th><td id="errModalLocation"></td></tr>
                        <tr><th>{{ __('URL') }}</th><td id="errModalUrl" style="word-break: break-all;"></td></tr>
                        <tr><th>{{ __('Method') }}</th><td id="errModalMethod"></td></tr>
                        <tr><th>{{ __('User Agent') }}</th><td id="errModalAgent" style="word-break: break-all;"></td></tr>
                    </table>
                    <h6>{{ __('Stack Trace') }}</h6>
                    <pre id="errModalTrace" style="max-height: 40vh; overflow: auto; background:#f8f9fa; padding:10px; font-size:12px;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ERROR DETAIL MODAL ENDS --}}
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.view-error', function () {
            var d = $(this).data();
            $('#errModalCode').text(d.code);
            $('#errModalDate').text(d.date);
            $('#errModalException').text(d.exception || '-');
            $('#errModalMessage').text(d.message || '-');
            $('#errModalLocation').text(d.location || '-');
            $('#errModalUrl').text(d.url || '-');
            $('#errModalMethod').text(d.method || '-');
            $('#errModalAgent').text(d.agent || '-');
            $('#errModalTrace').text($('#' + d.traceId).text());
        });
    </script>
@endsection
