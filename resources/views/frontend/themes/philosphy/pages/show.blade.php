@extends('frontend.themes.philosphy.layouts.layout')
@section('meta')
	@include('frontend.themes.philosphy.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	<section class="s-content s-content--narrow">
		<div class="row">
			<div class="s-content__header col-full">
				<h1 class="s-content__header-title">
					{{ $row->title }}
				</h1>
			</div> 

			<div class="s-content__media col-full">
				<div class="s-content__post-thumb">
					<p class="lead">{!! nl2br($row->body) !!}</p>
				</div>
			</div> 

			<div class="col-full s-content__main">
				<p class="lead"></p>
			</div> 
		</div> 
	</section> 

@stop