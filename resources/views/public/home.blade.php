@extends('public.layout.public')

@section('content')
	<div class="container">
		<div class="head_custom">
			@if (!empty($contents))
				@foreach ($contents['scroll_x'] as $item)
					<div class="card_custom">
						<a href="{{route('public.content_detail', ['id' => $item->id, 'title' => $item->title])}}">
						  <img src="{{$item->image_thumb}}" class="img_scroll_x" alt="{{$item->title}}">
						</a>
					  <div class="card-body card_body_custom">
					  	<a href="{{route('public.content_detail', ['id' => $item->id, 'title' => $item->title])}}">
						    <h5 class="card-text card_text_scroll_x">{{$item->title}}</h5>
					  	</a>
					    <small>{{date('d F Y H:i:s', $item->timestamp)}}</small>
					  </div>
					</div>
				@endforeach
			@endif
		</div>

		<div class="row">
			<div class="col-md-9">
				<div class="content_new">
					<a href="{{route('public.content_detail', ['id' => $contents['new']->id, 'title' => $contents['new']->title])}}">
					  <img src="{{$contents['new']->image}}" class="image_new" alt="{{$contents['new']->title}}">
					</a>
					<div class="content_new_text">
						<h5 class="content_new_text_title">{{$contents['new']->title}}</h5>
				    <small>{{date('d F Y H:i:s', $contents['new']->timestamp)}}</small>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<hr>
				<div class="new_feeds_header">
					<h2 class="new_feeds_header_title">Berita Terbaru</h2>
					<a href="{{route('public.all')}}" class="new_feeds_header_title_a">Lihat Semua</a>
				</div>
				<div class="new_feeds">
					@foreach ($contents['new_feeds'] as $new_feed)
						<div class="card_custom mb-4">
							<a href="{{route('public.content_detail', ['id' => $new_feed->id, 'title' => $new_feed->title])}}">
							  <img src="{{$new_feed->image_thumb}}" class="new_feeds_image" alt="{{$new_feed->title}}">
							</a>
						  <div class="card-body">
						  	<a href="{{route('public.content_detail', ['id' => $new_feed->id, 'title' => $new_feed->title])}}">
							    <h5 class="card-text card-text_custom">{{$new_feed->title}}</h5>
						  	</a>
						    <small>{{date('d F Y H:i:s', $new_feed->timestamp)}}</small>
						  </div>
						</div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="recomendation_section">
			<div class="row">
				<div class="col-md-9">
					<hr>
					<div class="new_feeds_header">
						<h2 class="new_feeds_header_title">Rekomendasi untuk anda</h2>
						<a href="{{route('public.all')}}" class="new_feeds_header_title_a">Lihat Semua</a>
					</div>
					<div class="recomendation">
						@foreach ($contents['recomendation'] as $recomendation)
							<div class="">
								<a href="{{route('public.content_detail', ['id' => $recomendation->id, 'title' => $recomendation->title])}}">
								  <img src="{{$recomendation->image_thumb}}" class="recomendation_image" alt="{{$recomendation->title}}">
								</a>
							  <div class="card-body">
							  	<a href="{{route('public.content_detail', ['id' => $recomendation->id, 'title' => $recomendation->title])}}">
								    <h5 class="card-text card_text_recomendation">{{$recomendation->title}}</h5>
							  	</a>
							    <small>{{date('d F Y H:i:s', $recomendation->timestamp)}}</small>
							  </div>
							</div>
						@endforeach
					</div>
				</div>
				<div class="col-md-3">
					<div class="">
						{{-- <div class="view_sticky"> --}}
						<hr>
						<div class="schedule_header">
							<h5>Jadwal</h5>
							<form action="" method="get" style="margin-top: 1rem;">
							  <div class="input-group mb-3">
							    <select class="form-control js-example-basic-single" name="code_countries">
							        <option value="1">Liga Inggris</option>
							        <option value="2">Liga Spanyol</option>
							        <option value="3">Liga Indonesia</option>
							    </select>
							  </div>
							</form>
						</div>
						<table class="table">
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
																		<img src="{{$team['logo']}}" width="30">
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
					</div>
				</div>
			</div>
		</div>

		<div class="home_images">
			<div class="row">
				<div class="col-md-12">
					<hr>
					<div class="new_feeds_header">
						<h2 class="new_feeds_header_title">Foto</h2>
					</div>
					<div class="head_custom" style="padding: 0 !important;">
						@if (!empty($contents))
							@foreach ($contents['scroll_x'] as $item)
								<div class="card_custom">
									<a href="{{route('public.content_detail', ['id' => $item->id, 'title' => $item->title])}}">
									  <img src="{{$item->image_thumb}}" class="img_scroll_x" alt="{{$item->title}}">
									</a>
								  <div class="card-body card_body_custom">
								  	<a href="{{route('public.content_detail', ['id' => $item->id, 'title' => $item->title])}}">
									    <h5 class="card-text card_text_scroll_x">{{$item->title}}</h5>
								  	</a>
								    <small>{{date('d F Y H:i:s', $item->timestamp)}}</small>
								  </div>
								</div>
							@endforeach
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-9">
				<hr>
				<div class="schedule_header">
					<h2 class="new_feeds_header_title">Jadwal Sepak Bola</h2>
					<form action="" method="get" style="margin-top: 1rem;">
					  <div class="input-group mb-3">
					    <select class="form-control js-example-basic-single" name="code_countries">
					        <option value="1">Liga Inggris</option>
					        <option value="2">Liga Spanyol</option>
					        <option value="3">Liga Indonesia</option>
					    </select>
					  </div>
					</form>
				</div>
				<div class="head_custom">
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
											<a href="" class="schedule_btn">SELENGKAPNYA</a>
										</div>
									</div>
								@endif
							@endforeach
						@endforeach
					@endif
				</div>
			</div>
			<div class="col-md-3">
				<hr>
				<div class="schedule_header">
					<h2 class="new_feeds_header_title">Klasemen</h2>
					<form action="" method="get" style="margin-top: 1rem;">
					  <div class="input-group mb-3">
					    <select class="form-control js-example-basic-single" name="code_countries">
					        <option value="1">Liga Inggris</option>
					        <option value="2">Liga Spanyol</option>
					        <option value="3">Liga Indonesia</option>
					    </select>
					  </div>
					</form>
				</div>
				@if (!empty($contents['football_standing']))				
					<div class="table-wrapper">
				    <table class="fl-table">
			        <thead>
				        <tr>
			            <th>Posisi</th>
			            <th>Tim</th>
			            <th>Pts</th>
				        </tr>
			        </thead>
			        <tbody>
			        	@foreach ($contents['football_standing'] as $football_standing)
			        		@foreach ($football_standing as $fs)
			        			@foreach ($fs['league']['standings'] as $standings)
					        		@foreach ($standings as $key => $standing)
					        			@if ($key < 8)
									        <tr>
								            <td>{{$standing['rank']}}</td>
								            <td>{{$standing['team']['name']}}</td>
								            <td>{{$standing['points']}}</td>
									        </tr>
					        			@endif
					        		@endforeach
			        			@endforeach
			        		@endforeach
			        	@endforeach
			        <tbody>
				    </table>
					</div>
					<a href="" class="schedule_btn_tbl" style="margin-top: 1rem; box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0.2 )">SELENGKAPNYA</a>
				@endif
			</div>
		</div>
	</div>
@endsection
