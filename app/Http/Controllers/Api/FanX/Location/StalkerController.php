<?php

namespace App\Http\Controllers\Api\Fanx\Location;

use App\Http\Controllers\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Services\ElasticService;


class StalkerController extends BaseController
{
    protected $params;

    protected $elasticService;

    public function __construct()
    {
        $this->elasticService = new ElasticService();

//        parent::__construct();
    }


    public function index(Request $request)
    {
        return view('location.fan');
    }


    public function get(Request $request)
    {
        $this->params = [
            'app' => $request->input('app'),
            'location' => $request->input('location', '37.497942,127.0254323'),
            'radius' => $request->input('radius', 1000),
            'searchDate' => $request->input('search_date',
                sprintf("%s ~ %s", now()->subDays(8)->toDateString(), now()->subDay()->toDateString())
            ),
        ];

        list($this->params['startDate'], $this->params['endDate']) = array_map('trim', explode('~', $this->params['searchDate']));

        $this->params['mediaIdx'] = config('celeb')[$this->params['app']]['media_idx'];

        $users = $this->getUsers();

        return response()->json([
            'result' => 'success',
            'errno' => 0,
            'message' => 'success',
            'data' => [
                'items' => $users
            ],
        ], Response::HTTP_OK);
    }


    protected function getUsers()
    {
        $query = $this->getQuery();

        $names = ['김**', '박**', '이**', '최**'];
        $staffs = [true, false];

        $request = [
            'index' => $this->getEsIndex(),
//            'type' => '_doc',
            'body' => [
                'size' => 0,
                'query' => $query,
                'aggs' => [
                    'users' => [
                        'terms' => [
                            'field' => 'user.ads_id',
                            'size' => 100,
                        ],
                    ]
                ],
            ],
        ];

//        Log::debug(print_r($request, true));

        $response = $this->elasticService->search($request, 'location');
        $result = [];

//        Log::debug(print_r($response, true));

        foreach ($response['aggregations']['users']['buckets'] as $bucket) {

            $result[] = [
                'ads_id' => $bucket['key'],
                'name' => $names[array_rand($names, 1)],
                'phone' => '010-1234-****',
                'staff' => $staffs[array_rand($staffs, 1)],
                'count' => $bucket['doc_count'],
            ];
        }

        return $result;
    }


    protected function getQuery()
    {
        $must = [];
        $mustNot = [];
        $should = [];
        $filter = [];

        $must[] = [
            'term' => [
                'media_idx' => $this->params['mediaIdx']
            ]
        ];

//        $mustNot[] = [
//            'geo_bounding_box' => [
//                'location.geo_point' => [
//                    'top_left' => [
//                        'lat' => 1,
//                        'lon' => -1,
//                    ],
//                    'bottom_right' => [
//                        'lat' => -1,
//                        'lon' => 1,
//                    ],
//                ]
//            ]
//        ];

        $filter[] = [
            'range' => [
                'reg_date' => [
                    'gte' => $this->params['startDate'],
                    'lte' => $this->params['endDate'],
                ]
            ]
        ];


        list($lat, $lon) = array_map('trim', explode(',', $this->params['location']));

        $filter[] = [
            'geo_distance' => [
                'distance' => sprintf("%sm", $this->params['radius']),
                'location.geo_point' => [
                    'lat' => $lat,
                    'lon' => $lon,
                ]
            ]
        ];

        $query = [
            'bool' => [
//                "minimum_should_match" => 1
            ]
        ];

        if (empty($must) === false) {
            $query['bool']['must'] = $must;
        }

        if (empty($mustNot) === false) {
            $query['bool']['must_not'] = $mustNot;
        }

        if (empty($should) === false) {
            $query['bool']['should'] = $should;
            $query['bool']['minimum_should_match'] = 1;
        }

        if (empty($filter) === false) {
            $query['bool']['filter'] = $filter;
        }

//        Log::debug(print_r($query, true));

        return $query;
    }


    protected function getEsIndex()
    {
        $dates = new CarbonPeriod(
            Carbon::createFromFormat('Y-m-d', $this->params['startDate'])->startOfMonth()->toDateString(),
            'P1M',
            Carbon::createFromFormat('Y-m-d', $this->params['endDate'])->endOfMonth()->toDateString()
        );

        return collect($dates)->map(function ($date) {
            return sprintf("logs_tg9_location_%s*", $date->format('Ym'));
        })->implode(',');
    }
}
