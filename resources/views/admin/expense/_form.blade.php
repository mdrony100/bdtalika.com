@include('includes.admin.form-both')
{{ csrf_field() }}

<div class="row">
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Title') }} *</label>
        <input type="text" name="title" class="form-control" required
            value="{{ old('title', $expense->title ?? '') }}" placeholder="{{ __('Domain renewal, Hosting, Theme purchase') }}">
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Type') }} *</label>
        <select name="type" class="form-control" required>
            @foreach ($types as $key => $label)
                <option value="{{ $key }}" {{ old('type', $expense->type ?? 'other') == $key ? 'selected' : '' }}>{{ __($label) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Amount') }} *</label>
        <input type="number" name="amount" class="form-control" min="0" step="0.01" required
            value="{{ old('amount', $expense->amount ?? '') }}" placeholder="0.00">
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Currency') }} *</label>
        <input type="text" name="currency" class="form-control" maxlength="10" required
            value="{{ old('currency', $expense->currency ?? 'BDT') }}">
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Billing Cycle') }} *</label>
        <select name="billing_cycle" class="form-control" required>
            @foreach ($billingCycles as $key => $label)
                <option value="{{ $key }}" {{ old('billing_cycle', $expense->billing_cycle ?? 'one_time') == $key ? 'selected' : '' }}>{{ __($label) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Expense Date') }} *</label>
        <input type="date" name="expense_date" class="form-control" required
            value="{{ old('expense_date', isset($expense) ? optional($expense->expense_date)->format('Y-m-d') : now()->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Renewal Date') }}</label>
        <input type="date" name="renewal_date" class="form-control"
            value="{{ old('renewal_date', isset($expense) ? optional($expense->renewal_date)->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Vendor') }}</label>
        <input type="text" name="vendor" class="form-control"
            value="{{ old('vendor', $expense->vendor ?? '') }}" placeholder="{{ __('Namecheap, Hostinger, Envato') }}">
    </div>
    <div class="col-md-6 col-xl-4 mb-3">
        <label>{{ __('Payment Method') }}</label>
        <input type="text" name="payment_method" class="form-control"
            value="{{ old('payment_method', $expense->payment_method ?? '') }}" placeholder="{{ __('Card, bKash, Bank') }}">
    </div>
    <div class="col-12 mb-3">
        <label>{{ __('Note') }}</label>
        <textarea name="note" class="form-control" rows="4" placeholder="{{ __('Optional note') }}">{{ old('note', $expense->note ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
    </div>
</div>
