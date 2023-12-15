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
