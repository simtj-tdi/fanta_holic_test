<?php

namespace App\Http\Controllers\Api\Fanx\Stats;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\CarbonPeriod;

use App\Http\Controllers\Controller as BaseController;
use App\TrendKeyword;

class KeywordController extends BaseController
{
    protected $params;


    public function __construct()
    {
//        parent::__construct();
    }


    public function get(Request $request)
    {
        $this->params = [
            'app' => $request->input('app'),
            'type' => $request->input('type', 'naver'),
            'startDate' => $request->input('start_date', now()->subWeek()->toDateString()),
            'endDate' => $request->input('end_date', now()->subDay()->toDateString()),
        ];

        list($labels, $items, $parameters) = $this->getStats();

        return response()->json([
            'result' => 'success',
            'errno' => 0,
            'message' => 'success',
            'data' => [
                'label' => $labels,
                'items'=> $items,
                'parameters' => $parameters,
            ],
        ], Response::HTTP_OK);
    }


    protected function getStats()
    {
        $labels = $this->getLabels();
        $stats = [];

        $keywords = TrendKeyword::where('app', $this->params['app'])->get();

        foreach ($keywords as $keyword) {

            $items = [];

            foreach ($labels as $label) {

                $item = $keyword->stats()
                    ->where([
                        ['type', '=', $this->params['type']],
                        ['date', '=', $label]
                    ])
                    ->first();

                if (empty($item)) {
                    $items[] = [
                        'label' => $label,
                        'pc_count' => 0,
                        'mobile_count' => 0,
                        'count' => 0,
                        'status' => 'none',
                        'amount' => 0.0,
                    ];

                    $count = 0;
                } else {
                    $items[] = [
                        'label' => $label,
                        'pc_count' => $item->pc_count,
                        'mobile_count' => $item->mobile_count,
                        'count' => $item->pc_count + $item->mobile_count,
                        'status' => 'none',
                        'amount' => 0.0,
                    ];

                    $count = $item->pc_count + $item->mobile_count;
                }
            }

            $items = $this->addItemStatus($items);

            $stats[] = [
                'name' => $keyword->keyword,
                'count' => $count,
                'items' => $items,
            ];
        }

        $stats = collect($stats)->sortByDesc('count')->values()->toArray();

        return [$labels, $stats, []];
    }


    protected function addItemStatus($items)
    {
        foreach ($items as $key => $item) {
            if ($key > 0)  {

                if ($items[$key - 1]['count'] == 0) {
                    if ($item['count'] == 0) {
                        $amount = 0.0;
                    } else {
                        $amount = 100.0;
                    }
                } else {
                    $amount = round(($item['count'] - $items[$key - 1]['count']) / $items[$key - 1]['count'] * 100, 2);
                }

                if ($item['count'] > $items[$key - 1]['count']) {
                    $status = 'increased';
                } else if ($item['count'] < $items[$key - 1]['count']) {
                    $status = 'decreased';
                } else {
                    $status = 'none';
                }

                $items[$key]['status'] = $status;
                $items[$key]['amount'] = $amount;
            }
        }

        return $items;
    }


    protected function getLabels()
    {
        $dates = new CarbonPeriod(
            $this->params['startDate'],
            'P1D',
            $this->params['endDate']
        );

        return collect($dates)->map(function ($item) {
            return $item->format('Y-m-d');
        })->toArray();
    }
}
