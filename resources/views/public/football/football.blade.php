@extends('public.layout.public')

@section('description', $meta_description)
@section('keywords', $meta_keywords)
@section('author', $meta_author)

@section('content')
	<div class="container">
		<div class="head_custom">
			@if (!empty($contents))
				@foreach ($contents['scroll_x'] as $item)
					<div class="card_custom">
						<a href="{{route('public.content_detail', ['id' => $item->id, 'title' => $item->title])}}">
						  <img src="{{$item->image_thumb}}" class="img_scroll_x" alt="{{$item->title}}">
						</a>
					  <div class="card-body card_body_custom">
					  	<a href="{{route('public.content_detail', ['id' => $item->id, 'title' => $item->title])}}">
						    <h5 class="card-text card_text_scroll_x">{{$item->title}}</h5>
					  	</a>
					    <small>{{date('d F Y H:i:s', $item->timestamp)}}</small>
					  </div>
					</div>
				@endforeach
			@endif
		</div>

		<div class="row">
			<div class="col-md-9">
				<div class="content_new">
					<a href="{{route('public.content_detail', ['id' => $contents['new']->id, 'title' => $contents['new']->title])}}">
					  <img src="{{$contents['new']->image}}" class="image_new" alt="{{$contents['new']->title}}">
					</a>
					<div class="content_new_text">
						<h5 class="content_new_text_title">{{$contents['new']->title}}</h5>
				    <small>{{date('d F Y H:i:s', $contents['new']->timestamp)}}</small>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="new_feeds_header">
					<h2 class="new_feeds_header_title">Berita Terbaru</h2>
					<a href="{{route('public.football_all', ['id' => $contents['new']->cat_ids, 'name' => $contents['new']->cat_name])}}" class="new_feeds_header_title_a">Lihat Semua</a>
				</div>
				<hr>
				<div class="new_feeds">
					@foreach ($contents['new_feeds'] as $new_feed)
						<div class="card_custom mb-4">
							<a href="{{route('public.content_detail', ['id' => $new_feed->id, 'title' => $new_feed->title])}}">
							  <img src="{{$new_feed->image_thumb}}" class="new_feeds_image" alt="{{$new_feed->title}}">
							</a>
						  <div class="card-body">
						  	<a href="{{route('public.content_detail', ['id' => $new_feed->id, 'title' => $new_feed->title])}}">
							    <h5 class="card-text card-text_custom">{{$new_feed->title}}</h5>
						  	</a>
						    <small>{{date('d F Y H:i:s', $new_feed->timestamp)}}</small>
						  </div>
						</div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="recomendation_section">
			<div class="new_feeds_header">
				<h2 class="new_feeds_header_title">Rekomendasi untuk anda</h2>
				<a href="{{route('public.football_all', ['id' => $contents['new']->cat_ids, 'name' => $contents['new']->cat_name])}}" class="new_feeds_header_title_a">Lihat Semua</a>
			</div>
			<hr>
			<div class="recomendation">
				@foreach ($contents['recomendation'] as $recomendation)
					<div class="card_custom">
						<a href="{{route('public.content_detail', ['id' => $recomendation->id, 'title' => $recomendation->title])}}">
						  <img src="{{$recomendation->image_thumb}}" class="recomendation_image" alt="{{$recomendation->title}}">
						</a>
					  <div class="card-body">
					  	<a href="{{route('public.content_detail', ['id' => $recomendation->id, 'title' => $recomendation->title])}}">
						    <h5 class="card-text card_text_recomendation">{{$recomendation->title}}</h5>
					  	</a>
					    <small>{{date('d F Y H:i:s', $recomendation->timestamp)}}</small>
					  </div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection