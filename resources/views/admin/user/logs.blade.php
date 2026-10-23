@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('User Logs') }} - {{ $user->name }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('User List') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Logs') }}</li>
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
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Admin') }}</th>
                                <th>{{ __('IP Address') }}</th>
                                <th>{{ __('Method') }}</th>
                                <th>{{ __('Data') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    <td><span class="badge badge-info">{{ $log->action }}</span></td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->admin->name ?? '-' }}</td>
                                    <td>{{ $log->ip_address ?? '-' }}</td>
                                    <td>{{ $log->method ?? '-' }}</td>
                                    <td>
                                        @if (!empty($log->data))
                                            <pre class="mb-0" style="white-space: pre-wrap; word-break: break-word;">{{ is_array($log->data) ? json_encode($log->data, JSON_PRETTY_PRINT) : $log->data }}</pre>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No logs found.') }}</td>
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
@endsection
