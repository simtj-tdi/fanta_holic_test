<?php


namespace App\Lib\Channel;

use App\Board;
use App\Enums\ChannelType;
use App\Lib\Util;
use Illuminate\Support\Facades\Log;
use InstagramScraper\Model\Media;
use \App\Lib\Channel\Factory\ChannelAbstractClass;

class Instagram extends ChannelAbstractClass
{
    private $channelKey;
    private $channelType = ChannelType::CHANNEL_INSTAGRAM;
    private $channelImagePath = 'images/instagram/thumbnail/';
    private $channelViedeoPath = 'videos/instagram/';

    public function __construct($channelKey, $artistsId)
    {
        $this->channelKey = $channelKey;
        $this->artistsId = $artistsId;

    }

    public function getChannelContents()
    {
        $instagram = new \InstagramScraper\Instagram();
        $instagramPageObj = $instagram->getPaginateMedias($this->channelKey);
        $instagramPageObj['hasNextPage'] = true;

        $cnt =0;

        while ($instagramPageObj['hasNextPage'] === true) {
            foreach ($instagramPageObj['medias'] as $key => $media) {
                Log::info(__METHOD__.' - media - '.json_encode($media));
                $cnt++;
                $duplicationCheck = $this->isValidation( $media);
                if (!empty($duplicationCheck) || $cnt > 30) {
                    $instagramPageObj['hasNextPage'] = false;
                    break 2;
                }


                $media = $instagram->getMediaById($media->getId());
                $detailMedias = $media->getSidecarMedias();

                $board = $this->setDataFormatting($media);
                $board['artists_id'] = $this->artistsId;
                parent::saveData($board);
            }
            $instagramPageObj = $instagram->getPaginateMedias($this->channelKey, $instagramPageObj['maxId']);
        }
        Log::info(__METHOD__ . " - Success Process Cnt : " . parent::$successCnt);
        return true;
    }

    public function isValidation( $channelModal): bool
    {
        $chk = Board::where('post', '=', $this->parsingPost($channelModal))->count();
        return $chk;
    }


    public function parsingPost( $channelModa): string
    {
        return '/p/' . $channelModa->getShortCode() . '/';
    }


    public function setDataFormatting( $channelModal): Board
    {
        $util = new Util();

        $board = new Board();
        $board->app = env('APP_NAME');
        $board->type = $this->channelType;
        $board->post = $this->parsingPost($channelModal);
        $board->post_type = $channelModal->getType() ;
        $board->title = '';
        $board->contents = $channelModal->getCaption();
        $board->sns_account = $this->channelKey;
        $board->gender = 1;
        $board->state = 1;
        $board->recorded_at = date('Y-m-d H:i:s', $channelModal->getCreatedTime());
        $board->created_at = date('Y-m-d H:i:s');


        $data = [];
        $oriData = [];

        $file = file_get_contents($channelModal->getImageLowResolutionUrl());
        $thumbnail = $util->AzureUploadImage($channelModal->getImageLowResolutionUrl(), $this->channelImagePath);
        $board->thumbnail_url = "/" . $this->channelImagePath . $thumbnail['fileName'];
        $board->thumbnail_w = (int)$thumbnail['width'];
        $board->thumbnail_h = (int)$thumbnail['height'];
        $board->ori_thumbnail = $channelModal->getImageLowResolutionUrl();

        //media 값이 비어서 올때가 있다
        if($board->post_type == 'image'){
            $data[0]['image'] = $board->thumbnail_url;
        }else{
            $data[0]['video']['poster'] = $board->thumbnail_url;
        }

        $detailMedias = $channelModal->getSidecarMedias();

        foreach ($detailMedias as $detailMediaKey => $detailMedia) {
            $oriData[] = $detailMedia->getImageLowResolutionUrl();
            if ($detailMedia->getType() === Media::TYPE_IMAGE) {
                $thumbnail = $util->AzureUploadImage($detailMedia->getImageLowResolutionUrl(), $this->channelImagePath);
                $data[$detailMediaKey]['image'] = "/" . $this->channelImagePath . $thumbnail['fileName'];
            } else {
                if ($detailMedia->getType() === Media::TYPE_VIDEO) {

                    if ($detailMediaKey == 0) {
                        unset($data[0]['image']);
                    }

                    $thumbnail = $util->AzureUploadImage($detailMedia->getImageLowResolutionUrl(),
                        $this->channelViedeoPath);
                    $data[$detailMediaKey]['video']['poster'] = "/" . $this->channelImagePath . $thumbnail['fileName'];

                    //$thumbnail = $util->AzureUploadImage($detailMedia->getVideoStandardResolutionUrl(), $this->channelViedeoPath);
                    //$data[$detailMediaKey]['video']['src'] = "/" .$this->channelViedeoPath. $thumbnail['fileName'];
                }
            }
        }

        $board->data = $data;
        $board->ori_data = $oriData;


        return $board;
    }


    public function getChannelContentsAll()
    {
        $instagram = new \InstagramScraper\Instagram();
        $instagramPageObj = $instagram->getPaginateMedias($this->channelKey);
        $cnt =0;

        while ($instagramPageObj['hasNextPage'] === true) {
            foreach ($instagramPageObj['medias'] as $key => $media) {
//            2147750277683272319

                $duplicationCheck = $this->isValidation($media);
                if (!empty($duplicationCheck) || $cnt > 30) {
                    $instagramPageObj['hasNextPage'] = false;
                    break;
                }

                $cnt++;

                $media = $instagram->getMediaById($media->getId());
                $detailMedias = $media->getSidecarMedias();

                $board = $this->setDataFormatting($media);
                $board['artists_id'] = $this->artistsId;
                parent::saveData($board);
            }
            $instagramPageObj = $instagram->getPaginateMedias($this->channelKey, $instagramPageObj['maxId']);
        }
        Log::info(__METHOD__ . " - Success Process Cnt : " . parent::$successCnt);
        return true;
    }


}
