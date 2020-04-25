<?php


namespace App\Http\Controllers\Api\Artist;

use App\Http\Controllers\Controller as baseController;
use App\Lib\Response;
use App\ArtistApply;
use Illuminate\Http\Request;


class Controller extends baseController
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function apply(Request $request)
    {
        $params = [
            'artist_name' => $request->input('artist_name')
        ];

        if (is_null($params['artist_name'])) {
            return $this->response->set_response(-2001, null);
        }

        $artistApply = new ArtistApply;
        $artistApply->artist_name = $params['artist_name'];
        $artistApply->save();

        return $this->response->set_response(0, '');
    }

}