@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('CRM - Leads') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item">{{ __('CRM - Leads') }}</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            @include('includes.admin.form-success')

            <div class="card mb-4">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs px-3 pt-2" id="crmTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tocall-tab" data-toggle="tab" href="#tocall" role="tab">{{ __('To Call') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="followup-tab" data-toggle="tab" href="#followup" role="tab">{{ __('Follow Up') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="endjourney-tab" data-toggle="tab" href="#endjourney" role="tab">{{ __('End Lead Journey') }}</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content p-3">
                    {{-- TO CALL --}}
                    <div class="tab-pane fade show active" id="tocall" role="tabpanel">
                        <p class="text-muted mb-2">{{ __('Non-paid customers with a business listing who need a follow-up call.') }}</p>
                        <div class="table-responsive">
                            <table id="tocalltable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Business') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    {{-- FOLLOW UP --}}
                    <div class="tab-pane fade" id="followup" role="tabpanel">
                        <p class="text-muted mb-2">{{ __('Leads being nurtured: Interested or scheduled for a next-day recall.') }}</p>
                        <div class="row px-0 pt-2 pb-3">
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <select id="followup_status_filter" class="form-control">
                                    <option value="all">{{ __('All') }}</option>
                                    <option value="interested">{{ __('Interested') }}</option>
                                    <option value="next_day_recall">{{ __('Next Day Recall') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="followuptable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Business') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Comment') }}</th>
                                        <th>{{ __('Last Called') }}</th>
                                        <th>{{ __('Next Recall') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    {{-- END LEAD JOURNEY --}}
                    <div class="tab-pane fade" id="endjourney" role="tabpanel">
                        <p class="text-muted mb-2">{{ __('Closed leads: Paid (converted) or Not Interested (rejected). No further follow-up needed.') }}</p>
                        <div class="row px-0 pt-2 pb-3">
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <select id="endjourney_status_filter" class="form-control">
                                    <option value="all">{{ __('All') }}</option>
                                    <option value="paid">{{ __('Paid') }}</option>
                                    <option value="not_interested">{{ __('Not Interested') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="endjourneytable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Business') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Comment') }}</th>
                                        <th>{{ __('Last Called') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SET STATUS MODAL --}}
    <div class="modal fade" id="setStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="setStatusForm">
                    {{ csrf_field() }}
                    <input type="hidden" name="listing_id" id="modal_listing_id">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Follow-up Status') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('Status') }}</label>
                            <select name="status" id="modal_status" class="form-control" required>
                                <option value="">{{ __('Select status') }}</option>
                                <option value="interested">{{ __('Interested') }}</option>
                                <option value="next_day_recall">{{ __('Next Day Recall') }}</option>
                                <option value="not_interested">{{ __('Not Interested') }}</option>
                                <option value="paid">{{ __('Paid') }}</option>
                            </select>
                        </div>
                        <div class="form-group" id="recall_date_group" style="display:none;">
                            <label>{{ __('Recall Date') }}</label>
                            <input type="date" name="next_recall_date" id="modal_recall_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Comment / Notes') }}</label>
                            <textarea name="comment" id="modal_comment" class="form-control" rows="4" placeholder="{{ __('Call notes for this lead...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Status') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        'use strict';

        var tocallTable = $('#tocalltable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('admin.crm.tocall.datatables') }}",
            columns: [
                {
                    data: 'user_name',
                    name: 'user_name',
                    render: function (data, type, row) {
                        return row.user_id ? '<a href="' + row.customer_link + '">' + data + '</a>' : data;
                    }
                },
                { data: 'business_name', name: 'business_name' },
                { data: 'phone', name: 'phone' },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            language: {
                processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
            }
        });

        var followupTable = $('#followuptable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('admin.crm.followup.datatables') }}",
                data: function (d) {
                    d.status_filter = $('#followup_status_filter').val();
                }
            },
            columns: [
                {
                    data: 'user_name',
                    name: 'user_name',
                    render: function (data, type, row) {
                        return row.user_id ? '<a href="' + row.customer_link + '">' + data + '</a>' : data;
                    }
                },
                { data: 'business_name', name: 'business_name' },
                { data: 'phone', name: 'phone' },
                { data: 'status_badge', name: 'status_badge', searchable: false, orderable: false },
                { data: 'comment', name: 'comment' },
                { data: 'last_called', name: 'last_called' },
                { data: 'next_recall', name: 'next_recall' },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            language: {
                processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
            }
        });

        var endjourneyTable = $('#endjourneytable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('admin.crm.endjourney.datatables') }}",
                data: function (d) {
                    d.status_filter = $('#endjourney_status_filter').val();
                }
            },
            columns: [
                {
                    data: 'user_name',
                    name: 'user_name',
                    render: function (data, type, row) {
                        return row.user_id ? '<a href="' + row.customer_link + '">' + data + '</a>' : data;
                    }
                },
                { data: 'business_name', name: 'business_name' },
                { data: 'phone', name: 'phone' },
                { data: 'status_badge', name: 'status_badge', searchable: false, orderable: false },
                { data: 'comment', name: 'comment' },
                { data: 'last_called', name: 'last_called' },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            language: {
                processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
            }
        });

        $('#followup_status_filter').on('change', function () {
            followupTable.ajax.reload();
        });

        $('#endjourney_status_filter').on('change', function () {
            endjourneyTable.ajax.reload();
        });

        function toggleRecallField() {
            if ($('#modal_status').val() === 'next_day_recall') {
                $('#recall_date_group').show();
            } else {
                $('#recall_date_group').hide();
            }
        }

        $(document).on('click', '.set-status-btn', function () {
            $('#modal_listing_id').val($(this).data('listing'));
            $('#modal_status').val($(this).data('status') || '');
            $('#modal_comment').val($(this).data('comment') || '');
            $('#modal_recall_date').val($(this).data('recall') || '');
            toggleRecallField();
            $('#setStatusModal').modal('show');
        });

        $('#modal_status').on('change', toggleRecallField);

        $('#setStatusForm').on('submit', function (e) {
            e.preventDefault();
            var $btn = $(this).find('button[type=submit]');
            $btn.prop('disabled', true);

            $.ajax({
                method: 'POST',
                url: "{{ route('admin.crm.update.status') }}",
                data: $(this).serialize(),
                success: function () {
                    $('#setStatusModal').modal('hide');
                    $('#setStatusForm')[0].reset();
                    tocallTable.ajax.reload(null, false);
                    followupTable.ajax.reload(null, false);
                    endjourneyTable.ajax.reload(null, false);
                },
                error: function () {
                    alert('{{ __('Something went wrong. Please try again.') }}');
                },
                complete: function () {
                    $btn.prop('disabled', false);
                }
            });
        });
    </script>
@endsection
