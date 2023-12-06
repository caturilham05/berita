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
        {{-- success --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        {{-- success --}}

        {{-- error --}}
        @if (session()->has('error'))
            <div class="alert alert-error alert-dismissible fade show" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        {{-- error --}}

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>
          </div>
          <div class="card-body">
            <form action="{{route('admin.leagues.process')}}" method="POST">
                @csrf
				        <div class="input-group mb-3">
									<select class="form-control js-example-basic-single" name="code_countries">
                    <option value="">-- Select Countries Code --</option>
										@foreach ($countries as $record)
				              <option value="{{$record->code}}">{{$record->name}} ({{$record->code}})</option>
										@endforeach
				          </select>
				        </div>
                @error('code_countries')
                  <div class="alert alert-danger mt-2">
                    {{ $message }}
                  </div>
                @enderror
                <button type="submit" class="btn btn-primary btn-block">Add Leagues</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('script')
<script>
	$(document).ready(function(){
	    $('.js-example-basic-single').select2();
	});
</script>
@endsection