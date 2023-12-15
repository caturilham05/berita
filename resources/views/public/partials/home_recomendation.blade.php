<div class="new_feeds_header">
	<h2 class="new_feeds_header_title">Rekomendasi untuk anda</h2>
	<a href="{{route('public.all')}}" class="new_feeds_header_title_a">Lihat Semua</a>
</div>
<div class="recomendation">
	@foreach ($contents['recomendation'] as $recomendation)
		<div class="">
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
