<?php

namespace App\Http\Controllers\FanX;

use App\CustomerRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    protected $params;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }


    public function showRequests(Request $request)
    {
        $this->params = [
            'status' => $request->input('status'),
            'contents' => $request->input('contents'),
            'sort_key' => $request->input('sort_key', 'created_at'),
            'sort_value' => $request->input('sort_value', 'desc'),
        ];

        $summary = $this->getSummary('request');
        $requests = $this->getRequests('request');

//        dd($groups);

        return view('fanx.customer.request')->with([
            'params' => $this->params,
            'summary' => $summary,
            'requests' => $requests,
        ]);
    }


    public function showErrors(Request $request)
    {
        $this->params = [
            'status' => $request->input('status'),
            'contents' => $request->input('contents'),
            'sort_key' => $request->input('sort_key', 'created_at'),
            'sort_value' => $request->input('sort_value', 'desc'),
        ];

        $summary = $this->getSummary('error');
        $requests = $this->getRequests('error');

//        dd($groups);

        return view('fanx.customer.error')->with([
            'params' => $this->params,
            'summary' => $summary,
            'requests' => $requests,
        ]);
    }


    protected function getSummary($type)
    {
        $summary = [
            'pending' => 0,
            'completed' => 0,
        ];

        try {

            $summary['pending'] = CustomerRequest::where('type', $type)->where('status', 'pending')->count();
            $summary['completed'] = CustomerRequest::where('type', $type)->where('status', 'completed')->count();

        } catch (QueryException $e) {

            Log::error($e->getMessage());
        }

        return $summary;
    }


    protected function getRequests($type)
    {
        try {

            $targets = CustomerRequest::orderBy('id', 'desc')
                ->where('type', $type)
                ->when(empty($this->params['status']) === false, function ($query) {
                    return $query->where('status', $this->params['status']);
                })
                ->when(empty($this->params['contents']) === false, function ($query) {
                    return $query->where('contents', 'like', "%{$this->params['contents']}%");
                })
                ->paginate(10);

        } catch (QueryException $e) {

            Log::error($e->getMessage());

            $targets = null;
        }

        return $targets;
    }
}
