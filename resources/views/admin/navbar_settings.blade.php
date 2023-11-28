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
	          <a href="{{route('admin.navbar_add')}}" class="btn btn btn-primary mb-3" style="cursor: pointer">Tambah Item</a>

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

            @if (empty($navbar_datas))
              <center>
                <span>Data tidak ditemukan</span>
              </center>
            @else
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Navbar Name</th>
                    <th>Route</th>
                    <th>URI</th>
                    <th>Ordering</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($navbar_datas as $item)
                      <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->route}}</td>
                        <td>{{$item->uri}}</td>
                        <td>{{$item->ordering}}</td>
                        <td style="width: 25%">
                            <form onsubmit="return confirm('Apakah Anda Yakin Ingin Menghapus Data {{$item->name}} ?');" action="{{ route('admin.navbar_delete', $item->id) }}" method="POST">
                                <a href="{{route('admin.navbar_edit', $item->id)}}" class="btn btn-sm btn-info" style="cursor: pointer; margin-left: 0.5rem;">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
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