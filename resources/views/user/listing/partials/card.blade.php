<!-- Single Listing Item -->
<div class="dsd-single-listing-wraps p-0 mb-3 position-relative">
@if ($data->status == 0)
<span class="pending_tag">Draft</span>
@elseif ($data->status == 1)
<span class="pending_tag approved">Approved</span>
@elseif ($data->status == 2)
<span class="pending_tag reject">Rejected</span>
@elseif ($data->status == 3)
<span class="pending_tag reject">Requires review</span>
@elseif ($data->status == 4)
<span class="pending_tag reject">Disabled</span>
@endif
<div class="dsd-single-lst-thumb">
<img src="{{ asset('assets/images/' . ($data->photo ?? 'default.png')) }}" class="img-fluid" alt="">
</div>
<div class="dsd-single-lst-caption">

@if ($data->status == '4')
<div class="alert alert-danger">
<div class="">Upgrade required! You've reached the limits of your free plan. Upgrade your plan</div>
</div>
@endif

<div class="dsd-single-lst-title">
<h5 @if($data->verified == 'yes') data-toggle="tooltip" title="Verified by BD Talika" @endif>{{ $data->name }} @if($data->verified == 'yes') <span class="verified-badge"><i class="fas fa-check-circle"></i></span> @endif</h5>
</div>

@if ($data->real_address)
<span class="agd-location">
<i class="lni lni-map-marker me-1"></i>
{{ $data->real_address }}
</span>
@endif
@if (count($data->reviews) > 0)
<div class="ico-content">
<div class="Rego-ft-first">
<div class="Rego-rating">
<div class="Rego-rates">
@php
$rate = ceil($data->directoryRatting($data->id));
@endphp
@for ($i = 1; $i <= $rate; $i++)
<i class="fas fa-star active"></i>
@endfor
@for ($i = 1; $i <= 5 - $rate; $i++)
<i class="fas fa-star"></i>
@endfor
</div>
</div>
<div class="Rego-price-range">
<span class="ft-medium">{{ $data->directoryRatting($data->id) }}
@lang('Reviews')</span>
</div>
</div>
</div>
@endif

@if ($data->status == '1')
<div class="text-danger mt-1">
{!! ListingPlanDuration($data->id) !!}
</div>
@endif
<div class="dsd-single-lst-footer mt-2">
<a href="{{ route('user.listing.edit.step1', [$data->id]) }}" class="btn btn-edit mr-1"><i class="fas fa-edit me-1"></i>@lang('Edit')</a>
@if ($data->status == '1')
<a href="{{ route('front.listing.details', $data->slug) }}" class="btn btn-view mr-1"><i class="fas fa-eye me-1"></i>@lang('View')</a>
@endif
@if ($data->status == '0' && $data->plan_id == null)
<a href="{{ route('user.listing.plan', [$data->id]) }}" class="btn bg-danger rounded text-light mr-1">@lang('Select Package')</a>
@endif
@if ($data->plan_id && optional($data->plan)->plan_type && in_array($data->plan->plan_type, ['free', 'monthly']))
<a href="{{ route('user.listing.plan', [$data->id]) }}" class="btn bg-dark rounded text-light mr-1">@lang('Upgrade for Lifetime')</a>
@endif
@if ($data->status == 2)
<a href="{{ route("user.listing.reject", $data->id) }}" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-delete mr-1 rejectView">
<i class="fas fa-eye me-1"></i>
@lang('Reject Reason')
</a>
@endif
</div>
@if ($data->plan_id && optional($data->plan)->plan_type && in_array($data->plan->plan_type, ['free']))
<div class="p-3 border rounded bg-light">
<h5 class="mb-1 text-danger">
<i class="fa fa-lock"></i> আপনার ফ্রি প্ল্যান থাকার কারণে
</h5>
<small class="text-muted">
কাস্টমার ফোন নাম্বার, WhatsApp নাম্বার, ওয়েবসাইট, Google Map এবং ভিডিও দেখতে পারে না।
</small>
</div>
@endif
</div>
</div>
