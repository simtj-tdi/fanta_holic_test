<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Album
 *
 * @property int $id
 * @property string|null $app 앱이름
 * @property int|null $order_num 리스트 표시시 순서
 * @property string|null $thumbnail_url 썸네일 이미지 url
 * @property string $title 앨범 제목
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $genre 앨범 장르
 * @property string $released_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Artist[] $artists
 * @property-read int|null $artists_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Music[] $musics
 * @property-read int|null $musics_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Album onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereOrderNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereReleasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Album withoutTrashed()
 * @mixin \Eloquent
 */
class Album extends Model
{
    use SoftDeletes;
    protected $fillable =[
          'app',
          'order_num',
          'thumbnail_url',
          'title',
          'genre',
          'released_at',
          'created_at',
          'updated_at',
          'deleted_at'
    ];
    protected $dates = ['created_at', 'updated_at'];


    public function artists(){
        return $this->belongsToMany(Artist::class,'artist_album');
    }

    public function musics(){
        return $this->hasMany(Music::class);
    }
}
