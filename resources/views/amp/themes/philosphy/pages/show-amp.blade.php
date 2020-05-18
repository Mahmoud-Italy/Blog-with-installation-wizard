@extends('amp.themes.philosphy.layouts.layout-amp')
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

        <header class="amp-wp-article-header">
            <h1 class="amp-wp-title">{{ $row->title }}</h1>
        </header>


        <div class="amp-wp-article-content">
            <p>{!! preg_replace(["/<img[^>]+>/","/<iframe[^>]+>/"], ["",""], $row->body) !!}</p>
        </div>

    </article>

@stop