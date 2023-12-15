@extends('public.layout.public')

@section('description', $meta_description)
@section('keywords', $meta_keywords)
@section('author', $meta_author)

@section('content')
	<div class="container-fluid">
		<div style="display: flex; justify-content: center; margin: 1rem; text-transform: uppercase;">
			<h2 id="title">{{$title.' '.$league}}</h2>
		</div>
		<div style="margin: 1rem 3rem; display: flex; align-items: center; justify-content: space-between;">
			<span>M = Menang, S = Seri, K = Kalah, D = Dimainkan, SG = Selisih Gol, Pts = Points</span>
			<form action="" method="get">
			  <div class="input-group mb-3">
			    <select class="form-control js-example-basic-single" id="standing" name="code_countries" aria-labelledby="standing_all">
		        <option value="39">Liga Inggris</option>
		        <option value="140">Liga Spanyol</option>
		        <option value="61">Liga Prancis</option>
		        <option value="274">Liga Indonesia</option>
			    </select>
			  </div>
			</form>
		</div>
		<div class="table-wrapper" id="standing_wrapper">
	    <table class="fl-table" id="standing_table">
        <thead>
	        <tr>
            <th>Posisi</th>
            <th>Tim</th>
            <th>M</th>
            <th>S</th>
            <th>K</th>
            <th>D</th>
            <th>SG</th>
            <th>Pts</th>
            <th>Hasil</th>
	        </tr>
        </thead>
        <tbody>
        	@foreach ($result as $football_standing)
        		@foreach ($football_standing as $fs)
        			@foreach ($fs['league']['standings'] as $standings)
		        		@foreach ($standings as $key => $standing)
					        <tr>
				            <td>{{$standing['rank']}}</td>
				            <td>
				            	<img src="{{$standing['team']['logo']}}" alt="{{$standing['team']['name']}}" width="30">
											{{$standing['team']['name']}}
				            </td>
				            <td>{{$standing['all']['win']}}</td>
				            <td>{{$standing['all']['draw']}}</td>
				            <td>{{$standing['all']['lose']}}</td>
				            <td>{{$standing['all']['played']}}</td>
				            <td>{{$standing['goalsDiff']}}</td>
				            <td>{{$standing['points']}}</td>
				            <td style="display: flex; align-items: center; justify-content: space-between;">
				            	<div style="color: {{$standing['form'][0]['color']}}; font-size: 13px; font-weight: bold;" >{{$standing['form'][0]['text']}}</div>
				            	<div style="color: {{$standing['form'][1]['color']}}; font-size: 13px; font-weight: bold;" >{{$standing['form'][1]['text']}}</div>
				            	<div style="color: {{$standing['form'][2]['color']}}; font-size: 13px; font-weight: bold;" >{{$standing['form'][2]['text']}}</div>
				            	<div style="color: {{$standing['form'][3]['color']}}; font-size: 13px; font-weight: bold;" >{{$standing['form'][3]['text']}}</div>
				            	<div style="color: {{$standing['form'][4]['color']}}; font-size: 13px; font-weight: bold;" >{{$standing['form'][4]['text']}}</div>
				            </td>
					        </tr>
		        		@endforeach
        			@endforeach
        		@endforeach
        	@endforeach
        </tbody>
	    </table>
			<div id="loading"></div>
		</div>
	</div>

	<div class="container-fluid" style="margin-top: 5rem;">
		<div style="margin: 1rem 3rem; display: flex; align-items: center; justify-content: center;">
			<div style="font-size: 15px; font-weight: bold; text-transform: uppercase;" id="gol_asist">Statistik Pemain</div>
		</div>
		@if (!empty($result_player))
			<div class="table-wrapper" style="margin-bottom: 3rem;">
		    <table class="fl-table" id="statistic_player_table">
	        <thead>
		        <tr>
	            <th>Peringkat</th>
	            <th>Foto</th>
	            <th>Pemain</th>
	            <th>Club</th>
	            <th>Main</th>
	            <th>Gol</th>
	            <th>Assist</th>
	            <th>Kartu Kuning</th>
	            <th>Kartu Kuning & Merah</th>
	            <th>Kartu Merah</th>
		        </tr>
	        </thead>
	        <tbody>
	        	@foreach ($result_player as $res)
	        		@foreach ($res as $key => $item)        			
	        			@if ($key < 10)
			        		<tr>
			        			<td><div class="player_text">{{$key+1}}</div></td>
			        			<td><div class="player_image"><img src="{{$item['player']['photo']}}" alt="{{$item['player']['name']}}" width="32"></div></td>
			        			<td><span class="player_name">{{$item['player']['name']}}</span></td>
			        			<td><span class="player_name">{{$item['statistics'][0]['team']['name']}}</span></td>
			        			<td><div class="player_text">{{$item['statistics'][0]['games']['appearences']}}</div></td>
			        			<td><div class="player_text">{{$item['statistics'][0]['goals']['total']}}</div></td>
			        			<td><div class="player_text">{{$item['statistics'][0]['goals']['assists']}}</div></td>
			        			<td><div class="player_text">{{$item['statistics'][0]['cards']['yellow']}}</div></td>
			        			<td><div class="player_text">{{$item['statistics'][0]['cards']['yellowred']}}</div></td>
			        			<td><div class="player_text">{{$item['statistics'][0]['cards']['red']}}</div></td>
			        		</tr>
	        			@endif
	        		@endforeach
	        	@endforeach
	        </tbody>
		    </table>
				<div id="loading_player"></div>
			</div>
		@else
			<center>
				<h5>Statistik pemain tidak ditemukan</h5>
			</center>
		@endif
	</div>
@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function(){
		$('#standing').on('change', async function(event){
			let league_id_origin = event.target.value;
			let league_name      = $("option:selected", this)[0].innerText
	
			for (let i = 0; i < 5; i++) {
			  $('#loading').append('<div class="skeleton-thq60keltlg"></div>');
			  $('#loading_player').append('<div class="skeleton-thq60keltlg"></div>');
			}

			$('#standing_table tbody').empty();
			$('#statistic_player_table tbody').empty();
			try{
				document.getElementById('title').innerText = `Klasemen ${league_name}`
				const fetch = await $.ajax({
					url: `/football/standing/change/ajax/${league_id_origin}`,
					type: 'GET',
					dataType: 'json'
				})

				if (fetch.status !== 200) return;
				if (fetch.result[league_id_origin].length === 0) return;
				$('#loading').empty();

				fetch.result[league_id_origin].map((standing, k) => {
					standing.league.standings.map((v,i) => {
						v.map((final, final_k) => {
							const trHtml = `
				        <tr>
			            <td>${final.rank}</td>
			            <td>
			            	<img src="${final.team.logo}" width="30">
			            	${final.team.name}
			            </td>
			            <td>${final.all.win}</td>
			            <td>${final.all.draw}</td>
			            <td>${final.all.lose}</td>
			            <td>${final.all.played}</td>
			            <td>${final.goalsDiff}</td>
			            <td>${final.points}</td>
			            <td style="display: flex; align-items: center; justify-content: space-between;">
			            	<div style="color: ${final.form[0].color}; font-size: 13px; font-weight: bold;" >${final.form[0].text}</div>
			            	<div style="color: ${final.form[1].color}; font-size: 13px; font-weight: bold;" >${final.form[1].text}</div>
			            	<div style="color: ${final.form[2].color}; font-size: 13px; font-weight: bold;" >${final.form[2].text}</div>
			            	<div style="color: ${final.form[3].color}; font-size: 13px; font-weight: bold;" >${final.form[3].text}</div>
			            	<div style="color: ${final.form[4].color}; font-size: 13px; font-weight: bold;" >${final.form[4].text}</div>
			            </td>
				        </tr>
				      `
							$("#standing_table tbody").append(trHtml);
						})
					})
				})

				const fetchPlayer = await $.ajax({
					url: `/statistic/player/${league_id_origin}`,
					type: 'GET',
					dataType: 'json'
				})
				if (fetch.status !== 200) return;
				$('#loading_player').empty();
				$('#statistic_player_table tbody').empty();
				fetchPlayer.result.map((res, k) => {
					res.map((stats, kstats) => {
						if (kstats < 10)
						{
							const trHtmlPlayer = `
			        		<tr>
			        			<td><div class="player_text">${kstats+1}</div></td>
			        			<td><div class="player_image"><img src="${stats.player.photo}" width="32"></div></td>
			        			<td><span class="player_name">${stats.player.name}</span></td>
			        			<td><span class="player_name">${stats.statistics[0].team.name}</span></td>
			        			<td><div class="player_text">${stats.statistics[0].games.appearences !== null ? stats.statistics[0].games.appearences : 0}</div></td>
			        			<td><div class="player_text">${stats.statistics[0].goals.total !== null ? stats.statistics[0].goals.total : 0}</div></td>
			        			<td><div class="player_text">${stats.statistics[0].goals.assists !== null ? stats.statistics[0].goals.assists : 0}</div></td>
			        			<td><div class="player_text">${stats.statistics[0].cards.yellow !== null ? stats.statistics[0].cards.yellow : 0}</div></td>
			        			<td><div class="player_text">${stats.statistics[0].cards.yellowred !== null ? stats.statistics[0].cards.yellowred : 0}</div></td>
			        			<td><div class="player_text">${stats.statistics[0].cards.red !== null ? stats.statistics[0].cards.red : 0}</div></td>
			        		</tr>
							`
							$("#statistic_player_table tbody").append(trHtmlPlayer);
						}
					})
				})
			}catch(e){
				console.log(e)
			}
		})
	})
</script>
@endsection