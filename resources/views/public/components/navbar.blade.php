<nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 1.5rem;">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{route('public.home')}}">Berita Olahraga</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ (request()->is('/')) ? 'active' : '' }}" aria-current="page" href="{{route('public.home')}}">Halaman Utama</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ (request()->is('all')) ? 'active' : '' }}" aria-current="page" href="{{route('public.all')}}">Semua Berita</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Kategori
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach ($navbars as $item)
              <li>
                <a class="nav-link {{ (request()->is($item->uri)) ? 'active' : '' }}" href="{{ route($item->route) }}">{{ $item->name }}</a>
              </li>
            @endforeach
          </ul>
        </li>
      </ul>
      <form class="d-flex" action="{{route('public.search')}}" method="GET">
        <input class="form-control me-2" type="search" placeholder="Cari Berita" aria-label="Search" name="keyword" value="{{$keyword ?? ''}}">
        <button class="btn btn-outline-success" type="submit">Cari</button>
      </form>
    </div>
  </div>
</nav>
<div class="container">
@error('keyword')
  <div class="alert alert-danger mt-2">
    {{ $message }}
  </div>
@enderror
</div>