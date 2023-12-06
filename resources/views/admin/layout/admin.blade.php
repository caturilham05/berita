<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with Meyawo landing page.">
    <meta name="author" content="Devcrud">
    <title>{{$title}}</title>
    <link rel="icon" href="{{asset('logo/svg/logo-no-background.svg')}}" />
    <!-- font icons -->
    <link rel="stylesheet" href="{{ asset('meyawo/public_html/assets/vendors/themify-icons/css/themify-icons.css') }}">
    <!-- Bootstrap + Meyawo main styles -->
    <link rel="stylesheet" href="{{ asset('meyawo/public_html/assets/css/meyawo.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">

    <!-- Page Navbar -->
    @include('admin.components.navbar')

    {{-- Content --}}
    @yield('content')
    {{-- Content --}}

    <!-- footer -->
    @include('admin.components.footer')
	
	<!-- core  -->
    <script src="{{ asset('meyawo/public_html/assets/vendors/jquery/jquery-3.4.1.js') }}"></script>
    <script src="{{ asset('meyawo/public_html/assets/vendors/bootstrap/bootstrap.bundle.js') }}"></script>

    <!-- bootstrap 3 affix -->
	<script src="{{ asset('meyawo/public_html/assets/vendors/bootstrap/bootstrap.affix.js') }}"></script>

    <!-- Meyawo js -->
    <script src="{{ asset('meyawo/public_html/assets/js/meyawo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('script')
</body>
</html>
