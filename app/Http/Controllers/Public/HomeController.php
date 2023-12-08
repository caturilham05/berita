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
                $groupedData[] = [
                    [
                        'fixture' => [
                            'id'        => 1,
                            'referee'   => 'lorem',
                            'timezone'  => 'UTC',
                            'date'      => '2023-12-01T15:00:00+00:00',
                            "timestamp" => 1702134000
                        ],
                        'league' => [
                            'id'      => 39,
                            "name"    => "Premier League",
                            "country" => "England",
                            "logo"    => "https://media-4.api-sports.io/football/leagues/39.png",
                            "flag"    => "https://media-4.api-sports.io/flags/gb.svg",
                            "season"  => 2023,
                            "round"   => "Regular Season - 15",
                        ],
                        'teams' => [
                            'home' => [
                                'id' => 1,
                                'name' => 'westham sdfsdf',
                                'logo' => 'https://media-4.api-sports.io/football/teams/48.png',
                                'winner' => null
                            ],
                            'away' => [
                                'id' => 2,
                                'name' => 'newcastle sdknfskdlnf',
                                'logo' => 'https://media-4.api-sports.io/football/teams/34.png',
                                'winner' => null
                            ]
                        ],
                        'goals' => [
                            'home' => 2,
                            'away' => 2
                        ],
                        'score' => [
                            'halftime' => [
                                'home' => 1,
                                'away' => 0
                            ],
                            'fulltime' => [
                                'home' => 2,
                                'away' => 2
                            ],
                            'extratime' => [
                                'home' => null,
                                'away' => null
                            ],
                            'pinalty' => [
                                'home' => null,
                                'away' => null
                            ],
                        ]
                    ],
                    [
                        'fixture' => [
                            'id'        => 1,
                            'referee'   => 'lorem',
                            'timezone'  => 'UTC',
                            'date'      => '2023-12-11T15:00:00+00:00',
                            "timestamp" => 1702134000
                        ],
                        'league' => [
                            'id'      => 140,
                            "name"    => "Premier League",
                            "country" => "England",
                            "logo"    => "https://media-4.api-sports.io/football/leagues/39.png",
                            "flag"    => "https://media-4.api-sports.io/flags/gb.svg",
                            "season"  => 2023,
                            "round"   => "Regular Season - 15",
                        ],
                        'teams' => [
                            'home' => [
                                'id' => 1,
                                'name' => 'westham sdjfskd fjsdf',
                                'logo' => 'https://media-4.api-sports.io/football/teams/48.png',
                                'winner' => null
                            ],
                            'away' => [
                                'id' => 2,
                                'name' => 'newcastle slkdfnlsd f',
                                'logo' => 'https://media-4.api-sports.io/football/teams/34.png',
                                'winner' => null
                            ]
                        ],
                        'goals' => [
                            'home' => 2,
                            'away' => 2
                        ],
                        'score' => [
                            'halftime' => [
                                'home' => 1,
                                'away' => 0
                            ],
                            'fulltime' => [
                                'home' => 2,
                                'away' => 2
                            ],
                            'extratime' => [
                                'home' => null,
                                'away' => null
                            ],
                            'pinalty' => [
                                'home' => null,
                                'away' => null
                            ],
                        ]
                    ],
                ];
            break;
            
            default:
                return;
            break;
        }

        return $groupedData;
    }

    public function football_standing()
    {
        $output = [];
        switch (env('APP_ENV'))
        {
            case 'live':
                $ls     = LS::select('league_id_origin', 'year')->whereIn('league_id_origin', [39])->where('year', date('Y'))->get()->toArray();
                if (empty($ls)) return false;
                foreach ($ls as $key => $value)
                {
                    $uri         = sprintf('standings?season=%s&league=%s', $value['year'], $value['league_id_origin']);
                    $competition = FunctionHelper::rapidApiFootball($uri, 'GET');
                    $output[]    = $competition['response'] ?? [];
                }
            break;

            case 'local':
                $output[] = [
                    [
                        'league'   => [
                            'id'        => 39,
                            "name"      => "Premier League",
                            "country"   => "England",
                            "logo"      => "https://media-4.api-sports.io/football/leagues/39.png",
                            "flag"      => "https://media-4.api-sports.io/flags/gb.svg",
                            "season"    => 2023,
                            'standings' => [
                                [
                                    [
                                        'rank' => 1,
                                        'team' => [
                                            'id'   => 1,
                                            'name' => 'Liverpool',
                                            'logo' => 'lorem',
                                        ],
                                        'points'      => 73,
                                        'goalsDiff'   => 45,
                                        'group'       => 'Premier League',
                                        'form'        => 'WWW',
                                        'status'      => 'same',
                                        'description' => 'Promotion - Champions League (Group Stage)',
                                        'all' => [
                                            'played' => 25,
                                            'win'    => 24,
                                            'draw'   => 1,
                                            'lose'   => 0,
                                            'goals'  => [
                                                'for'     => 60,
                                                'againts' => 15
                                            ]
                                        ],
                                        'home' => [
                                            'played' => 13,
                                            'win'    => 13,
                                            'draw'   => 0,
                                            'lose'   => 0,
                                            'goals'  => [
                                                'for'     => 35,
                                                'againts' => 9
                                            ]
                                        ],
                                        'away' => [
                                            'played' => 12,
                                            'win'    => 11,
                                            'draw'   => 1,
                                            'lose'   => 0,
                                            'goals'  => [
                                                'for'     => 25,
                                                'againts' => 6
                                            ]
                                        ],
                                        'update' => '2020-02-02T00:00:00+00:00'
                                    ],
                                    [
                                        'rank' => 2,
                                        'team' => [
                                            'id'   => 3,
                                            'name' => 'MU',
                                            'logo' => 'lorem',
                                        ],
                                        'points'      => 73,
                                        'goalsDiff'   => 45,
                                        'group'       => 'Premier League',
                                        'form'        => 'WWW',
                                        'status'      => 'same',
                                        'description' => 'Promotion - Champions League (Group Stage)',
                                        'all' => [
                                            'played' => 25,
                                            'win'    => 24,
                                            'draw'   => 1,
                                            'lose'   => 0,
                                            'goals'  => [
                                                'for'     => 60,
                                                'againts' => 15
                                            ]
                                        ],
                                        'home' => [
                                            'played' => 13,
                                            'win'    => 13,
                                            'draw'   => 0,
                                            'lose'   => 0,
                                            'goals'  => [
                                                'for'     => 35,
                                                'againts' => 9
                                            ]
                                        ],
                                        'away' => [
                                            'played' => 12,
                                            'win'    => 11,
                                            'draw'   => 1,
                                            'lose'   => 0,
                                            'goals'  => [
                                                'for'     => 25,
                                                'againts' => 6
                                            ]
                                        ],
                                        'update' => '2020-02-02T00:00:00+00:00'
                                    ],
                                ]
                            ]
                        ],
                    ]
                ];
            break;
            
            default:
                return;
            break;
        }

        return $output;
    }
}