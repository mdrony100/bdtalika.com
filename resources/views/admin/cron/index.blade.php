@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Cron Logs') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Cron Logs') }}</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('cron.update.missing.whatsapp.listings.ai') }}" target="_blank" class="btn btn-info btn-sm">
                            {{ __('Run AI Listing Update Cron') }}
                        </a>
                        <span class="text-muted ml-2">{{ route('cron.update.missing.whatsapp.listings.ai') }}</span>
                    </div>

                    <div class="d-flex flex-wrap align-items-start justify-content-between mb-3">
                        <form method="GET" action="{{ route('admin.cron.logs') }}" class="form-inline">
                            <select name="job" class="form-control mr-2 mb-2">
                                <option value="">{{ __('All Jobs') }}</option>
                                @foreach ($jobs as $job)
                                    <option value="{{ $job }}" {{ request('job') == $job ? 'selected' : '' }}>{{ $jobLabels[$job] ?? $job }}</option>
                                @endforeach
                            </select>
                            <select name="status" class="form-control mr-2 mb-2">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>{{ __('Success') }}</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            </select>
                            <button type="submit" class="btn btn-primary mb-2">{{ __('Filter') }}</button>
                            <a href="{{ route('admin.cron.logs') }}" class="btn btn-secondary ml-2 mb-2">{{ __('Reset') }}</a>
                        </form>

                        <form method="POST" action="{{ route('admin.cron.logs.clear') }}" class="form-inline mb-2"
                            onsubmit="return confirm('{{ __('Are you sure you want to delete selected cron logs?') }}');">
                            @csrf
                            @method('DELETE')
                            <select name="job" class="form-control mr-2 mb-2">
                                <option value="">{{ __('All Log Types') }}</option>
                                @foreach ($jobs as $job)
                                    <option value="{{ $job }}">{{ $jobLabels[$job] ?? $job }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> {{ __('Delete Logs') }}
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Job') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Processed') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Message') }}</th>
                                    <th>{{ __('Details') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                        <td><span class="badge badge-secondary">{{ $jobLabels[$log->job] ?? $log->job }}</span></td>
                                        <td>
                                            @if ($log->status === 'success')
                                                <span class="badge badge-success">{{ __('Success') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('Failed') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->processed }}</td>
                                        <td>{{ $log->duration !== null ? $log->duration . 's' : '-' }}</td>
                                        <td>{{ $log->message ?? '-' }}</td>
                                        <td class="text-center">
                                            @if (!empty($log->stats))
                                                <a href="javascript:;" class="btn btn-sm btn-primary view-cron-details mb-1"
                                                    data-toggle="modal" data-target="#cronDetailsModal"
                                                    data-job="{{ e($log->job) }}"
                                                    data-date="{{ $log->created_at->format('d M Y, h:i A') }}"
                                                    data-status="{{ e($log->status) }}"
                                                    data-message="{{ e($log->message ?? '-') }}"
                                                    data-details-id="cron-details-{{ $log->id }}">
                                                    <i class="fas fa-eye"></i> {{ __('View') }}
                                                </a>
                                                <pre id="cron-details-{{ $log->id }}" style="display:none;">{{ json_encode($log->stats, JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                -
                                            @endif
                                            <form method="POST" action="{{ route('admin.cron.logs.delete', $log->id) }}" class="d-inline"
                                                onsubmit="return confirm('{{ __('Delete this cron log?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mb-1">
                                                    <i class="fas fa-trash-alt"></i> {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('No cron logs found.') }}</td>
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

    <div class="modal fade" id="cronDetailsModal" tabindex="-1" role="dialog" aria-labelledby="cronDetailsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cronDetailsModalTitle">{{ __('Cron Log Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-3">
                        <tr><th style="width: 130px;">{{ __('Date') }}</th><td id="cronModalDate"></td></tr>
                        <tr><th>{{ __('Job') }}</th><td id="cronModalJob"></td></tr>
                        <tr><th>{{ __('Status') }}</th><td id="cronModalStatus"></td></tr>
                        <tr><th>{{ __('Message') }}</th><td id="cronModalMessage"></td></tr>
                    </table>
                    <div id="cronListingLinksWrap" class="mb-3" style="display:none;">
                        <h6>{{ __('Listing Public Pages') }}</h6>
                        <div id="cronListingLinks" class="d-flex flex-wrap"></div>
                    </div>
                    <h6>{{ __('Details') }}</h6>
                    <pre id="cronModalDetails" style="max-height: 50vh; overflow: auto; background:#f8f9fa; padding:10px; font-size:12px; white-space: pre-wrap; word-break: break-word;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.view-cron-details', function () {
            var d = $(this).data();
            var details = $('#' + d.detailsId).text() || '-';

            $('#cronModalDate').text(d.date || '-');
            $('#cronModalJob').text(d.job || '-');
            $('#cronModalStatus').text(d.status || '-');
            $('#cronModalMessage').text(d.message || '-');
            $('#cronModalDetails').text(details);

            $('#cronListingLinks').empty();
            $('#cronListingLinksWrap').hide();

            try {
                var parsed = JSON.parse(details);
                var listings = [];

                if (Array.isArray(parsed.updated)) {
                    listings = listings.concat(parsed.updated);
                }

                if (Array.isArray(parsed.failed)) {
                    listings = listings.concat(parsed.failed);
                }

                listings.forEach(function (listing) {
                    if (!listing.public_url) {
                        return;
                    }

                    var title = listing.new_title || listing.title || listing.old_title || ('Listing #' + listing.id);
                    var button = $('<a></a>')
                        .attr('href', listing.public_url)
                        .attr('target', '_blank')
                        .addClass('btn btn-sm btn-outline-primary mr-2 mb-2')
                        .text('#' + listing.id + ' - ' + title);

                    $('#cronListingLinks').append(button);
                });

                if ($('#cronListingLinks').children().length > 0) {
                    $('#cronListingLinksWrap').show();
                }
            } catch (e) {
                $('#cronListingLinksWrap').hide();
            }
        });
    </script>
@endsection
