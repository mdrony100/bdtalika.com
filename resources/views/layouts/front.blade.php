<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $siteMetaDescription = trim(strip_tags($gs->meta_description ?? ''));
    if ($siteMetaDescription !== '' && !preg_match('/bangladesh[.!?]?\s*$/i', $siteMetaDescription)) {
        $siteMetaDescription .= ' Bangladesh';
    }
@endphp
@if (Request::routeIs('front.listing.details'))@stack('meta_info')
@else @if (Request::routeIs('front.listing') || Request::routeIs('front.listing.page') || Request::routeIs('front.page') || Request::routeIs('front.contact') || Request::routeIs('sitemap') || Request::routeIs('front.category.directory') || Request::routeIs('front.advertise') || Request::routeIs('front.blog') || Request::routeIs('blog.details') || Request::routeIs('front.public-holiday'))@stack('meta_info')@else<title>{{ $gs->title }}</title>
<meta property="og:title" content="{{ $gs->title }}">
@if (isset($gs->meta_keyword)) <meta name="keywords" content="{{ $gs->meta_keyword }}">@endif
@if ($siteMetaDescription !== '')
<meta name="description" content="{{ $siteMetaDescription }}">
<meta property="og:description" content="{{ $siteMetaDescription }}">@endif
@endif
<meta name="Developer" content="developed by bdtalika">
<meta property="og:site_name" content="bdtalika.com">
@if (isset($gs->og_image) && !Request::routeIs('blog.details'))
    <meta property="og:image" content="{{ asset('assets/images/' . $gs->og_image) }}">
@endif

<meta property="og:type" content="website">
<meta property="og:url" content="https://bdtalika.com/">
<meta property="og:image:width" content="600">
<meta property="og:image:height" content="315">
@endif
@if ($gs->app_mode == 1)
<meta name="robots" content="index">
@else
<meta name="robots" content="noindex">
@endif
@php
    $canonicalUrl = url()->current();
    if (strpos($canonicalUrl, '/public/') !== false) {
        $canonicalUrl = str_replace('/public', '', $canonicalUrl);
    }
    $canonicalUrl = str_replace('://www.', '://', $canonicalUrl);
@endphp
<link rel="canonical" href="{{ $canonicalUrl }}">
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'BDTalika', 'url' => 'https://bdtalika.com/', 'logo' => asset('assets/images/' . $gs->logo), 'description' => $siteMetaDescription], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => 'BDTalika', 'url' => 'https://bdtalika.com/', 'potentialAction' => ['@type' => 'SearchAction', 'target' => 'https://bdtalika.com/listing?title={search_term_string}', 'query-input' => 'required name=search_term_string']], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<link href="{{ asset('assets/front/css/styles.min.css') }}?v=2" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/front/css/styles.php?color=' . str_replace('#', '', $gs->colors)) }}">
@if ($default_font->font_value)
<link href="https://fonts.googleapis.com/css?family={{ $default_font->font_value }}&display=swap" rel="stylesheet">
@else
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet"> @endif @if ($default_font->font_family)<link rel="stylesheet" id="colorr" href="{{ asset('assets/front/css/font.php?font_familly=' . $default_font->font_family) }}">@else
<link rel="stylesheet" id="colorr" href="{{ asset('assets/front/css/font.php?font_familly=' . 'Open Sans') }}">@endif
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="{{ asset('assets/front/css/summernote.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/front/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/mdtimepicker.css') }}">
<link href="{{ asset('assets/admin/css/tagify.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/front/css/plugins/font-awesome.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/custom.min.css?v=3') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/' . $gs->favicon) }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/' . $gs->favicon) }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/' . $gs->favicon) }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/' . $gs->favicon) }}">
@if (!empty($seo->google_analytics))
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $seo->google_analytics }}"></script>

<script>
window.dataLayer = window.dataLayer || [];

function gtag() {
dataLayer.push(arguments);
}
gtag('js', new Date());
gtag('config', '{{ $seo->google_analytics }}');
</script>
@endif


<script>
(function(w, d, s, l, i) {
w[l] = w[l] || [];
w[l].push({
'gtm.start': new Date().getTime(),
event: 'gtm.js'
});
var f = d.getElementsByTagName(s)[0],
j = d.createElement(s),
dl = l != 'dataLayer' ? '&l=' + l : '';
j.async = true;
j.src =
'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
f.parentNode.insertBefore(j, f);
})(window, document, 'script', 'dataLayer', 'GTM-NCSSBVRC');
</script>
@stack('css')
<!-- google adsense -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6432054082014031"
crossorigin="anonymous"></script>
</head>
<body>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NCSSBVRC" height="0" width="0"
style="display:none;visibility:hidden"></iframe></noscript>
@if ($gs->is_loader == 1)
<div class="preloader" id="preloader" style="background: url({{ asset('assets/images/' . $gs->loader) }}) no-repeat scroll center center #FFF;"></div>
@endif
<div id="main-wrapper" class="sticky-top bg-white header-light dark-text">
@includeIf('partials.front.nav')

{{-- top banner --}}
{{-- @if (isset($top_banner)) 
<div class="top_banner position-relative text-center">
<div class="cls_tpban">
<svg  xmlns="http://www.w3.org/2000/svg"  width="22"  height="22"  viewBox="0 0 24 24"  fill="none"  stroke="#ffffff"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
</div>
<a href="{{ $top_banner->link }}" class="bannerCountView" data-id="{{ $top_banner->id }}" rel="noreferrer" target="_blank">
<img src="{{ asset('assets/images/'.$top_banner->image) }}" class="img-fluid" alt="">
</a>
</div>
@endif --}}
</div>
<div class="clearfix"></div>
@yield('content')
@if(auth()->check() && request()->routeIs('user.listing.plan'))
@includeIf('partials.user.whatsapp-widget')
@endif
<a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
@if ($gs->is_cookie)
@include('cookie-consent::index')
@endif
{{-- 
@if (Request::routeIs('front.index')) 
@if ($alertPopup)
<div class="popup_alert shadow-lg bg-white rounded p-2">
<div class="close">
<i class="fa fa-times"></i>
</div>
@if ($alertPopup->type == '1')
<div class="alert_img">
<a href="{{ $alertPopup->link }}" target="_blank">
<img src="{{ asset('assets/images/'.$alertPopup->image) }}" width="450" height="250" class="img-fluid rounded"
alt="image">
</a>
</div>
@endif
@if ($alertPopup->type == '2')
<div class="alert_video">
<div class="ratio ratio-16x9" id="myVideo">
{!! $alertPopup->iframe_video !!}
</div>
</div>
@endif 
@if ($alertPopup->type == '3')
<div class="alert_video">
<div class="ratio ratio-16x9">
<video controls width="300" autoplay height="180" id="cst_video">
<source src="{{ asset('assets/images/' . $alertPopup->video_file) }}">
</video>
</div>
</div>
@endif
<a href="{{ $alertPopup->link }}" target="_blank" data-id="{{ $alertPopup->id }}" class="alertpopupClickCount btn btn-primary btn-sm w-100 mt-1 text-uppercase shadow-none py-2">
View
</a>
</div>
@endif
@endif
--}}
<script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/front/js/summernote.js') }}"></script>
<script src="{{ asset('assets/front/js/slick.js') }}"></script>
<script src="{{ asset('assets/front/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/front/js/dropzone.js') }}"></script>
<script src="{{ asset('assets/front/js/counterup.js') }}"></script>
<script src="{{ asset('assets/front/js/lightbox.js') }}"></script>
<script src="{{ asset('assets/front/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/front/js/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/front/js/lightbox.js') }}"></script>
<script src="{{ asset('assets/front/js/jQuery.style.switcher.js') }}"></script>
<script src="{{ asset('assets/front/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/front/js/mdtimepicker.js') }}"></script>
<script src="{{ asset('assets/admin/js/tagify.js') }}"></script>
<script src="{{ asset('assets/front/js/custom.js') }}"></script> 

<script>
'use strict';
const mainurl = '{{ url('/') }}';
let tawkto = '{{ $gs->is_talkto }}';
</script>
<script>
if (tawkto == 1) {
var Tawk_API = Tawk_API || {},
Tawk_LoadStart = new Date();
(function() {
var s1 = document.createElement("script"),
s0 = document.getElementsByTagName("script")[0];
s1.async = true;
s1.src = 'https://embed.tawk.to/62305c801ffac05b1d7ea682/{{ $gs->talkto }}';
s1.charset = 'UTF-8';
s1.setAttribute('crossorigin', '*');
s0.parentNode.insertBefore(s1, s0);
})();
}
</script>
<script>
'use strict';
@if (Session::has('message'))
toastr.options = {
"closeButton": true,
"progressBar": true
}
toastr.success("{{ session('message') }}");
@endif

@if (Session::has('error'))
toastr.options = {
"closeButton": true,
"progressBar": true
}
toastr.error("{{ session('error') }}");
@endif

@if (Session::has('info'))
toastr.options = {
"closeButton": true,
"progressBar": true
}
toastr.info("{{ session('info') }}");
@endif

@if (Session::has('warning'))
toastr.options = {
"closeButton": true,
"progressBar": true
}
toastr.warning("{{ session('warning') }}");
@endif

@if ($errors->any())
@foreach ($errors->all() as $error)
toastr.options = {
"closeButton": true,
"progressBar": true
}
toastr.error("{{ $error }}");
@endforeach
@endif
</script>
<script>
$(window).on('load', function() {
const allPlaceholderGlow = $('.placeholder-wave');
const allPlaceholder = $('.placeholder');
allPlaceholderGlow.removeClass('placeholder-wave');
allPlaceholder.removeClass('placeholder');
});
</script>
<script>
$(document).ready(function() {
// popup alert hide
$('.popup_alert .close').on('click', function() {
$('.popup_alert').fadeOut(function() {
$("#myVideo iframe").attr("src", null);
});
var video = $('#cst_video')[0];  
video.pause();
video.currentTime = 0;

});

// top banner hide
$('.cls_tpban').on('click', function(){
$('.top_banner').slideUp();
})
});

// top banner click count
$(document).on('click', '.bannerCountView', function(event){
var bannerId = $(this).data('id'); 
$.ajax({
url: "{{ route('front.topbanner.click.count') }}/" + bannerId, 
type: 'POST',
data: { 
id: bannerId,
_token: '{{ csrf_token() }}'
},
success: function(response) {
console.log(response);
},
error: function(xhr, status, error) {
console.error('Error updating view count:', error);
}
});
});

// alert popup click count
$(document).on('click', '.alertpopupClickCount', function(event){
var id = $(this).data('id'); 
$.ajax({
url: "{{ route('front.alertpopup.click.count') }}/" + id, 
type: 'POST',
data: { 
id: id,
_token: '{{ csrf_token() }}'
},
success: function(response) {
console.log(response);
},
error: function(xhr, status, error) {
console.error('Error updating view count:', error);
}
});
});
</script>
<script>

/*
|--------------------------------------------------------------------------
| MONA EXPAND TOGGLE
|--------------------------------------------------------------------------
*/

function toggleMonaExpand()
{
    $('#monaBox').toggleClass('expanded');

    /*
    |--------------------------------------------------------------------------
    | CHANGE ICON
    |--------------------------------------------------------------------------
    */

    if($('#monaBox').hasClass('expanded'))
    {
        $('#monaExpand').html('🗗');
    }
    else
    {
        $('#monaExpand').html('⛶');
    }
}

</script>
<script>

$(document).ready(function(){
    /*
    |--------------------------------------------------------------------------
    | OPEN CHAT
    |--------------------------------------------------------------------------
    */
    $('#monaToggle').on('click', function(){
        $('#monaBox').toggleClass('active');
    });
    /*
    |--------------------------------------------------------------------------
    | CLOSE CHAT
    |--------------------------------------------------------------------------
    */
    $('#monaClose').on('click', function(){

        $('#monaBox').removeClass('active');

    });
    /*
    |--------------------------------------------------------------------------
    | ENTER KEY
    |--------------------------------------------------------------------------
    */
    $('#message').on('keypress', function(e){

        if(e.which == 13)
        {
            sendMessage();
        }

    });

});

/*
|--------------------------------------------------------------------------
| SET SUGGESTION
|--------------------------------------------------------------------------
*/

function setSuggestion(text)
{
    $('#message').val(text);
}

/*
|--------------------------------------------------------------------------
| SEND MESSAGE
|--------------------------------------------------------------------------
*/
function sendMessage()
{
    let message = $('#message').val().trim();

    if(message == '')
    {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | USER MESSAGE
    |--------------------------------------------------------------------------
    */

    $('#chatMessages').append(`
        <div class="user-message">
            ${message}
        </div>
    `);

    /*
    |--------------------------------------------------------------------------
    | CLEAR INPUT
    |--------------------------------------------------------------------------
    */

    $('#message').val('');

    scrollChatBottom();

    /*
    |--------------------------------------------------------------------------
    | LOADING MESSAGE
    |--------------------------------------------------------------------------
    */

    let loadingId = 'loading-' + Date.now();

    $('#chatMessages').append(`
        <div class="bot-message" id="${loadingId}">
            <div class="typing-area">

                <span></span>
                <span></span>
                <span></span>

            </div>
        </div>
    `);

    scrollChatBottom();

    /*
    |--------------------------------------------------------------------------
    | AJAX REQUEST
    |--------------------------------------------------------------------------
    */

    $.ajax({

        url: '/mona/chat',

        type: 'POST',

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        data: JSON.stringify({
            message: message
        }),

        contentType: 'application/json',

        success: function(response)
        {
            /*
            |--------------------------------------------------------------------------
            | REMOVE LOADING
            |--------------------------------------------------------------------------
            */

            $('#' + loadingId).remove();

            /*
            |--------------------------------------------------------------------------
            | BOT MESSAGE
            |--------------------------------------------------------------------------
            */

            $('#chatMessages').append(`
                <div class="bot-message">
                    ${response.reply}
                </div>
            `);

            scrollChatBottom();
        },

        error: function(xhr)
        {
            $('#' + loadingId).remove();

            let errorMessage = 'Something went wrong.';

            /*
            |--------------------------------------------------------------------------
            | LARAVEL ERROR
            |--------------------------------------------------------------------------
            */

            if(xhr.responseJSON)
            {
                if(xhr.responseJSON.reply)
                {
                    errorMessage = xhr.responseJSON.reply;
                }

                else if(xhr.responseJSON.message)
                {
                    errorMessage = xhr.responseJSON.message;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SHOW ERROR
            |--------------------------------------------------------------------------
            */

            $('#chatMessages').append(`
                <div class="bot-message">
                    ${errorMessage}
                </div>
            `);

            scrollChatBottom();
        }

    });
}

/*
|--------------------------------------------------------------------------
| AUTO SCROLL
|--------------------------------------------------------------------------
*/

function scrollChatBottom()
{
    let chatBody = $('#chatMessages');

    chatBody.scrollTop(chatBody[0].scrollHeight);
}

</script>
@stack('js')

{{-- Listing impression tracking (analytics) --}}
<script>
    window.LISTING_IMPRESSION_URL = "{{ route('front.listing.analytics.impressions') }}";
    window.LISTING_IMPRESSION_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ asset('assets/front/js/listing-impressions.js') }}" defer></script>

<!-- footer for user pages -->
@include('partials.front.footer')
@if(request()->is('user/listing/plan/*'))
<div id="planNotification" class="plan-notification">
    <div id="notifyAvatar" class="notify-avatar"></div>
    <img id="notifyPhoto" src="" alt="">
    <div class="notify-content">
        <div id="notifyText"></div>
        <a id="notifyLink" href="">
            Check Listing
        </a>
    </div>
</div>
@endif
<audio id="notifySound">
    <source src="{{ asset('assets/images/notification.mp3') }}" type="audio/mpeg">
</audio>
</body>
@if($gs->is_active_ai_assistant == 1)
<!-- MONA FLOAT CHATBOT -->
<div class="mona-chatbot">
    <!-- FLOAT BUTTON -->
    <div class="mona-toggle" id="monaToggle">
        <div class="mona-toggle-inner">
            <div class="mona-icon">
                💬
            </div>
            <div class="mona-toggle-text">
                Ask Mona
            </div>
        </div>
    </div>
    <!-- CHAT WINDOW -->
    <div class="mona-box" id="monaBox">
        <!-- HEADER -->
        <div class="mona-header">
        <div class="mona-header-left">
            <div class="mona-avatar">
                M
            </div>
            <div>
                <h3 style="color:#fff;">Mona AI (Beta Version)</h3>
                <span>Business Discovery Assistant</span>
            </div>
        </div>
        <!-- RIGHT ACTIONS -->
        <div class="mona-header-actions">
            <!-- EXPAND BUTTON -->
            <button
                class="mona-expand"
                id="monaExpand"
                onclick="toggleMonaExpand()"
                title="Expand Chat"
            >
                ⛶
            </button>

            <!-- CLOSE BUTTON -->

            <button
                class="mona-close"
                id="monaClose"
            >
                ✕
            </button>

        </div>

    </div>
        <!-- BODY -->
        <div class="mona-body" id="chatMessages">
            <div class="mona-welcome">
                <h2>Hello 👋 I’m Mona</h2>
                <p>
                    I can help you discover businesses by category and location.
                </p>
                <!-- QUICK SUGGESTIONS -->
                <div class="mona-section-title">
                    Popular Searches
                </div>
                <div class="mona-suggestions">
                    <button onclick="setSuggestion('Restaurant in Banani')">
                        🍽️ Restaurant in Banani
                    </button>
                    <button onclick="setSuggestion('Hospital in Mirpur')">
                        🏥 Hospital in Mirpur
                    </button>
                    <button onclick="setSuggestion('Women parlour in Dhaka')">
                        💇 Women parlour in Dhaka
                    </button>
                    <button onclick="setSuggestion('Gym in Uttara')">
                        🏋️ Gym in Uttara
                    </button>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
        <div class="mona-footer">
            <input
                type="text"
                id="message"
                placeholder="Search businesses by category or location..."
            >
            <button onclick="sendMessage()">
                ➤
            </button>
        </div>
    </div>
</div>
@endif
</html>
