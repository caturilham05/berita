@extends('admin.layout.admin')

@section('content')
  <header id="home" class="header">
    <div class="overlay"></div>
    <div class="header-content container">
      <h1 class="header-title">
          <span class="up">{{$title}}</span>
      </h1>
    </div>
  </header>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <a href="{{route('admin.leagues.add')}}" class="btn btn btn-primary mb-3" style="cursor: pointer">Add {{$title}}</a>
              </div>
              <div class="col-md-8">
                <form action="" method="get">
                  <div class="input-group mb-3">
                    <select class="form-control js-example-basic-single" name="code_countries">
                      <option value="">-- Select Countries Code --</option>
                      @foreach ($countries as $record)
                        <option value="{{$record->code}}" {{ $request->code_countries == $record->code ? 'selected' : '' }}>{{$record->name}} ({{$record->code}})</option>
                      @endforeach
                    </select>
                    <select class="form-control js-example-basic-single" name="year">
                      <option value="">-- Select Year --</option>
                      @foreach ($years as $year)
                        <option value="{{$year->year}}" {{ $request->year == $year->year ? 'selected' : '' }}>{{$year->year}}</option>
                      @endforeach
                    </select>
                    <button type="submit" class="btn btn-info btn-sm ml-3 mr-3">Cari</button>
                    <a href="{{route('admin.leagues')}}" class="btn btn btn-warning btn-sm" style="cursor: pointer">Reset</a>
                  </div>
                </form>
              </div>
            </div>
            @if (!$datas->items())
              <center>
                <h3>Data Tidak Ditemukan</h3>
              </center>
            @else
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Code Countries</th>
                    <th>Name League</th>
                    <th>Type</th>
                    <th>Logo</th>
                    <th>Year</th>
                    <th>Start</th>
                    <th>End</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($datas as $item)
                      <tr>
                        <td>{{$item->code_countries}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->type}}</td>
                        <td><img src="{{$item->logo}}" width="100" ></td>
                        <td>{{$item->year}}</td>
                        <td>{{date('d F Y', strtotime($item->start_date))}}</td>
                        <td>{{date('d F Y', strtotime($item->end_date))}}</td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
          @if (!empty($datas))
            <div class="card-footer clearfix">
            {!! $datas->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

@endsection

@section('script')
<script>
  $(document).ready(function(){
      $('.js-example-basic-single').select2();
  });
</script>
@endsection