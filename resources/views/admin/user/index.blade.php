@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('User List') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>

                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('User List') }}</a></li>
            </ol>
        </div>
    </div>


    <!-- Row -->
    <div class="row mt-3">
        <!-- Datatables -->
        <div class="col-lg-12">

            @include('includes.admin.form-success')

            <div class="card mb-4">
                <div class="row px-3 pt-3">
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <select id="listing_filter" class="form-control">
                            <option value="all">{{ __('All Customers') }}</option>
                            <option value="has">{{ __('Has Business') }}</option>
                            <option value="none">{{ __('No Business') }}</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive p-3">
                    <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Created') }}</th>
                                <th>{{ __('Verified Email') }}</th>
                                <th>{{ __('Consent') }}</th>
                                <th>{{ __('status') }}</th>
                                <th>{{ __('Options') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- DataTable with Hover -->

    </div>
    <!--Row-->

    {{-- STATUS MODAL --}}

    <div class="modal fade confirm-modal" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update Status') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to change the status.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</a>
                    <a href="javascript:;" class="btn btn-success btn-ok">{{ __('Update') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS MODAL ENDS --}}

    {{-- ATTRIBUTE MODAL --}}
    <div class="modal fade" id="attribute" tabindex="-1" role="dialog" aria-labelledby="attribute" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/' . $gs->admin_loader) }}" alt="admin">
                </div>

                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade confirm-modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        {{ __('You are about to delete this User.') }}
                    </p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</a>
                    <a href="javascript:;" class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
                </div>
            </div>
        </div>
    </div>


    <div class="sub-categori">
        <div class="modal fade confirm-modal" id="vendorform" tabindex="-1" role="dialog"
            aria-labelledby="vendorformLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel">{{ __('Send Message') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply1">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <input type="email" class="form-control" id="eml1" name="to"
                                                    placeholder="{{ __('Email') }}" readonly value=""
                                                    required="">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="subj1" name="subject"
                                                    placeholder="{{ __('Subject') }}" value="" required="">
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" name="message" id="msg1" cols="20" rows="6"
                                                    placeholder="{{ __('Your Message') }} "required=""></textarea>
                                            </div> <button class="submit-btn btn btn-primary text-center" id="emlsub1"
                                                type="submit">{{ __('Send Message') }}</button>
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
    
@endsection


@section('scripts')
    <script>
        'use strict';

        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('admin-user-datatables') }}",
                data: function (d) {
                    d.listing_filter = $('#listing_filter').val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name',
                    render: function(data, type, row) {
                        var socialLoginBadge = row.social_login == 'yes' ?
                            '<a href="javascript:void(0)" title="Login with Gmail"><img src="{{ asset('assets/images/google.png') }}" width="20px"></a>' :
                            '';
                        return data + ' (' + row.business_count + ') ' + socialLoginBadge;
                    }
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'created',
                    name: 'created'
                },
                {
                    data: 'email_verified',
                    name: 'email_verified',
                    render: function(data, type, row) {
                        if (data == 'Yes') {
                            return '<span class="badge badge-success">Yes</span>';
                        } else {
                            return '<span class="badge badge-danger">No</span>';
                        }
                    }
                },
                {
                    data: 'whatsapp_consent',
                    name: 'whatsapp_consent',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'status',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }
            ],
            language: {
                processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
            }
        });

        $('#listing_filter').on('change', function () {
            table.ajax.reload();
        });

        $(document).on('click', '.send', function () {
            $('#eml1').val($(this).data('email'));
        });

        $('#emailreply1').on('submit', function (e) {
            e.preventDefault();
            var button = $('#emlsub1');
            button.prop('disabled', true);

            $.ajax({
                method: 'POST',
                url: "{{ route('admin-user-send-email') }}",
                data: $(this).serialize(),
                success: function () {
                    $('#vendorform').modal('hide');
                    $('#emailreply1')[0].reset();
                    table.ajax.reload(null, false);
                },
                complete: function () {
                    button.prop('disabled', false);
                }
            });
        });
    </script>
@endsection
