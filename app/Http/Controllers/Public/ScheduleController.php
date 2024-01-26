<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Helpers\FunctionHelper;

use App\Models\Contents;
use App\Models\Leagues;
use App\Models\LeaguesSeasons as LS;

use DateTime;
use DateTimeZone;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $schedule_content      = $this->schedule_content($request->id, $request->date);
        $schedule_content_date = array_keys($schedule_content);

        return view('public.football.schedule', [
            'title'                 => 'Jadwal & Hasil Pertandingan',
            'title_content'         => 'Jadwal & Hasil',
            'meta_description'      => 'Jadwal dan hasil pertandingan sepakbola',
            'meta_keywords'         => 'jadwal, pertandingan, schedule, sepakbola, hasil pertandingan, jadwal liga, liga inggris, liga spanyol, liga indonesia, liga prancis',
            'meta_author'           => 'kbsnews',
            'league_id_origin'      => $request->id,
            'schedule_content_date' => $schedule_content_date,
            'date_real'             => $request->date,
        ]);
    }

    public function schedule_content($league_id_origin, $date_custom)
    {
        $dates                        = FunctionHelper::two_weeks_range();
        $dates['prev_dates']['first'] = array_reverse($dates['prev_dates']['first']);
        $dates_merge                  = array_merge($dates['prev_dates']['first'], $dates['next_dates']['first']);
        $dates_merge                  = array_unique($dates_merge);
        $dates_merge                  = array_values($dates_merge);

        switch (env('APP_ENV'))
        {
            case 'live':
                if (Cache::has('fixtures_per_date'))
                {
                    $schedules = Cache::get('fixtures_per_date');
                }
                else
                {
                    $uri         = sprintf('fixtures?season=%s&league=%s&date=%s', FunctionHelper::year_def(), $league_id_origin, $date_custom);
                    $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                    $schedules[] = $competition['response'] ?? [];
                    // Cache::add('fixtures_per_date', $schedules, now()->addMinutes(300));
                }

                $schedules_final = [];
                foreach ($schedules as $key => $schedule)
                {
                    foreach ($schedule as $schedule_v)
                    {
                        $tz        = 'UTC';
                        $dt        = new DateTime($schedule_v['fixture']['date'], new DateTimeZone($tz)); //first argument "must" be a string
                        $dt->setTimestamp($schedule_v['fixture']['timestamp']); //adjust the object to correct timestamp
                        $date_fixture_repair = $dt->format('D, d M');

                        foreach ($dates_merge as $value_date_merge)
                        {
                            if ($date_fixture_repair == $value_date_merge)
                            {
                                $schedules_final[$value_date_merge][] = $schedule_v;
                            }
                        }
                    }
                }
                $schedules_datas = [];
                foreach ($dates_merge as $item) $schedules_datas[$item] = isset($schedules_final[$item]) ? $schedules_final[$item] : [];
            break;

            case 'local':
                $schedules       = FunctionHelper::football_schedule_dummy($league_id_origin);
                $schedules_final = [];
                foreach ($schedules as $key => $schedule)
                {
                    foreach ($schedule as $schedule_v)
                    {
                        $tz        = 'UTC';
                        $dt        = new DateTime($schedule_v['fixture']['date'], new DateTimeZone($tz)); //first argument "must" be a string
                        $dt->setTimestamp($schedule_v['fixture']['timestamp']); //adjust the object to correct timestamp
                        $date_fixture_repair = $dt->format('D, d M');

                        foreach ($dates_merge as $value_date_merge)
                        {
                            if ($date_fixture_repair == $value_date_merge)
                            {
                                $schedules_final[$value_date_merge][] = $schedule_v;
                            }
                        }
                    }
                }
                $schedules_datas = [];
                foreach ($dates_merge as $item) $schedules_datas[$item] = isset($schedules_final[$item]) ? $schedules_final[$item] : [];
            break;
            
            default:
                // code...
                break;
        }

        return $schedules_datas;
    }

    public function schedule_content_ajax($league_id_origin, $date)
    {
        $res         = $this->schedule_content($league_id_origin, $date);
        $date_custom = date('D, d M', strtotime($date));
        $data        = isset($res[$date_custom]) ? $res[$date_custom] : [];
        $html        = view('public.football.schedule_england_content', [
            'data'             => $data,
            'league_id_origin' => $league_id_origin,
            'date'             => $date,
            'date_custom'      => $date_custom
        ])->render();

        return response()->json([
            'ok'      => 1,
            'message' => 'success',
            'html'    => $html,
            'data'    => $data
        ]);
    }
}
