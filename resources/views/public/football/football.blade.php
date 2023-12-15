@extends('public.layout.public')

@section('description', $meta_description)
@section('keywords', $meta_keywords)
@section('author', $meta_author)

@section('content')
	<div class="container">
    @include('public.partials.home_head', ['contents' => $contents])

		<div class="row">
			<div class="col-md-9">
		    @include('public.partials.home_content_new', ['contents' => $contents])
			</div>
			<div class="col-md-3">
				<hr>
				<div class="new_feeds_header">
					<h2 class="new_feeds_header_title">Berita Terbaru</h2>
					<a href="{{route('public.football_all', ['id' => $contents['new']->cat_ids, 'name' => $contents['new']->cat_name])}}" class="new_feeds_header_title_a">Lihat Semua</a>
				</div>
		    @include('public.partials.home_content_new_right', ['contents' => $contents])
			</div>
		</div>

		<div class="recomendation_section">
			<div class="row">
				<div class="col-md-9">
					<hr>
			    @include('public.partials.home_recomendation', ['contents' => $contents])
				</div>
				<div class="col-md-3">
			    @include('public.partials.home_schedule_mini', ['contents' => $contents])
				</div>
			</div>
		</div>

		<div class="home_images">
			<div class="row">
				<div class="col-md-12">
					<hr>
					<div class="new_feeds_header">
						<h2 class="new_feeds_header_title">Foto</h2>
					</div>
		      @include('public.partials.photo', ['content_multi_images' => $contents['content_multi_images']])
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-9">
				<hr>
	      @include('public.partials.home_schedule', ['content_multi_images' => $contents['content_multi_images']])
				<div id="loading_schedule_scroll"></div>
			</div>
			<div class="col-md-3">
				<hr>
	      @include('public.partials.home_standing', ['content_multi_images' => $contents['content_multi_images']])
			</div>
		</div>
	</div>
@endsection