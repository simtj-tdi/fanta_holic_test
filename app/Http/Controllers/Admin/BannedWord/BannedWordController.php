<?php

namespace App\Http\Controllers\Admin\BannedWord;

use App\BannedWord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannedWordController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        if($user!=null){
            $app = $user->app;
        }else{
            $app= 'pinxy';
        }

        $rows = BannedWord::where('app',$app)->get();

        foreach($rows as $word){
            $words[]= $word->name;
        }

        return view('words.ban')->with([
            'banned_word_menu' => 'active',
            'rows'=> isset($words) ? $words :[]
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

        $count = BannedWord::where('app',$app)
            ->where('name',$params['word'])
            ->count();

        if($count == 0){
            BannedWord::create([
                'app'   =>  $app,
                'name'  =>  $params['word']
            ]);
        }else{
            return redirect()->back()->with([
                'alert' =>  '이미 있는 금칙어입니다',
                'banned_word_menu' => 'active'
            ]);
        }

        return redirect()->back()->with([
            'banned_word_menu' => 'active'
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

        BannedWord::where('app',$app)
            ->where('name',$params['word'])
            ->get()
            ->last()
            ->delete();

        return response()->json(true);
    }
}
