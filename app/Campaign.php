<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Campaign
 *
 * @property int $id
 * @property string|null $app 앱 구분자
 * @property string|null $hashtag 게시물 검색시 사용할 해시태그들 "," 로 구분
 * @property string|null $title 광고 제목
 * @property string|null $event_type 광고 타입 [M=멜론 스트리밍 , I = 설치형, F= 친구초대, C =클릭형]
 * @property int $order_num 광고 노출 순서
 * @property string|null $img_url 광고 이미지 url
 * @property int $repeat 반복시간 [1시간 = 60 / 일회성 = 0]
 * @property string|null $description 이벤트 타입 설명
 * @property string|null $url 이벤트 실행할 이동 url
 * @property string|null $app_package 앱 패키지명
 * @property int $item_count 광고 보상 아이템 개수
 * @property string|null $push_title fcm push title
 * @property string|null $psuh_message fcm push content
 * @property int $state 게시여부 [1=게시/ 0=비게시]
 * @property string|null $start_date 광고시작 시간
 * @property string|null $end_date 광고 종료 시간
 * @property string|null $thumbnail_1_1 event_type = C일때 , 1x1 이미지 url
 * @property string|null $thumbnail_2_1 event_type = C일때 , 2x1 이미지 url
 * @property string|null $thumbnail_3_1 event_type = C일때 , 3x1 이미지 url
 * @property string|null $thumbnail_1_2 event_type = C일때 , 1x2 이미지 url
 * @property string|null $thumbnail_2_2 event_type = C일때 , 2x2 이미지 url
 * @property string|null $thumbnail_3_3 event_type = C일때 , 3x3 이미지 url
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $push_tick push->tick
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereAppPackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereHashtag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereImgUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereItemCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereOrderNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign wherePsuhMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign wherePushTick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign wherePushTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereRepeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereThumbnail11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereThumbnail12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereThumbnail21($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereThumbnail22($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereThumbnail31($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereThumbnail33($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign withoutTrashed()
 * @mixin \Eloquent
 */
class Campaign extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'app',
        'hashtag',
        'title',
        'event_type',
        'order_num',
        'img_url',
        'repeat',
        'description',
        'url',
        'app_package',
        'item_count',
        'push_title',
        'push_message',
        'push_tick',
        'state',
        'start_date',
        'end_date',
        'thumnail_1_1',
        'thumnail_2_1',
        'thumnail_3_1',
        'thumnail_1_2',
        'thumnail_2_2',
        'thumnail_3_3',
        'created_at',
        'updated_at'
    ];
}
