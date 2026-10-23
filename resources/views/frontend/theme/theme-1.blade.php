@extends('layouts.front')

@push('css')
@endpush

@section('content')
    @if (in_array('Banner', $home_modules))
        <div class="home-banner margin-bottom-0"
            style="background:#f41b3b url({{ asset('assets/images/' . $ps->hero_photo) }}) no-repeat;" data-overlay="4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-11 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="banner_caption text-center mb-5">
                            <h1 class="banner_title ft-bold mb-1">{{ $ps->hero_title }}</h1>
                            <p class="fs-md ft-medium">{{ $ps->hero_subtitle }}</p>
                        </div>

                        <form class="main-search-wrap fl-wrap" action="{{ route('front.listing') }}" method="GET">
                            <div class="main-search-item">
                                <input type="text" class="form-control radius" name="title"
                                    placeholder="Nail salons, plumbers, takeout...">
                            </div>
                            <div class="main-search-item">
                                <input type="text" class="form-control" name="real_address"
                                    placeholder="San Francisco, CA">
                            </div>
                            <div class="main-search-item">
                                <select class="form-control" name="category">
                                    <option value="">@lang('Choose Categories')</option>
                                    @foreach ($categories as $key => $category)
                                        <option value="{{ $category->slug }}">{{ $category->title }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="main-search-button">
                                <button class="btn full-width theme-bg text-white" type="submit">@lang('Search')<i
                                        class="fas fa-search"></i></button>
                            </div>
                        </form>

                        <div class="Rego-bnr-cats">
                            <ul>
                                @foreach ($categories as $key => $category)
                                    <li><a
                                            href="{{ route('front.listing', ['category' => [$category->slug]]) }}">{{ $category->title }}</a>
                                    <li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (in_array('Category', $home_modules))
        <!-- =========================== Listing Category ======================= -->
        <section class="gray middle min">
            <div class="container">

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                        <div class="sec_title position-relative text-center mb-5">
                            <h6 class="text-muted mb-0">{{ $ps->category_title }}</h6>
                            <h2 class="ft-bold">{{ $ps->category_subtitle }}</h2>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center">
                    @foreach ($categories as $key => $data)
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                            <div class="Rego-img-catg-wrap">
                                <div class="Rego-catg-city">{{ $data->listings->where('status', 1)->count() }}
                                    @lang('Listings')</div>
                                <div class="Rego-img-catg-thumb"><a
                                        href="{{ route('front.listing', ['category' => [$data->slug]]) }}"><img
                                            src="{{ asset('assets/images/' . $data->photo) }}" class="img-fluid"
                                            alt="{{ $data->title }}"></a></div>
                                <div class="Rego-img-catg-caption">
                                    <h4 class="fs-md mb-0 ft-medium m-catrio">{{ $data->title }}</h4>
                                    <a href="{{ route('front.listing', ['category' => [$data->slug]]) }}"
                                        class="Rego-cat-arrow"><i class="lni lni-arrow-right-circle"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
        <!-- =========================== Listing Category End ===================== -->
    @endif

    @if (in_array('Feature Directory', $home_modules))
        <!-- ===========================  Featured Listing ======================= -->
        <section class="space min">
            <div class="container">

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                        <div class="sec_title position-relative text-center mb-5">
                            <h2 class="ft-bold theme-cl">{{ $ps->directory_title }}</h2>
                        </div>
                    </div>
                </div>

                <!-- row -->
                <div class="row justify-content-center">
                    @foreach ($featured_listing as $key => $listing)
                        <!-- Single -->
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                            <div class="Rego-grid-wrap card">
                                <div class="Rego-grid-upper">
                                    <div class="Rego-bookmark-btn">
                                        <button type="button" class="wishList" data-listing="{{ $listing->id }}"
                                            data-user={{ auth()->id() }}>
                                            <i
                                                class="lni lni-heart{{ $listing->userFavourite(auth()->id(), $listing->id) ? '-filled' : '' }} position-absolute"></i>
                                        </button>
                                    </div>
                                    <div class="Rego-pos ab-left">
                                        {{-- <div class="Rego-status open me-2">{{ $listing->openClose($listing->id) }}</div> --}}
                                        @if ($listing->is_feature)
                                            <div class="Rego-featured-tag">@lang('Featured')</div>
                                        @endif
                                    </div>
                                    <div class="Rego-grid-thumb">
                                        <a href="{{ route('front.listing.details', $listing->slug) }}"
                                            class="d-block text-center m-auto">
                                            <img src="{{ asset('assets/images/' . $listing->photo) }}" class="img-fluid"
                                                alt=" {{ $listing->name }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="Rego-grid-fl-wrap">
                                    <div class="Rego-caption px-3 py-2">
                                        @if ($listing->user_id == null && $listing->admin_id == null)
                                            <div class="Rego-author">
                                                <a href="{{ route('front.author.details', 'admin') }}">
                                                    <img src="{{ asset('assets/images/' . getAdmin()->photo) }}"
                                                        class="img-fluid circle" alt="">
                                                </a>
                                            </div>
                                        @else
                                            <div class="Rego-author">
                                                <a href="{{ route('front.author.details', $listing->user->username) }}">
                                                    <img src="{{ asset('assets/images/' . $listing->user->photo) }}"
                                                        class="img-fluid circle" alt="{{ $listing->user->username }}">
                                                </a>
                                            </div>
                                        @endif

                                        <div class="Rego-cates">
                                            <a
                                                href="{{ route('front.listing', ['category' => $listing->category->slug]) }}">{{ $listing->category->title }}</a>
                                        </div>

                                        <h4 class="mb-0 ft-medium medium">
                                            <a href="{{ route('front.listing.details', $listing->slug) }}"
                                                class="text-dark fs-md">
                                                {{ $listing->name }}
                                                <span class="verified-badge">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            </a>
                                        </h4>
                                        <div class="Rego-middle-caption mt-3">
                                            <div class="Rego-location"><i
                                                    class="fas fa-map-marker-alt"></i>{{ $listing->real_address }}</div>
                                            <div class="Rego-call"><i
                                                    class="fas fa-phone-alt"></i>{{ $listing->phone_number }}</div>
                                        </div>
                                    </div>
                                    <div class="Rego-grid-footer py-3 px-3">
                                        {{-- @if (count($listing->reviews) > 0)
                                            <div class="Rego-ft-first">
                                                <div class="Rego-rating">
                                                    <div class="Rego-pr-average high">
                                                        {{ $listing->directoryRatting($listing->id) }}</div>
                                                    <div class="Rego-rates">
                                                        @php
                                                            $rate = ceil($listing->directoryRatting($listing->id));
                                                        @endphp

                                                        @for ($i = 1; $i <= $rate; $i++)
                                                            <i class="fas fa-star active"></i>
                                                        @endfor

                                                        @for ($i = 1; $i <= 5 - $rate; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        @endif --}}
                                        @if (count($listing->reviews) > 0)
                                            <div class="Rego-rating overlay">
                                                <div class="Rego-pr-average high">
                                                    {{ $listing->directoryRatting($listing->id) }}</div>
                                                <div class="Rego-aldeio">
                                                    <div class="Rego-rates">
                                                        @php
                                                            $rate = ceil($listing->directoryRatting($listing->id));
                                                        @endphp
                                                        @for ($i = 1; $i <= $rate; $i++)
                                                            <i class="fas fa-star active"></i>
                                                        @endfor
                                                        @for ($i = 1; $i <= 5 - $rate; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    <div class="Rego-all-review">
                                                        <span>{{ count($listing->reviews) }} @lang('Reviews')</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="Rego-ft-last">
                                            <span class="small">{{ $data->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- /row -->

            </div>
        </section>
        <!-- =========================== Featured Listing End ===================== -->
    @endif

    @if (in_array('Packages', $home_modules))
        <!-- =========================== Price Box ======================= -->
        <section class="bg-dark space min">
            <div class="container">

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                        <div class="sec_title position-relative text-center mb-5">
                            <h6 class="text-muted mb-0 text-light">{{ $ps->plan_title }}</h6>
                            <h2 class="ft-bold text-light">{{ $ps->plan_subtitle }}</h2>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">

                    @if (count($plans) > 0)
                        @foreach ($plans as $index => $data)
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                <div class="Rego-price-wrap {{ $index == 1 ? '' : 'dark-price' }}">
                                    <div class="Rego-author-header">
                                        <div class="Rego-price-currency">
                                            <h3>
                                                <span class="Rego-new-price"
                                                    style="color:{{ $data->price_color }}">{{ globalCurrency()->sign }}<del>{{ showAmount($data->price) }}</del></span>
                                                @if ($data->prev_price > 0)
                                                    <span
                                                        class="Rego-old-price">{{ globalCurrency()->sign }}<del>{{ showAmount($data->prev_price) }}</del></span>
                                                @endif
                                            </h3>
                                        </div>
                                        <div class="Rego-price-title">
                                            <div class="Rego-price-tlt">
                                                <h4>{{ $data->title }}</h4>
                                            </div>

                                        </div>
                                        <div class="Rego-price-subtitle">{{ $data->subtitle }}</div>
                                    </div>
                                    <div class="Rego-price-body">
                                        <ul class="price__features">
                                            @if ($data->attribute != null)
                                                @foreach (json_decode($data->attribute) as $key => $attribute)
                                                    <li><i class="fa fa-angle-right"></i>{{ $attribute }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="Rego-price-bottom">
                                        <a class="Rego-price-btn {{ $index == 1 ? 'active' : '' }}"
                                            href="{{ route('user.package.subscription', $data->id) }}"><i
                                                class="fas fa-shopping-basket"></i> @lang('Purchase Now') </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>

            </div>
        </section>
        <!-- ===========================  Price Box End ===================== -->
    @endif

    @if (in_array('Faq', $home_modules))
        <!-- =========================== FAQ Start ======================= -->
        <section>
            <div class="container">

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                        <div class="sec_title position-relative text-center mb-5">
                            <h6 class="text-muted mb-0">Ask Questions</h6>
                            <h2 class="ft-bold">Frequently Asked Questions</h2>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-11 col-md-12 col-12">
                        <div id="accordion2" class="accordion">
                            @foreach ($faqs as $key => $data)
                                <div class="card">
                                    <div class="card-header" id="h{{ $key }}">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-bs-toggle="collapse"
                                                data-bs-target="#ord{{ $key }}" aria-expanded="true"
                                                aria-controls="ord{{ $key }}">
                                                {{ $data->title }}
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="ord{{ $key }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                                        aria-labelledby="h{{ $key }}" data-parent="#accordion2">
                                        <div class="card-body">
                                            {{ $data->details }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- =========================== FAQ End ===================== -->
    @endif

    @if (in_array('CTAs', $home_modules))
        @include('partials.front.cta')
    @endif


    @include('partials.front.footer')
@endsection

@push('js')
    <script>
        'use strict';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.wishList', function() {
            let $this = $(this);
            let listingId = $(this).data('listing');
            let userId = $(this).data('user');
            if (userId == '') {
                window.location.href = mainurl + '/user/login'
            }

            $.ajax({
                method: "POST",
                url: mainurl + '/listing/wishlist',
                data: {
                    listing_id: listingId,
                    user_id: userId
                },
                success: function(data) {
                    if (data.success) {
                        $this.children().prop('class', '');
                        $this.children().prop('class', 'lni lni-heart-filled  position-absolute');
                        toastr.success(data.success);
                    } else {
                        $this.children().prop('class', '');
                        $this.children().prop('class', 'lni lni-heart  position-absolute');
                        toastr.error(data.error);
                    }
                }

            });

        })
    </script>
@endpush
