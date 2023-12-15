<div class="content_new">
	<a href="{{route('public.content_detail', ['id' => $contents['new']->id, 'title' => $contents['new']->title])}}">
	  <img src="{{$contents['new']->image}}" class="image_new" alt="{{$contents['new']->title}}">
	</a>
	<div class="content_new_text">
		<h5 class="content_new_text_title">{{$contents['new']->title}}</h5>
    <small>{{date('d F Y H:i:s', $contents['new']->timestamp)}}</small>
	</div>
</div>
