  
  <!-- Meta tags -->
  <title>{{ $meta->meta_title ?? '' }}</title>
  <meta name="description" content="{{ ($meta->meta_description) ?? '' }}" />
  <meta name="keywords" content="{{ $meta->meta_keywords ?? '' }}" />
  <meta name="author" content="{{ $meta->meta_title ?? '' }}" />
  <link rel="alternate" title="{{ $meta->meta_title ?? '' }}" href="{{ url()->current() }}" />
  <link rel="canonical" href="{{ url()->current() }}">
  <!-- End Meta tags -->

  <!-- Schema.org -->
  <meta itemprop="name" content="{{ $meta->meta_title ?? '' }}">
  <meta itemprop="description" content="{{ ($meta->meta_description) ?? '' }}">
  <meta itemprop="image" content="{{ $meta->image ?? '' }}">
  <!-- End Schema.org -->

  <!-- Facebook Meta -->
  <meta property="og:title" content="{{ $meta->meta_title ?? '' }}" />
  <meta property="og:type" content="{{ $meta->meta_title ?? '' }}" />
  <meta property="og:image" content="{{ $meta->image ?? '' }}" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:site_name" content="{{ url()->current() }}" />
  <meta property="og:description" content="{{ $meta->meta_description ?? '' }}" />
  <!-- End Facebook Meta -->

  <!-- Twitter Meta -->
  <meta name="twitter:card" content="summary" />
  <meta name="twitter:site" content="@{{ url()->current() }}" />
  <meta name="twitter:title" content="{{ $meta->meta_title ?? '' }}" />
  <meta name="twitter:description" content="{{ $meta->meta_description ?? '' }}" />
  <meta name="twitter:image" content="{{ $meta->image ?? '' }}" />
  <meta name="twitter:image:alt" content="{{ $meta->meta_title ?? '' }}" />
  <!-- End Twitter Meta -->
  