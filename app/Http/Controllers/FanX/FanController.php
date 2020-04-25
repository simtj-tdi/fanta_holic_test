<?php

namespace App\Http\Controllers\FanX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FanController extends Controller
{
    public function typeShow(){
        $data = collect([
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
            'home' => [
                [
                    'name' => '서울',
                    'count' => 61,
                ],
                [
                    'name' => '경기',
                    'count' => 16,
                ],
                [
                    'name' => '부산',
                    'count' => 6.1,
                ],
                [
                    'name' => '경남',
                    'count' => 4.1,
                ],
                [
                    'name' => '경북',
                    'count' => 3.9,
                ],
                [
                    'name' => '대전',
                    'count' => 3.1,
                ],
                [
                    'name' => '전남',
                    'count' => 2.9,
                ],
                [
                    'name' => '울산',
                    'count' => 2.1,
                ],
                [
                    'name' => '광주',
                    'count' => 1.9,
                ],
                [
                    'name' => '강원',
                    'count' => 1.3,
                ],
                [
                    'name' => '인천',
                    'count' => 1,
                ],
                [
                    'name' => '전북',
                    'count' => 0.9,
                ],
                [
                    'name' => '충남',
                    'count' => 0.8,
                ],
                [
                    'name' => '제주',
                    'count' => 0.6,
                ],
                [
                    'name' => '대구',
                    'count' => 0.4,
                ],
                [
                    'name' => '충북',
                    'count' => 0.2,
                ],
                [
                    'name' => '세종',
                    'count' => 0.1,
                ],
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
        ]);
        return view('fanx.fan.type')->with('data', $data);
    }

    public function characterShow(){
        $data = collect([
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

        $tableData = collect([
            'sns' => [
                [
                    'id' => 1,
                    'image' => 'https://lh3.googleusercontent.com/92AY78SXqDEtztpNoCsHRoAbajB-6OmGaRYA103OsvD_D6Q1u0HVkT6mNt7egXovaw=s180-rw',
                    'name' => '밴드',
                    'category' => '소셜',
                    'installed' => '21,812,072',
                    'trend' => 'down',
                    'differ' => '-0.44%',
                    'newInstall' => '84,920',
                ],
                [
                    'id' => 2,
                    'image' => 'https://lh3.googleusercontent.com/ccWDU4A7fX1R24v-vvT480ySh26AYp97g1VrIB_FIdjRcuQB2JP2WdY7h_wVVAeSpg=s180-rw',
                    'name' => 'Facebook',
                    'category' => '소셜',
                    'installed' => '16,280,911',
                    'trend' => 'down',
                    'differ' => '-0.04%',
                    'newInstall' => '97,729',
                ],
                [
                    'id' => 3,
                    'image' => 'https://lh3.googleusercontent.com/2sREY-8UpjmaLDCTztldQf6u2RGUtuyf6VT5iyX3z53JS4TdvfQlX-rNChXKgpBYMw=s180-rw',
                    'name' => 'Instagram',
                    'category' => '소셜',
                    'installed' => '16,216,007',
                    'trend' => 'up',
                    'differ' => '+0.07%',
                    'newInstall' => '116,087',
                ],
                [
                    'id' => 4,
                    'image' => 'https://lh3.googleusercontent.com/sTug7DxquD6aKrTTnsejKXYoK9CrvZo2i6HiuuSXwx5-hU5cm15DPU6ew_CRvji0WhTL=s180',
                    'name' => '카카오스토리',
                    'category' => '소셜',
                    'installed' => '15,104,346',
                    'trend' => 'down',
                    'differ' => '-0.42%',
                    'newInstall' => '50,855',
                ],
                [
                    'id' => 5,
                    'image' => 'https://lh3.googleusercontent.com/Zt1Ac3OOTTTaSrro-Ji6ttmVHx3qrzUklYeCC3RxTScjWGEyZjlJkqJ8t58z0zWENJb_=s180-rw',
                    'name' => '네이버 카페',
                    'category' => '소셜',
                    'installed' => '8,688,822',
                    'trend' => 'down',
                    'differ' => '-0.80%',
                    'newInstall' => '34,950',
                ],
            ],
            'hobby' => [
                [
                    'id' => 1,
                    'image' => 'https://lh3.googleusercontent.com/VO-a327nKJ6bzPUIUKH0YYVPnO21AWzHJ9bISKKDeGy17VOUpDcMQU_n55Xj0SpOgsK3=s180-rw',
                    'name' => '기프티콘',
                    'category' => '엔터테인먼트',
                    'installed' => '10,590,468',
                    'trend' => 'up',
                    'differ' => '+0.27%',
                    'newInstall' => '26,768',
                ],
                [
                    'id' => 2,
                    'image' => 'https://lh3.googleusercontent.com/jSV-3vWrK8Qz2E03q5lM-xbLNMGAlSSEawE5OnJJA2CF5Qt092A6QPs2nmAc7-ptPhk=s180-rw',
                    'name' => 'CGV',
                    'category' => '엔터테인먼트',
                    'installed' => '9,957,962',
                    'trend' => 'up',
                    'differ' => '+0.12%',
                    'newInstall' => '111,053',
                ],
                [
                    'id' => 3,
                    'image' => 'https://lh3.googleusercontent.com/IbaA7kEvFSiV7MpFWcHO1hsafsF8VjViLJpfmuAHIua7Eb8Jyth1CWnTiG3UW-kJPNE=s180-rw',
                    'name' => '올레tv 모바일',
                    'category' => '엔터테인먼트',
                    'installed' => '7,535,056',
                    'trend' => 'up',
                    'differ' => '+0.08%',
                    'newInstall' => '13,361',
                ],
                [
                    'id' => 4,
                    'image' => 'https://lh3.googleusercontent.com/zE509iUwfsuWZ3I7rXL3k_-dTsbS6g_Qo53G8csY8QsMN5Px0h3vXukDYlpr5rrg-VaF=s180-rw',
                    'name' => '롯데시네마',
                    'category' => '엔터테인먼트',
                    'installed' => '5,512,952',
                    'trend' => 'down',
                    'differ' => '-0.26%',
                    'newInstall' => '72,953',
                ],
                [
                    'id' => 5,
                    'image' => 'https://lh3.googleusercontent.com/3lEm_PEXBNs1TRL2EOC4vC0pGZt_vhHSGYyf3CfLol71kW_eEP1MrN0AXywBv2nJtrhb=s180-rw',
                    'name' => '메가박스',
                    'category' => '엔터테인먼트',
                    'installed' => '4,229,110',
                    'trend' => 'up',
                    'differ' => '+0.47%',
                    'newInstall' => '73,790',
                ],
            ],
            'shopping' => [
                [
                    'id' => 1,
                    'image' => 'https://lh3.googleusercontent.com/vQDaqflYMGXqN0NkPju5d_LZCdqRiqWw29S97A9quVzrqy2kBp2qnkeThnRCWBBKpVo=s180',
                    'name' => '쿠팡',
                    'category' => '쇼핑',
                    'installed' => '17,889,263',
                    'trend' => 'up',
                    'differ' => '+0.03%',
                    'newInstall' => '161,790',
                ],
                [
                    'id' => 2,
                    'image' => 'https://lh3.googleusercontent.com/zakJrJt6QEQtDarduSCLskVggGantPX7d8EwRBuWDLhes2oxIJ3lsCGT3-ApRYQnhtM=s180-rw',
                    'name' => '11번가',
                    'category' => '쇼핑',
                    'installed' => '15,329,181',
                    'trend' => 'down',
                    'differ' => '-0.17%',
                    'newInstall' => '68,383',
                ],
                [
                    'id' => 3,
                    'image' => 'https://lh3.googleusercontent.com/O3vCOLN-qDOhkzJX0GngcJopvdH38dminAUdkw-_5UhI4y1lPQ3mDoQHVC78Y8_YhA=s180-rw',
                    'name' => '티몬',
                    'category' => '쇼핑',
                    'installed' => '13,251,471',
                    'trend' => 'down',
                    'differ' => '-0.01%',
                    'newInstall' => '108,726',
                ],
                [
                    'id' => 4,
                    'image' => 'https://lh3.googleusercontent.com/Mfst2T2OPuSakZUu-rVs2gY3RzfySFWjZiX_XEldL_4cNvbXA6_tl4GaIq3Z3-AHkdE=s180',
                    'name' => '위메프',
                    'category' => '쇼핑',
                    'installed' => '11,683,945',
                    'trend' => 'down',
                    'differ' => '-0.42%',
                    'newInstall' => '117,806',
                ],
                [
                    'id' => 5,
                    'image' => 'https://lh3.googleusercontent.com/VY8sEwjQzJP2Wgf2n9yT7tn0JPJnQJiTHRupxheaLnnwGceMv_gQOG2gP8XPbpK0LA=s180-rw',
                    'name' => 'G마켓',
                    'category' => '쇼핑',
                    'installed' => '10,557,801',
                    'trend' => 'down',
                    'differ' => '-0.05%',
                    'newInstall' => '50,843',
                ],
            ],
            'game' => [
                [
                    'id' => 1,
                    'image' => 'https://lh3.googleusercontent.com/l1J0x4Kg12WEsHw_R3AgyIJayRdQ7UVJXO4aQUj3eoeeWAdWSnHN8gdJ4wOTrfVvQCaO=s180-rw',
                    'name' => '브롤스타즈',
                    'category' => '액션',
                    'installed' => '3,497,484',
                    'trend' => 'up',
                    'differ' => '+0.19%',
                    'newInstall' => '187,646',
                ],
                [
                    'id' => 2,
                    'image' => 'https://lh3.googleusercontent.com/t_GaUTn-7syFOI230AwwJ-UEcrqDbniw51CEnS663NhnPdW7lzDd8ydc4ia3bpKcxec=s180-rw',
                    'name' => '배틀그라운드',
                    'category' => '액션',
                    'installed' => '2,632,229',
                    'trend' => 'up',
                    'differ' => '+0.45%',
                    'newInstall' => '102,016',
                ],
                [
                    'id' => 3,
                    'image' => 'https://lh3.googleusercontent.com/pKzfaKdd50d-DSQPtdXf8lO-YBIW99sLPqt6Z3y1RHRlrsYXEBiaRzbeM6z3tgwDgH4=s180-rw',
                    'name' => '프렌즈팝',
                    'category' => '퍼즐',
                    'installed' => '2,557,901',
                    'trend' => 'up',
                    'differ' => '+0.16%',
                    'newInstall' => '6,509',
                ],
                [
                    'id' => 4,
                    'image' => 'https://lh3.googleusercontent.com/_POQ14G2nQQjtBfq8hKMZkxczbuURtqw9Xnvvoayxrg1VuMZKPMNbczLuhFCRRIDvRza=s180',
                    'name' => '한게임 신맞고',
                    'category' => '카드',
                    'installed' => '1,977,506',
                    'trend' => 'up',
                    'differ' => '+1.53%',
                    'newInstall' => '16,241',
                ],
                [
                    'id' => 5,
                    'image' => 'https://lh3.googleusercontent.com/yiGs7u7kCVyRQ18fRdkObMIZ1zIVrekZ5ZqqGCM5VEFaJxy7Je2xp_MDYz8pjdSIuA=s180',
                    'name' => '궁수의 전설',
                    'category' => '액션',
                    'installed' => '1,407,934',
                    'trend' => 'down',
                    'differ' => '-0.74%',
                    'newInstall' => '83,499',
                ],
            ]
        ]);

        return view('fanx.fan.character')->with('data', $data)->with('tableData', $tableData);
    }
}
