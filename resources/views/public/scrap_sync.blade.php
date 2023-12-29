@extends('public.layout.public')
@section('script')
	<script type="text/javascript">
		 let url = "{{$url}}/scrap/{{$date}}/{{$page + 1}}"
	  setTimeout(function(){
	    location = `${url}`
	  },3600)
	</script>	
@endsection