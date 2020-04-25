<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Music
 *
 * @property int $id
 * @property int $album_id 앨범 id
 * @property string|null $app 앱이름
 * @property string|null $title 노래제목
 * @property string $thumbnail_url 썸네일 이미지 url
 * @property int $repeat 보상 반복 시간 텀(분), 일회성 =0
 * @property int $reward_count 멜론 스트리밍 듣기 후 보상개수
 * @property string|null $mv_url 유투브 뮤직비디오 url
 * @property string|null $melon_url 멜론 url
 * @property string|null $push_title 보상지급 푸시 알람시 제목
 * @property string|null $push_content 보상지급 푸시 알람시 내용
 * @property string|null $push_tick 보상지급 푸시 알람시 틱
 * @property int $state 게시 1 비게시 0
 * @property string|null $start_date 기간정해서 게시할시 시작일
 * @property string|null $end_date 기간정해서 게시할시 종료일
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $lyrics 가사
 * @property int $play_count 스트리밍 횟수
 * @property int $dj_state db 목록에 1:게시 / 0:비게시
 * @property-read \App\Album $album
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Artist[] $artists
 * @property-read int|null $artists_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Music onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereDjState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereLyrics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereMelonUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereMvUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music wherePlayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music wherePushContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music wherePushTick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music wherePushTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereRepeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereRewardCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Music whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Music withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Music withoutTrashed()
 * @mixin \Eloquent
 */
class Music extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'app',
        'play_count',
        'order_num',
        'title',
        'lyrics',
        'thumbnail_url',
        'repeat',
        'app_package',
        'reward_count',
        'album_id',
        'melon_url',
        'mv_url',
        'push_title',
        'push_content',
        'push_tick',
        'state',
        'start_date',
        'end_date',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function artists()
    {
        return $this->belongsToMany(Artist::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
