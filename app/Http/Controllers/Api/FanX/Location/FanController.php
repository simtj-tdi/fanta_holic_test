<?php

namespace App\Http\Controllers\Api\Fanx\Location;

use App\Http\Controllers\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Services\ElasticService;


class FanController extends BaseController
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
            'topLeft' => $request->input('top_left'),
            'bottomRight' => $request->input('bottom_right'),
            'precision' => $request->input('precision'),
            'type' => $request->input('type', 'route'), //동선
            'date' => $request->input('date', now()->subDay()->toDateString()),
            'adsId' => $request->input('ads_id'),
        ];

//        Log::debug(__METHOD__.' - params - '.json_encode($this->params));

        if (empty($this->params['date'])) {
            $this->params['date'] = now()->subDay()->toDateString();
        }

        $this->params['targetDate'] = Carbon::createFromFormat('Y-m-d', $this->params['date']);
        $this->params['mediaIdx'] = config('celeb')[$this->params['app']]['media_idx'];

//        Log::debug(print_r($this->params, true));

        if ($this->params['type'] == 'home') {
            $this->params['locationField']  = 'home.geo_point';
        } else if ($this->params['type'] == 'company') {
            $this->params['locationField']  = 'company.geo_point';
        } else {
            $this->params['locationField']  = 'location.geo_point';
        }

        $users = $this->getUsers();

        return response()->json([
            'result' => 'success',
            'errno' => 0,
            'message' => 'success',
            'data' => $users,
        ], Response::HTTP_OK);
    }


    protected function getUsers()
    {
        $query = $this->getQuery();

        if ($this->params['type'] == 'route') {
            $esType = 'location';
            $esIndex = sprintf("logs_tg9_location_201909*", $this->params['targetDate']->format('Ym'));
        } else {
            $esType = 'analysis';
            $esIndex = sprintf("tg9_user_location_201909", $this->params['targetDate']->format('Ym'));
        }

        if (empty($this->params['adsId']) === false) {

            $body =  [
                'size' => 10000,
                '_source' => [
                    'location.geo_point'
                ],
                'query' => $query,
            ];

        } else {

            $body =  [
                'size' => 0,
                'query' => $query,
                'aggs' => [
                    'filtered_cells' => [
                        'geohash_grid' => [
                            'field' => $this->params['locationField'],
                            'precision' => $this->getPrecision(),
                        ],
                        'aggs' => [
                            'users' => [
                                'cardinality' => [
                                    'field' => 'user_idx'
                                ]
                            ],
                            'centroid' => [
                                'geo_centroid' => [
                                    'field' => $this->params['locationField']
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        $request = [
            'index' => $esIndex,
//            'type' => '_doc',
            'body' => $body
        ];

        Log::debug(print_r($request, true));

        $response = $this->elasticService->search($request, $esType);
        $result = [];

//        dd($response);

        if (empty($this->params['adsId']) === false) {
            foreach ($response["hits"]["hits"] as $bucket) {
                $result[] = [
                    $bucket['_source']['location']['geo_point']['lat'],
                    $bucket['_source']['location']['geo_point']['lon'],
                    0
                ];
            }
        } else {
            foreach ($response["aggregations"]["filtered_cells"]["buckets"] as $bucket) {
                $result[] = [
                    $bucket["centroid"]['location']['lat'],
                    $bucket["centroid"]['location']['lon'],
                    $bucket["users"]['value'],
                ];
            }
        }

        return $result;
    }


    protected function getQuery()
    {
        $must = [];
        $mustNot = [];
        $should = [];
        $filter = [];

        if (empty($this->params['adsId']) === false) {

            $must[] = [
                'term' => [
                    'user.ads_id' => $this->params['adsId']
                ]
            ];

        } else {

            $must[] = [
                'term' => [
                    'media_idx' => $this->params['mediaIdx']
                ]
            ];
        }

        $mustNot[] = [
            'geo_bounding_box' => [
                $this->params['locationField'] => [
                    'top_left' => [
                        'lat' => 1,
                        'lon' => -1,
                    ],
                    'bottom_right' => [
                        'lat' => -1,
                        'lon' => 1,
                    ],
                ]
            ]
        ];

        $filter[] = [
            'range' => [
                'reg_date' => [
                    'gte' => $this->params['targetDate']->toDateString(),
                    'lte' => $this->params['targetDate']->toDateString(),
                ]
            ]
        ];

        $filter[] = [
            'geo_bounding_box' => [
                $this->params['locationField'] => [
                    'top_left' => $this->params['topLeft'],
                    'bottom_right' => $this->params['bottomRight'],
                ]
            ]
        ];

//        if ($this->params['type'] == 'home') {
//            $filter[] = [
//                'geo_bounding_box' => [
//                    $this->params['locationField'] => [
//                        'top_left' => $this->params['topLeft'],
//                        'bottom_right' => $this->params['bottomRight'],
//                    ]
//                ]
//            ];
//        } else if ($this->params['type'] == 'company') {
//            $filter[] = [
//                'geo_bounding_box' => [
//                    'company.geo_point' => [
//                        'top_left' => $this->params['topLeft'],
//                        'bottom_right' => $this->params['bottomRight'],
//                    ]
//                ]
//            ];
//        } else {
//            $filter[] = [
//                'geo_bounding_box' => [
//                    'location.geo_point' => [
//                        'top_left' => $this->params['topLeft'],
//                        'bottom_right' => $this->params['bottomRight'],
//                    ]
//                ]
//            ];
//        }

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


    protected function getPrecision()
    {
        if ($this->params['precision'] > 12) {
            return 12;
        } else {
            return $this->params['precision'];
        }
    }
}
