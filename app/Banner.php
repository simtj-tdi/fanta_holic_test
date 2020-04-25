<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Banner
 *
 * @property int $id
 * @property string|null $app 앱이름
 * @property string|null $board music:음원페이지용, notice:공지페이지용
 * @property string|null $img_url 이미지 url
 * @property int|null $order_num 게시 순서
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereBoard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereImgUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereOrderNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $img_w
 * @property int $img_h
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereImgH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Banner whereImgW($value)
 */
class Banner extends Model
{
    protected $fillable = [
        'app',
        'board',
        'img_url',
        'order_num'
    ];
}
