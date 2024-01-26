@if (empty($data))
	<div class="schedule_content" style="display: block !important;">
		<center>
			<h5>Tidak Ada Jadwal</h5>
		</center>
	</div>
@else
	@foreach ($data as $item)
		@php
			$winner_home_color = !empty($item['teams']['home']['winner']) ? '#000' : 'var(--abu)';
			$winner_away_color = !empty($item['teams']['away']['winner']) ? '#000' : 'var(--abu)';
		@endphp
		<div class="schedule_content">
			<div class="schedule_content_team">
				<span class="schedule_content_team_name">{{$item['teams']['home']['name']}}</span>
				<img src="{{$item['teams']['home']['logo']}}" alt="{{$item['teams']['home']['name']}}" width="50" height="50">
			</div>

			<div class="schedule_content_score">
				<span class="schedule_content_score_team" style="color: {{$winner_home_color}} !important">{{$item['goals']['home']}}</span>
				<span class="schedule_content_score_v">V</span>
				<span class="schedule_content_score_team" style="color: {{$winner_away_color}} !important">{{$item['goals']['away']}}</span>
			</div>

			<div class="schedule_content_team">
				<img src="{{$item['teams']['away']['logo']}}" alt="{{$item['teams']['away']['name']}}" width="50" height="50">
				<span class="schedule_content_team_name">{{$item['teams']['away']['name']}}</span>
			</div>
		</div>
	@endforeach	
@endif
