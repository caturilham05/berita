@extends('admin.layout.admin')

@section('content')
  <header id="home" class="header">
    <div class="overlay"></div>
    <div class="header-content container">
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
      @if (session()->has('ErrorCat'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <span>{{ session('ErrorCat') }}</span>
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
      <form action="{{route('admin.scrap')}}" method="POST">
          @csrf
          {{-- Input URL --}}
          <span>Contoh: https://detik.com</span>
          <div class="input-group mb-3">
              <input type="text" class="form-control " name="url" placeholder="Masukkan url yang ingin anda scrap htmlnya">
          </div>
          @error('url')
            <div class="alert alert-danger mt-2">
              {{ $message }}
            </div>
          @enderror
          <div class="input-group mb-3">
              <input type="text" class="form-control " name="page" placeholder="Masukkan halaman yang ingin anda akses">
          </div>
          {{-- Select Category --}}
          <div class="input-group mb-3">
            <select class="form-control" name="category">
            <option value="0">Choose Type Content</option>
            @foreach ($category as $k => $cat)
              <option value="{{$k}}">{{$cat}}</option>
            @endforeach
            </select>
          </div>
          @error('category')
            <div class="alert alert-danger mt-2">
              {{ $message }}
            </div>
          @enderror
          <button type="submit" class="btn btn-primary btn-block">Mulai Scrapping</button>
      </form>
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
            @if (empty($contents))
              <center>
                <h3>Data tidak ditemukan</h3>
              </center>
            @else
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>image_thumb</th>
                    <th>Active / Inactive</th>
                    <th>created date</th>
                    <th>Synchronized</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($contents as $item)
                      <tr>
                        <td>{{$item->title}}</td>
                        <td><img src="{{$item->image_thumb}}"></td>
                        <td>{{!empty($item->is_active) ? 'Active' : 'Inactive'}}</td>
                        <td>{{date('d F Y H:i:s', $item->timestamp)}}</td>
                        <td>{{!empty($item->content) ? 'Sudah Tersinkron' : 'Belum Tersinkron'}}</td>
                        <td style="width: 25%">
                          @if (!empty($item->content))
                            <a href="{{route('admin.dashboard.content_detail', $item->id)}}" class="btn btn-sm btn-info" style="cursor: pointer">Detail</a>
                            <a href="{{route('admin.dashboard.content_edit', $item->id)}}" class="btn btn-sm btn-warning" style="cursor: pointer; margin-left: 0.5rem;">Edit</a>
                          @else
                            <div style="display: flex; align-items: center; flex-wrap: wrap;">
                              <form action="{{route('admin.dashboard.content_detail_scrap')}}" method="POST">
                                  @csrf
                                  <input type="hidden" class="form-control " name="id" value="{{$item->id}}">
                                  <input type="hidden" class="form-control " name="url" value="{{$item->url}}">
                                  <button type="submit" class="btn btn-primary btn-sm" style="cursor: pointer">Sinkron Konten</button>
                              </form>
                              <a href="{{route('admin.dashboard.content_detail', $item->id)}}" class="btn btn-sm btn-info" style="cursor: pointer; margin-left: 0.5rem;">Detail</a>
                              <a href="{{route('admin.dashboard.content_edit', $item->id)}}" class="btn btn-sm btn-warning" style="cursor: pointer; margin-left: 0.5rem;">Edit</a>
                            </div>
                          @endif
                        </td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
          @if (!empty($contents))
            <div class="card-footer clearfix">
            {!! $contents->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection