<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="icon" href="{{asset('logo/svg/logo-no-background.svg')}}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('meyawo/public_html/assets/css/custom.css') }}">
    <title>Admin Login</title>
  </head>
  <body>
  	<center>
  	</center>
  	<div class="login_page">
			<div class="card" style="width: 18rem;">
				<div class="header_card">
					<h1>Admin Login</h1>
				</div>
			  <div class="card-body">
          @if (session()->has('loginError'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <span>{{ session('loginError') }}</span>
              </div>
          @endif
	        <form action="{{route('login')}}" method="POST">
	            @csrf
	            <div class="input-group mb-3">
	                <input type="text" class="form-control " name="email" placeholder="Input Email" autofocus required>
	            </div>
	            <div class="input-group mb-3">
	                <input type="password" class="form-control" name="password" placeholder="Input Password" autofocus required>
	            </div>
	            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
	        </form>
			  </div>
			</div>  		
  	</div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>