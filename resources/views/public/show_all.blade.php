@extends('public.layout.public')

@section('description', $meta_description)
@section('keywords', $meta_keywords)
@section('author', $meta_author)

@section('content')
	<div class="container">
		<div class="indeks">
			<div class="indeks_menu_title">{{$title}}</div>
			<div class="indeks_menu_box">
				<form action="{{!isset($_GET['id']) ? route('public.all') : ($_GET['id'] == 2 ? route('public.motogp_all') : ($_GET['id'] == 6 ? route('public.football_all') : NULL))}}" method="GET">
					<span class="span_form">Lihat Berdasarkan Tanggal</span>
					<input type="date" name="ondate" class="input_date" value="{{$ondate ?? ''}}">
					<button type="submit" class="btn_custom btn-lg">Cari</button>
				</form>
			</div>
		</div>

		@foreach ($contents as $c)
			<div class="list_all">
				<div class="list_all_img_frame">
					<img src="{{$c->image_thumb}}" class="list_all_img" alt="{{$c->title}}">
				</div>
				<div class="list_all_text">
					<a href="{{route('public.content_detail', ['id' => $c->id, 'title' => urlencode($c->title)])}}" class="list_all_text_h5"><h5 >{{$c->title}}</h5></a>
					<small class="list_all_text_small">{{date('d F Y H:i:s', $c->timestamp)}}</small>
				</div>
			</div>
		@endforeach
    @if (!empty($contents))
      {!! $contents->withQueryString()->links('pagination::bootstrap-4') !!}
    @endif
	</div>
@endsection