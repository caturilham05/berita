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
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls = LS::select('league_id_origin', 'year')->where('league_id_origin', $request->league_id_origin)->where('year', date('Y'))->get()->toArray();
                if (empty($ls)) return abort(404);
                if (Cache::has('fixtures'))
                {
                    $output = Cache::get('fixtures');
                }
                else
                {
                    foreach ($ls as $key => $value)
                    {
                        $uri         = sprintf('fixtures?season=%s&league=%s', $value['year'], $value['league_id_origin']);
                        $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                        $output[]    = $competition['response'] ?? [];
                    }
            
                    Cache::add('fixtures', $output, now()->addMinutes(60));
                }

                // Define the date and time string in the Indonesian timezone
                $dateString = date('Y-m-d H:i:s');
                $timezoneIndonesia = new DateTimeZone('Asia/Jakarta'); // Use the appropriate timezone for Indonesia

                // Create a DateTime object with the Indonesian timezone
                $dateTimeIndonesia = new DateTime($dateString, $timezoneIndonesia);

                // Convert the DateTime object to UTC timezone
                $timezoneUTC = new DateTimeZone('UTC');
                $dateTimeUTC = new DateTime();
                $dateTimeUTC->setTimezone($timezoneUTC);
                $dateTimeUTC->setTimestamp($dateTimeIndonesia->getTimestamp());

                // Format the result in UTC
                $utcDateString = $dateTimeUTC->format('Y-m-d H:i:s');

                print($utcDateString);
                // convert to timestamp
                $utcDateString = strtotime($utcDateString);

                $oneWeekAgo = strtotime('-7 days');

                $filteredData = [];
                $lessThan     = [];
                $moreThan     = [];
                $groupedData  = [];
                $mergeData    = [];

                foreach ($output as $key => $value)
                {
                    foreach ($value as $item)
                    {
                        $fixtureDateTimestamp = strtotime($item['fixture']['date']);
                        $leagueId             = $item['league']['id'];
                        
                        // Check if the fixture date is now or in the future
                        if ($fixtureDateTimestamp <= $utcDateString)
                        {
                            if (!isset($lessThan[$leagueId])) $lessThan[$leagueId] = [];
                            $lessThan[$leagueId][] = $item;
                        }
                        else
                        {
                            if (!isset($moreThan[$leagueId])) $moreThan[$leagueId] = [];
                            $moreThan[$leagueId][] = $item;
                        }
                        // if ($fixtureDateTimestamp <= $utcDateString)
                        // {
                        //     // echo '<pre>';
                        //     // print_r($item);
                        //     // echo '</pre>';
                        //     if (!isset($groupedData[$leagueId])) $groupedData[$leagueId] = [];
                        //     $groupedData[$leagueId][] = $item;
                        // }
                    }
                }

                $mergeData   = array_merge($lessThan, $moreThan);
                $lessThan    = array_slice($mergeData[0], -7);
                $moreThan    = array_slice($mergeData[1], 0, 7);
                $groupedData = array_merge($lessThan, $moreThan);
            break;

            case 'local':
                $groupedData = FunctionHelper::football_schedule_dummy($request->league_id_origin);                
            break;

            default:
                return;
            break;
        }

        return view('public.football.schedule', [
            'title'            => 'Jadwal & Hasil Pertandingan',
            'schedules'        => $groupedData ?? '',
            'meta_description' => 'Jadwal dan hasil pertandingan sepakbola',
            'meta_keywords'    => 'jadwal, pertandingan, schedule, sepakbola, hasil pertandingan, jadwal liga, liga inggris, liga spanyol, liga indonesia, liga prancis',
            'meta_author'      => 'kbsnews'
        ]);
    }
}
