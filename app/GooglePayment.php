<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GooglePayment
 *
 * @property int $id
 * @property int $user_id 유저정보
 * @property string $product_id 상품 ID
 * @property int $state 결제상태 0: 결제완료, 1:환불, 2:결제대기
 * @property string $order_id 주문번호
 * @property string $purchase_token 영수증 토큰
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment wherePurchaseToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GooglePayment whereUserId($value)
 * @mixin \Eloquent
 */
class GooglePayment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'state',
        'purchase_token',
        'order_id'
    ];

    protected $dates = ['created_at','updated_at'];
}
