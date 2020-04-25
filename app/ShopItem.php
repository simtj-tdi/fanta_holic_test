<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ShopItem
 *
 * @property int $id
 * @property string $app 앱이름
 * @property string $url 상품 url
 * @property string $thumbnail_url 썸네일 url
 * @property int $thumbnail_w 썸네일 가로길이
 * @property int $thumbnail_h 썸네일 세로길이
 * @property string $title 상품명
 * @property int $price 가격
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereThumbnailH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereThumbnailW($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShopItem whereUrl($value)
 * @mixin \Eloquent
 */

// todo 관리자화면
class ShopItem extends Model
{
    protected  $fillable = [
        'app',
        'url',
        'thumbnail_url',
        'thumbnail_w',
        'thumbnail_h',
        'title',
        'price'
    ];
}
