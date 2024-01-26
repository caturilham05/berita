@extends('public.layout.public')

@section('description', $meta_description)
@section('keywords', $meta_keywords)
@section('author', $meta_author)

@section('content')
	<div class="container-fluid">
		<center>
			<h1 style="margin-top: 1rem">{{$title_content}}</h1>
		</center>
		<ul class="nav nav-tabs">
			  <li class="nav-item">
			    <a style="color: #000;" class="nav-link {{ request('id') == 39 || !request()->has('id') ? 'active' : '' }}" href="{{ route('public.schedule', ['id' => 39, 'date' => $date_real]) }}" data-url="{{ route('public.schedule', ['id' => 39, 'date' => $date_real]) }}">Liga Inggris</a>
			  </li>
			  <li class="nav-item">
			    <a style="color: #000;" class="nav-link {{ request('id') == 140 || !request()->has('id') ? 'active' : '' }}" href="{{ route('public.schedule', ['id' => 140, 'date' => $date_real]) }}" data-url="{{ route('public.schedule', ['id' => 140, 'date' => $date_real]) }}">Liga Spanyol</a>
			  </li>
			  <li class="nav-item">
			    <a style="color: #000;" class="nav-link {{ request('id') == 61 || !request()->has('id') ? 'active' : '' }}" href="{{ route('public.schedule', ['id' => 61, 'date' => $date_real]) }}" data-url="{{ route('public.schedule', ['id' => 61, 'date' => $date_real]) }}">Liga Prancis</a>
			  </li>
			  <li class="nav-item">
			    <a style="color: #000;" class="nav-link {{ request('id') == 274 || !request()->has('id') ? 'active' : '' }}" href="{{ route('public.schedule', ['id' => 274, 'date' => $date_real]) }}" data-url="{{ route('public.schedule', ['id' => 274, 'date' => $date_real]) }}">Liga Indonesia</a>
			  </li>
		</ul>

		<div class="tab-content">
			  <div class="tab-pane fade {{ request('id') == 39 || !request()->has('id') ? 'show active' : '' }}" id="tab_content_{{request('id')}}">
			    @include('public.football.schedule_england')
			  </div>
			  <div class="tab-pane fade {{ request('id') == 140 || !request()->has('id') ? 'show active' : '' }}" id="tab_content_{{request('id')}}">
			    @include('public.football.schedule_england')
			  </div>
			  <div class="tab-pane fade {{ request('id') == 61 || !request()->has('id') ? 'show active' : '' }}" id="tab_content_{{request('id')}}">
			    @include('public.football.schedule_england')
			  </div>
			  <div class="tab-pane fade {{ request('id') == 274 || !request()->has('id') ? 'show active' : '' }}" id="tab_content_{{request('id')}}">
			    @include('public.football.schedule_england')
			  </div>
		</div>
	</div>
@endsection
@section('script')
	<script type="text/javascript">
		$(document).ready(() => {
			league_id_origin = {{request('id')}};
			let date         = $('a.schedule_date_nav_link.active').data('date')
			let dateFinal    = dateFormat(date)

			if ($('.active #schedule_root_' + league_id_origin).length > 0) {
				scheduleContent(league_id_origin, date, dateFinal)
			}

			$('.active .schedule_date_nav_link').on('click', function() {
				let dateChange     = $(this).data('date')
				let dateFinalClick = dateFormat(dateChange)

				if ($('.active #schedule_root_' + league_id_origin).length > 0) {
					scheduleContent(league_id_origin, dateChange, dateFinalClick)
				}

			})

			function dateFormat(dateParams){
				const d  = new Date()
				let year = d.getFullYear()

				let pattern          = /.*,\s(.*)/is
				let dateChangeRegex  = dateParams.match(pattern)
				let dateChangeConcat = `${dateChangeRegex[1]} ${year}`

				// Split the input date into day, month, and year
				const parts      = dateChangeConcat.split(" ");
				const dayParts   = parts[0];
				const monthParts = parts[1];
				const yearParts  = parts[2];

				// Define a mapping of monthParts names to monthParts numbers
				const monthMap = {
				  Jan: "01",
				  Feb: "02",
				  Mar: "03",
				  Apr: "04",
				  May: "05",
				  Jun: "06",
				  Jul: "07",
				  Aug: "08",
				  Sep: "09",
				  Oct: "10",
				  Nov: "11",
				  Dec: "12",
				};

				// Convert the monthParts name to a monthParts number
				const monthNumber = monthMap[monthParts];

				// Create the desired date string in the format "yyyy-MM-dd"
				const outputDate = `${yearParts}-${monthNumber}-${dayParts}`;
				return outputDate
			}

			async function scheduleContent(league_id_origin, dateParams, dateParamsOri){
				for (let i = 0; i < 5; i++) {
					$('.active #schedule_root_' + league_id_origin).html('<div class="skeleton-thq60keltlg" style=""></div>');
				}
				await $.ajax({
					url: `schedule/content/ajax/${league_id_origin}/${dateParamsOri}`,
					type: 'GET',
					dataType: 'json',
          beforesend:function(){
						$('.active #schedule_root_' + league_id_origin).html('')
          },
          success:function(response){

            $('.active #schedule_root_' + league_id_origin).html(response.html)
          }
				})
			}
		})
	</script>
@endsection
