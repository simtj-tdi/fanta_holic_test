<?php

namespace App\Http\Controllers\Admin\Standard;

use App\Standard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function index(Request $request){

        $user = $request->user();
        if($user!=null){
            $app = $user->app;
        }else{
            $app= 'pinxy';
        }

        $standard = Standard::where('app',$app)->get()->last();
        $ranking_standard = json_decode($standard->ranking);

        return view('standard.index')->with([
            'standard_menu'=>'active',
            'standard' => $standard,
            'ranking_standard'=> $ranking_standard
        ]);
    }

    public function update(Request $request,$id){

        $user = $request->user();
        if($user!=null){
            $app = $user->app;
        }else{
            $app= 'pinxy';
        }

        $validator =$this->validate($request,[
            'spamming'      =>  'required|integer',
            'spam_count'      =>  'required|integer',
            'blind_count'        =>  'required|integer',
            'black_count'      =>  'required|integer',
            'comment_like_score'      =>  'required|integer',
            'article_like_score'      =>  'required|integer',
            'comment_score'      =>  'required|integer',
            'login_reward'      =>  'required|integer',
            'Chairman'      =>  'required|integer',
            'vice_Chairman'      =>  'required|integer|min:'.$request->input('Chairman').'|max:'.$request->input('Honor_supporters'),
            'Honor_supporters'      =>  'required|integer|min:'.$request->input('vice_Chairman').'|max:'.$request->input('supporters'),
            'supporters'      =>  'required|integer|min:'.$request->input('Honor_supporters').'|max:'.$request->input('fanclup'),
            'fanclup'      =>  'required|integer|min:'.$request->input('supporters').'|max:'.$request->input('fan'),
            'fan'      =>  'required|integer|min:'.$request->input('fanclup'),
        ],[
            'vice_Chairman.min' =>  '회장 기준보다 커야합니다.',
            'vice_Chairman.max' =>  '명예 서포터즈 기준보다 작아야 합니다.',
            'Honor_supporters.min' =>  '부회장 기준보다 커야합니다',
            'Honor_supporters.max' =>  '서포터즈 기준보다 작아야 합니다.',
            'supporters.min' =>  '명예 서포터즈 기준보다 커야합니다',
            'supporters.max' =>  '팬클럽 기준보다 작아야 합니다.',
            'fanclup.min' =>  '서포터즈 기준보다 커야합니다',
            'fanclup.max' =>  '팬 기준보다 작아야 합니다.',
            'fan.min' =>  '팬클럽 기준보다 커야합니다',
        ]);

        $params = [
            'app'   =>  $app,
            'spamming'      =>  $request->input('spamming'),
            'spam_count'      =>  $request->input('spam_count'),
            'blind_count'    =>  $request->input('blind_count'),
            'black_count'      =>  $request->input('black_count'),
            'comment_like_score'      =>  $request->input('comment_like_score'),
            'article_like_score'      =>  $request->input('article_like_score'),
            'comment_score'      =>  $request->input('comment_score'),
            'login_reward'      =>  $request->input('login_reward'),
            'Chairman'      =>  $request->input('Chairman'),
            'vice_Chairman'      =>  $request->input('vice_Chairman'),
            'Honor_supporters'      =>  $request->input('Honor_supporters'),
            'supporters'      =>  $request->input('supporters'),
            'fanclup'      =>  $request->input('fanclup'),
            'fan'      =>  $request->input('fan'),
        ];

        Standard::find($id)->update([
            'spamming'      =>  $params['spamming'],
            'spam_count'    =>  $params['spam_count'],
            'blind_count'   =>  $params['blind_count'],
            'black_count'   =>  $params['black_count'],
            'comment_like_score'    =>  $params['comment_like_score'],
            'article_like_score'    =>  $params['article_like_score'],
            'comment_score'         =>  $params['comment_score'],
            'login_reward'          =>  $params['login_reward'],
            'ranking'               =>json_encode([
                'Chairman'          => $params['Chairman'],
                'vice_Chairman'     => $params['vice_Chairman'],
                'Honor_supporters'  => $params['Honor_supporters'],
                'supporters'        => $params['supporters'],
                'fanclup'           => $params['fanclup'],
                'fan'               => $params['fan']
            ])
        ]);

        $standard= Standard::find($id);

        return redirect()->back();
    }
}
