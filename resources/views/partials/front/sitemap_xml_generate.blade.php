@php
$base = 'https://bdtalika.com';
@endphp
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
<loc>{{ $base }}</loc>
<changefreq>daily</changefreq>
<priority>1.0</priority>
</url>
<!-- Category Listing Pages -->
@foreach ($categories as $category)
<url>
<loc>{{ $base }}/listing/bangladesh/{{ $category->slug }}</loc>
<changefreq>daily</changefreq>
<priority>0.8</priority>
</url>
@endforeach
<!-- Blog Posts -->
@foreach ($posts as $post)
<url>
<loc>{{ $base }}/blog-details/{{ $post->slug }}</loc>
@if(!empty($post->updated_at))
<lastmod>{{ \Carbon\Carbon::parse($post->updated_at)->toAtomString() }}</lastmod>
@endif
<changefreq>weekly</changefreq>
<priority>0.7</priority>
</url>
@endforeach
<!-- Blog Categories -->
@foreach (($blogCategories ?? []) as $blogCategory)
<url>
<loc>{{ $base }}/blog/category/{{ $blogCategory->slug }}</loc>
<changefreq>weekly</changefreq>
<priority>0.6</priority>
</url>
@endforeach
<!-- Custom Pages -->
@foreach (($pages ?? []) as $page)
@if(!empty($page->slug))
<url>
<loc>{{ $base }}/{{ $page->slug }}</loc>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>
@endif
@endforeach
<!-- Static Pages -->
<url>
<loc>{{ $base }}/listing</loc>
<changefreq>daily</changefreq>
<priority>0.9</priority>
</url>
<url>
<loc>{{ $base }}/blogs</loc>
<changefreq>daily</changefreq>
<priority>0.8</priority>
</url>
<url>
<loc>{{ $base }}/contact</loc>
<changefreq>monthly</changefreq>
<priority>0.5</priority>
</url>
</urlset>
