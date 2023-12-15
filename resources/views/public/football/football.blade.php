@extends('public.layout.public')

@section('description', $meta_description)
@section('keywords', $meta_keywords)
@section('author', $meta_author)

@section('content')
	<div class="container">
    @include('public.partials.home_head', ['contents' => $contents])

		<div class="row">
			<div class="col-md-9">
		    @include('public.partials.home_content_new', ['contents' => $contents])
			</div>
			<div class="col-md-3">
				<hr>
				<div class="new_feeds_header">
					<h2 class="new_feeds_header_title">Berita Terbaru</h2>
					<a href="{{route('public.football_all', ['id' => $contents['new']->cat_ids, 'name' => $contents['new']->cat_name])}}" class="new_feeds_header_title_a">Lihat Semua</a>
				</div>
		    @include('public.partials.home_content_new_right', ['contents' => $contents])
			</div>
		</div>

		<div class="recomendation_section">
			<div class="row">
				<div class="col-md-9">
					<hr>
			    @include('public.partials.home_recomendation', ['contents' => $contents])
				</div>
				<div class="col-md-3">
			    @include('public.partials.home_schedule_mini', ['contents' => $contents])
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
		      @include('public.partials.photo', ['content_multi_images' => $contents['content_multi_images']])
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-9">
				<hr>
	      @include('public.partials.home_schedule', ['content_multi_images' => $contents['content_multi_images']])
				<div id="loading_schedule_scroll"></div>
			</div>
			<div class="col-md-3">
				<hr>
	      @include('public.partials.home_standing', ['content_multi_images' => $contents['content_multi_images']])
			</div>
		</div>
	</div>
@endsection

{{-- @section('script')
	<script>
	  // $(document).ready(function(){
			// $('#schedule_mini').on('change',(event) => {
			// 	for (let i = 0; i < 5; i++) {
			// 	  $('#loading_schedule_mini').append('<div class="skeleton-thq60keltlg"></div>');
			// 	}
			// 	$('#schedule_mini_table tbody').empty();
			// 	let league_id_origin = event.target.value;
			// 	$.ajax({
			// 		url: `football/schedule/change/ajax/${league_id_origin}`,
			// 		type: 'GET',
			// 		dataType: 'json',
			// 		success:function(response){
			// 			if (response.status !== 200) return;
			// 			if (response.result[league_id_origin].length === 0) return;
			// 			$('#loading_schedule_mini').empty();
			// 			response.result[league_id_origin].map((schedule, k) => {
			// 				if (k < 5)
			// 				{
			// 					const date = extractDate(schedule.fixture.date)
			// 					let trHtml = 
			// 					`<tr>
			// 						<td>
			// 							<div class="schedule_body">
			// 								<div class="schedule_body_block">
			// 									<div class="schedule_content_item_first_left">
			// 										<img src="${schedule.teams.home.logo}" width="30">
			// 										<span class="schedule_content_item_first_left_text">${schedule.teams.home.name}</span>
			// 									</div>
			// 									<div class="schedule_content_item_first_left">
			// 										<img src="${schedule.teams.away.logo}" width="30">
			// 										<span class="schedule_content_item_first_left_text">${schedule.teams.away.name}</span>
			// 									</div>
			// 								</div>
			// 								<div class="schedule_content_item_first_right">
			// 									<span class="schedule_content_item_first_right_text">${date.explodeDateFinal[0]}/${date.explodeDateFinal[1]}</span>
			// 									<span class="schedule_content_item_first_right_text">${date.time[0]}:${date.time[1]}</span>
			// 								</div>
			// 							</div>
			// 						</td>
			// 					</tr>`

	  //            $("#schedule_mini_table tbody").append(trHtml);
			// 				}
			// 			})
			// 		}
			// 	})
			// });

			// $('#schedule').on('change', (event) => {
			// 	for (let i = 0; i < 5; i++) {
			// 	  $('#loading_schedule_scroll').append('<div class="skeleton-thq60keltlg"></div>');
			// 	}
			// 	$('#schedule_scroll').empty();
			// 	let league_id_origin = event.target.value;
			// 	$.ajax({
			// 		url: `football/schedule/change/ajax/${league_id_origin}`,
			// 		type: 'GET',
			// 		dataType: 'json',
			// 		success:function(response){
			// 			if (response.status !== 200) return;
			// 			if (response.result[league_id_origin].length === 0) return;
			// 			$('#loading_schedule_scroll').empty();
			// 			response.result[league_id_origin].map((schedule, k) => {
			// 				if (k < 5)
			// 				{
			// 					const date = extractDate(schedule.fixture.date)
			// 					const month = monthFunc(date.explodeDateFinal[1])

			// 					const trHtml = `
			// 						<div class="schedule_scroll">
			// 							<div class="schedule_date">
			// 								<div class="schedule_date_month">
			// 									<span class="schedule_date_day">${date.explodeDateFinal[0]}</span>
			// 									<span>${month}<br>${date.explodeDateFinal[3]}</span>
			// 								</div>
			// 								<div class="schedule_date_time">${date.time[0]}:${date.time[1]}</div>
			// 							</div>
			// 							<div class="schedule_date_name">${schedule.teams.home.name} VS ${schedule.teams.away.name}</div>
			// 							<div class="schedule_date_event">${schedule.league.name}</div>
			// 							<div class="schedule_btn_block">
			// 								<a href="" class="schedule_btn">SELENGKAPNYA</a>
			// 							</div>
			// 						</div>
			// 					`
	  //            $("#schedule_scroll").append(trHtml);
			// 				}
			// 			})
			// 		}
			// 	})
			// })

			// $('#standing').on('change', async function(event){
			// 	let league_id_origin = event.target.value;
			// 	let league_name      = $("option:selected", this)[0].innerText
			// 	let url              = "{{url()->current()}}"
			// 	let urlFull          = `${url}/standing/${league_id_origin}/detail/${encodeURI(league_name)}`

			// 	for (let i = 0; i < 5; i++) {
			// 	  $('#loading_standing').append('<div class="skeleton-thq60keltlg"></div>');
			// 	}
			// 	$('#standing_table tbody').empty();

			// 	try{
			// 		document.getElementById('standing_btn').href = urlFull
			// 		const fetch = await $.ajax({
			// 			url: `football/standing/change/ajax/${league_id_origin}`,
			// 			type: 'GET',
			// 			dataType: 'json',
			// 		})

			// 		if (fetch.status !== 200) return;
			// 		if (fetch.result[league_id_origin].length === 0) return;
			// 	  $('#loading_standing').empty();
			// 		fetch.result[league_id_origin].map((standing, k) => {
			// 			standing.league.standings.map((v,i) => {
			// 				v.map((final, final_k) => {
			// 					if (final_k < 10)
			// 					{
			// 						const trHtml = `
			// 			        <tr>
			// 		            <td>${final.rank}</td>
			// 		            <td>${final.team.name}</td>
			// 		            <td>${final.points}</td>
			// 			        </tr>
			// 			      `
			// 						$("#standing_table tbody").append(trHtml);
			// 					}
			// 				})
			// 			})
			// 		})
			// 	}catch(e){
			// 		console.log(e)
			// 	}
			// })

			// function extractDate(scheduleDate)
			// {
			// 	const getDate          = scheduleDate
			// 	var options            = { year: 'numeric', month: 'short', day: 'numeric'};
			// 	const getDateExtract   = new Date(getDate)
			// 	const getDateFinal     = getDateExtract.toLocaleString("id-ID")
			// 	const explodeDateFinal = getDateFinal.split('/');
			// 	const explode2 				 = (explodeDateFinal[2].indexOf(',') === -1) ? explodeDateFinal[2].split(' ') : explodeDateFinal[2].split(',')
			// 	const time 						 = explode2[1].split('.')
			// 	explodeDateFinal.push(explode2[0])
			// 	const obj = {
			// 		'explodeDateFinal': explodeDateFinal,
			// 		'time': time
			// 	}
			// 	return obj
			// }

			// function monthFunc(month)
			// {
			// 	let monthMinus  = month - 1;
			// 	const monthReal = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sept', 'Okt', 'Nov', 'Des'];
			// 	return monthReal[monthMinus]
			// }
	  // });
	</script>
@endsection --}}