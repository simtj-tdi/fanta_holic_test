<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CustomerRequest
 *
 * @property int $id
 * @property string $app
 * @property string $type
 * @property string $status
 * @property string|null $category
 * @property string $contents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */

//todo 관리자화면 (껍데기)
class CustomerRequest extends Model
{
    protected $fillable = [
        'app',
        'type',
        'status',
        'category',
        'contents',
    ];
}
