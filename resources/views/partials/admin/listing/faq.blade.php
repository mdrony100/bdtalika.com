@if (isset($faqs) && $faqs->count() > 0)
@foreach ($faqs as $key => $faq)
<input type="hidden" name="faq_id[]" value="{{ $faq->id }}">
<div class="card mb-4">
<div class="card-header py-3">
<div class="d-flex align-items-center justify-content-between">
<div>
<h6 class="m-0 font-weight-bold text-primary">{{ __('FAQ Items') }}</h6>
</div>
<div>
<a href="{{ route('admin.listing.faq.delete', $faq->id) }}"
onclick="return confirm('Are you sure you want to delete this FAQ?')" class="faqDelete"
title="Remove">
<i class="fas fa-trash-alt text-danger"></i>
</a>
</div>
</div>
</div>

<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="faq_name[]" placeholder="{{ __('Enter Name') }}"
value="{{ $faq->faq_name }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Description') }}</h4>
</div>
</div>
<div class="col-lg-9">
<textarea name="faq_details[]" class="input-field" placeholder="{{ __('Description') }}" cols="30" rows="10">{{ $faq->faq_details }}</textarea>
</div>
</div>
</div>
</div>

@endforeach
@else
<div class="card mb-4">
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
<h6 class="m-0 font-weight-bold text-primary">{{ __('FAQ Items') }}</h6>
</div>
<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="faq_name[]" placeholder="{{ __('Enter Name') }}"
value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Description') }}</h4>
</div>
</div>
<div class="col-lg-9">
<textarea name="faq_details[]" class="input-field" placeholder="{{ __('Description') }}" cols="30" rows="10"></textarea>
</div>
</div>

</div>
</div>
@endif
