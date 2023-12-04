@extends('public.layout.public')
@section('content')
	<div class="container">
			@if (empty($contents))
				<center>
					<div class="search_info">
						Hasil pencarian <span class="search_info_span">"{{$keyword}}"</span> tidak ditemukan
					</div>
				</center>
			@else
				<div class="search_info">
					Hasil pencarian <span class="search_info_span">"{{$keyword}}"</span>, <span class="search_info_span">{{$contents_total}}</span> hasil ditemukan
				</div>
				<hr>
				<article>
					@foreach ($contents as $item)
						<div class="search_block">
							<div class="search_block_img">
								<img src="{{$item->image_thumb}}" class="search_block_img_item">
							</div>
							<div class="search_block_text">
								<small>{{date('d F Y H:i:s', $item->timestamp)}}</small>
						  	<a href="{{route('public.content_detail', ['id' => $item->id, 'title' => $item->title])}}">
							    <h3 class="search_block_text_h3">{{$item->title}}</h3>
						  	</a>
							</div>
						</div>
					@endforeach
				</article>
	      {!! $contents->withQueryString()->links('pagination::bootstrap-5') !!}
			@endif
	</div>
@endsection