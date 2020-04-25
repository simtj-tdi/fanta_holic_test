<?php

namespace App\Http\Controllers\Api\Follow;

use App\Board;
use App\Push;
use App\Lib\LobbyClassv6;
use App\Follow;
use App\Lib\Response;
use App\Lib\Util;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Lib\Log;
use App\Http\Controllers\Controller as baseController;


class Controller extends baseController
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    //팔로우
    public function index(Request $request){

        $user = Auth('api')->user();

        /*if(!$request->input('artist_id')){
            return $this->response->set_response(-2001,null);
        }*/
        if(!$request->input('artist_type')){
            return $this->response->set_response(-2001,null);
        }

        $artist_id_arr = explode(",",$request->input('artist_id'));

        $artist_type = $request->input('artist_type');

        if($artist_type == "all" ){
          $artist_type = "group-W,group-M,solo,group-MIX,trot-M,trot-W";
        }

        $artist_type_arr = explode(",",$artist_type);

        Follow::join("artists","artists.id","=","follows.artist_id")
        ->where('follows.user_id', '=', $user->id)
        ->whereIn('artists.team_type', $artist_type_arr )
        ->delete();// 팔로우 초기화

        foreach($artist_id_arr as $artist_id){
          $params = [
              'artist_id' =>  $artist_id,
              'user_id'  =>  $user->id
          ];

          $result = Follow::create($params); // 팔로우 등록
        }

        $data = [
            'follow'   => count($artist_id_arr)
        ];

        return $this->response->set_response(0,$data);

    }

    //언팔로우
    public function unfollow(Request $request){

        $user = Auth('api')->user();

        $params = [
            'artist_id' =>  $request->input('artist_id'),
            'user_id'  =>  $user->id
        ];

        if(!$params['artist_id']){
            return $this->response->set_response(-2001,null);
        }

        $result = Follow::where('artist_id', '=', $params['artist_id'])->where('user_id', '=', $params['user_id'])->delete();
        $data = array();
        if($result > 0){
          return $this->response->set_response(0,null);
        }else{
          return $this->response->set_response(-2001,null);
        }
    }

    //팔로우 아티스트 리스트
    public function get_list_follow_artist(Request $request)
    {
        $user = Auth('api')->user();

        $team_type = $request->input('type');

        $artist_type = $request->input('artist_type');

        if($artist_type == "all" ){
          $artist_type = "group-W,group-M,solo,group-MIX,trot-M,trot-W";
        }

        $artist_type_arr = explode(",",$artist_type);

        $results = Follow::join("artists","artists.id","=","follows.artist_id")
        ->where('follows.user_id', '=', $user->id)
        ->whereIn('artists.team_type', $artist_type_arr )
        ->select('artists.id')
        ->get();// 팔로우 초기화
        $response['body'] = $results;
        $response['count'] = count($results);
        return $this->response->set_response(0, $response);
    }



}
