<?php

namespace App\Http\Controllers\Admin\Search;

use App\Http\Controllers\Controller;
//use App\RecommendKeyword;
use App\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['list'] = Keyword::orderBy('id','desc')->get();
        return view('Search.RecommendKeyword.index',$params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if( 10 < Keyword::get()->count() )
        {
            return Response::make('<script>alert("추천 검색어는 10개만 등록 가능합니다.");history.back();</script>');
        }

        $keyword = new Keyword;
        $keyword->name = $request->name;
        $keyword->save();
        return redirect( route('keyword.index'));
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo $id;
        exit;
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        echo $id;
        exit;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $keyword = Keyword::find($id);

        $keyword->name = $request->name;
        $keyword->save();
        return redirect( route('keyword.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $keword = Keyword::find($id);
        $ret = $keword->delete();
        return redirect( route('keyword.index'));
    }
}
