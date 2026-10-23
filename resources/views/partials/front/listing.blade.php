@if (count($listings) > 0)
<div class="row gy-3 row-cols-1 row-cols-xl-1">
@foreach ($listings as $key => $data)
<div class="col overflow-hidden position-relative">
<div class="listing_property mb-2 placeholder-wave shadow-sm">
@if ($data->is_feature)
<div class="Rego-pos ab-left reatured_tag"><div class="Rego-status open me-2">Featured</div></div>
@endif
<div class="wishlist_tag">
<button type="button" class="wishList" data-listing="{{ $data->id }}" data-user={{ auth()->id() ?? $data->id }}>
<i class="lni lni-heart{{ $data->userFavourite(auth()->id(), $data->id) ? '-filled' : '' }} position-absolute"></i>
</button>
</div>
<a href="{{ route('front.listing.details', $data->slug) }}" class="stretched-link" @if($data->verified == 'yes') data-toggle="tooltip"
       title="Verified by BD Talika" @endif >
<div class="d-sm-flex position-relative align-items-center w-100">
<div class="listing_img  me-sm-3">
<img src="{{ asset('assets/images/' . ($data->photo ?? 'default.png')) }}" width="120" alt="{{ $data->name }}">
</div>
<div class="w-100">
<div class="mb-2 mt-2 ft-medium medium">
<!-- <div class="listing_title mb-1 placeholder w-100">{{ $data->name }} </div>  -->
<div class="listing_title mb-1 placeholder w-100">
    {{ $data->name }}
    @if($data->verified == 'yes')
    <i class="fa fa-check-circle text-success ml-1"></i>
    @endif
</div>

@if (isset($data->real_address))
<p class="placeholder mb-3">
{{ $data->real_address }},
<a href="{{ route('front.listing', ['area' => $data->division->slug]) }}" class="z_index color_link position-relative" title="Companies in {{ $data->division->name }}">
<strong>{{ $data->division->name }}</strong>
</a>                                        
</p>
@endif 
<div class="mb-3">
@if ($data->average_rating)
<span class="placeholder me-2 bg bg-success p-1 rounded text-white px-2" style="background: #058a07 !important;">{{ $data->average_rating }}</span>
<span class="me-2 placeholder">
@for ($i = 1; $i <= 5; $i++)
@if ($i <= floor($data->average_rating))
<i class="fas fa-star text-warning"></i>
@elseif ($i == ceil($data->average_rating) && $data->average_rating - floor($data->average_rating) > 0)
<i class="fas fa-star-half-alt text-warning"></i>
@else
<i class="fas fa-star text-secondary"></i>
@endif
@endfor
</span>
@endif
@if ($data->total_review)
<span class="placeholder">{{ $data->total_review }} Reviews</span>
@endif
</div>
<div class="listing_desc placeholder w-100">{!! Str::limit(strip_tags($data->description), 200) !!}</div>
</div>
<div class="d-flex align-items-center">
<div class="close_tag placeholder">{{ $data->category->title }}</div>
@if (isset($data->listingType->name))
<div class="close_tag placeholder">{{ $data->listingType->name ?? '' }}</div>
@endif
</div>
<div class="listing_location_info">
<div class="d-sm-flex align-items-center justify-content-between">
<div>
<ul class="d-flex align-items-center p-0 mt-3">
@if (isset($data->phone_number))
<li class="placeholder"><i class="fas fa-phone-alt"></i> <span class="d-block">Phone</span></li>
@endif
@if (isset($data->email))
<li class="placeholder"> <i class="fas fa-envelope"></i> <span class="d-block">Address</span></li>
@endif
@if (isset($data->map_location))
<li class="placeholder"> <i class="fas fa-map-marker-alt"></i> <span class="d-block">Map</span></li>
@endif
@if (isset($data->website))
<li class="placeholder"><i class="fas fa-globe"></i> <span class="d-block">Website</span></li>
@endif
@if ($data->galleries->count() != 0 || $data->photo != null)
@php
$galleries    = $data->galleries->count();
$total_photos = ($data->photo != null ? 1 : 0) + $galleries;
@endphp
<li class="placeholder">
<i class="fas fa-image"></i> {{ $total_photos }}
<span class="d-block">Photos</span>
</li>
@endif
</ul>
</div>
<div class="position-relative z-50 placeholder">
<a href="{{ route('front.listing.details', $data->slug) }}" class="view_more rounded btn btn-light btn-sm p-1 px-3">View More</a>
</div>
</div>
</div> 
</div>
</div>
</a>
</div>
</div> 
@endforeach
</div>
@else
<div class="text-center mt-4"><img src="{{ asset('assets/images/no-data.png') }}" class="img-fluid" alt=""></div>
@endif
@if ($listings->hasPages())
<div class="row pb-5">
<div class="col-lg-12 col-md-12 col-sm-12">
{{-- {{ $listings->links() }} --}}
{{ $listings->withQueryString()->links() }}
</div>
</div>
@endif




