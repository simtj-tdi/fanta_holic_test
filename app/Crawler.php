<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Crawler
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property object $auth
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $finaled_at
 * @property string $term
 * @property string $state
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereFinaledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Crawler whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Crawler extends Model
{
    protected $dates = ['created_at', 'updated_at','finaled_at'];
    protected $casts = [
        'id' => 'int',
        'type' => 'string',
        'name' => 'string',
        'auth' => 'object',
    ];
}
