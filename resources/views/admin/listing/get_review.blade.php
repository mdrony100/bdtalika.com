@extends('layouts.admin')
@section('styles')
    <style>
        .ctm_btn {
            border-radius: 0px;
            border-left: 0;
            box-shadow: none !important;
            background: #6777ef;
            color: #fff;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #eaecf4 !important;
            opacity: 1;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Directory') }}
                <a class="btn btn-primary btn-rounded btn-sm" href="{{ route('admin.listing.index') }}"><i
                        class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
            </h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.listing.edit', $data->id) }}">{{ __('Listing Reviews') }}</a>
                </li>
            </ol>
        </div>
    </div>


    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            @if ($listing_id)
            @else
                <div class="card mb-2 py-3">
                    <div class="card-body">
                        <form action="{{ route('admin.listing.reviewFetch', $data->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" hidden name="listing_id" value="{{ $data->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Lasting Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $data->name }}" required
                                            name="listing_name">
                                    </div>
                                    @error('listing_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Lasting Address <span
                                                class="text-danger">*</span></label> 
                                        <input type="text" class="form-control" name="address" required
                                            value="{{ $data->real_address }}">
                                    </div>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="ctm_btn btn btn-primary input-group-text">
                                        Scrap Review
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            @endif
            <div class="card mb-2">
                @if (isset($reviews) && $reviews->count() > 0)
                    <form action="{{ route('admin.listing.review.update', $data->id) }}" method="post">
                        @csrf
                        @foreach ($reviews as $review)
                            <input type="text" hidden name="review_id[]" value="{{ $review->id }}">
                            <div class="card-body pb-0">
                                <div class="">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="" class="form-label">Image</label> <br>
                                            <img src="{{ $review['user_photo'] ?? asset('assets/front/img/t-7.png') }}"
                                                width="100" height="100" alt="User Photo">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="form-label">Name</label>
                                            <input type="text" name="name[]" id="name"
                                                value="{{ $review['name'] ?? '' }}" readonly class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="form-label">Rating</label>
                                            <input type="text" name="rating[]" id="rating"
                                                value="{{ $review['rate'] ?? '' }}" readonly class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label for="" class="form-label">Message</label>
                                            <textarea name="message[]" id="message" cols="30" class="form-control" rows="5">{{ $review['message'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center py-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                @endif

            </div>


        </div>
    </div>
@endsection
