<nav class="custom-navbar" data-spy="affix" data-offset-top="20">
    <div class="container">
        <a class="logo" href="#">Admin Berita</a>
        <ul class="nav">
            <li class="item">
                <a class="link" href="{{route('admin.dashboard')}}">Home</a>
            </li>
            <li class="item">
                <a class="link" href="{{route('admin.category')}}">Category Scrap</a>
            </li>
            <li class="item">
                <a class="link"href="{{route('admin.tag')}}">Tag Scrap</a>
            </li>
            <li class="item">
                <a class="link"href="{{route('admin.settings')}}">Navbar Settings</a>
            </li>
            <li class="item">
                <form action="/admin/logout" method="post">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary mt-1 mb-1 ml-3"><i class="fas fa-arrow-right"></i> Logout</button>
                </form>
            </li>
        </ul>
        <a href="javascript:void(0)" id="nav-toggle" class="hamburger hamburger--elastic">
            <div class="hamburger-box">
              <div class="hamburger-inner"></div>
            </div>
        </a>
    </div>
</nav>