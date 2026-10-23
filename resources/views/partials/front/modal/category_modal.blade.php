@php $area_query = $area_query ?? 'bangladesh'; @endphp
<div class="custom_modal category_modal">
<div class="modal fade" id="category_modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="category_modalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title mb-0" id="category_modalLabel">
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tags" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path
d="M3 8v4.172a2 2 0 0 0 .586 1.414l5.71 5.71a2.41 2.41 0 0 0 3.408 0l3.592 -3.592a2.41 2.41 0 0 0 0 -3.408l-5.71 -5.71a2 2 0 0 0 -1.414 -.586h-4.172a2 2 0 0 0 -2 2z" />
<path d="M18 19l1.592 -1.592a4.82 4.82 0 0 0 0 -6.816l-4.592 -4.592" />
<path d="M7 10h-.01" />
</svg>
Select a Category
</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M18 6l-12 12" />
<path d="M6 6l12 12" />
</svg>
</button>
</div>
<div class="modal-body">
<div class="row">
@if (isset($top_categories) && $top_categories->count() > 0)
<div class="category_list">
<span class="ps-3">All Categories</span>
<ul class="p-2 mb-0">
@foreach ($top_categories as $key => $cat)
<li>
<a class="nav-link {{ $cat->listings->count() == '0' ? 'disabled' : '' }}" href="{{ route('front.listing', ['area' => $area_query, 'category' => $cat->slug]) }}">
<b class="{{ $cat->icon }}"></b>
{{ $cat->title }} ({{ $cat->listings->count() }})
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M9 6l6 6l-6 6" />
</svg>
</a>
</li>
@endforeach
</ul>
</div>
<div class="text-center p-2 more_cat">
<a class="border-0 d-inline" href="{{ route('front.category.directory') }}">
<span>View All Categories</span>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M9 6l6 6l-6 6" />
</svg>
</a>
</div>
@endif
</div>
</div>
</div>
</div>
</div>
</div>
