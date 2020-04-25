<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static USE_ITEM()
 * @method static static BUY_ITEM()
 * @method static static REGISTER_BOARD()
 * @method static static STREAMING_MUSIC()
 */
final class UserScoreLogType extends Enum
{
    const USE_ITEM = 'I';        //아이템을 사용했을때 ( 하트 )
    const BUY_ITEM = 'A';        //인앱샵 이용시
    const REGISTER_BOARD = 'B';  //게시물을 등록했을때
    const STREAMING_MUSIC = 'S'; //음악을 들었을때
}
