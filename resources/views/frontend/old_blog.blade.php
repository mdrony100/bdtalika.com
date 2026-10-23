@extends('layouts.front')
@push('meta_info')
    <title>{{ $data->meta_title ?? '' }}</title>
    <meta property="og:title" content="{{ $data->meta_title ?? '' }}">
    <meta name="description" content="{{ $data->meta_description ?? '' }}">
    <meta property="og:description" content="{{ $data->meta_description ?? '' }}">
@endpush
@push('css')
    <style>
        .paragrap {
            font-size: 16px;
        }

        .recent_post {
            transition: all 0.2s ease;
            -webkit-transition: all 0.2s ease;
            -moz-transition: all 0.2s ease;
            -ms-transition: all 0.2s ease;
            -o-transition: all 0.2s ease;
        }

        .recent_post:hover {
            background: #f3f3f3;
        }

        .side-filter-box h6.ft-medium {
            font-size: 20px;
            font-weight: 600;
        }

        .side-filter-box h6.ft-medium {
            font-size: 20px;
            font-weight: 600;
        }

        .color_link {
            color: #1198ED;
        }
    </style>
@endpush

@section('content')
    <div class="bg-light py-2">
        <div class="container">
            <div class="row">
                <div class="colxl-12 colz-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="color_link"
                                    href="{{ route('front.index') }}">@lang('Home')</a></li>
                            <li class="breadcrumb-item"><a href="#">@lang('Blogs')</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Blog Start ============================ -->
    <section class="middle">
        <div class="container">
            <div class="header_card mb-4">
                @if (isset($data->title) || isset($data->sub_title))
                    <div class="header_card mb-4">
                        @if (isset($data->title))
                            <h1 class="mb-2">{{ $data->title }}</h1>
                        @endif
                        @if (isset($data->sub_title))
                            <div class="paragrap">{{ $data->sub_title }}</div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-8 col-xl-9 mb-4 mb-lg-0">
                    @if (count($blogs) == 0)
                        <div class="col-12 text-center">
                            <h3 class="m-0">{{ __('No Blog Found') }}</h3>
                        </div>
                    @else
                        <div class="row gy-4">
                            @foreach ($blogs as $data) 
                                <div class="col-md-6 col-xl-4">
                                    <div class="blg_grid_box card" style="height: 100% !important;">
                                        <div class="blg_grid_thumb">
                                            <a href="{{ route('blog.details', $data['slug']) }}"
                                                title="{{ $data['title']['rendered'] }}">
                                                <img src="{{ $data['yoast_head_json']['og_image'][0]['url'] ?? 'default-image.jpg' }}"
                                                    class="w-100" alt="{{ $data['title']['rendered'] }}">
                                            </a>
                                        </div>
                                        <div class="blg_grid_caption">
                                            @foreach ($data['blog_categories'] as $cat)
                                                <div class="blg_tag">
                                                    <a class="text-danger" href="{{ route('front.blogcategory', $cat['slug']) }}">
                                                        {!! $cat['name'] !!}
                                                    </a>
                                                </div> 
                                            @endforeach
                                            <div class="blg_title">
                                                <h4>
                                                    <a href="{{ route('blog.details', $data['slug']) }}"
                                                        title="{{ $data['title']['rendered'] }}">
                                                        {{ $data['title']['rendered'] }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="blg_desc">
                                                {{ Str::limit(strip_tags($data['yoast_head_json']['description'] ?? ''), 250) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if ($blogs->count() > 0 && $blogs->hasPages())
                        <div class="row justify-content-center">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="position-relative text-center">
                                    {{ $blogs->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 col-xl-3">
                    @if (isset($recentPosts) && $recentPosts->count() > 0)
                        <div class="side-filter-box p-0 position-relative mb-3">
                            <div class="category_name p-3 pb-0 mb-2">
                                <h6 class="ft-medium">Recent Posts</h6>
                            </div>
                            <div class="">
                                @foreach ($recentPosts as $post)
                                    <div
                                        class="recent_post p-2 position-relative overflow-hidden {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex align-items-center">
                                            <div class="img me-3">
                                                @if (isset($post['featured_media']))
                                                    <a href="{{ route('blog.details', $post['slug']) }}"
                                                        class="stretched-link">
                                                        <img src="{{ $post['yoast_head_json']['og_image'][0]['url'] ?? 'default-image.jpg' }}"
                                                            alt="{{ $post['title']['rendered'] }}" class="rounded"
                                                            style="width: 70px; height: 70px; object-fit: cover;">
                                                    </a>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 style="font-size:16px">{{ $post['title']['rendered'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (isset($categories) && $categories->count() > 0)
                        <div class="side-filter-box position-relative">
                            <div class="category_name mb-2">
                                <h6 class="ft-medium">Categories</h6>
                            </div>
                            <div class="">
                                <ul class="no-ul-list filter-list">
                                    @foreach ($categories as $category)
                                        <li class="pb-2">
                                            <a href="{{ route('front.blogcategory', $category['slug']) }}">
                                                {!! $category['name'] !!}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Blog Start ============================ -->
    {{-- @include('partials.front.cta') --}}
@endsection
@push('js')
@endpush
