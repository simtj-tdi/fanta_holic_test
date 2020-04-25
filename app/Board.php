<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Board extends Model
{
    use softDeletes;
    protected $fillable = [
        'app',
        'artists_id',
        'user_id',
        'type',
        'post',
        'post_type',
        'thumbnail_url',
        'thumbnail_w',
        'thumbnail_h',
        'title',
        'contents',
        'sns_account',
        'ori_tag',
        'custom_tag',
        'data',
        'ori_thumbnail',
        'ori_data',
        'gender',
        'state',
        'deleted',
        'created_date',
        'updated_date',
        'text_check',
        'face_check',
        'search_type',
        'search',
        'app_review',
        'created_at',
        'updated_at',
        'recorded_at',
        'video_duration',
        'view_count',
        'item_count',
        'validation_at'
    ];

    protected $appends = ['like_count', 'dislike_count'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at', 'recorded_at', 'validation_at'];
//    protected $hidden = ['ori_data', 'ori_thumbnail'];
    protected $casts = [
        'id' => 'int',
        'app' => 'string',
        'type' => 'string',
        'post' => 'string',
        'post_type' => 'string',
        'thumbnail_url' => 'string',
        'thumbnail_w' => 'int',
        'thumbnail_h' => 'int',
        'title' => 'string',
        'contents' => 'string',
        'sns_account' => 'string',
        'ori_tag' => 'array',
        'custom_tag' => 'array',
        'data' => 'object',
        'ori_data'=> 'array',
    ];

    public function getLikeCountAttribute()
    {
        return (int)$this->userresponsetoboard()->where('response', 1)->count();
    }

    public function getDisLikeCountAttribute()
    {
        return (int)$this->userresponsetoboard()->where('response', 0)->count();
    }

    public function userresponsetoboard()
    {
        return $this->hasMany(UserResponseToBoard::class);
    }

    static function getList($params)
    {
        if (empty($params['app'])) {
            throw new Exception('validation fail ');
        }
        $query = self::query();
//        $query = $query->where('app', $params['app']);

        $query = $query->whereBetween($params['schDateType'],
            [
                Carbon::parse($params['startDate'])->startOfDay()->format('Y-m-d H:i:s'),
                Carbon::parse($params['endDate'])->endOfDay()->format('Y-m-d H:i:s')
            ]);
        $query = $query->when(!empty($params['schChannel']), function ($query) use ($params) {
            return $query->where('type', $params['schChannel']);
        });

        $query = $query->when($params['text_check'] != 3, function ($query) use ($params) {
            return $query->where('text_check', $params['text_check']);
        });

//        $query = $query->when($params['schState'] != null, function ($query) use ($params) {
//            return $query->where('state', (int)$params['schState']);
//        });

        $query = $query->when($params['tags'], function ($query) use ($params) {
            if ($params['is_eng_or_num']) {
                //영어 숫자 일경우 3글자부터 중복체크
                if (mb_strlen($params['tags']) > 2) {
                    return $query->where(function ($query) use ($params) {
                        $query->where('ori_tag', 'like', '%' . $params['tags'] . '%')
                            ->orwhere('custom_tag', 'like', '%' . $params['tags'] . '%');
                    });
                } else {
                    return $query->where(function ($query) use ($params) {
                        $query->where('ori_tag', 'like', '%"' . $params['tags'] . '"%')
                            ->orwhere('custom_tag', 'like', '%"' . $params['tags'] . '"%');
                    });
                }
            } else {
                //한글,일본어,한자 2글자부터 중복체크
                if (mb_strlen($params['tags']) > 1) {
                    return $query->where(function ($query) use ($params) {
                        $query->where('ori_tag', 'like', '%' . $params['tags'] . '%')
                            ->orwhere('custom_tag', 'like', '%' . $params['tags'] . '%');
                    });
                } else {
                    return $query->where(function ($query) use ($params) {
                        $query->where('ori_tag', 'like', '%"' . $params['tags'] . '"%')
                            ->orwhere('custom_tag', 'like', '%"' . $params['tags'] . '"%');
                    });
                }
            }
        });

        $query = $query->when($params['schState'] !== null, function ($query) use ($params) {
            return $query->where('state', $params['schState']);
        });
        $query = $query->when(!empty($params['schVal']), function ($query) use ($params) {
            $schColumn = ['title', 'contents'];
            if (!empty($params['schType'])) {
                $query = $query->where($params['schType'], $params['search']);
            } else {
                foreach ($schColumn as $schKey => $column) {
                    if ($schKey == 0) {
                        $query = $query->where($column, 'like', '%' . $params['schVal'] . '%');
                    } else {
                        $query = $query->orWhere($column, 'like', '%' . $params['schVal'] . '%');
                    }
                }
            }
            return $query;
        });
        return $query;
    }


    public function comments()
    {
        return $this->hasMany(Comment::class)->join('users','users.id' , '=','comments.user_id');
    }

}
