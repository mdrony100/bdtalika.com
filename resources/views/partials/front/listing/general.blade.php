<!-- Room Booking -->
<div class="jb-apply-form bg-white rounded py-4 px-4 border mb-4">
<h4 class="ft-bold mb-1">@lang('Send a Message')</h4>
<form action="{{ route('front.listing.enquiry') }}" method="post">
@csrf

<input type="hidden" name="listing_id" value="{{ $data->id}}">
<input type="hidden" name="type" value="{{ $data->type }}">

<div class="Rego-boo-space mt-3">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1">@lang('Email')</label>
<input type="email" name="email" class="form-control border rounded ps-3" value="" placeholder="Email" autocomplete="off">
</div>
</div>


<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1">@lang('Description')</label>
<textarea class="form-control border rounded ps-3" name="details" placeholder="@lang('Write your message')"></textarea>
</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12">
<button type="submit" class="btn text-light rounded full-width theme-bg ft-medium">@lang('Submit')</button>
</div>
</div>
</div>
</form>
</div>

