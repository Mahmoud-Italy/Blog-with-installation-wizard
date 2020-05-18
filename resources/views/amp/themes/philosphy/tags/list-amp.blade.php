@extends('amp.themes.philosphy.layouts.layout-amp')
@section('meta')
    <title>{{ str_replace('-',' ',\Request::segment(3)) }} | AMP</title>
@stop
@section('content')


	<header id="top" class="amp-wp-header">
        <div>
            <a href="{{ url('/amp') }}">
                <span class="amp-wp-header-logo-img" 
                    style="background-image: url( /assets/frontend/img/logo.png );">
                </span>
                <span class="amp-site-title">
				    {{ str_replace('-',' ',\Request::segment(3)) }}
                </span>
            </a>
        </div>
    </header>

    @foreach($data as $key => $row)
        <article class="amp-wp-article">
            <div class="amp-wp-article-categories">
                <div class="post-categories-container">
                    <div class="post-categories">
                        <a href="{{url('/amp/category/'.$row->subcat_slug)}}"
                            title="{{ $row->subcat_name }}">
                            {{ $row->subcat_name }}
                        </a>
                    </div>
                </div>
            </div>

            <header class="amp-wp-article-header">
                <a href="{{url('/amp/news/'.$row->slug_url)}}">
                    <h1 class="amp-wp-title">{{ $row->title }}</h1>
                </a>
                <div class="">
                    <div class="amp-wp-meta amp-wp-posted-on">
                        <time datetime="{{ $row->date }}">
                            {{ $row->date }}
                        </time>
                    </div>
                    <div class="amp-wp-meta amp-wp-posted-on">
                        <time datetime="{{ $row->estimate_reading_time }}">
                            {{ $row->estimate_reading_time }}
                        </time>
                    </div>
                </div>
            </header>

            <figure class="amp-wp-article-featured-image wp-caption">
                <a href="{{url('/amp/news/'.$row->slug_url)}}">
                    <amp-img width="1024" height="768" src="{{ $row->image }}" 
                            class="attachment-large size-large wp-post-image amp-wp-enforced-sizes" 
                            alt="{{ $row->title }}" 
                            srcset="{{ $row->image }}" layout="intrinsic">
                        <noscript><img width="1024" height="768" src="{{ $row->image }}" 
                            class="attachment-large size-large wp-post-image" 
                            alt="{{ $row->title }}" 
                            srcset="{{ $row->image }}" sizes="(max-width: 1024px) 100vw, 1024px">
                        </noscript>
                    </amp-img>
                </a>
            </figure>

        </article>
    @endforeach

@stop