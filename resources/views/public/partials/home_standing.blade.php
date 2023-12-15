<div class="schedule_header">
	<h2 class="new_feeds_header_title">Klasemen</h2>
	<form action="" method="get" style="margin-top: 1rem;">
	  <div class="input-group mb-3">
	    <select class="form-control js-example-basic-single" id="standing" name="code_countries" aria-labelledby="standing">
        <option value="39">Liga Inggris</option>
        <option value="140">Liga Spanyol</option>
        <option value="61">Liga Prancis</option>
        <option value="274">Liga Indonesia</option>
	    </select>
	  </div>
	</form>
</div>

@if (!empty($contents['football_standing']))
	<div class="table-wrapper">
    <table class="fl-table" id="standing_table">
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
	        			@if ($key < 10)
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
      </tbody>
    </table>
	<div id="loading_standing"></div>
	</div>
	<a href="{{route('public.standing', ['id' => 39, 'title' => 'Liga Inggris'])}}" id="standing_btn" class="schedule_btn_tbl" style="margin-top: 1rem; box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0.2 )">SELENGKAPNYA</a>
@endif
