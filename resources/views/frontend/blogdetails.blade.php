@extends('layouts.front')
@push('meta_info')
    <title>{{ $data->title }}</title>
    <meta property="og:title" content="{{ $data->title ?? '' }}">
    <meta name="description" content="{{ $data->meta_description ?? '' }}">
    <meta property="og:description" content="{{ $data->meta_description ?? '' }}">
    <meta property="keywords" content="{{ $data->meta_tag ?? '' }}">
    <meta property="og:image:secure_url" content="{{asset('assets/images/'.$data->photo)}}">
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

        .recent_post img {
            width: 100px !important;
            border-radius: 6px;
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
        .single_post p span {
    font-size: 20px !important;
    line-height: 34px;
}
.side-filter-box.p-0.position-relative.mb-3 h4 a {
    font-size: 20px !important;
}
.side-filter-box h6.ft-medium {
    font-size: 25px;
    font-weight: 600;
}
.no-ul-list li {
    list-style: none;
    font-size: 17px;
}
.recent_post.p-2.position-relative.overflow-hidden.border-bottom h4 a{
    font-size: 17px !important;
    font-weight: bold;
}
    </style>
@endpush

@section('content')
@php
    $blogPostingSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BlogPosting',
        'headline'        => strip_tags($data->title),
        'description'     => \Illuminate\Support\Str::limit(strip_tags($data->meta_description ?: $data->details), 160),
        'image'           => $data->photo ? asset($data->photo) : asset('assets/images/' . $gs->logo),
        'url'             => route('blog.details', $data->slug),
        'datePublished'   => optional($data->created_at)->toAtomString(),
        'dateModified'    => optional($data->updated_at)->toAtomString(),
        'author'          => ['@type' => 'Organization', 'name' => 'BDTalika'],
        'publisher'       => ['@type' => 'Organization', 'name' => 'BDTalika', 'logo' => ['@type' => 'ImageObject', 'url' => asset('assets/images/' . $gs->logo)]],
        'mainEntityOfPage'=> ['@type' => 'WebPage', '@id' => route('blog.details', $data->slug)],
    ];
    if (!empty($data->tags)) {
        $blogPostingSchema['keywords'] = $data->tags;
    }
    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement'=> [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('front.index')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('front.blog')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => strip_tags($data->title), 'item' => route('blog.details', $data->slug)],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($blogPostingSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <div class="bg-light py-2">
        <div class="container">
            <div class="row">
                <div class="colxl-12 colz-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="color_link"
                                    href="{{ route('front.index') }}">@lang('Home')</a></li>
                            <li class="breadcrumb-item"><a class="color_link" href="{{ route('front.blog') }}">Blog</a></li>
                            <li class="breadcrumb-item"><a href="#">{{ $data->title }}</a></li>
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
                                    <img src="{{asset('assets/images/'.$data->photo)}}"
                                        alt="{{ $data->title }}" class="img-fluid rounded">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="mb-4 category_tag">
                                    <a class="blg_tag mb-2" href="{{ route('front.blogcategory', $data->category->slug) }}">
                                        {{ $data->category->name }}
                                    </a>
                                </div>
                                <div class="mb-4 post_time">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('F j, Y') }}
                                </div>
                                <h1 class="title">{{ $data->title }}</h1>
                            </div>
                        </div>
                        <div class="post-content">
                            {!! $data->details !!}
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
                                                <a href="{{ route('blog.details',$post->slug) }}"
                                                    class="stretched-link">
                                                    <img src="{{asset('assets/images/'.$post->photo)}}" class="img-fluid" alt="{{ $post->title }}">
                                                </a>
                                            </div>
                                            <div>
                                                <h4 style="font-size:16px">
                                                    <a href="{{ route('blog.details',$post->slug) }}" title="{{ $post->title }}">{{ $post->title }}</a>
                                                </h4>
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
                                        <a href="{{ route('front.blogcategory', $category->slug) }}">
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

            {{-- Related posts --}}
            @if ($relatedPosts->isNotEmpty())
            <div class="py-5">
                <h6 class="ft-medium mb-4" style="font-size: 20px;font-weight: 600;">Related Posts</h6>
                <div class="row gy-4">
                    @foreach ($relatedPosts as $item)
                        <div class="col-md-6 col-xl-3">
                            <div class="blg_grid_box card" style="height: 100% !important;">
                                <div class="blg_grid_thumb">
                                    <a href="{{ route('blog.details', $item->slug) }}"
                                        title="{{ $item->title }}">
                                        <img src="{{asset('assets/images/'.$item->photo)}}" class="w-100" alt="{{ $item->title }}">
                                    </a>
                                </div>
                                <div class="blg_grid_caption">
                                    <a class="blg_tag mb-2" href="{{ route('front.blogcategory', $item->category->slug) }}">
                                        {{ $item->category->name }}
                                    </a>
                                    <div class="blg_title">
                                        <h4>
                                            <a href="{{ route('blog.details', $item->slug) }}"
                                                title="{{ $item->title }}">
                                                {{ $item->title }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="blg_desc">
                                        {{ Str::limit(strip_tags($item->details), 250) }}
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
