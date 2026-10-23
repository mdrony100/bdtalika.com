@extends('layouts.front')
@php
    $selectArea = $area_query_display ?? 'Bangladesh';
    $selectCategory = $category_query_display ?? 'Select Category';
@endphp
@push('meta_info')
    <title>{{ $seo_title ?? (($meta_title ?? '') . ' ' . ($selected_area ?? '')) }}</title>
    <meta property="og:title" content="{{ $seo_title ?? (($meta_title ?? '') . ' ' . ($selected_area ?? '')) }}">
    @if (isset($meta_description))
        <meta name="description" content="{{ strip_tags($meta_description) }}">
        <meta property="og:description" content="{{ strip_tags($meta_description) }}">
    @else
        <meta name="description"
            content="Explore the complete Bangladesh business directory list on BDTalika. Find companies, hospitals, universities, restaurants, hotels and services across Bangladesh. Submit your company to our free business listing website in Bangladesh.">
        <meta property="og:description"
            content="Explore the complete Bangladesh business directory list on BDTalika. Find companies, hospitals, universities, restaurants, hotels and services across Bangladesh. Submit your company to our free business listing website in Bangladesh.">
        @endif @if (isset($gs->meta_keyword))
            <meta name="keywords" content="{{ $gs->meta_keyword }}">
        @endif
    @endpush
    @push('css')
        <style>
            .no-ul-list.filter-list {
                max-height: 400px;
                overflow: hidden;
                overflow-y: auto;
            }

            .ft-medium.medium h1 {
                line-height: 17px;
                margin-bottom: 22px;
            }

            .header_card {
            background: #f8f8f8;
            padding: 14px 12px;
            border-radius: 6px !important;
        }

            .header_card h1 {
                font-size: 20px;
                line-height: 22px;
            }

            a.disabled {
                color: #A0A0A0 !important;
                pointer-events: none;
                cursor: default;
            }

            @media screen and (max-width:575px) {
                .header_card h1 {
                    font-size: 14px;
                }
            }
            .bottomtext p {
                font-size: 18px;
                color: #01132d;
                line-height: 30px;
                text-align: center;
            }
            .bottomtext h2 {
                text-align: center;
                font-size: 24px;
                font-weight: bold;
            }
        </style>
    @endpush
    @section('content')
        <div class="bg-light py-2">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 colz-lg-12 col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="color_link"
                                    href="{{ route('front.listing') }}">Bangladesh</a></li>
                                    @if (empty($category_query_display) && empty($area_query_display)) 
                                        <li class="breadcrumb-item">
                                            <a class=""
                                            href="#">All Listings</a>
                                        </li>
                                    @endif
                                @if (!empty($category_query_display))
                                    <li class="breadcrumb-item"><a class="color_link"
                                            href="{{ route('front.category.directory') }}">Categories</a></li>
                                    <li class="breadcrumb-item"><span>{{ $category_query_display }}</span></li>
                                @endif
                                @if (empty($category_query_display))
                                    @if (!empty($area_query_display))
                                        <li class="breadcrumb-item"><a class="color_link"
                                                href="{{ route('front.cities.directory') }}">Cities</a></li>
                                        <li class="breadcrumb-item"><span>{{ $area_query_display }}</span></li>
                                    @endif
                                @endif

                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="bg-white py-3">
            <div class="container">
                <form onsubmit="event.preventDefault()">
                    <div class="header_card mb-3 rounded">
                        <h1>{{ $meta_title ?? '' }} {{ $selected_area ?? '' }}</h1>
                        @if(!empty($meta_description))
                        <p class="mb-0 mt-1 small">{!! strip_tags($meta_description) !!}</p>
                        @endif
                        @if(!empty($related_guides) && count($related_guides))
                        <div class="mt-1 small">
                            <span class="fw-bold">Guide:</span>
                            @foreach($related_guides as $guide)
                            <a class="ms-1" href="{{ route('blog.details', $guide->slug) }}">{{ $guide->title }}</a>@if(!$loop->last) &middot; @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="banner_section pb-3">
                        <div class="d-lg-flex align-items-center">
                            <div class="d-flex">
                                <div class="mb-2 mb-lg-0">
                                    <button type="button" class="modal_btn d-flex align-items-center me-2 me-sm-0"
                                        data-bs-toggle="modal" data-bs-target="#location_modal">
                                        <img src="{{ asset('assets/images/icon/location.svg') }}" class="pe-1"
                                            alt="Location">
                                        <span>{{ $area_query_display ?? 'All of Bangladesh' }}</span>
                                    </button>
                                </div>
                                <div class="mb-2 mb-lg-0">
                                    <button type="button" class="modal_btn d-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#category_modal">
                                        <img src="{{ asset('assets/images/icon/tag.svg') }}" class="pe-1" alt="Location">
                                        <span>{{ $category_query_display ?? 'Select Category' }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="from-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keywords"
                                        value="{{ request()->get('keywords') }}" placeholder="Search by business name..."
                                        style="padding-right: 50px;">
                                    <button type="submit" class="input-group-text" id="key_search">
                                        <img src="{{ asset('assets/images/icon/search.svg') }}" alt="Location">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 order-lg-2">
                        <div class="bg-white rounded mb-4">
                            <div class="sidebar-widgets collapse miz_show" id="search_open" data-bs-parent="#search_open">
                                <div class="search-inner">
                                    <div class="side-filter-box">
                                        <div class="side-filter-box-body">
                                            <div class="inner_widget_link">
                                                <h6 class="ft-medium">@lang('Find by Categories')</h6>
                                                <ul class="no-ul-list filter-list">
                                                    @foreach ($all_categories as $key => $category)
                                                        <li class="pb-1">
                                                            <a class="{{ $category->listings->count() == 0 ? 'disabled' : '' }}"
                                                                href="{{ route('front.listing', ['area' => $area_query, 'category' => $category->slug]) }}">
                                                                {{ $category->title }}
                                                                ({{ $category->listings->count() }})
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="inner_widget_link">
                                                <h6 class="ft-medium">@lang('Find by Locations')</h6>
                                                <ul class="no-ul-list filter-list">
                                                    @foreach ($divisions as $key => $location)
                                                        <li class="pb-1">
                                                            <a class="{{ $location->listings->count() == 0 ? 'disabled' : '' }}"
                                                                href="{{ route('front.listing', ['area' => $location->slug, 'category' => $get_category_query]) }}">
                                                                {{ $location->name }} ({{ $location->listings->count() }})
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 order-lg-1">
                        <div id="show_search_items">@includeIf('partials.front.listing')</div>
                    </div>
                </div>
                @if (!empty($related_categories) && count($related_categories))
                    <div class="popular_categories pb-5 pt-5">
                        <h4><strong>Related Categories</strong></h4>
                        <div class="list">
                            <ul class="m-0 p-0">
                                @foreach ($related_categories as $key => $cat)
                                    <li><a class="{{ $cat->listings->count() == '0' ? 'disabled' : '' }}"
                                            href="{{ route('front.listing', ['area' => $area_query, 'category' => $cat->slug]) }}">
                                            {{ $cat->title }} ({{ $cat->listings->count() }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <div class="bottomtext">
                        <div class="listing-seo-content">
                        <h2>Bangladesh Business Directory List – Find Companies & Services</h2>

                        <p>
                        BDTalika is one of the leading <strong>Bangladesh business directory</strong> platforms where users can easily discover companies, services, and organizations from different industries across the country. Our directory includes thousands of verified listings such as hospitals, universities, restaurants, beauty salons, hotels, IT companies, travel agencies, and many other businesses. If you are looking for trusted companies or local services, our <strong>Bangladesh business directory list</strong> helps you find the right business quickly and easily.
                        </p>

                        <p>
                        This platform works as a complete <strong>online directory website in Bangladesh</strong> where people can browse businesses by category, location, or service type. Whether you want to find a restaurant in Dhaka, a hospital in Chittagong, or a university in Sylhet, our directory makes it simple to explore businesses throughout Bangladesh. Our goal is to connect customers with reliable companies and help businesses grow their online visibility.
                        </p>

                        <p>
                        BDTalika also works as a modern <strong>Yellow Pages Bangladesh</strong> where business owners can promote their services and reach more customers online. If you own a company, you can submit your business to our <strong>free business listing website in Bangladesh</strong> and increase your online presence. By listing your company in our <strong>business listing website Bangladesh</strong>, you can attract potential clients and grow your business faster.
                        </p>

                        </div>
                    </div>
                </div>
            </div>

        </section>
        @include('partials.front.modal.location_modal')
        @include('partials.front.modal.category_modal')
        <form id="search_item" class="d-none" action="{{ route('front.listing') }}" method="get">
            <input type="text" name="type[]" id="type" value="">
            <input type="text" name="location[]" id="location" value="">
            <input type="text" name="category[]" id="categories" value="">
            <button type="submit" id="search_btn_submit" class="d-none"></button>
        </form>
    @endsection
    @push('js')
        <script>
            // add to wishlist
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
        <script>
            // on click modal scroll to top
            $(document).ready(function() {
                $('.modal_list .nav-link').on('click', function() {
                    var modalContent = $('.modal-body');
                    modalContent.animate({
                        scrollTop: 0
                    }, 300);
                });
            });
            // on click city hide division tab
            $(document).on('click', '.cityClick', function() {
                $('.divisionTab').addClass('d-none');
                $('.cityTab').removeClass('d-none');
            });
            $(document).on('click', '.divisionClick', function() {
                $('.cityTab').addClass('d-none');
                $('.divisionTab').removeClass('d-none');
            });
            // mobile device modal how hide
            $(document).on('click', '.listItem .nav-link', function() {
                if ($(window).width() <= 991) {
                    $('.listItem').hide();
                    $('#go_back').show();
                    $('.cityTab').show();
                    $('.divisionTab').show();
                }
                $('.subItem').show();
            });
            $(document).on('click', '#go_back', function() {
                if ($(window).width() <= 991) {
                    $('.listItem').show();
                    $('#go_back').hide();
                    $('.cityTab').hide();
                    $('.divisionTab').hide();
                }
                $('.subItem').hide();
                $('.subItem').empty();
            });
            // more city data in modal
            $('.moreCity').each(function() {
                var LiN = $(this).find('li').length;
                if (LiN > 10) {
                    $('li', this).eq(9).nextAll().hide().addClass('toggleable');
                    $(this).append('<li class="more ps-2">More...</li>');
                }
            });
            $('.moreCity').on('click', '.more', function() {
                if ($(this).hasClass('less')) {
                    $(this).text('More...').removeClass('less');
                } else {
                    $(this).text('Less...').addClass('less');
                }
                $(this).siblings('li.toggleable').slideToggle();
            });

            // keywords search
            $(document).on('click', '#key_search', function(e) {
                var keywords = $('#keywords').val();
                var new_url = old_url = $(location).attr("href");
                new_url = makeUrl(new_url, 'keywords', keywords);
                window.location.href = new_url;
            })

            $(document).on('click', '.filter_by', function(e) {
                var new_url = old_url = $(location).attr("href");
                var param = $(this).val();
                var val = 0;
                if ($(this).is(":checked")) {
                    var val = 1;
                    var new_url = makeUrl(old_url, param, val);
                } else {
                    var val = 0;
                    var new_url = makeUrl(old_url, param, val);
                }
                new_url = makeUrl(new_url, 'page', 1);
                window.location.href = new_url;
            })

            function makeUrl(old_url, param, val) {
                var url = new URL(old_url);
                var search_params = url.searchParams;
                search_params.set(param, val);
                url.search = search_params.toString();
                return url.toString();
            }
        </script>
    @endpush
