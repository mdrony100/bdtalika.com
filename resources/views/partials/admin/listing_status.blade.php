<form action="{{ route('admin.listing.reject.submit', $listing->id) }}" method="post">
<div class="modal-body">
@csrf
<div class="input-group">
<label for="">Reject Reason <span class="text-danger">*</span></label>
<textarea name="reject_reason" id="reject_reason" class="input-field" cols="30" rows="5"
placeholder="Type your reason" required></textarea>
@error('reject_reason')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>

</div>
<div class="modal-footer">
<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</a>
<div id="submitBtn">
<button type="submit" class="btn btn-success btn-ok">{{ __('Submit') }}</button>
</div>
</div>
</form>
