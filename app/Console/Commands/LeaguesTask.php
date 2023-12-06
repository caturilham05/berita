<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\FunctionHelper;
use App\Models\Leagues;
use App\Models\LeaguesSeasons as LS;

class LeaguesTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:leagues-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CURL data ke RapidAPI untuk mendapatkan data Leagues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leagues_api = FunctionHelper::rapidApiFootball('leagues?code=ID', 'GET');
        if (empty($leagues_api) || empty($countries_api)) return false;

        $leagues = [];
        $seasons = [];

        foreach ($leagues_api['response'] as $league_response)
        {
            $leagues[] = [
                'id_origin'      => $league_response['league']['id'],
                'code_countries' => 'ID',
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

            $this->info('Custom task executed successfully!');
        } catch (Exception $e) {
            DB::rollBack(); // <= Rollback in case of an exception
            $this->info('error');
        }
    }
}
