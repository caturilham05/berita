@extends('admin.layout.admin')

@section('content')
  <header id="home" class="header">
    <div class="overlay"></div>
    <div class="header-content container">
      <h1 class="header-title">
          <span class="up">{{$title}}</span>
      </h1>
    </div>
  </header>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>
          </div>
          <div class="card-body">
            <form action="{{route('admin.navbar_process', 0)}}" method="POST">
                @csrf
                {{-- Navbar Name --}}
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Navbar Name</label>
                  <input type="text" class="form-control" aria-describedby="emailHelp" name="name" placeholder="Navbar Name">
                </div>
                @error('name')
                  <div class="alert alert-danger mt-2">
                    {{ $message }}
                  </div>
                @enderror

                {{-- URI --}}
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">URI</label>
                  <input type="text" class="form-control" aria-describedby="emailHelp" name="uri" placeholder="URI">
                </div>
                @error('uri')
                  <div class="alert alert-danger mt-2">
                    {{ $message }}
                  </div>
                @enderror

                {{-- Route --}}
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Route</label>
                  <input type="text" class="form-control" aria-describedby="emailHelp" name="route" placeholder="Route">
                  <div class="form-text">Url yang akan dituju</div>
                </div>
                @error('route')
                  <div class="alert alert-danger mt-2">
                    {{ $message }}
                  </div>
                @enderror
                <button type="submit" class="btn btn-primary btn-block">Add Navbar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection