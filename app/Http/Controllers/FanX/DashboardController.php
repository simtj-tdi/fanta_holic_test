<?php

namespace App\Http\Controllers\FanX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    private $data;
    private $appData;

    public function __construct()
    {
        $this->data = collect([
            'gender' => [
                [
                    'name' => '남성',
                    'count' => 28.9,
                ],
                [
                    'name' => '여성',
                    'count' => 71.1,
                ],
            ],
            'age' => [
                [
                    'name' => '10대',
                    'count' => 48.9,
                ],
                [
                    'name' => '20대',
                    'count' => 23.29,
                ],
                [
                    'name' => '30대',
                    'count' => 13,
                ],
                [
                    'name' => '40대',
                    'count' => 3.78,
                ],
                [
                    'name' => '50대',
                    'count' => 3.42,
                ],
                [
                    'name' => '60대 이상',
                    'count' => 7.61,
                ],
            ],
            'location' => [
                [
                    'name' => '서울',
                    'count' => 28.5,
                ],
                [
                    'name' => '경기',
                    'count' => 25.4,
                ],
                [
                    'name' => '부산',
                    'count' => 9.1,
                ],
                [
                    'name' => '경남',
                    'count' => 7.1,
                ],
                [
                    'name' => '대전',
                    'count' => 4.1,
                ],
                [
                    'name' => '광주',
                    'count' => 3.9,
                ],
                [
                    'name' => '충북',
                    'count' => 2.3,
                ],
                [
                    'name' => '인천',
                    'count' => 1,
                ],
                [
                    'name' => '기타',
                    'count' => 12.7,
                ]
                // [
                //     'name' => '전남',
                //     'count' => 2.9,
                // ],
                // [
                //     'name' => '울산',
                //     'count' => 2.1,
                // ],
                // [
                //     'name' => '전북',
                //     'count' => 0.9,
                // ],
                // [
                //     'name' => '충남',
                //     'count' => 0.8,
                // ],
                // [
                //     'name' => '제주',
                //     'count' => 0.6,
                // ],
                // [
                //     'name' => '대구',
                //     'count' => 0.4,
                // ],
                // [
                //     'name' => '충북',
                //     'count' => 0.2,
                // ],
                // [
                //     'name' => '세종',
                //     'count' => 0.1,
                // ],
            ],
            'country' => [
                [
                    'name' => '한국',
                    'count' => 31,
                ],
                [
                    'name' => '중국',
                    'count' => 16,
                ],
                [
                    'name' => '태국',
                    'count' => 7.1,
                ],
                [
                    'name' => '일본',
                    'count' => 6.1,
                ],
                [
                    'name' => '필리핀',
                    'count' => 5.9,
                ],
                [
                    'name' => '베트남',
                    'count' => 4.9,
                ],
                [
                    'name' => '대만',
                    'count' => 4.8,
                ],
                [
                    'name' => '미국',
                    'count' => 2.1,
                ],
                [
                    'name' => '캐나다',
                    'count' => 1.9,
                ],
                [
                    'name' => '영국',
                    'count' => 1.3,
                ],
                [
                    'name' => '프랑스',
                    'count' => 0.9,
                ],
                [
                    'name' => '독일',
                    'count' => 0.8,
                ],
                [
                    'name' => '기타',
                    'count' => 8.6,
                ],
            ]
        ]);;
        $this->appData = collect([
            'sns' => [
                [
                    'name' => '밴드',
                    'count' => 21812072,
                ],
                [
                    'name' => '페이스북',
                    'count' => 16280911,
                ],
                [
                    'name' => '인스타그램',
                    'count' => 16216007,
                ],
                [
                    'name' => '카카오스토리',
                    'count' => 15104346,
                ],
                [
                    'name' => '네이버카페',
                    'count' => 8688822,
                ],
                [
                    'name' => '기타',
                    'count' => 11567896,
                ],
            ],
            'game' => [
                [
                    'name' => '아케이드',
                    'count' => 5321456,
                ],
                [
                    'name' => '퍼즐',
                    'count' => 4562195,
                ],
                [
                    'name' => '액션',
                    'count' => 3579521,
                ],
                [
                    'name' => '롤플레잉',
                    'count' => 2765408,
                ],
                [
                    'name' => '보드',
                    'count' => 2156845,
                ],
            ],
            'hobby' => [
                [
                    'name' => '영상스트리밍',
                    'count' => 4568415,
                ],
                [
                    'name' => '운동',
                    'count' => 3472139,
                ],
                [
                    'name' => '게임',
                    'count' => 1416631,
                ],
                [
                    'name' => '여행',
                    'count' => 1413135,
                ],
                [
                    'name' => '웹툰',
                    'count' => 1355211,
                ],
                [
                    'name' => '독서',
                    'count' => 954577,
                ],
                [
                    'name' => '기타',
                    'count' => 1455577,
                ],
            ],
            'shopping' => [
                [
                    'name' => '오픈마켓',
                    'count' => 6563415,
                ],
                [
                    'name' => '소셜커머스',
                    'count' => 5542378,
                ],
                [
                    'name' => '백화점',
                    'count' => 2456781,
                ],
                [
                    'name' => '마트',
                    'count' => 2458006,
                ],
                [
                    'name' => '홈쇼핑',
                    'count' => 1275068,
                ],
                [
                    'name' => '기타',
                    'count' => 1278996,
                ],
            ]
        ]);
    }

    public function index ()
    {
        return view('fanx.dashboard.index')->with('data', $this->data)->with('appData', $this->appData);
    }

    public function sample ($id)
    {
        return view('fanx.dashboard.sample-' . $id)->with('data', $this->data)->with('appData', $this->appData);
    }
}
