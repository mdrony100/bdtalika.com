@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Claim Directories') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Claim Directories') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            @include('includes.admin.form-success')
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Listing Title') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                                @php
                                                    $createdAt = !empty($item->created_at) ? \Carbon\Carbon::parse($item->created_at) : null;
                                                @endphp
                                                {{ $createdAt ? $createdAt->format('l, d M Y - h:i A') : '-' }}
                                            </td>
                                      <td>  <a href="{{ route('admin.claim.listing.view', $item->id) }}"
                                            class="viewDetails btn btn-primary" data-toggle="modal"
                                            data-target="#detailsModal">View Details</a>
                                            @if($item->status == 2) 
                                                <span class="text-success">Owner Changed</span>
                                            @else
                                        <a href="{{ route('admin.claim.listing.solve', $item->id) }}"
                                            class="btn btn-success"
                                            onclick="return confirm('Are you sure? Ownership of this listing will be TRANSFERRED to the claimer (the registered user with this claim email) and the claim will be marked as resolved. Continue?')">Owner Bdtalika</a>
                                            @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade confirm-modal" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="statusModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <div id="dataHtml"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("body").on('click', '.viewDetails', function() {
                let url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        $('#dataHtml').html(response.data.details);
                    }
                })
            });
        });
    </script>
@endsection
