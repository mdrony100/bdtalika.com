@extends('layouts.front')
@push('meta_info')
    <title>{{ $meta->meta_title ?? ""}}</title>
    <meta property="og:title" content="{{ $meta->meta_title ?? '' }}">
    <meta name="description" content="{{ $meta->meta_description ?? '' }}">
    <meta property="og:description" content="{{ $meta->meta_description  ?? '' }}">
    <meta name="keywords" content="Bangladesh business blog,business directory Bangladesh,company listing Bangladesh,business guide Bangladesh,BDTalika blog,Bangladesh companies,business tips Bangladesh,online business directory Bangladesh,business listing guide">
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

        .recent_post img {
            width: 100px !important;
            border-radius: 6px;
        }

        .color_link {
            color: #1198ED;
        }
        .blg_desc p{
                font-size: 16px;
                line-height: 1.8;
                color: #4e6579;
                margin: 0;
        }
        .side-filter-box h6.ft-medium {
            font-size: 25px;
            font-weight: 600;
        }
        .no-ul-list li {
    list-style: none;
    font-size: 17px;
}
.side-filter-box.p-0.position-relative.mb-3 h4 a {
    font-size: 20px;
}
.blg_title h4 {
    font-size: 17px;
    font-weight: bold;
}
.recent_post.p-2.position-relative.overflow-hidden.border-bottom h4 a{
    font-size: 17px !important;
    font-weight: bold;
}
.bottomtext p {font-size: 18px;color: #01132d;line-height: 30px;text-align: center;}
            .bottomtext h2 {text-align: center;font-size: 24px;font-weight: bold;}
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
                @if (isset($meta->title) || isset($meta->sub_title))
                    <div class="header_card mb-4">
                        @if (isset($meta->title))
                            <h1 class="mb-2 text-bold">{{ $meta->title }}</h1>
                        @endif
                        @if (isset($meta->sub_title))
                            <div class="paragrap">{{ $meta->sub_title }}</div>
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
                                            <a href="{{ route('blog.details',$data->slug) }}" title="{{ $data->title }}">
                                                <img src="{{asset('assets/images/'.$data->photo)}}" class="img-fluid" alt="{{ $data->title }}">
                                            </a>
                                        </div>
                                        <div class="blg_grid_caption"> 
                                            <a class="blg_tag mb-2" href="{{ route('front.blogcategory', $data->category->slug) }}">
                                                {{ $data->category->name }}
                                            </a>
                                            <div class="blg_title">
                                                <h4>
                                                    <a href="{{ route('blog.details',$data->slug) }}" title="{{ $data->title }}">{{ $data->title }}</a>
                                                </h4>
                                            </div>
                                            <div class="blg_desc"> 
                                                 <p>{{ Str::limit(strip_tags($data->details), 250) }}</p>
                                            </div>
                                            <div class="readmore mt-3">
                                                <a class="btn btn-default" href="{{ route('blog.details',$data->slug) }}">Read More..</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if ($blogs->count() > 0)
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
                                                <a href="{{ route('blog.details',$post->slug) }}"
                                                    class="stretched-link">
                                                    <img src="{{asset('assets/images/'.$post->photo)}}" class="img-fluid" alt="{{ $post->title }}">
                                                </a>
                                            </div>
                                            <div>
                                                <h4>
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
            <div class="bottomtext" style="margin: 30px 0px; ">
        <div class="blog-seo-content">
            <h2>Latest Business Guides and Industry Insights</h2>
            <p>
            Welcome to the BDTalika Blog, your trusted source for business insights, industry updates, and practical guides related to companies and services in Bangladesh. Our blog is designed to help entrepreneurs, business owners, and customers discover valuable information about different industries, service providers, and business opportunities across the country.
            </p>

            <p>
            Through our blog articles, we publish helpful guides about business directories, company listings, digital marketing, startup ideas, and industry trends in Bangladesh. Whether you are looking for the best restaurants in Dhaka, reliable hospitals and clinics, travel agencies, IT companies, or professional services, our blog provides useful recommendations and insights to help you make better decisions.
            </p>

            <p>
            BDTalika is one of the growing <strong>online business directories in Bangladesh</strong> where businesses can showcase their services and reach potential customers. Our blog also shares tips on how companies can improve their online visibility by adding their business to a <strong>Bangladesh business directory</strong> and optimizing their listings for better search results.
            </p>

            <p>
            Stay updated with the latest business news, service guides, and directory insights through the BDTalika blog and explore the dynamic business ecosystem of Bangladesh.
            </p>

            </div>
    </div>
        </div>
    </section>
    <!-- ======================= Blog Start ============================ -->
    {{-- @include('partials.front.cta') --}}
@endsection
@push('js')
@endpush
