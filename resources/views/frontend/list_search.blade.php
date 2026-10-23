@extends('layouts.front')
@php
$slug = request('area');
$title = ucwords(str_replace('-', ' ', $slug));
$dataType = str_replace('_', ' ', request('type'));
@endphp
@push('meta_info')
<title>{{ ucfirst($dataType) }} in {{ ucfirst($title) }}</title>
<meta property="og:title" content="{{ ucfirst($dataType) }} in {{ ucfirst($title) }}">
<meta name="description" content="Find {{ ucfirst($dataType) }} list in {{ ucfirst($title) }} with {{ ucfirst($dataType) }} address, photos, map view, open times, videos, reviews and much more at bdtalika.com"/>
<meta property="og:description" content="Find {{ ucfirst($dataType) }} list in {{ ucfirst($title) }} with {{ ucfirst($dataType) }} address, photos, map view, open times, videos, reviews and much more at bdtalika.com">
@endpush
@push('css')
<style>
.header_card {
    background: #f8f8f8;
    padding: 14px 12px;
    border-radius: 6px !important;
}
.header_card h1 {font-size: 18px;}
</style>
@endpush
@section('content')
<section class="bg-light py-3">
<div class="container">
<div class="breadcrumb mb-3">
<a href="{{ route('front.index') }}">Home <i class="fa fa-angle-right"></i> </a>
<a href="#">{{ ucfirst($dataType) }} in {{ ucfirst($title) }}</a>
</div>
<div class="header_card mb-4">
<h1>{{ ucfirst($dataType) }} in {{ ucfirst($title) }}</h1>
</div>
<div class="row">
<div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">
<div id="show_search_items">
@includeIf('partials.front.listing')
</div>
</div>
@if (isset($recent_added) && $recent_added->count() > 0)
<div class="col-lg-3">
<div class="sec_title position-relative mb-3">
<h3 class="ft-bold">Recently Added</h3>
</div>
<div class="row">
@foreach ($recent_added as $product)
<div class="recent_ads mb-3 rounded col-sm-6 col-lg-12 placeholder-wave">
<div class="d-flex position-relative box-shadow1">
{{-- <div class="ads_tag">
<span>{{ $product->openClose($product->id) }}</span>
</div> --}}
<div class="me-2 recent_img">
<img src="{{ asset('assets/images/' . $product->photo) }}" class="flex-shrink-0" width="75" height="75" alt="{{ Str::limit($product->name, 28) }}">
</div>
<div class="media-body">
<div class="media-heading placeholder">{{ Str::limit($product->name, 28) }}</div>
@if (isset($product->real_address))
<div class="location placeholder">
<i class="fas fa-map-marker-alt me-1"></i>
{{ Str::limit($product->real_address, 28) }}
</div>
@endif
@if (isset($product->phone_number))
<div class="phone placeholder">
<i class="fas fa-phone-alt me-1"></i>
{{ $product->phone_number }}
</div>
@endif
<a href="{{ route('front.listing.details', $product->slug) }}" class="stretched-link"></a>
</div>
</div>
</div>
@endforeach
</div>
</div>
@endif
</div>
</div>
</section>
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
toastr.error(data.error);}}});})
</script>
@endpush
