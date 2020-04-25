<?php


namespace App\Lib\Channel;



use App\Board;

use App\Enums\ChannelType;

use App\Lib\Channel\Factory\ChannelAbstractClass;
use App\Lib\Util;

use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

use \Thujohn\Twitter\Facades\Twitter as TwitterApi;


class Twitter extends ChannelAbstractClass
{
  private $screen_name;
  private $channelType = ChannelType::CHANNEL_TWITTER;
  private $channelImagePath = 'images/twitter/thumbnail';
  private $channelViedeoPath = 'videos/twitter/';

  public function __construct($screen_name,$artistsId){
    $this->screen_name = $screen_name;
    $this->artistsId = $artistsId;
  }
  public function getChannelContents(){
    $params = [
            'screen_name' => $this->screen_name,
            'count' => 20,
            'include_entities' => true,
//            'max_id' => null,
            'format' => 'object'
          ];

    $limitMaxCnt = 100;
    $currentCnt = 1;

    while ($currentCnt < $limitMaxCnt) {

      $tweetList = TwitterApi::getUserTimeline($params);

      if (count($tweetList) <= 1) {
        $check = false;
      }

      $lastTweet = last($tweetList);
      $params['max_id'] = $lastTweet->id;

      foreach ($tweetList as $tweet) {

        if ($this->isValidation($tweet)) {
            break 2;
        }
        $board = $this->setDataFormatting($tweet);
        $board['artists_id'] = $this->artistsId;

        parent::saveData($board);
      }
      $currentCnt++;
    }

    Log::info(__METHOD__ . " - Success Process Cnt : " . parent::$successCnt);
    return true;
  }

    private function parsingPost(\stdClass $channelModel): string
    {
      return $channelModel->id;
    }

    public function setDataFormatting( $channelMode)
    {
      $board = new Board();
      $util = new Util();

      Log::debug(__METHOD__ . ' - content - ' . json_encode($channelMode));
      $board->app = env('APP_NAME');
      $board->type = $this->channelType;
      $board->post = $channelMode->id;
      $board->title = '';
      $board->contents = $channelMode->text;
      $board->sns_account = $this->screen_name;

//        $board->ori_tag = $channelMode->entities->hashtags;
      $board->ori_tag = [];
      $board->gender = 1;
      $board->state = 1;
      $board->created_at = date('Y-m-d H:i:s');
      $board->recorded_at = Carbon::parse($channelMode->created_at)->format('Y-m-d H:i:s');

      if (!empty($channelMode->extended_entities->media)) {
        $medias = $channelMode->extended_entities->media;
        $data = [];
        $ori_data = [];
        foreach ($medias as $mediaKey => $media) {
          $response = $util->AzureUploadImage($media->media_url, $this->channelImagePath, 640, $media->id . '_');
          $imagePath = $response['path'];


          if ($mediaKey === 0) {
            $board->thumbnail_url = $response['path'];
            $board->thumbnail_w = $response['width'];
            $board->thumbnail_h = $response['height'];
            $board->ori_thumbnail = $media->media_url;
          }
          if ($media->type === "video") {
            $videos = $media->video_info->variants;

            foreach ($videos as $key => $video) {
              if ($video->content_type != "video/mp4") {
                continue;
              }
              $remoteUrl = $video->url;

              $mp4File = $util->AzureUploadImage($remoteUrl, $this->channelViedeoPath);
              $data[$mediaKey]['video']['src'] = "/" . $this->channelViedeoPath.$mp4File['fileName'];
              $data[$mediaKey]['video']['poster'] = $imagePath;
              break;
            }
          } else {
            $data[$mediaKey]['image'] = $imagePath;
          }
        }

        $board->data = $data;
        $board['artists_id'] = $this->artistsId;
        $board->ori_data = $board->thumbnail_url;
      }

        return $board;
    }

    public function saveData(\App\Board $board)
    {
        return $board->save();
    }

    protected function isValidation( $channelModel): bool
    {
       //이미지, 비디오가 있으면 건너 뛴다
       if (empty($channelModel->extended_entities->media)) {
            return true;
        }

      //중복된 검색이어도 건너 뛴다
      return Board::where('post', '=', $this->parsingPost($channelModel))->count();
    }

    public function getChannelContentsAll()
    {
      $check = true;
      $params = [
        'screen_name' => $this->screen_name,
        'count' => 51,
        'include_entities' => true,
        //            'max_id' => null,
        'format' => 'object'
      ];
      $limitMaxCnt = 10;
      $currentCnt = 1;
      $cnt =0;
      while ($check) {

        $tweetList = TwitterApi::getUserTimeline($params);
        if (count($tweetList) <= 1) {
          $check = false;
        }

        $lastTweet = last($tweetList);
        $params['max_id'] = $lastTweet->id;

        foreach ($tweetList as $tweet) {
          $cnt++;
          if ($this->isValidation($tweet) || $cnt > 50) {
            continue;
          }
          $board = $this->setDataFormatting($tweet);

          $board['artists_id'] = $this->artistsId;
          parent::saveData($board);
        }
            $currentCnt++;
      }
      Log::info(__METHOD__ . " - Success Process Cnt : " . parent::$successCnt);
      return true;
    }
  }
