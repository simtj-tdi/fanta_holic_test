<?php

namespace App\Lib\Channel;

use App\Board;
use App\Lib\Util;
use GuzzleHttp\Client;
use \App\Lib\Channel\Factory\ChannelAbstractClass;
use Illuminate\Support\Facades\Log;

class VLive extends ChannelAbstractClass
{
    private $appId;
    private $account;
    private $channelType = 'vlive';
    private $channelImagePath = 'images/vlive/thumbnail/';
    private $channelViedeoPath = 'videos/vlive/';

    private $vLiveContentsUrl = 'https://api-vfan.vlive.tv/v2/channel.%s/home';
    private $vLiveDetailContentUrl = 'https://www.vlive.tv/video/%s';
    private $vLiveDetailContentJsonUrl = 'http://global.apis.naver.com/rmcnmv/rmcnmv/vod_play_videoInfo.json?key=%s&pid=&sid=2024&ver=2.0&devt=html5_pc&doct=json&ptc=http&cpt=vtt&cpl=zh_CN&lc=zh_CN&videoId=%s&cc=CN';

    public function __construct($appId, $account, $channelId ,$artistsId)
    {
        $this->appId = $appId;
        $this->account = $account;
        $this->artistsId = $artistsId;
        $this->channelId = $channelId;
    }

    public function getChannelContents()
    {
        $client = new Client();
        $params = [
            'gcc' => 'KR',
            'locale' => 'ko',
            'app_id' => $this->appId,
        ];
        $chk = true;
        $cnt =0;
        while ($chk) {

            $contentsUrl = sprintf($this->vLiveContentsUrl, $this->channelId) . '?' . http_build_query($params);
            $response = $client->get($contentsUrl);
            if ($response->getStatusCode() !== 200) {
                Log::error(__METHOD__ . ' - get vlive response code is not 200 - ' . json_encode($response));
                break;
            }
            $listResponseBodyContents = json_decode($response->getBody()->getContents());
            $contentList = $listResponseBodyContents->contentList;
            $params['next'] = $listResponseBodyContents->page->next;
            $chk = ((int)$params['next'] > 0) ? true : false;

            Log::debug(__METHOD__ . ' - key  - ' . $params['next'] . ' - chk - ' . json_encode($chk));

            foreach ($contentList as $content) {
                parent::$maxCnt++;
                $dupleCheck = $this->isValidation($content);
                $cnt++;
                if ($dupleCheck || $cnt > 50) {
                    Log::warning(__METHOD__ . ' - duple data  - ' . json_encode($content));
                    break 2;
                }
                $board = $this->setDataFormatting($content);
                if (empty($board)) {
                    Log::info(__METHOD__ . ' - board - ' . json_encode($board));
                    continue;
                }
                $board['artists_id'] = $this->artistsId;
                parent::saveData($board);
            }
        }

        Log::info(__METHOD__ . " - Success Process Cnt : " . parent::$successCnt . ', maxCnt :' . parent::$maxCnt);
        return true;
    }


    private function parsingPost($channelModa): string
    {
        $type = '';
        if(!empty($channelModa->type) && strtolower($channelModa->type) == 'video') {
            $type = "video/" . $channelModa->videoSeq;
        } else if(!empty($channelModa->image_list)){
            $type = $channelModa->post_id;
        }
        return $type;

//        return (!empty($channelModa->type) && strtolower($channelModa->type) == 'video') ? "video/" . $channelModa->videoSeq : print_r($channelModa->image_list[1]);
//        return (!empty($channelModa->type) && strtolower($channelModa->type) == 'video') ? "video/" . $channelModa->videoSeq : $channelModa->post_id;
    }

    public function setDataFormatting( $channelMode)
        {
        $client = new Client();
        $board = new Board();

        if (empty($channelMode->type)) {
            if (empty($channelMode->image_list[0])) {
                $channelMode->thumbnail = null;
            } else {
                $channelMode->thumbnail = $channelMode->image_list[0]->thumb;
            }

        }
        Log::debug(__METHOD__ . ' - params -' . json_encode($channelMode));

        $board->app = env('APP_NAME');
        $board->type = $this->channelType;
        $board->post = $this->parsingPost($channelMode);

        if(!empty($channelMode->type) && strtolower($channelMode->type) == 'video') {
            $board->post_type  = 'video';
        } else if(!empty($channelMode->image_list) && strtolower($channelMode->image_list[0]->type) == 'photo'){
            $board->post_type  = 'image';
        }
        $board->title = $channelMode->title;
        $board->contents = (!empty($channelMode->type) && strtolower($channelMode->type) == 'video') ? $channelMode->title : $channelMode->content;
        $board->sns_account = $this->account;
        $board->ori_tag = [];
        $board->gender = 1;
        $board->state = 1;


        if ($channelMode->thumbnail !== null) {
            $util = new Util();
            $response = $util->AzureUploadImage($channelMode->thumbnail, $this->channelImagePath);
            $board->thumbnail_url = '/' . $this->channelImagePath . $response['fileName'];
            $board->thumbnail_w = $response['width'];
            $board->thumbnail_h = $response['height'];
            $board->ori_thumbnail = $channelMode->thumbnail;

            $data = [];

            /**
             * 1. video 인데 유료 비디오일경우 리스트에서만 제공되는 파일을 가져가면 된다
             * 2. video 인데 무료 인 경우에는 상세 페이지 들어가서 얻는 키를 얻어서 다른 API를 호출해서 가져가야 한다
             * 3. 이미지, 텍스트만 있는 게시물일수도 있다.
             */

            if (!empty ($channelMode->type) && strtolower($channelMode->type) == 'video') {
                $board->recorded_at = $channelMode->createdAt;
                if ($channelMode->productType === "PAID") {
                    if(isset($channelMode->videoPlaylist->videoList)){
                      $videosList = $channelMode->videoPlaylist->videoList;
                      echo "\n";
                      echo 'https://www.vlive.tv/video/init/view?videoSeq=' . $videosList[0]->videoSeq . "\n";
                      echo "https://www.vlive.tv/video/" . $channelMode->videoSeq . "?channelCode=FE619";

                      $detailResponse = $client->get('https://www.vlive.tv/video/init/view?videoSeq=' . $videosList[0]->videoSeq,
                          ['curl' => [CURLOPT_REFERER => 'https://www.vlive.tv/video/' . $channelMode->videoSeq . '?channelCode=FE619']]);
                      $responseBodyContents = $detailResponse->getBody()->getContents();
  //                    preg_match("#(?s)oVideoStatus\s*=\s*({.+?})\s*<\/script#", $responseBodyContents, $matches);
                      preg_match('#vid"\s+:\s+(\S+)",\s+"inkey"\s+:\s+"(\S+)",#', $responseBodyContents, $matches);
                      if (isset($matches[1])) {
  //                        list(, $videoid, $key) = explode(',', $matches[1]);
                          $videoid = trim(str_replace('"', ''
                              , $matches[1]));
                          $key = trim(str_replace('"', '', $matches[2]));

                          $video_info_url = sprintf("https://apis.naver.com/rmcnmv/rmcnmv/vod/play/v2.0/%s?key=%s",$videoid,$key );
                      }
                    }else{
                      $video_info_url = 'https://apis.naver.com/rmcnmv/rmcnmv/vod/play/v2.0/%s?key=%s';
                    }

                    $board->state = 0;// 유료인 경우 데이터는 저장, 게시는 안되게 저장.
                } else {
                    $detailResponse = $client->get(sprintf($this->vLiveDetailContentUrl, $channelMode->videoSeq));

                    $responseBodyContents = $detailResponse->getBody()->getContents();
                    preg_match('#vlive.video.init\(([\S\s]*?)\);#', $responseBodyContents, $matches);
                    if (isset($matches[1])) {
                        list(, , , , , $videoid, $key,) = explode(',', $matches[1]);
                        $videoid = trim(str_replace('"', '', $videoid));
                        $key = trim(str_replace('"', '', $key));

                        $video_info_url = sprintf($this->vLiveDetailContentJsonUrl, $key, $videoid);

                        if (empty($videoid)) {
                            Log::error(__METHOD__ . ' - videoId is empty -' . $matches[1]);
                        }
                    }
                }
                $response = $client->get($video_info_url);
                $vliveModel = json_decode($response->getBody()->getContents());

                if (!empty($vliveModel->videos) && count($vliveModel->videos->list) > 1) {
                    foreach ($vliveModel->videos->list as $video) {
                        //화질이 1920 미만은 패스
                        /*if ($video->encodingOption->width < 1920) {
                            continue;
                        }*/
                        $videoSource = $video->source;
                        //$response = $util->AzureUploadImage($videoSource, $this->channelViedeoPath);
                        //$data[0]['video']['src'] = "/" . $this->channelViedeoPath . '/' . $response['fileName'];
                        $data[0]['video']['poster'] = $board->thumbnail_url;
                        break;
                    }
                }

            } else {
                // 이미지
                $data[0]['image'] = $board->thumbnail_url;
                $board->recorded_at = date('Y-m-d H:i:s', substr($channelMode->created_at, 0, 10));
            }

            $board->data = $data;
            $board->ori_data = [];
        }

        return $board;
    }

//    public function saveData(\App\Board $board){
//        $board->save();
//        $this->successCnt++;
//    }

    public function isValidation($channelModel): bool
    {
        return Board::where('post', '=', $this->parsingPost($channelModel))->where('type', '=', 'vlive')->count();
    }

    public function getChannelContentsAll()
    {

        $client = new Client();
        $params = [
            'gcc' => 'KR',
            'locale' => 'ko',
            'app_id' => $this->appId,
        ];
        $chk = true;
        $cnt = 0;
        while ($chk) {

            $contentsUrl = $this->vLiveContentsUrl . '?' . http_build_query($params);
            $response = $client->get($contentsUrl);

            if ($response->getStatusCode() !== 200) {
                Log::error(__METHOD__ . ' - get vlive response code is not 200 - ' . json_encode($response));
                throw new \Exception('response code not 200');
            }
            $listResponseBodyContents = json_decode($response->getBody()->getContents());
            $contentList = $listResponseBodyContents->contentList;
            $params['next'] = $listResponseBodyContents->page->next;
            $chk = ((int)$params['next'] > 0) ? true : false;

            Log::debug(__METHOD__ . ' - key  - ' . $params['next'] . ' - chk -' . json_encode($chk));

            foreach ($contentList as $content) {
                $cnt++;
                $dupleCheck = $this->isValidation($content);
                if ($dupleCheck || $cnt > 50 ) {
                    continue;
                }

                $board = $this->setDataFormatting($content);
                $board['artists_id'] = $this->artistsId;
                parent::saveData($board);
            }
        }
        Log::info(__METHOD__ . " - Success Process Cnt : " . parent::$successCnt);
        return true;
    }

}
