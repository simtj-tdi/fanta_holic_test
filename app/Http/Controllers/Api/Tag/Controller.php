<?php

namespace App\Http\Controllers\Api\Tag;

use App\RecommendTag;
use App\Tag;
use Illuminate\Http\Request;
use App\Lib\Response;
use App\Http\Controllers\Controller as BaseController;
use Elasticsearch\ClientBuilder;

class Controller extends BaseController
{
    protected $response;
    protected $client;
    public function __construct()
    {
        $this->response = new Response();
        $this->client = ClientBuilder::create()
            ->setHosts(['http://elastic:aktlakfh!@34@52.231.165.45:9200'])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();

    }

    public function index(Request $request){
        $next =$request->input('next',1);
//        $tags = Tag::orderBy('id','desc')->forPage($next,10)->get();
        $tags = RecommendTag::all()->forPage($next,10);

        if(count($tags)){
            foreach($tags as $tag){
                $list[] = $tag->name;
            }
            $list = array_unique($list);
            $list = array_values($list);
            $list = array_map(function($tag){
                return '#'.$tag;
            },$list);

            if(count($tags) < 10){
                $next = -1;
            }else{
                $next +=1;
            }
            return $this->response->set_response(0,[
                'next'=>$next,
                'tags'=>$list
            ]);
        }else{
            return $this->response->set_response(-2001, null);
        }
    }

    public function search(Request $request,$tag){
        $tags = Tag::where('name', 'LIKE','%'.$tag.'%')->get();
        if(count($tags)){
            foreach($tags as $tag){
                $list[] = $tag->name;
            }
            $list = array_unique($list);
            $list = array_values($list);
            $list = array_map(function($tag){
                return '#'.$tag;
            },$list);
            return $this->response->set_response(0,['tags'=>$list]);
        }else{
            return $this->response->set_response(-2001, null);
        }
    }

    public function popular_tags(Request $request){
        $popular_tags = collect($this->client->search([
            'from' => 0,
            'index' => 'user_likes',
            'type' => 'docs',
            "size"=>0,
            'body' => [
                'aggs' => ['tags' => ['terms'=>['field'=>'tag.keyword']]]
            ],
        ])
        ['aggregations']['tags']['buckets']
        )->toArray();

        $top10_tags = array_slice($popular_tags,0,10);
        $top10_tags = array_map(function($tag) {
            return '#'.$tag['key'];
        },$top10_tags);
        return $this->response->set_response(0,['tags'=>$top10_tags]);
    }
}
