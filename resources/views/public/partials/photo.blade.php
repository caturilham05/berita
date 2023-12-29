<div class="head_custom" style="padding: 0 !important;">
	@if (!empty($content_multi_images))
		@foreach ($content_multi_images as $item)
			@if (!empty($item->images))			
				<div class="card_custom">
					<div class="media_icon media_icon_top_right">
						<i class="fa-solid fa-camera"></i>&nbsp;{{!empty($item->images) ? count($item->images) : 0 }} foto</div>
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
			@endif
		@endforeach
	@endif
</div>
