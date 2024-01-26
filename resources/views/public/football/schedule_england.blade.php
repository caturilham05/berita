<div class="schedule_match_day">Matchday 21 of 38</div>
<div class="schedule_date">
	@foreach ($schedule_content_date as $date)
		@php
			$request_date = date('D, d M', strtotime(request('date')));
		@endphp
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a href="#{{$date}}" id="schedule_date_nav_link" class="nav-link schedule_date_nav_link {{$request_date == $date ? 'active' : ''}}" data-date="{{$date}}">{{$date}}</a>
			</li>
		</ul>
	@endforeach
</div>

<div class="container" id="schedule_root_{{request('id')}}"></div>