<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leagues;
use App\Models\LeaguesSeasons as LS;
use App\Models\Countries;
use Illuminate\Support\Facades\DB;
use App\Helpers\FunctionHelper;

class LeaguesController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->code_countries, $request->years);
        $countries      = Countries::select('id', 'code', 'name', 'flag')->get();
        $league_seasons = DB::table('leagues_seasons')->select(
            'leagues_seasons.id',
            'leagues_seasons.league_id',
            'leagues_seasons.league_id_origin',
            'leagues_seasons.year',
            'leagues_seasons.start_date',
            'leagues_seasons.end_date',
            'leagues_seasons.current',
            'leagues.code_countries',
            'leagues.name',
            'leagues.type',
            'leagues.logo',
            'leagues_seasons.created_at'
        )
        ->join('Leagues', 'leagues.id', '=', 'leagues_seasons.league_id')
        ->when($request->code_countries, function ($q) use ($request){
            $q->where('leagues.code_countries', $request->code_countries);
        })
        ->when($request->year, function ($q) use ($request){
            $q->where('leagues_seasons.year', $request->year);
        })
        ->OrderBy('year', 'DESC')
        ->paginate(20);
        $years = LS::select('year')->groupBy('year')->OrderBy('year', 'DESC')->get();

        return view('admin.leagues', [
            'title'     => 'Leagues',
            'datas'     => $league_seasons ?? '',
            'countries' => $countries,
            'years'     => $years,
            'request'   => $request,
        ]);
    }

    public function add()
    {
        $countries = Countries::select('id', 'code', 'name', 'flag')->get();
        return view('admin.leagues_add', [
            'title'     => 'Add League',
            'countries' => $countries
        ]);
    }

    public function process(Request $request)
    {
        $this->validate($request, [
            'code_countries' => 'required|unique:leagues,code_countries',
        ],[
            'code_countries.required' => 'Kode negara tidak boleh kosong',
            'code_countries.unique'   => sprintf('Kode negara %s sudah pernah disinkronkan, silahkan cek kembali', $request->code_countries)
        ]);

        $uri         = sprintf('leagues?code=%s', $request->code_countries);
        $leagues_api = FunctionHelper::rapidApiFootball($uri, 'GET');

        $leagues   = [];
        $seasons   = [];

        foreach ($leagues_api['response'] as $league_response)
        {
            $leagues[] = [
                'id_origin'      => $league_response['league']['id'],
                'code_countries' => $request->code_countries,
                'name'           => $league_response['league']['name'],
                'type'           => $league_response['league']['type'],
                'logo'           => $league_response['league']['logo']
            ];

            foreach ($league_response['seasons'] as $season)
            {
                $seasons[$league_response['league']['id']][] =[
                    'league_id'        => 0,
                    'league_id_origin' => $league_response['league']['id'],
                    'year'             => $season['year'],
                    'start'            => $season['start'],
                    'end'              => $season['end'],
                    'current'          => !empty($season['current']) ? 1 : 0
                ];
            }
        }

        try {
            DB::beginTransaction(); // <= Starting the transaction
            DB::table('leagues')->insert($leagues);

            $leagues_datas = Leagues::select()->whereDate('created_at', date('Y-m-d'))->get()->toArray();
            if (!empty($leagues_datas))
            {
                $seasons_insert = [];
                foreach ($leagues_datas as $value)
                {
                    if (isset($seasons[$value['id_origin']]))
                    {
                        foreach ($seasons[$value['id_origin']] as $k_season => $v_season)
                        {
                            $seasons_insert[] = [
                                'league_id'        => $value['id'],
                                'league_id_origin' => $v_season['league_id_origin'],
                                'year'             => $v_season['year'],
                                'start_date'       => $v_season['start'],
                                'end_date'         => $v_season['end'],
                                'current'          => $v_season['current'],
                            ];
                        }
                    }
                }
            }

            DB::table('leagues_seasons')->insert($seasons_insert);
            DB::commit(); // <= Commit the changes
            $ret = true;
        } catch (Exception $e) {
            DB::rollBack(); // <= Rollback in case of an exception
            $ret = false;
        }

        if (!$ret) return back()->with('error', sprintf('Liga %s gagal disinkronkan', $request->code_countries));
        return back()->with('success', sprintf('Liga %s berhasil disinkronkan', $request->code_countries));
    }
}
