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
