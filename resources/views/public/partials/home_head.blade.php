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
