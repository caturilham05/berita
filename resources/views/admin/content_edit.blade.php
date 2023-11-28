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
      @if (session()->has('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <span>{{ session('error') }}</span>
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
    </div>
  </header>
  <section class="section">
    <div class="container">
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
      <form action="{{route('admin.dashboard.content_edit_proccess', $content->id)}}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <select class="form-control" name="is_active">
            @foreach ($set_is_active as $k => $v)
              <option value="{{$k}}" {{ ( $k == $content->is_active) ? 'selected' : '' }}>{{$v}}</option>
            @endforeach
          </select>
        </div>
        @error('is_active')
          <div class="alert alert-danger mt-2">
            {{ $message }}
          </div>
        @enderror
        <button type="submit" class="btn btn-primary btn-block">Edit</button>
      </form>
    </div>
  </section>
@endsection