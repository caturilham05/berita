@extends('admin.layout.admin')

@section('content')
  <header id="home" class="header">
    <div class="overlay"></div>
    <div class="header-content container">
      @if (session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <span>{{ session('success') }}</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      <h1 class="header-title">
          <span class="up">Lorem ipsum dolor sit amet</span>
      </h1>
      @if (session()->has('urlError'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <span>{{ session('urlError') }}</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      <form action="{{route('admin.tag.scrap')}}" method="POST">
          @csrf
          <div class="input-group mb-3">
              <input type="text" class="form-control " name="url" placeholder="Masukkan url yang ingin anda scrap htmlnya" autofocus>
          </div>
          @error('url')
            <div class="alert alert-danger mt-2">
              {{ $message }}
            </div>
          @enderror
          <button type="submit" class="btn btn-primary btn-block">Mulai Scrapping</button>
      </form>
    </div>
  </header>
@endsection

