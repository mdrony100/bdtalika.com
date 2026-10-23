@extends('layouts.admin')

@section('content')
<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add New Directory') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.listing.index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">{{ __('Directory') }}</a></li>
    </ol>
  </div>
</div>

{{-- <div class="row my-3">
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.listing.create',['type'=>'restaurant']) }}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@lang('Restaurant')</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>@lang('Create restaurant directory')</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-glass-cheers fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.listing.create',['type'=>'hotel']) }}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@lang('Hotel')</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>@lang('Create hotel directory')</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pizza-slice fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.listing.create',['type'=>'beauty']) }}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@lang('Beauty')</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>@lang('Create beauty directory')</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paint-roller fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.listing.create',['type'=>'real_estate']) }}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@lang('Real Estate')</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>@lang('Create beauty directory')</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.listing.create',['type'=>'hospitals']) }}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@lang('Hospitals')</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>@lang('Create hospitals directory')</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stethoscope fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('admin.listing.create',['type'=>'car']) }}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@lang('Car')</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>@lang('Create job directory')</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>


</div> --}}

<div class="my-3">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.listing.create.type') }}" method="post" class="pt-5 pb-5">
                @csrf
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-7 col-xl-5">
                        <div class="form-group">
                            <label for="" class="form-label">Business type <span class="text-danger">*</span></label>
                            <select name="type_id" id="type_id" class="form-control  form-select" required>
                                <option value="" class="d-none">Please select a type</option>
                                @foreach ($listing_types as $key => $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control select2 form-select" required>
                                <option value="" class="d-none">Select Category</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
 </div>
@endsection

@section('scripts')
    <script>
        // type to category
        $(document).ready(function() {
            $(document).on('change', '#type_id', function() {
                let id = $(this).val();
                if (id !== "") {
                    $.ajax({
                        url: "{{ route('admin.businesstype.to.category') }}/" + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="category_id"]').empty();
                            $('select[name="category_id"]').append(
                                '<option value="" class="d-none">Select Category</option>');
                            $.each(data, function(key, value) {
                                $('select[name="category_id"]').append(
                                    '<option value="' + value.id + '">' + value
                                    .title + '</option>'
                                );
                            });
                        }
                    });
                } else {
                    $('select[name="category_id"]').empty();
                }
            });
        });
    </script>
@endsection
 
