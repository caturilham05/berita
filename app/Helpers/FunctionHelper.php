<?php

namespace App\Helpers;

class FunctionHelper {

    public static function curlCustom($url = '', $headers = array(), $method = 'GET', $fields = array() , $is_document = 0)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => ''
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

    public static function rapidApiFootball($uri = '', $method = 'GET' )
    {
        $host    = env('RAPIDAPIFOOTBALL');
        $key     = env('RAPIDAPIKEY');
        $url     = sprintf('https://%s/%s', $host, $uri);
        $headers = [sprintf('X-RapidAPI-Host:%s', $host), sprintf('X-RapidAPI-Key:%s', $key)];
        $output  = FunctionHelper::curlCustom($url, $headers, $method);
        $output  = json_decode($output, true);
        return $output;
    }

    public static function football_schedule_dummy($id = 0)
    {
        $groupedData[$id ?? 0] = [
            [
                'fixture' => [
                    'id'        => 1,
                    'referee'   => 'lorem dari ajax',
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
                        'name' => 'westham',
                        'logo' => 'https://media-4.api-sports.io/football/teams/48.png',
                        'winner' => null
                    ],
                    'away' => [
                        'id' => 2,
                        'name' => 'newcastle',
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
                    'referee'   => 'lorem dari ajax 2',
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
                        'name' => 'westham',
                        'logo' => 'https://media-4.api-sports.io/football/teams/48.png',
                        'winner' => null
                    ],
                    'away' => [
                        'id' => 2,
                        'name' => 'newcastle baru',
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

        return $groupedData;
    }

    public static function football_standing_dummy($id = 0)
    {
        $output[$id ?? 0] = [
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
                                    'logo' => 'https://media-4.api-sports.io/football/leagues/39.png',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'WLLDD',
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
                                'form'        => 'DDLLW',
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
                                'rank' => 3,
                                'team' => [
                                    'id'   => 1,
                                    'name' => 'Liverpool',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'WWWWD',
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
                                'rank' => 4,
                                'team' => [
                                    'id'   => 3,
                                    'name' => 'MU',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'LLLDD',
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
                                'rank' => 5,
                                'team' => [
                                    'id'   => 1,
                                    'name' => 'Liverpool',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'WDDWD',
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
                                'rank' => 6,
                                'team' => [
                                    'id'   => 3,
                                    'name' => 'MU',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'WWWWW',
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
                                'rank' => 7,
                                'team' => [
                                    'id'   => 1,
                                    'name' => 'Liverpool',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'WWWLL',
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
                                'rank' => 8,
                                'team' => [
                                    'id'   => 3,
                                    'name' => 'MU',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'DDLLL',
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
                                'rank' => 9,
                                'team' => [
                                    'id'   => 1,
                                    'name' => 'Liverpool',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'WWDDD',
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
                                'rank' => 10,
                                'team' => [
                                    'id'   => 3,
                                    'name' => 'MU',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'DDDDD',
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
                                'rank' => 11,
                                'team' => [
                                    'id'   => 1,
                                    'name' => 'Liverpool',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'LLLLL',
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
                                'rank' => 12,
                                'team' => [
                                    'id'   => 3,
                                    'name' => 'MU',
                                    'logo' => 'lorem',
                                ],
                                'points'      => 73,
                                'goalsDiff'   => 45,
                                'group'       => 'Premier League',
                                'form'        => 'LLLLL',
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

        return $output;
    }

    public static function statistic_player_dummy($id = 0)
    {
        $output[] = [
            [
                'player' => [
                    'id'        => 1,
                    'name'      => 'Haland',
                    'Firstname' => 'Erling',
                    'lastname'  => 'Braut Haland',
                    'age'       => 23,
                    'birth'     => [
                        'date' => '2000-10-01',
                        'place' => 'leeds',
                        'country' => 'england'
                    ],
                    'nationality' => 'Norway',
                    'height' => '194 CM',
                    'weight' => '88 KG',
                    'injured' => false,
                    'photo' => 'https://media-4.api-sports.io/football/players/1100.png'
                ],
                'statistics' => [
                    [
                        'team' => [
                            'id'   => 1,
                            'name' => 'M. City',
                            'logo' => 'https://media-4.api-sports.io/football/teams/50.png'
                        ],
                        'league' => [
                            'id' => 39,
                            'name' => 'Premier League',
                            'country' => 'Englang',
                            'logo' => 'https://media-4.api-sports.io/football/leagues/39.png',
                            'flag' => "https://media-4.api-sports.io/flags/gb.svg",
                            'season' => 2023
                        ],
                        'games' => [
                            'appearences' => 15,
                            'lineups'     => 15,
                            'minutes'     => 1292,
                            'number'      => null,
                            'position'    => 'Attacker',
                            'rating'      => '8.9',
                            'captain'     => false
                        ],
                        'substitues' => [
                            'in'    => 0,
                            'out'   => 3,
                            'bench' => 0
                        ],
                        'shots' => [
                            'tota' => 49,
                            'on'   => 31
                        ],
                        'goals' => [
                            'total'    => 14,
                            'conceded' => 0,
                            'assists'  => 4,
                            'saves'    => null
                        ],
                        'passes' => [
                            'total'    => 168,
                            'key'      => 16,
                            'accuracy' => 8
                        ],
                        'tackles' => [
                            'total'         => 3,
                            'blocks'        => 1,
                            'interceptions' => 2
                        ],
                        'duels' => [
                            'total' => 88,
                            'won' => 44
                        ],
                        'dribbles' => [
                            'attempts' => 11,
                            'success'  => 6,
                            'past'     => null
                        ],
                        'fouls' => [
                            'drawn'    => 16,
                            'commited' => 10
                        ],
                        'cards' => [
                            'yellow'    => 1,
                            'yellowred' => 0,
                            'red'       => 0
                        ],
                        'penalty' => [
                            'won'      => null,
                            'commited' => null,
                            'scored'   => 3,
                            'missed'   => 1,
                            'saved'    => null
                        ]
                    ]
                ]
            ],
            [
                'player' => [
                    'id'        => 1,
                    'name'      => 'Haland',
                    'Firstname' => 'Erling',
                    'lastname'  => 'Braut Haland',
                    'age'       => 23,
                    'birth'     => [
                        'date' => '2000-10-01',
                        'place' => 'leeds',
                        'country' => 'england'
                    ],
                    'nationality' => 'Norway',
                    'height' => '194 CM',
                    'weight' => '88 KG',
                    'injured' => false,
                    'photo' => 'https://media-4.api-sports.io/football/players/1100.png'
                ],
                'statistics' => [
                    [
                        'team' => [
                            'id'   => 1,
                            'name' => 'M. City',
                            'logo' => 'https://media-4.api-sports.io/football/teams/50.png'
                        ],
                        'league' => [
                            'id' => 39,
                            'name' => 'Premier League',
                            'country' => 'Englang',
                            'logo' => 'https://media-4.api-sports.io/football/leagues/39.png',
                            'flag' => "https://media-4.api-sports.io/flags/gb.svg",
                            'season' => 2023
                        ],
                        'games' => [
                            'appearences' => 15,
                            'lineups'     => 15,
                            'minutes'     => 1292,
                            'number'      => null,
                            'position'    => 'Attacker',
                            'rating'      => '8.9',
                            'captain'     => false
                        ],
                        'substitues' => [
                            'in'    => 0,
                            'out'   => 3,
                            'bench' => 0
                        ],
                        'shots' => [
                            'tota' => 49,
                            'on'   => 31
                        ],
                        'goals' => [
                            'total'    => 14,
                            'conceded' => 0,
                            'assists'  => 4,
                            'saves'    => null
                        ],
                        'passes' => [
                            'total'    => 168,
                            'key'      => 16,
                            'accuracy' => 8
                        ],
                        'tackles' => [
                            'total'         => 3,
                            'blocks'        => 1,
                            'interceptions' => 2
                        ],
                        'duels' => [
                            'total' => 88,
                            'won' => 44
                        ],
                        'dribbles' => [
                            'attempts' => 11,
                            'success'  => 6,
                            'past'     => null
                        ],
                        'fouls' => [
                            'drawn'    => 16,
                            'commited' => 10
                        ],
                        'cards' => [
                            'yellow'    => 1,
                            'yellowred' => 0,
                            'red'       => 0
                        ],
                        'penalty' => [
                            'won'      => null,
                            'commited' => null,
                            'scored'   => 3,
                            'missed'   => 1,
                            'saved'    => null
                        ]
                    ]
                ]
            ],
        ];
        return $output;
    }
}
