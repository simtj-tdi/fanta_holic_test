<?php

namespace App\Lib\Channel;

use Alaouy\Youtube\Facades\Youtube as YoutubeApi;
use App\Board;
use App\Lib\Channel\Factory\ChannelAbstractClass;
use App\Lib\Channel\Factory\stdClass;
use App\Lib\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Youtube extends ChannelAbstractClass
{
    private $channelId;
    private $apiKey;
    private $artistsId;
    private $channelType = 'youtube';
    private $channelImagePath = 'images/youtube/thumbnail';
    private $channelViedeoPath = 'videos/youtube/';


    public function __construct($apiKey, $channelId, $artistsId)
    {
        $this->channelId = $channelId;
        $this->apiKey = $apiKey;
        //$this->apiKey = "AIzaSyA9j0v3wdPm_NjL-VEfNX7MFT84AO_90zw";
        //$this->apiKey = "AIzaSyBDk59oCrpsaqtbrVZE7fFd9hqeVNuuHhk";
        $this->artistsId = $artistsId;
    }

    public function getChannelContents()
    {
        YoutubeApi::setApiKey($this->apiKey);

        $currentCnt = 0;
        $perCnt = 10;
        $totalCnt = 20;

        $cnt =0;

        $params = [
            'type' => 'video',
            'channelId' => $this->channelId,
            'part' => implode(', ', ['id', 'snippet']),
            'maxResults' => $perCnt,
            'pageToken' => null,
            'order' => 'date'
        ];

        while ($currentCnt < $totalCnt) {
            $currentCnt += $perCnt;
            Log::debug(__METHOD__ . ' - params -' . json_encode($params));
            $channel = (object)YoutubeApi::searchAdvanced($params, true);
            Log::debug(__METHOD__ . ' - channel -' . json_encode($channel));
            if (!empty($channel->info['nextPageToken'])) {
                $params['pageToken'] = $channel->info['nextPageToken'];
                $totalCnt = $channel->info['totalResults'];
            }

            foreach ($channel->results as $key => $content) {
                $dupleChk = $this->isValidation($content);
                $cnt++;
                if ($dupleChk || $cnt > 20) {
                    break 2;
                }
                $videos = YoutubeApi::getVideoInfo($this->parsingPost($content), ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status']);
                $board = $this->setDataFormatting($videos);
                $board['artists_id'] = $this->artistsId;
                parent::saveData($board);
            }
        }

        Log::info(__METHOD__ . " - Success Process Cnt : ".parent::$successCnt);
    }

    private function parsingPost( $channelModa): string
    {
        return $channelModa->id->videoId;
    }


    protected function setDataFormatting( $channelMode)
    {
        $board = new Board();
        $util = new Util();

        Log::debug(__METHOD__ . ' - content - ' . json_encode($channelMode));
        $board->app = env('APP_NAME');
        $board->type = $this->channelType;
        $board->post = $channelMode->id;
        $board->title = $channelMode->snippet->title;
        $board->contents = $channelMode->snippet->description;
        $board->sns_account = $channelMode->snippet->channelId;
        $board->ori_tag = [];
        $board->gender = 1;
        $board->state = 1;
        $board->created_at = date('Y-m-d H:i:s');
        $board->recorded_at = Carbon::parse($channelMode->snippet->publishedAt)->format('Y-m-d H:i:s');

        if(!empty($channelMode->contentDetails->duration))
        {
            $youtube_video_duration = $channelMode->contentDetails->duration;
            $video_duration = new \DateTime('@0');
            $video_duration->add(new \DateInterval($youtube_video_duration));
            $board->video_duration = $video_duration->format('H:i:s');
        }

        $thumbnail = $channelMode->snippet->thumbnails->high;
        if ($thumbnail !== null) {

            $response = $util->AzureUploadImageCropped($thumbnail->url, $this->channelImagePath,
                $channelMode->id . '_');
            $board->thumbnail_url = '/' . $this->channelImagePath . '/' . $response['fileName'];
            $board->thumbnail_w = $response['width'];
            $board->thumbnail_h = $response['height'];
            $board->ori_thumbnail = $thumbnail->url;

            $board->data = array(['image' => $board->thumbnail_url]);
            $board->ori_data = array($board->thumbnail_url);
        }

        return $board;
    }



    protected function isValidation( $channelModel)
    {
        return Board::where('post', '=', $this->parsingPost($channelModel))->count();
        // TODO: Implement isValidation() method.
    }

    public function getChannelContentsAll()
    {
        YoutubeApi::setApiKey($this->apiKey);

        $currentCnt = 0;
        $perCnt = 10;
        $totalCnt = 20;

        $cnt = 0;

        $params = [
            'type' => 'video',
            'channelId' => $this->channelId,
            'part' => implode(', ', ['id', 'snippet']),
            'maxResults' => $perCnt,
            'pageToken' => null,
        ];

        while ($currentCnt < $totalCnt) {
            $currentCnt += $perCnt;
            Log::debug(__METHOD__ . ' - params -' . json_encode($params));
            $channel = (object)YoutubeApi::searchAdvanced($params, true);
            Log::debug(__METHOD__ . ' - channel -' . json_encode($channel));
            if (!empty($channel->info['nextPageToken'])) {
                $params['pageToken'] = $channel->info['nextPageToken'];
                $totalCnt = $channel->info['totalResults'];
            }

            foreach ($channel->results as $key => $content) {
                $dupleChk = $this->isValidation($content);
                $cnt++;
                if ($dupleChk || $cnt > 20) {
                    continue;
                }
                $videos = YoutubeApi::getVideoInfo($this->parsingPost($content), ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status']);
                $board = $this->setDataFormatting($videos);
                $board['artists_id'] = $this->artistsId;
                parent::saveData($board);
            }
        }

        Log::info(__METHOD__ . " - Success Process Cnt : ".parent::$successCnt);
    }

}
