<?php

namespace App;

use App\Http\Traits\PushTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Push
 *
 * @property int $id
 * @property string|null $app fcm 보낼 앱
 * @property string|null $batch_type 발송 타입 [A=전체 발송/ P = 개인 발송]
 * @property string|null $managed_type 관리용 타입 [M = 관리자 등록 / N = 새게시물 등록 / C = 대댓글 알림]
 * @property int|null $new_post_count batch_type = N 일 때,등록된 게시물 개수
 * @property int|null $user_id 개인발송 일 때 발송할 유저 아이디
 * @property string|null $title fcm title
 * @property string|null $content fcm content
 * @property string|null $tick fcm tick
 * @property string|null $push_type 푸시 종류 [T=text/ I=image]
 * @property string|null $img_url push_type = image 일 때 ,배너 이미지 url
 * @property string|null $action 행동 종류 [M=이동,A=앱실행,B=특정 게시물로 이동]
 * @property string|null $url action= M 일때, 이동할 url
 * @property string|null $board_type action=B 일 때 ,게시물 타입(ads,notice,new,instagram,youtube ...]
 * @property int|null $board_id action = B 일 때 ,게시물 id
 * @property string|null $state push 진행 상태 [R=대기,S=발송중,Y=발송완료/X=발송취소]
 * @property int $success
 * @property int $fail
 * @property string $start_date 발송 시작 시간
 * @property string $streaming_url action = S일 때 url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $campaign_id 광고 관련 push 일경우 광고 id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereBatchType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereBoardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereImgUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereManagedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereNewPostCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push wherePushType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereStreamingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereTick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Push whereUserId($value)
 * @mixin \Eloquent
 */
class Push extends Model
{
    use PushTrait;
    protected $fillable=[
        'app',
        'campaign_id',
        'batch_type',
        'managed_type',
        'new_post_count',
        'user_id',
        'title',
        'content',
        'tick',
        'push_type',
        'img_url',
        'action',
        'url',
        'board_type',
        'board_id',
        'state',
        'success',
        'fail',
        'start_date',
        'end_date',
        'streaming_url',
        'created_at',
        'updated_at'
    ];

    public function send_all_user($app){
        $push = Push::select([
            'id',
            'app',
            'batch_type',
            'managed_type',
            'new_post_count',
            'title',
            'content',
            'tick',
            'push_type',
            'img_url',
            'action',
            'url',
            'board_type',
            'board_id',
            'streaming_url',
            'state'
        ])
            //앱
            ->where('app',$app)
            //발송 타입
            // 발송 타입
            ->where('batch_type', 'A')
            // 진행 상태
            ->where('state', 'R')
            // 시작일
            ->where('start_date', '<', Carbon::now()->toDateTimeString())
            ->orderBy('id')
            ->limit(1)
            ->first();

        if($push != null &&$push->action == 'B'){
            $board = Board::where('id',$push->board_id)->get()->last();
            $board->created_at_timestamp = (string)(Carbon::createFromTimeString($board['created_at'])->timestamp);
        }else{
            $board = '';
        }

        // 대기 중 목록이 있을 경우
        if ( ! is_null($push)) {
            // 발송 개 수
            $limit = 1000;

            $query = Device::where('app',$app)
                ->where('is_push',1)
                ->where('fcm_token','!=',null)
                ->groupBy('fcm_token');
            $count = $query->count();

            $loop = (int) ceil($count / $limit);

            $push->update([
                'state'=>'S'
            ]);

            $success = $fail = 0;
            for ($i = 0; $i < $loop; $i++) {
                $offset = $i * $limit;
                $items = $query->select(['user_id', 'store_type', 'fcm_token'])
                    ->skip($offset)
                    ->take($limit)
                    ->get()
                    ->keyBy('user_id')
                    ->transform(function ($item) use ($push,$board) {
                        $data = [
                            'registration_ids' => [$item->fcm_token],
                            'data' => [
                                'id' => $push->id,
                                'user_id'   =>  $item->user_id,
                                'batch_type' => $push->batch_type,
                                'title' => $push->title,
                                'message' => $push->contents,
                                'tick' => $push->tick,
                                'push_type' => $push->push_type,
                                'push_type_sub' => [
                                    'img_url' => ($push->push_type == 'I') ? $push->img_url : ''
                                ],
                                'action' => $push->action,
                                'action_sub' => [
                                    'url' => ($push->action == 'M') ? $push->url : '',
                                    'type' => ($push->action == 'B') ? $push->board_type : '',
                                    'board_id' => ($push->action == 'B') ? $push->board_id : '',
                                    'cdn_url'   => ($push->action =='B') ? app('config')['celeb'][$push->app]['cdn'] : '',
                                    'board'   => ($push->action =='B') ? json_encode($board) : '',
                                    'campaign' =>  ($push->action== 'S')   ?   json_decode($push->streaming_url) : ''
                                ]
                            ]
                        ];


                        if ($item->store_type == 'ios') {
                            $data = array_merge($data, [
                                'notification' => [
                                    'title' => $push->title,
                                    'body' => $push->contents,
                                    'sound' => 'default',
                                    'badge' => 1
                                ]
                            ]);
                        }
                        return $data;
                    });
                // 푸시 발송
                $results = collect($this->sender($app, $items));
                $success += $results->sum('success');
                $fail += $results->sum('failure');

                sleep(1);
            }

            // 상태 변경 (완료)
            Push::where('id', $push->id)
                ->update([
                    'state' => 'Y',
                    'success' => $success,
                    'fail' => $fail
                ]);
        }else{
        }
    }

    public function individualPush($user_id,$app){

    }
}
