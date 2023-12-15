<div class="">
	<hr>
	<div class="schedule_header">
		<h5>Jadwal</h5>
		<form action="" method="get" style="margin-top: 1rem;">
		  <div class="input-group mb-3">
		    <select class="form-control js-example-basic-single" id="schedule_mini" name="code_countries" aria-labelledby="code_countries">
		        <option value="39">Liga Inggris</option>
		        <option value="140">Liga Spanyol</option>
		        <option value="61">Liga Prancis</option>
		        <option value="274">Liga Indonesia</option>
		    </select>
		  </div>
		</form>
	</div>
	<table class="table" id="schedule_mini_table">
		<tbody>
			@if (empty($contents['football_schedule']))
				<h5>Jadwal Bola Belum Tersedia</h5>
			@else
				@foreach ($contents['football_schedule'] as $football_schedule)
					@foreach ($football_schedule as $key => $schedule)
						@if ($key < 5)
							<tr>
								<td>
									<div class="schedule_body">
										<div class="schedule_body_block">
											@foreach ($schedule['teams'] as $team)
												<div class="schedule_content_item_first_left">
													<img src="{{$team['logo']}}" alt="{{$team['name']}}" width="30">
													<span class="schedule_content_item_first_left_text">
														{{$team['name']}}
													</span>
												</div>
											@endforeach
										</div>
										<div class="schedule_content_item_first_right">
											<span class="schedule_content_item_first_right_text">{{date('j / n', strtotime($schedule['fixture']['date']))}}</span>
											<span class="schedule_content_item_first_right_text">{{date('H:i', strtotime($schedule['fixture']['date']))}}</span>
										</div>
									</div>
								</td>
							</tr>
						@endif
					@endforeach
				@endforeach
			@endif
		</tbody>
	</table>
	<div id="loading_schedule_mini"></div>
	<hr>
	@if (empty($contents['most_comments']))
		<h5>Saat ini tidak ada komentar terbanyak</h5>
	@else
		<div class="new_feeds_header">
			<h2 class="new_feeds_header_title">Komentar Terbanyak</h2>
		</div>
		@foreach ($contents['most_comments'] as  $value)
			<div style="display: flex; padding-bottom: 1rem;">
				<div style="display: flex; flex-direction: column; align-items: center; margin-right: 1rem;">
					<h5 style="color: var(--green);">{{$value['total']}}</h5>
					<div style="font-size: 9px; letter-spacing: 0.5px;">Komentar</div>
				</div>
				<div  style="font-size: 13px; font-weight: 500;">
					<a class="card_text_recomendation" href="{{route('public.content_detail', ['id' => $value['id'], 'title' => $value['title']])}}">{{$value['title']}}</a>
				</div>
			</div>
		@endforeach
	@endif
</div>