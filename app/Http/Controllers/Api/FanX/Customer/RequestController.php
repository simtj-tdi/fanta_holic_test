<?php

namespace App\Http\Controllers\Api\FanX\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\CustomerRequest;

class RequestController extends Controller
{
    public function __construct()
    {
//        parent::__construct();
    }


    public function store(Request $request)
    {
        $result = CustomerRequest::create([
            'app' => $request->input('app'),
            'type' => $request->input('type'),
            'category' => $request->input('category'),
            'contents' => $request->input('contents'),
        ]);

        return response()->json([
            'result' => 'success',
            'errno' => 0,
            'message' => 'success',
            'data' => new \stdClass(),
        ], Response::HTTP_OK);
    }


    public function delete(Request $request, $id)
    {
        $item = CustomerRequest::find($id);

        if (empty($item) === false) {
            $item->delete();
        }

        return response()->json([
            'result' => 'success',
            'errno' => 0,
            'message' => 'success',
            'data' => new \stdClass(),
        ], Response::HTTP_OK);
    }
}

