@extends('layouts.front')
@push('meta_info')
    <title>{{ $data['title']['rendered'] }}</title>
    <meta property="og:title" content="{{ $data->meta_title ?? '' }}">
    <meta name="description" content="{{ $data->meta_description ?? '' }}">
    <meta property="og:description" content="{{ $data->meta_description ?? '' }}">
    <meta property="og:image:secure_url"
        content="{{ $data['yoast_head_json']['og_image'][0]['url'] ?? 'default-image.jpg' }}">
@endpush
@push('css')
    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=673219c5f1297b00124e7f1b&product=sticky-share-buttons&source=platform"
        async="async"></script>
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

        .category_tag .badge {
            color: #fff;
            z-index: 1;
            background-color: #000;
            padding: 8px 18px;
            border-radius: 21px;
            font-weight: 700;
            font-size: 15px;
        }

        .post_time {
            font-size: 16px;
        }

        .title {
            font-size: 26px;
            color: #000;
            font-weight: bold;
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
                            <li class="breadcrumb-item"><a class="color_link" href="#">Blog</a></li>
                            <li class="breadcrumb-item"><a href="#">{{ $data['title']['rendered'] }}</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Blog Start ============================ -->
    <section class="middle">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-9 mb-4 mb-lg-0">
                    <div class="sharethis-sticky-share-buttons"></div>
                    <div class="single_post">
                        <div class="shareaholic-canvas" data-app="share_buttons" data-app-id="33224875"></div>
                        <div class="row d-flex align-items-center">
                            <div class="col-xl-6">
                                <div class="featured-image mb-3">
                                    <img src="{{ $data['yoast_head_json']['og_image'][0]['url'] ?? 'default-image.jpg' }}"
                                        alt="{{ $data['title']['rendered'] }}" class="img-fluid rounded">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="mb-4 category_tag">
                                    @foreach ($data['blog_categories'] as $category)
                                        <a href="{{ route('front.blogcategory', $category['slug']) }}">
                                            <span class="badge bg-dark rounded-pill px-3">{{ $category['name'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mb-4 post_time">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($data['date'])->format('F j, Y') }}
                                </div>
                                <h1 class="title">{{ $data['title']['rendered'] }}</h1>
                            </div>
                        </div>
                        <div class="post-content">
                            {!! $data['content']['rendered'] !!}
                        </div>

                        {{-- coments --}}
                        <div class="mt-5">
                            <div id="disqus_thread"></div>
                        </div>

                    </div>
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
                                                            alt="{{ $data['title']['rendered'] }}" class="rounded"
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
                                            <a
                                                href="{{ route('front.blogcategory', $category['slug']) }}">{!! $category['name'] !!}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Related posts --}}
            @if ($relatedPosts->isNotEmpty())
            <div class="py-5">
                <h6 class="ft-medium mb-4" style="font-size: 20px;font-weight: 600;">Related Posts</h6>
                <div class="row gy-4">
                    @foreach ($relatedPosts as $item)
                        <div class="col-md-6 col-xl-3">
                            <div class="blg_grid_box card" style="height: 100% !important;">
                                <div class="blg_grid_thumb">
                                    <a href="{{ route('blog.details', $item['slug']) }}"
                                        title="{{ $item['title']['rendered'] }}">
                                        <img src="{{ $item['yoast_head_json']['og_image'][0]['url'] ?? 'default-image.jpg' }}"
                                            class="w-100" alt="{{ $item['title']['rendered'] }}">
                                    </a>
                                </div>
                                <div class="blg_grid_caption">
                                    @foreach ($item['blog_categories'] as $cat)
                                        <div class="blg_tag">
                                            <a class="text-danger"
                                                href="{{ route('front.blogcategory', $cat['slug']) }}">
                                                {!! $cat['name'] !!}
                                            </a>
                                        </div>
                                    @endforeach
                                    <div class="blg_title">
                                        <h4>
                                            <a href="{{ route('blog.details', $item['slug']) }}"
                                                title="{{ $item['title']['rendered'] }}">
                                                {{ $item['title']['rendered'] }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="blg_desc">
                                        {{ Str::limit(strip_tags($item['yoast_head_json']['description'] ?? ''), 250) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif



        </div>
    </section>
    <!-- ======================= Blog Start ============================ -->
    {{-- @include('partials.front.cta') --}}


@endsection

@push('js')
    <script>
        (function() { // DON'T EDIT BELOW THIS LINE
            var d = document,
                s = d.createElement('script');
            s.src = 'https://blog-vh1e1np37f.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <script id="dsq-count-scr" src="//blog-vh1e1np37f.disqus.com/count.js" async></script>
@endpush
