@extends('layouts.admin')

@section('styles')
<style>
.paginate nav {
float: right;
}
</style>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4 py-3">
<h1 class="h3 mb-0 text-gray-800">{{ __('Dashboard') }}</h1>
<ol class="breadcrumb m-0 py-0">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Search Keywords') }}</a></li>
</ol>
</div>


<div class="row mb-3">
<div class="col-xl-12 col-lg-12 mb-4">
<div class="card">
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
<h6 class="m-0 font-weight-bold text-primary">@lang('User Search Keywords')</h6>
</div>
@if (count($keywords) > 0)
<div id="geniustable" class="table-responsive table--mobile-lg">
<table class="table align-items-center table-flush">
<thead class="thead-light">
<tr>
<th>@lang('Keyword')</th>
<th style="width:20%;">@lang('Date')</th>
</tr>
</thead>
<tbody>
@foreach ($keywords as $key => $data)
<tr>
<td>{{ $data->keywords }}</td>
<td>{{ date('M d, Y', strtotime($data->created_at)) }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@else
<p class="text-center">@lang('NO KEYWORDS FOUND')</p>
@endif

<div class="paginate p-2 text-right">
{{ $keywords->links() }}
</div>
</div>
</div>
</div>

@endsection
