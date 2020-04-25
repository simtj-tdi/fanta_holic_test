<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Artist
 *
 * @property int $id
 * @property string|null $app 앱이름
 * @property string|null $name 가수이름
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Album[] $albums
 * @property-read int|null $albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Music[] $musics
 * @property-read int|null $musics_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Artist onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artist withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Artist withoutTrashed()
 * @mixin \Eloquent
 */
class Artist extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'app',
        'name',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function musics() {
        return $this->belongsToMany(Music::class,'artist_music');
    }

    public function albums(){
        return $this->belongsToMany(Album::class,'artist_album');
    }
}
