@extends('admin.layout.admin')

@section('content')
  <header id="home" class="header">
      <div class="overlay"></div>
    <div class="header-content container">
      <center>
        <h1 class="header-title">
            <span class="up">{{$title}}</span>
        </h1>
      </center>
      @if (session()->has('urlError'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <span>{{ session('urlError') }}</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      @if (session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <span>{{ session('success') }}</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      @if (!empty($content))
        <center>
          <form action="{{route('admin.dashboard.content_detail_scrap')}}" method="POST">
              @csrf
              <input type="hidden" class="form-control " name="id" value="{{$content->id}}">
              <input type="hidden" class="form-control " name="url" value="{{$content->url}}">
              <button type="submit" class="btn btn-primary btn-block">Mulai Scrapping</button>
          </form>
        </center>
      @endif
      <a href="{{$content->url}}" class="btn btn-info btn-block" target="_blank">Lihat Konten Asli</a>
    </div>
  </header>
  @if (empty($content->content))
    <center>
      <div class="alert alert-danger alert-dismissible fade show m-5" role="alert">
          <span>Konten tidak ditemukan</span>
      </div>
    </center>
  @else  
    <section class="section test">
      <div class="container-fluid">
        <div style="display: flex; align-items: center; justify-content: center; flex-direction: column; margin: 1rem;">
          <h1>{{$content->title}}</h1>
          <span style="font-size: 1rem; font-weight: 600;">{{date('d F Y H:i:s', $content->timestamp)}}</span>          
        </div>
        <div style="display: flex; align-items: center; justify-content: center;">
          <img src="{{$content->image}}">
        </div>
        <div class="m-3">
          {!! $content->content !!}
        </div>
      </div>
    </section>
  @endif
@endsection