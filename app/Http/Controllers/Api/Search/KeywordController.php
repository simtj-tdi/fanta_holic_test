<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class KeywordController extends Controller
{
    protected $response;

    public function __construct()
    {
        $this->response = new \App\Lib\Response();
    }

    public function index()
    {
        $keywords = Keyword::orderBy('created_at', 'desc')->get()->toArray();
        $keywords = ['keywords' => $keywords];

        return Response::json($this->response->set_response(0, $keywords));
    }

    public function show(Request $request, $keyword)
    {
        $keywords = Keyword::orderBy('created_at', 'desc')->whereLike('name', $keyword)->get()->toArray();
        $keywords = ['keywords' => $keywords];

        return Response::json($this->response->set_response(0, $keywords));
    }
}
