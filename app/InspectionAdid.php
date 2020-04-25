<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\InspectionAdid
 *
 * @property int $id
 * @property string|null $ad_id 검수 ad_id
 * @property string|null $comment ad_id 설명
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid whereAdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InspectionAdid whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InspectionAdid extends Model
{
    protected $fillable = [
        'ad_id',
        'comment',
        'created_at',
        'updated_at'
    ];
}
