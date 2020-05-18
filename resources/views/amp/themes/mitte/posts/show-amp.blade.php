@extends('amp.themes.mitte.layouts.layout-amp')
@section('meta')
    <title>{{ $row->meta_title }} | AMP</title>
@stop
@section('content')

	<header id="top" class="amp-wp-header">
        <div>
            <a href="{{ url('/amp') }}">
                <span class="amp-wp-header-logo-img" 
                    style="background-image: url( /assets/frontend/img/logo.png );">
                </span>
            </a>
        </div>
    </header>
    
    <article class="amp-wp-article">
        <div class="amp-wp-article-categories">
            <div class="post-categories-container">
                <div class="post-categories">
                    <a href="{{ url('category/'.$row->subcat_slug) }}"
                        title="{{ $row->subcat_name }}">
                        {{ $row->subcat_name }}
                    </a>
                </div>
            </div>
        </div>

        <header class="amp-wp-article-header">
            <h1 class="amp-wp-title">{{ $row->title }}</h1>
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
        </figure>

        <div class="amp-wp-article-content">
            <p>{!! preg_replace(["/<img[^>]+>/","/<iframe[^>]+>/"], ["",""], $row->body) !!}</p>
        </div>

        <footer class="amp-wp-article-footer">
            <div class="amp-wp-meta amp-wp-tax-tag">
                <span class="amp-wp-tax-title">Tags</span>
                    @foreach($row->tags as $tag)
                        <a href="{{ url('/amp/tag/'.strtolower(str_replace(' ','-',$tag))) }}" 
                            title="{{ $tag }}"
                            rel="tag"> 
                            {{$tag}} 
                        </a> 
                    @endforeach
            </div>
        </footer>

        <div class="amp-wp-article-related-posts">
            <h2>Related posts</h2>
            <div class="amp-wp-article-related-posts-list">

                @foreach($related as $rel)
                    <div class="amp-wp-article-related-posts-item">
                        <a href="{{ url('/amp/post/'.$rel->slug_url) }}">
                            <div class="sh-ratio">
                                <div class="sh-ratio-container">
                                    <div class="sh-ratio-content" 
                                        style="background-image: url( {{ $rel->image }} )">
                                    </div>
                                </div>
                            </div>
                            <h3>{{ $rel->title }}</h3>
                            <div class="amp-wp-article-related-posts-meta">
                                <div class="amp-wp-meta">{{ $rel->date }} </div>
                                <div class="amp-wp-meta">{{ $rel->estimate_reading_time }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </article>

@stop