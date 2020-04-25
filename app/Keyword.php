<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Keyword
 *
 * @property int $id
 * @property string $name 키워드 네임
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Keyword onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Keyword whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Keyword withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Keyword withoutTrashed()
 * @mixin \Eloquent
 */
class Keyword extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at','created_at','updated_at'];
}
