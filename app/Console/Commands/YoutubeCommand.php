<?php

namespace App\Console\Commands;

use App\CollectBatch;
use App\YoutubeDeveloperKey;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Lib\Util;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class YoutubeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawling:youtube';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Youtube Crawling';

    /**
     * youtube api url
     */
    protected $search_url = "https://www.googleapis.com/youtube/v3/search?";
    protected $video_url = "https://www.googleapis.com/youtube/v3/videos?";

    /**
     * youtube api developer key
     */

    protected $developer_key;
    /**
     * youtube api parameter maxResult
     */
    protected $max_results = 50;

    /**
     * img path
     */
//    protected $path = 'pinxy/images/youtube/thumbnail/';
    protected $path;

    protected $util;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->util = new Util();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apps = array_keys(config('celeb'));

        foreach($apps as $app){
            $channel = config('celeb')[$app]['youtube_channel'];
            $this->channel_v2($channel,$app);
        }
    }

    protected function channel_v2(string $search,$app)
    {
        $next = true;
        while ($next) {
            $youtube_key = YoutubeDeveloperKey::where('state',1)->orderBy('count','asc')->get()->first();
            $developer_key = $youtube_key->key;

            $youtube_key->update([
                'count' => $youtube_key->count+50
            ]);

            $option = array(
                'part' => 'snippet',
                'channelId' => $search,
                'type' => 'video',
                'maxResults' => $this->max_results,
                'order' => 'date',
                'key' => $developer_key
            );
            if (isset($nextPageToken)) {
                $option['pageToken'] = $nextPageToken;
            }

            $call_api = $this->search_url.http_build_query($option, 'a', '&');

            $response = Curl::to($call_api)->get();
            $response = json_decode($response, true);

            if(isset($response['error'])){
                $youtube_key ->update([
                    'state' => 0
                ]);
                $next = false;
            }

            if (isset($response['nextPageToken'])) {
                $nextPageToken = $response['nextPageToken'];
            } else {
                $next = false;
            }

            if (isset($response['items']) && count($response['items']) > 0) {

                foreach ($response['items'] as $item) {

                    $isPast = Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($item['snippet']['publishedAt']), 'Asia/Seoul')->toDateTimeString());

                    if ($isPast < 2) {
                        // get hashtag
                        $option = array(
//                            'part' => 'id,snippet,contentDetails,statistics',
                            'part' => 'id,snippet,contentDetails',
                            'id' => $item['id']['videoId'],
                            'key' => $developer_key
                        );
                        $detail_api = $this->video_url.http_build_query($option, 'a', '&');
                        $detail_res = Curl::to($detail_api)->get();
                        $detail_res = json_decode($detail_res, true);
//                        $view_count = $detail_res['items'][0]['statistics']['viewCount'];
                        $youtube_video_duration = $detail_res['items'][0]['contentDetails']['duration'];
//                        $video_duration = new \DateTime('1970-01-01');
                        $video_duration = new \DateTime('@0');
                        $video_duration->add(new \DateInterval($youtube_video_duration));
                        $video_duration->format('H:i:s');

                        $hashtags = [];

                        if (isset($detail_res['items'][0]['snippet']['tags'])) {
                            $hashtags = $detail_res['items'][0]['snippet']['tags'];
                            foreach ($hashtags as $hashtag) {
                                $matches = explode('#', $hashtag);

                                foreach ($matches as $match) {
                                    if ($match != "" && $match != null) {
                                        $tags[] = [
                                            'name' => $match,
                                            'board' => 'youtube',
                                            'type' => 'ori'
                                        ];
                                    }
                                }
                            }
                        }
                        
                        //유투브 썸네일 저장
//                        $thumbnail = $this->util->DownloadImageToAzure($this->path, $item['id']['videoId'].'crop' . '.jpg', $item['snippet']['thumbnails']['high']['url']);
                        //유투브 썸네일 위아래 검은 공간 자르고 저장
                        $thumbnail = $this->util->SaveCroppedThumbnaliAzuree($this->path, $item['id']['videoId'].'crop' . '.jpg', $item['snippet']['thumbnails']['high']['url']);
                        $data = new \stdClass();
                        $data->image = '/' . $this->path . $thumbnail;
                        $inserts[] = [
                            'app' => $app,
                            'type' => 'youtube',
                            'post' => $item['id']['videoId'],
                            'post_type' => $item['snippet']['channelId'],
                            'thumbnail_url' => '/' . $this->path . $thumbnail,
                            'thumbnail_w' => $item['snippet']['thumbnails']['high']['width'],
//                            'thumbnail_h' => $item['snippet']['thumbnails']['high']['height'],
                            'thumbnail_h' => 270, //썸네일 자르고 저장시 270픽셀 고정
                            'title' => $item['snippet']['title'],
                            'contents' => $item['snippet']['description'],
                            'sns_account' => $item['snippet']['channelTitle'],
                            'ori_tag' => json_encode($hashtags),
                            'ori_thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                            'data'  => json_encode([$data]),
                            'gender' => 2,
                            'state' => 0,
                            'created_at' => Carbon::createFromTimestamp(strtotime($item['snippet']['publishedAt']), 'Asia/Seoul')->toDateTimeString(),
                            'updated_at' => Carbon::now(),
                            'recorded_at'   =>  Carbon::now(),
                            'video_duration'    =>  $video_duration,
//                            'view_count'    =>  $view_count,
                            'search_type' => 'channel',
                            'search' => str_replace("+", " ", $search)
                        ];
                    } else {
                        $next = false;
                    }
                }
                if (!empty($inserts)) {
                    $this->insertIgnore("boards", $inserts);
                }

                if (!empty($tags)) {
                    $this->insertIgnore("tags", $tags);
                }
            }
        }
    }

    //키워드 검색 api
    protected function keyword(string $search)
    {
        $youtube_key = YoutubeDeveloperKey::where('state',1)->orderBy('count','asc')->get()->first();
        $developer_key = $youtube_key->key;
        $this->youtube_key->update([
            'count' => $youtube_key->count+50
        ]);
        $option = array(
            'part' => 'snippet',
            'q' => $search,
            'type' => 'video',
            'maxResults' => $this->max_results,
            'order' => 'date',
            'key' => $developer_key
        );
        $call_api = $this->search_url.http_build_query($option, 'a', '&');

        $response = Curl::to($call_api)->get();
        $response = json_decode($response, true);
        if(isset($response['error'])){
            $this->youtube_key ->update([
                'state' => 0
            ]);
            CollectBatch::where('search',$search)->update([
                'state'=>3
            ]);
        }
        $inserts = [];
        $tags = [];
        if (isset($response['items'])) {
            foreach ($response['items'] as $item) {
                $isPast = Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($item['snippet']['publishedAt']), 'Asia/Seoul')->toDateTimeString());

                if ($isPast < 3) {
                    // get hashtag
                    $option = array(
                        'part' => 'id,snippet',
                        'id' => $item['id']['videoId'],
                        'key' => $developer_key
                    );
                    $tag_api = $this->video_url.http_build_query($option, 'a', '&');
                    $tag_res = Curl::to($tag_api)->get();
                    $tag_res = json_decode($tag_res, true);
                    $hashtags = [];

                    if (isset($tag_res['items'][0]['snippet']['tags'])) {
                        $hashtags = $tag_res['items'][0]['snippet']['tags'];
                        foreach ($hashtags as $hashtag) {
                            $matches = explode('#', $hashtag);

                            foreach ($matches as $match) {
                                if ($match != "" && $match != null) {
                                    $tags[] = [
                                        'name' => $match,
                                        'board' => 'youtube',
                                        'type' => 'ori'
                                    ];
                                }
                            }
                        }
                    }

                    $thumbnail = $this->util->DownloadImageToAzure($this->path, $item['id']['videoId'] . '.jpg', $item['snippet']['thumbnails']['high']['url']);
//                    $thumbnail = $this->util->SaveCroppedThumbnaliAzuree($this->path, $item['id']['videoId'] . '.jpg', $item['snippet']['thumbnails']['high']['url']);

                    if ($thumbnail) {
                        $inserts[] = [
                            'app' => 'pinxy',
                            'type' => 'youtube',
                            'post' => $item['id']['videoId'],
                            'post_type' => $item['snippet']['channelId'],
                            'thumbnail_url' => '/' . $this->path . $thumbnail,
                            'thumbnail_w' => $item['snippet']['thumbnails']['high']['width'],
                            'thumbnail_h' => $item['snippet']['thumbnails']['high']['height'],
                            'title' => $item['snippet']['title'],
                            'contents' => $item['snippet']['description'],
                            'sns_account' => $item['snippet']['channelTitle'],
                            'ori_tag' => json_encode($hashtags),
                            'ori_thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                            'gender' => 2,
                            'state' => 0,
                            'created_at' => $item['snippet']['publishedAt'],
                            'search_type' => 'keyword',
                            'search' => str_replace("+", " ", $search),
                            'recorded_at'   =>  Carbon::now()
                        ];
                    }
                }
            }

            if (!empty($inserts)) {
                $this->insertIgnore("boards", $inserts);
            }

            if (!empty($tags)) {
                $this->insertIgnore("tags", $tags);
            }
        }
    }

    protected function insertIgnore(string $table, $datas)
    {
        $questionMarks = '';
        $values = [];
        foreach ($datas as $k => $array) {
            if ($k > 0) {
                $questionMarks .= ',';
            }
            $questionMarks .= '(?' . str_repeat(',?', count($array) - 1) . ')';
            $values = array_merge($values, array_values($array));
        }

        $query = 'INSERT IGNORE INTO ' . $table . ' (' . implode(',', array_keys($array)) . ') VALUES ' . $questionMarks;
        return DB::insert($query, $values);
    }
}
