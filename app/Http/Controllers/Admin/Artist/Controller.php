<?php


namespace App\Http\Controllers\Admin\Artist;

use App\ArtistApply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function applyList(Request $request)
    {
        $params['list'] = ArtistApply::orderBy('id','desc')->Paginate(10);;

        return view('artist.applyList', $params);
    }

    public function applyDelete($id)
    {
        $artistApply = ArtistApply::find($id);
        $ret = $artistApply->delete();
        
        return redirect( route('artist.Apply'));
    }
}