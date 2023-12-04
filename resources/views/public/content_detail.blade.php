@extends('public.layout.public')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				@if (!empty($content->images))
					<article class="detail">
						<div class="detail_header">
							<h1 class="detail_title">{{$content->title}}</h1>
							<small class="detail_date">{{date('d F Y H:i:s', $content->timestamp)}}</small>
						</div>
						<div id="carouselExampleCaptions" class="carousel slide mt-2" data-bs-ride="carousel">
						  <div class="carousel-indicators">
						    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
						    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
						    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
						  </div>
						  <div class="carousel-inner">
						  	@foreach ($content->images as $key => $image)
							    <div class="carousel-item <?php echo $key == 0 ? 'active' : ''?>">
							      <img src="{{$image['images']}}" class="d-block w-100" alt="{{$image['text']}}">
							      <div class="carousel-caption d-none d-md-block">
							        {{-- <p>{{$image['text']}}</p> --}}
							      </div>
							    </div>
						  	@endforeach
						  </div>
						  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
						    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
						    <span class="visually-hidden">Previous</span>
						  </button>
						  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
						    <span class="carousel-control-next-icon" aria-hidden="true"></span>
						    <span class="visually-hidden">Next</span>
						  </button>
						</div>
					</article>
				@else
					<article class="detail">
						<div class="detail_header">
							<h1 class="detail_title">{{$content->title}}</h1>
							<small class="detail_date">{{date('d F Y H:i:s', $content->timestamp)}}</small>
						</div>
						<div class="detail_content">
							<img src="{{$content->image}}" style="width: 100%" class="detail_content_img">
							<div class="detail_content_text">{!! $content->content !!}</div>
						</div>
					</article>
				@endif
			</div>
			<div class="col-md-4">
				<div class="detail_right">
					<div class="detail_right_header">{{$populer}}</div>
					<hr>
					@foreach ($all as $key => $value)
						<div class="detail_right_content">
							<div class="detail_right_content_number">#{{$key}}</div>
							<a href="" class="detail_right_content_list">{{$value->title}}</a>
						</div>
					@endforeach
					<a href="{{route('public.all')}}" class="btn_custom btn-lg" style="margin-top: 1rem;">Lihat Semua Berita</a>
				</div>
			</div>
		</div>
	</div>
@endsection