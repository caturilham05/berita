<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with Meyawo landing page.">
    <meta name="author" content="Devcrud">
    <title>{{$title}}</title>
    <!-- font icons -->
    <link rel="stylesheet" href="{{ asset('meyawo/public_html/assets/vendors/themify-icons/css/themify-icons.css') }}">
    <!-- Bootstrap + Meyawo main styles -->
    <link rel="stylesheet" href="{{ asset('meyawo/public_html/assets/css/meyawo.css') }}">
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

</body>
</html>
