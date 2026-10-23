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
<div class="my-3">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.listing.update.type', $data->id) }}" method="post" class="pt-5 pb-5">
                @csrf
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-7 col-xl-5">
                        <div class="form-group">
                            <label for="" class="form-label">Business type <span class="text-danger">*</span></label>
                            <select name="type_id" id="type_id" class="form-control  form-select" required>
                                @foreach ($listing_types as $key => $type)
                                    <option value="{{ $type->id }}" {{ $type->id  == $data->type ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control select2 form-select" required>
                                @foreach ($categories as $key => $cat)
                                    <option value="{{ $cat->id }}" {{ $cat->id  == $data->category_id ? 'selected' : '' }}>
                                        {{ $cat->title }}
                                    </option>
                                @endforeach
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
 
