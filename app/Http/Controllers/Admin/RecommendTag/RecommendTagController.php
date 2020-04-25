<?php

namespace App\Http\Controllers\Admin\RecommendTag;

use App\RecommendTag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class RecommendTagController extends BaseController
{
    public function index(Request $request){
        $user = $request->user();
        if($user!=null){
            $app = $user->app;
        }else{
            $app= 'pinxy';
        }

        $rows = RecommendTag::where('app',$app)->get();

        foreach($rows as $word){
            $words[]= $word->name;
        }
        if(!isset($words)){
            $words=[];
        }

        return view('words.recommend')->with([
            'recommend_tag_menu' => 'active',
            'rows'=>$words
        ]);
    }

    public function store(Request $request){
        $user = $request->user();
        if($user!=null){
            $app = $user->app;
        }else{
            $app= 'pinxy';
        }

        $params = [
            'word'  =>  $request->input('word')
        ];

        $count = RecommendTag::where('app',$app)
            ->where('name',$params['word'])
            ->count();
        if($count == 0){
            RecommendTag::create([
                'app'   =>  $app,
                'name'  =>  $params['word']
            ]);
        }else{
            return redirect()->back()->with([
                'alert' =>  '이미 있는 금칙어입니다',
                'recommend_tag_menu' => 'active'
            ]);
        }

        return redirect()->back()->with([
            'recommend_tag_menu' => 'active'
        ]);
    }
    public function delete(Request $request){
        $user = $request->user();
        if($user!=null){
            $app = $user->app;
        }else{
            $app= 'pinxy';
        }

        $params = [
            'word'  =>  $request->input('word')
        ];
        RecommendTag::where('app',$app)
            ->where('name',$params['word'])->get()->last()->delete();
        return response()->json(true);
    }
}
