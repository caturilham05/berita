<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\FunctionHelper;
use App\Models\Contents;
use App\Models\Category;
use App\Models\Tags;
use App\Models\Leagues;
use App\Models\LeaguesSeasons as LS;
use App\Models\Countries;
use App\Models\Comments;

use DateTime;
use DateTimeZone;

class HomeController extends Controller
{

    public function index()
    {
        if (Cache::has('contents'))
        {
            $contents = Cache::get('contents');
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
                'images',
                'content',
                'timestamp',
                'is_active',
                'url',
                'created_at',
                'updated_at'
            )->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();

            Cache::add('contents', $contents, now()->addMinutes(30));
        }

        if (empty($contents)) abort(404);
        $most_comments        = Comments::get()->groupBy('content_id')->skip(0)->take(5)->map->count()->toArray();
        $football_schedule    = $this->football_schedule();
        $football_standing    = $this->football_standing();
        $new_content          = $contents[0];
        $new_feeds            = array_slice($contents, 1, 2, true);
        $content_multi_images = [];
        $most_comments_items  = [];
        $group_by_cat_ids     = [];
        shuffle($contents);
        foreach ($contents as $value)
        {
            if (isset($most_comments[$value->id]))
            {
                $most_comments_items[] = [
                    'id'    => $value->id,
                    'title' => $value->title,
                    'total' => $most_comments[$value->id]
                ];
            }
            if (!empty($value->images))
            {
                $value->images          = json_decode($value->images, 1);
                $content_multi_images[] = $value;
            }
            $group_by_cat_ids[$value->cat_ids] = $value;
        }

        usort($most_comments_items, function($a, $b){
          return $b['total'] - $a['total'];
        });

        $data = [
            'new'                  => $new_content,
            'new_feeds'            => $new_feeds,
            'scroll_x'             => array_slice($contents, 30, 10, true),
            'recomendation'        => array_slice($contents, 15, 8, true),
            'football_schedule'    => $football_schedule ?? '',
            'football_standing'    => $football_standing ?? '',
            'content_multi_images' => $content_multi_images,
            'content_by_cat_id'    => $group_by_cat_ids,
            'most_comments'        => $most_comments_items
        ];

        return view('public.home', [
            'title'            => 'Home',
            'contents'         => $data ?? '',
            'meta_description' => 'Informasi dan berita olahraga terbaru tentang sepakbola, moto gp, basket, tenis, bulutangkis, formula 1, fakta, gosip dan foto video.',
            'meta_keywords'    => 'informasi olahraga, berita olahraga, berita olahraga terbaru, berita olahraga terlengkap, klasemen sepakbola, jadwal pertandingan, hasil pertandingan, fakta olahraga, gosip olahraga, sepakbola, tenis, bulutangkis, formula 1, moto gp, basket',
            'meta_author'      => 'kbsnews'
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
            )->where('is_active', 1)->where('content', '<>', '')->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
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
            )->where('is_active', 1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->paginate(10);
        }

        return view('public.show_all', [
            'title'            => 'Semua Konten Berita',
            'contents'         => $contents ?? '',
            'ondate'           => $request->ondate,
            'meta_description' => 'Informasi dan berita olahraga terbaru tentang sepakbola, moto gp, basket, tenis, bulutangkis, formula 1, fakta, gosip dan foto video.',
            'meta_keywords'    => 'informasi olahraga, berita olahraga, berita olahraga terbaru, berita olahraga terlengkap, klasemen sepakbola, jadwal pertandingan, hasil pertandingan, fakta olahraga, gosip olahraga, sepakbola, tenis, bulutangkis, formula 1, moto gp, basket',
            'meta_author'      => 'kbsnews'
        ]);
    }

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
        }

        $contents = DB::table('contents')->select('id','title')->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->skip(20)->take(7)->get()->toArray();

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
        $meta_keywords = implode(',', explode(' ', $content->title));
        $comment_total = Comments::where('content_id', $request->id)->count();
        $contentsClass = new Contents();
        $images        = $contentsClass->getImages();

        return view('public.content_detail', [
            'title'            => 'Konten Detail',
            'populer'          => 'Berita Terpopuler',
            'content'          => $content ?? '', /*didalam content ada method comment yang dibuat dari model Contents untuk menampilkan data parent comment*/
            'all'              => $contents_new ?? '',
            'comment_total'    => $comment_total,
            'meta_description' => $content->title,
            'meta_keywords'    => $meta_keywords,
            'meta_author'      => 'kbsnews',
            'images'           => $images,
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
            'title'            => 'Hasil Pencarian',
            'keyword'          => $request->keyword,
            'contents'         => !empty($contents->total()) ? $contents : null,
            'contents_total'   => $contents->total(),
            'meta_description' => 'Informasi dan berita olahraga terbaru tentang sepakbola, moto gp, basket, tenis, bulutangkis, formula 1, fakta, gosip dan foto video.',
            'meta_keywords'    => $request->keyword,
            'meta_author'      => 'kbsnews'
        ]);
    }

    /*football*/
    public function football()
    {
        if (!Cache::has('content_football'))
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
            )->where('cat_ids', 6)->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();
            Cache::add('content_football', $contents, now()->addMinutes(30));
        }
        else
        {
            $contents = Cache::get('content_football');
        }

        if (!isset($contents[0])) abort(404);

        $most_comments        = Comments::get()->groupBy('content_id')->skip(0)->take(5)->map->count()->toArray();
        $football_schedule    = $this->football_schedule();
        $football_standing    = $this->football_standing();
        $category             = Category::select('id', 'name')->get()->toArray();
        $category_new         = [];
        $content_multi_images = [];
        $most_comments_items  = [];

        foreach ($category as $value) $category_new[$value['id']] = $value['name'];
        foreach ($contents as $key => $value)
        {
            if (isset($most_comments[$value->id]))
            {
                $most_comments_items[] = [
                    'id'    => $value->id,
                    'title' => $value->title,
                    'total' => $most_comments[$value->id]
                ];
            }

            if (!empty($value->images))
            {
                $value->images          = json_decode($value->images, 1);
                $content_multi_images[] = $value;
            }

            $value->cat_name = $category_new[$value->cat_ids];
            $contents[$key]  = $value;
        }

        $data = [
            'new'                  => $contents[0],
            'new_feeds'            => array_slice($contents, 1, 2, true),
            'scroll_x'             => array_slice($contents, -10, 10, true),
            'recomendation'        => array_slice($contents, -25, 8, true),
            'cat_id'               => $contents[0]->cat_ids,
            'football_schedule'    => $football_schedule ?? '',
            'football_standing'    => $football_standing ?? '',
            'content_multi_images' => $content_multi_images,
            'most_comments'        => $most_comments_items
        ];

        return view('public.football.football', [
            'title'            => 'Football',
            'contents'         => $data,
            'meta_description' => 'Informasi dan berita olahraga terbaru tentang sepakbola, moto gp, basket, tenis, bulutangkis, formula 1, fakta, gosip dan foto video.',
            'meta_keywords'    => 'informasi olahraga, berita olahraga, berita olahraga terbaru, berita olahraga terlengkap, klasemen sepakbola, jadwal pertandingan, hasil pertandingan, fakta olahraga, gosip olahraga, sepakbola, tenis, bulutangkis, formula 1, moto gp, basket',
            'meta_author'      => 'kbsnews'
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
            )->where('cat_ids', 6)->where('is_active', 1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->paginate(10);
        }

        return view('public.show_all', [
            'title'            => 'Semua Konten Sepak Bola',
            'contents'         => $contents ?? '',
            'meta_description' => 'Informasi dan berita olahraga terbaru tentang sepakbola, moto gp, basket, tenis, bulutangkis, formula 1, fakta, gosip dan foto video.',
            'meta_keywords'    => 'informasi olahraga, berita olahraga, berita olahraga terbaru, berita olahraga terlengkap, klasemen sepakbola, jadwal pertandingan, hasil pertandingan, fakta olahraga, gosip olahraga, sepakbola, tenis, bulutangkis, formula 1, moto gp, basket',
            'meta_author'      => 'kbsnews'
        ]);
    }
    /*football*/

    /*motogp*/
    public function motogp()
    {
        if (Cache::has('content_motogp'))
        {
            $contents = Cache::get('content_motogp');
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
            )->where('cat_ids', 2)->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();
            Cache::add('content_motogp', $contents, now()->addMinutes(30));
        }

        if (empty($contents)) abort(404);

        $most_comments        = Comments::get()->groupBy('content_id')->skip(0)->take(5)->map->count()->toArray();
        $football_schedule    = $this->football_schedule();
        $football_standing    = $this->football_standing();
        $category             = Category::select('id', 'name')->get()->toArray();
        $category_new         = [];
        $content_multi_images = [];
        $most_comments_items  = [];

        foreach ($category as $value) $category_new[$value['id']] = $value['name'];

        foreach ($contents as $key => $value)
        {
            if (isset($most_comments[$value->id]))
            {
                $most_comments_items[] = [
                    'id'    => $value->id,
                    'title' => $value->title,
                    'total' => $most_comments[$value->id]
                ];
            }

            if (!empty($value->images))
            {
                $value->images          = json_decode($value->images, 1);
                $content_multi_images[] = $value;
            }

            $value->cat_name = $category_new[$value->cat_ids];
            $contents[$key]    = $value;
        }

        $data = [
            'new'                  => $contents[0],
            'new_feeds'            => array_slice($contents, 1, 2, true),
            'scroll_x'             => array_slice($contents, -10, 10, true),
            'recomendation'        => array_slice($contents, -25, 8, true),
            'cat_id'               => $contents[0]->cat_ids,
            'football_schedule'    => $football_schedule ?? '',
            'football_standing'    => $football_standing ?? '',
            'content_multi_images' => $content_multi_images,
            'most_comments'        => $most_comments_items
        ];

        return view('public.motogp.motogp', [
            'title'            => 'Moto-GP',
            'contents'         => $data,
            'meta_description' => 'Informasi dan berita olahraga terbaru tentang sepakbola, moto gp, basket, tenis, bulutangkis, formula 1, fakta, gosip dan foto video.',
            'meta_keywords'    => 'informasi olahraga, berita olahraga, berita olahraga terbaru, berita olahraga terlengkap, klasemen sepakbola, jadwal pertandingan, hasil pertandingan, fakta olahraga, gosip olahraga, sepakbola, tenis, bulutangkis, formula 1, moto gp, basket',
            'meta_author'      => 'kbsnews'
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
            )->where('cat_ids', 2)->where('is_active', 1)->where('content', '<>', '')->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
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
            )->where('cat_ids', 2)->where('is_active', 1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->paginate(10);
        }

        return view('public.show_all', [
            'title'            => 'Semua Konten Moto-GP',
            'contents'         => $contents ?? '',
            'meta_description' => 'Informasi dan berita olahraga terbaru tentang sepakbola, moto gp, basket, tenis, bulutangkis, formula 1, fakta, gosip dan foto video.',
            'meta_keywords'    => 'informasi olahraga, berita olahraga, berita olahraga terbaru, berita olahraga terlengkap, klasemen sepakbola, jadwal pertandingan, hasil pertandingan, fakta olahraga, gosip olahraga, sepakbola, tenis, bulutangkis, formula 1, moto gp, basket',
            'meta_author'      => 'kbsnews'
        ]);
    }
    /*motogp*/

    public function football_schedule()
    {
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls = LS::select('league_id_origin', 'year')->whereIn('league_id_origin', [39])->where('year', FunctionHelper::year_def())->get()->toArray();
                if (empty($ls)) return false;
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
                $ls = LS::select('league_id_origin', 'year')->where('league_id_origin', $request->id_origin)->where('year', FunctionHelper::year_def())->first();

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
                $ls = LS::select('league_id_origin', 'year')->where('league_id_origin', [39])->where('year', FunctionHelper::year_def())->get();
                if (empty($ls)) return false;
                if (Cache::has('standings'))
                {
                    $output = Cache::get('standings');
                }
                else
                {
                    foreach ($ls as $key => $value)
                    {
                        $uri         = sprintf('standings?season=%s&league=%s', $value['year'], $value['league_id_origin']);
                        $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                        $output[]    = $competition['response'] ?? [];
                    }
            
                    Cache::add('standings', $output, now()->addMinutes(60));
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
                $year = LS::select('year')->where('league_id_origin', $request->id)->where('year', FunctionHelper::year_def())->value('year');
                if (empty($year)) abort(404);
                if (Cache::has('standings') && Cache::has('players'))
                {
                    $output        = Cache::get('standings');
                    $output_player = Cache::get('players');
                }
                else
                {
                    $uri         = sprintf('standings?season=%s&league=%s', $year, $request->id);
                    $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                    $output[]    = $competition['response'] ?? [];
                    Cache::add('standings', $output, now()->addMinutes(60));

                    $uri_player         = sprintf('players/topscorers?season=%s&league=%s', $year, $request->id);
                    $competition_player = FunctionHelper::rapidApiFootball($uri_player, 'GET');
                    $output_player[]    = $competition_player['response'] ?? [];
                    Cache::add('players', $output_player, now()->addMinutes(60));
                }

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
            'title'            => 'Klasemen',
            'league'           => $request->title,
            'result'           => $output ?? '',
            'result_player'    => $output_player ?? '',
            'meta_description' => sprintf('Klasemen %s', $request->title),
            'meta_keywords'    => 'klasemen sepakbola, ranking sepakbola, peringkat sepakbola, peringkat pemain, statistik pemain, jadwal sepakbola',
            'meta_author'      => 'kbsnews'
        ]);
    }

    public function football_standing_change(Request $request)
    {
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls = LS::select('league_id_origin', 'year')->where('league_id_origin', $request->id_origin)->where('year', FunctionHelper::year_def())->first();

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
                $year = LS::select('year')->where('league_id_origin', $request->id)->where('year', FunctionHelper::year_def())->value('year');
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