@extends('public.layout.public')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				@if (!empty($content->images))
					<article class="detail">
						<div class="container-fluid">
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
								      <img src="{{$image['images']}}" class="d-block w-100" alt="{{$image['text']}}" style="max-height: 750px;">
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
							<h5 style="margin-top: 1rem">{{$image['text']}}</h5>
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
		<hr>
		<div class="row">
			<div class="col-md-8">
				<div class="comment">
					<div style="font-size: 15px; font-weight: 500;">Komentar ({{$comment_total}})</div>
					<div class="comment_card">
			      @if (session()->has('success'))
		          <div class="alert alert-success alert-dismissible fade show" role="alert">
		            <span>{{ session('success') }}</span>
		          </div>
			      @endif
			      <form action="{{route('public.comment.post', ['content_id' => $content->id])}}" method="POST">
			      	@csrf
			        {{-- <input class="form-control p-2 mb-3" type="hidden" name="content_id" value="{{$content->id}}"> --}}
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
			        <button class="btn btn-primary mt-4" type="submit">Kirim</button>
			      </form>
					</div>
				</div>
				<div class="comment_lists">
		      @include('public.partials.reply', ['comments' => $content->comment, 'content_id' => $content->id])
				</div>
			</div>
			<div class="col-md-4">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
			</div>
		</div>
	</div>
@endsection
@section('script')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.balas').each(function(i, e){
				$(`#${e.id}`).on('click', (event) => {
					switch(event.target.innerText)
					{
						case 'Balas':
							$(`#form_${e.id}`)[0].hidden = false
							event.target.innerText = 'Tutup'
						break;

						case 'Tutup':
							$(`#form_${e.id}`)[0].hidden = true
							event.target.innerText = 'Balas'
						break;

						default:
						break;
					}
				})
			})
		})

	</script>
@endsection