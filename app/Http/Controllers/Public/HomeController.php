<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contents;
use App\Models\Category;
use App\Models\Tags;
use App\Models\Leagues;
use App\Models\LeaguesSeasons as LS;
use App\Models\Countries;
use Illuminate\Support\Facades\DB;
use App\Helpers\FunctionHelper;
use DateTime;
use DateTimeZone;

class HomeController extends Controller
{

    public function index()
    {
        $contents = DB::table('contents')->select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'images',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();

        if (empty($contents)) abort(404);

        $football_schedule    = $this->football_schedule();
        $football_standing    = $this->football_standing();
        $new_content          = $contents[0];
        $new_feeds            = array_slice($contents, 1, 2, true);
        $content_multi_images = [];
        $group_by_cat_ids     = [];
        shuffle($contents);
        foreach ($contents as $value)
        {
            if (!empty($value->images)) $content_multi_images[] = $value;
            $group_by_cat_ids[$value->cat_ids] = $value;
        }

        $data = [
            'new'                  => $new_content,
            'new_feeds'            => $new_feeds,
            'scroll_x'             => array_slice($contents, 30, 10, true),
            'recomendation'        => array_slice($contents, 15, 8, true),
            'football_schedule'    => $football_schedule ?? [],
            'football_standing'    => $football_standing ?? [],
            'content_multi_images' => $content_multi_images,
            'content_by_cat_id'    => $group_by_cat_ids,
        ];
        // dd($data['football_standing'][0]);
        return view('public.home', [
            'title'    => 'Home',
            'contents' => $data ?? '',
        ]);
    }

    public function show_all(Request $request)
    {
        if (isset($request->ondate))
        {
            $contents = DB::table('contents')->select(
                'id',
                'tag_ids',
                'cat_ids',
                'title',
                'intro',
                'image',
                'image_thumb',
                'content',
                'timestamp',
                'is_active',
                'url',
                'created_at',
                'updated_at'
            )->where('is_active', 1)->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        else
        {
            $contents = DB::table('contents')->select(
                'id',
                'tag_ids',
                'cat_ids',
                'title',
                'intro',
                'image',
                'image_thumb',
                'content',
                'timestamp',
                'is_active',
                'url',
                'created_at',
                'updated_at'
            )->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        }

        return view('public.show_all', [
            'title'    => 'Semua Konten Berita',
            'contents' => $contents ?? '',
            'ondate'   => $request->ondate
        ]);
    }

    // public function show_all_search_date(Request $request){
    //     $this->validate($request, [
    //         'timestamp' => 'required',
    //     ]);

    //     dd($request->timestamp);
    // }

    public function content_detail(Request $request)
    {
        if (empty($request->id) || empty($request->title)) abort(404);

        $content = Contents::select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'images',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->where('id', $request->id)->where('is_active', 1)->get()->first();
        if (empty($content)) abort(404);
        $content->content = preg_replace('/<a\s+href="https:\/\/www.detik.com[^"]*">([^<]*)<\/a>/', '$1', $content->content);

        if (!empty($content->images))
        {
            $content->images  = json_decode($content->images, true);
            $content->images  = array_slice($content->images, 0, 3);
        }

        $contents = DB::table('contents')->select('title')->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->skip(20)->take(7)->get()->toArray();

        $contents_new = [];
        if (!empty($contents))
        {
            $no = 0;
            foreach ($contents as $key => $value)
            {
                $no++;
                $contents_new[$no] = $value;
            }
        }

        return view('public.content_detail', [
            'title'   => 'Konten Detail',
            'populer' => 'Berita Terpopuler',
            'content' => $content ?? '',
            'all'     => $contents_new ?? ''
        ]);
    }

    public function search(Request $request)
    {
        $this->validate($request, ['keyword' => 'required'], ['keyword.required' => 'pencarian berita tidak boleh kosong']);

        $contents = DB::table('contents')->select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->where('title', 'like', "%".addslashes($request->keyword)."%")->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        return view('public.search', [
            'title'          => 'Hasil Pencarian',
            'keyword'        => $request->keyword,
            'contents'       => !empty($contents->total()) ? $contents : null,
            'contents_total' => $contents->total()
        ]);
    }
    /*football*/
    public function football()
    {
        $contents = DB::table('contents')->select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->where('cat_ids', 6)->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();
        if (empty($contents)) abort(404);

        $category     = Category::select('id', 'name')->get()->toArray();
        $category_new = [];
        foreach ($category as $value) $category_new[$value['id']] = $value['name'];

        foreach ($contents as $key => $value)
        {
            $value->cat_name = $category_new[$value->cat_ids];
            $contents[$key]    = $value;
        }

        $data = [
            'new'           => $contents[0],
            'new_feeds'     => array_slice($contents, 1, 2, true),
            'scroll_x'      => array_slice($contents, -10, 10, true),
            'recomendation' => array_slice($contents, -25, 8, true),
            'cat_id'        => $contents[0]->cat_ids,
        ];

        return view('public.football.football', [
            'title'    => 'Football',
            'contents' => $data
        ]);        
    }

    public function football_show_all(Request $request)
    {
        if (isset($request->ondate))
        {
            $contents = DB::table('contents')->select(
                'id',
                'tag_ids',
                'cat_ids',
                'title',
                'intro',
                'image',
                'image_thumb',
                'content',
                'timestamp',
                'is_active',
                'url',
                'created_at',
                'updated_at'
            )->where('cat_ids', 6)->where('is_active', 1)->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        else
        {
            $contents = DB::table('contents')->select(
                'id',
                'tag_ids',
                'cat_ids',
                'title',
                'intro',
                'image',
                'image_thumb',
                'content',
                'timestamp',
                'is_active',
                'url',
                'created_at',
                'updated_at'
            )->where('cat_ids', 6)->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        return view('public.show_all', [
            'title'    => 'Semua Konten Sepak Bola',
            'contents' => $contents ?? '',
        ]);
    }
    /*football*/

    /*motogp*/
    public function motogp()
    {
        $contents = DB::table('contents')->select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->where('cat_ids', 2)->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();
        if (empty($contents)) abort(404);

        $category     = Category::select('id', 'name')->get()->toArray();
        $category_new = [];
        foreach ($category as $value) $category_new[$value['id']] = $value['name'];

        foreach ($contents as $key => $value)
        {
            $value->cat_name = $category_new[$value->cat_ids];
            $contents[$key]    = $value;
        }

        $data = [
            'new'           => $contents[0],
            'new_feeds'     => array_slice($contents, 1, 2, true),
            'scroll_x'      => array_slice($contents, -10, 10, true),
            'recomendation' => array_slice($contents, -25, 8, true),
            'cat_id'        => $contents[0]->cat_ids,
        ];

        return view('public.motogp.motogp', [
            'title'    => 'Moto-GP',
            'contents' => $data
        ]);        
    }
    public function motogp_show_all(Request $request)
    {
        if (isset($request->ondate))
        {
            $contents = DB::table('contents')->select(
                'id',
                'tag_ids',
                'cat_ids',
                'title',
                'intro',
                'image',
                'image_thumb',
                'content',
                'timestamp',
                'is_active',
                'url',
                'created_at',
                'updated_at'
            )->where('cat_ids', 2)->where('is_active', 1)->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        else
        {
            $contents = DB::table('contents')->select(
                'id',
                'tag_ids',
                'cat_ids',
                'title',
                'intro',
                'image',
                'image_thumb',
                'content',
                'timestamp',
                'is_active',
                'url',
                'created_at',
                'updated_at'
            )->where('cat_ids', 2)->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        }

        return view('public.show_all', [
            'title'    => 'Semua Konten Moto-GP',
            'contents' => $contents ?? '',
        ]);
    }
    /*motogp*/

    public function football_schedule()
    {
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls = LS::select('league_id_origin', 'year')->whereIn('league_id_origin', [39])->where('year', date('Y'))->get()->toArray();
                if (empty($ls)) return false;
                foreach ($ls as $key => $value)
                {
                    $uri         = sprintf('fixtures?season=%s&league=%s', $value['year'], $value['league_id_origin']);
                    $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                    $output[]    = $competition['response'] ?? [];
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

                // convert to timestamp
                $utcDateString = strtotime($utcDateString);

                $filteredData = [];
                $groupedData  = [];

                foreach ($output as $key => $value)
                {
                    foreach ($value as $item)
                    {
                        $fixtureDateTimestamp = strtotime($item['fixture']['date']);
                        $leagueId             = $item['league']['id'];
                        
                        // Check if the fixture date is now or in the future
                        if ($fixtureDateTimestamp >= $utcDateString)
                        {
                            if (!isset($groupedData[$leagueId])) $groupedData[$leagueId] = [];
                            $groupedData[$leagueId][] = $item;
                        }
                    }
                }
            break;

            case 'local':
                $groupedData = FunctionHelper::football_schedule_dummy();                
            break;

            default:
                return;
            break;
        }

        return $groupedData;
    }

    public function football_schedule_change(Request $request)
    {
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls = LS::select('league_id_origin', 'year')->where('league_id_origin', $request->id_origin)->where('year', date('Y'))->first();

                if (empty($ls))
                {
                    return response()->json([
                        'status'  => 404,
                        'message' => 'data not found',
                        'result'  => []
                    ], 200);
                }

                $uri         = sprintf('fixtures?season=%s&league=%s', $ls['year'], $ls['league_id_origin']);
                $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                $output[]    = $competition['response'] ?? [];

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

                // convert to timestamp
                $utcDateString = strtotime($utcDateString);

                $filteredData = [];
                $groupedData  = [];

                foreach ($output as $key => $value)
                {
                    foreach ($value as $item)
                    {
                        $fixtureDateTimestamp = strtotime($item['fixture']['date']);
                        $leagueId             = $item['league']['id'];
                        
                        // Check if the fixture date is now or in the future
                        if ($fixtureDateTimestamp >= $utcDateString)
                        {
                            if (!isset($groupedData[$leagueId])) $groupedData[$leagueId] = [];
                            $groupedData[$leagueId][] = $item;
                        }
                    }
                }
            break;

            case 'local':
                $groupedData = FunctionHelper::football_schedule_dummy($request->id_origin);
            break;
            
            default:
                return response()->json([
                    'status'  => 404,
                    'message' => 'not found',
                    'result'  => []
                ], 404);
            break;
        }

        return response()->json([
            'status'  => 200,
            'message' => 'success',
            'result'  => $groupedData
        ], 200);
    }

    public function football_standing()
    {
        $output = [];
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls = LS::select('league_id_origin', 'year')->where('league_id_origin', [39])->where('year', date('Y'))->get();
                if (empty($ls)) return false;
                foreach ($ls as $key => $value)
                {
                    $uri         = sprintf('standings?season=%s&league=%s', $value['year'], $value['league_id_origin']);
                    $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                    $output[]    = $competition['response'] ?? [];
                }
            break;

            case 'local':
                $output = FunctionHelper::football_standing_dummy();
            break;
            
            default:
                return;
            break;
        }

        return $output;
    }

    public function football_standing_view(Request $request)
    {
        switch (env('APP_ENV'))
        {
            case 'live':
                $year = LS::select('year')->where('league_id_origin', $request->id)->where('year', date('Y'))->value('year');
                if (empty($year)) abort(404);

                $uri         = sprintf('standings?season=%s&league=%s', $year, $request->id);
                $competition = FunctionHelper::rapidApiFootball($uri, 'GET');

                $uri_player         = sprintf('players/topscorers?season=%s&league=%s', $year, $request->id);
                $competition_player = FunctionHelper::rapidApiFootball($uri_player, 'GET');

                $output[]        = $competition['response'] ?? [];
                $output_player[] = $competition_player['response'] ?? [];

                if (!empty($output))
                {
                    foreach ($output as $key => $value)
                    {
                        foreach ($value as $k_league => $league)
                        {
                            foreach ($league['league']['standings'] as $k_standings => $standings)
                            {
                                foreach ($standings as $k_standing => $standing)
                                {
                                    $standing['form'] = str_split($standing['form']);
                                    foreach ($standing['form'] as $k_form => $form)
                                    {
                                        switch ($form)
                                        {
                                            case 'W':
                                                $color = '#0da200';
                                            break;

                                            case 'L':
                                                $color = '#ff0000';
                                            break;

                                            case 'D':
                                                $color = '#404040';
                                            break;
                                            
                                            default:
                                                $color = '#e4e5e6';
                                            break;
                                        }
                                        $standing['form_format'][] = [
                                            'text'  => $form,
                                            'color' => $color
                                        ];
                                    }
                                    unset($standing['form']);
                                    $standing['form'] = $standing['form_format'];
                                    unset($standing['form_format']);
                                    $output[$key][$k_league]['league']['standings'][$k_standings][$k_standing]['form'] = $standing['form'];
                                }
                            }
                        }
                    }
                }
            break;

            case 'local':
                $output        = FunctionHelper::football_standing_dummy($request->id);
                $output_player = FunctionHelper::statistic_player_dummy($request->id);
            break;
            
            default:
                return;
            break;
        }

        return view('public.football.standing', [
            'title'         => 'Klasemen',
            'league'        => $request->title,
            'result'        => $output ?? [],
            'result_player' => $output_player ?? [],
        ]);
    }

    public function football_standing_change(Request $request)
    {
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls = LS::select('league_id_origin', 'year')->where('league_id_origin', $request->id_origin)->where('year', date('Y'))->first();

                if (empty($ls))
                {
                    return response()->json([
                        'status'  => 404,
                        'message' => 'data not found',
                        'result'  => []
                    ], 200);
                }

                $uri                         = sprintf('standings?season=%s&league=%s', $ls['year'], $ls['league_id_origin']);
                $competition                 = FunctionHelper::rapidApiFootball($uri, 'GET');
                $output[$request->id_origin] = $competition['response'] ?? [];

                if (!empty($output))
                {
                    foreach ($output as $key => $value)
                    {
                        foreach ($value as $k_league => $league)
                        {
                            foreach ($league['league']['standings'] as $k_standings => $standings)
                            {
                                foreach ($standings as $k_standing => $standing)
                                {
                                    $standing['form'] = str_split($standing['form']);
                                    foreach ($standing['form'] as $k_form => $form)
                                    {
                                        switch ($form)
                                        {
                                            case 'W':
                                                $color = '#0da200';
                                            break;

                                            case 'L':
                                                $color = '#ff0000';
                                            break;

                                            case 'D':
                                                $color = '#404040';
                                            break;
                                            
                                            default:
                                                $color = '#e4e5e6';
                                            break;
                                        }
                                        $standing['form_format'][] = [
                                            'text'  => $form,
                                            'color' => $color
                                        ];
                                    }
                                    unset($standing['form']);
                                    $standing['form'] = $standing['form_format'];
                                    unset($standing['form_format']);
                                    $output[$key][$k_league]['league']['standings'][$k_standings][$k_standing]['form'] = $standing['form'];
                                }
                            }
                        }
                    }
                }
            break;

            case 'local':
                $output = FunctionHelper::football_standing_dummy($request->id_origin);
            break;
            
            default:
                return response()->json([
                    'status'  => 404,
                    'message' => 'not found',
                    'result'  => []
                ], 404);
            break;
        }

        return response()->json([
            'status'  => 200,
            'message' => 'success',
            'result'  => $output
        ], 200);
    }


    public function football_statistic_player_change(Request $request)
    {
        switch (env('APP_ENV'))
        {
            case 'live':
                $year = LS::select('year')->where('league_id_origin', $request->id)->where('year', date('Y'))->value('year');
                if (empty($year)) abort(404);
                $uri         = sprintf('players/topscorers?season=%s&league=%s', $year, $request->id);
                $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                $output[]    = $competition['response'] ?? [];
            break;
            
            case 'local':
                $output = FunctionHelper::statistic_player_dummy($request->id);
            break;
            
            default:
                return response()->json([
                    'status'  => 404,
                    'message' => 'invalid action',
                    'result'  => []
                ], 404);
            break;
        }

        return response()->json([
            'status'  => 200,
            'message' => 'success',
            'result'  => $output
        ], 200);
    }
}