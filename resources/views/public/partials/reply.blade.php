@foreach ($comments as $comment)
	<div class="reply">
		<h3>{{$comment->name}}</h3>
		<small>{{date('d F Y H:i:s', strtotime($comment->created_at))}}</small>
		<div>{{$comment->comment}}</div>
		<div class="reply_icon">
			<div class="reply_icon_item">
		    <form action="{{route('public.comment.like', $comment->id)}}" method="POST">
		    	@csrf
		      <button class="btn btn-light btn-sm" type="submit"><i class="fa-regular fa-thumbs-up"></i>&nbsp;{{$comment->like ?? 0}}</button>
		    </form>
			</div>
			<div class="reply_icon_item">
		    <form action="{{route('public.comment.dislike', $comment->id)}}" method="POST">
		    	@csrf
		      <button class="btn btn-light btn-sm" type="submit"><i class="fa-regular fa-thumbs-down"></i>&nbsp;{{$comment->dislike ?? 0}}</button>
		    </form>
			</div>
			<div class="reply_icon_item balas" id="{{$comment->id}}" data-id="{{$comment->id}}">Balas</div>
		</div>
	</div>
	<div class="reply_block">
    <form action="{{route('public.comment.reply', ['content_id' => $content_id,  'comment_id' => $comment->id])}}" method="POST" id="form_{{$comment->id}}" hidden>
    	@csrf
      <input class="form-control p-2 mb-3" type="text" placeholder="Nama Lengkap" name="name">
      @error('name')
        <div class="alert alert-danger mt-2">
          {{ $message }}
        </div>
      @enderror
      <input class="form-control p-2 mb-3" type="email" placeholder="Email" name="email">
      @error('email')
        <div class="alert alert-danger mt-2">
          {{ $message }}
        </div>
      @enderror
    	<textarea class="form-control mb-3" type="textarea" placeholder="Berikan komentar anda ..." name="comment"></textarea>
      @error('comment')
        <div class="alert alert-danger mt-2">
          {{ $message }}
        </div>
      @enderror
      <button class="btn btn-primary mt-1" type="submit">Balas</button>
    </form>
		<hr>
    @include('public.partials.reply', ['comments' => $comment->replies])
	</div>
@endforeach
