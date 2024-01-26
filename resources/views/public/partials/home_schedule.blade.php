<div class="schedule_header">
	<h2 class="new_feeds_header_title">Jadwal Sepak Bola</h2>
	<form action="" method="get" style="margin-top: 1rem;">
	  <div class="input-group mb-3">
	    <select class="form-control js-example-basic-single" id="schedule" name="code_countries" aria-labelledby="schedule">
        <option value="39">Liga Inggris</option>
        <option value="140">Liga Spanyol</option>
        <option value="61">Liga Prancis</option>
        <option value="274">Liga Indonesia</option>
	    </select>
	  </div>
	</form>
</div>
<div class="head_custom" id="schedule_scroll">
	@if (!empty($contents['football_schedule']))
		@foreach ($contents['football_schedule'] as $schedule)
			@foreach ($schedule as $key => $item)
				@if ($key < 10)
					<div class="schedule_scroll">
						<div class="schedule_date">
							<div class="schedule_date_month">
								<span class="schedule_date_day">{{date('d', strtotime($item['fixture']['date']))}}</span>
								<span>{{date('M', strtotime($item['fixture']['date']))}}<br>{{date('Y', strtotime($item['fixture']['date']))}}</span>
							</div>
							<div class="schedule_date_time">{{date('H:i', strtotime($item['fixture']['date']))}}</div>
						</div>
						<div class="schedule_date_name">{{$item['teams']['home']['name']}} VS {{$item['teams']['away']['name']}}</div>
						<div class="schedule_date_event">{{$item['league']['name']}}</div>
						<div class="schedule_btn_block">
							<a href="{{route('public.schedule', ['id' => 39, 'date' => date('Y-m-d', strtotime($item['fixture']['date']))])}}" class="schedule_btn">SELENGKAPNYA</a>
						</div>
					</div>
				@endif
			@endforeach
		@endforeach
	@endif
</div>
