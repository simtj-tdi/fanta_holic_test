<?php


namespace App\Lib\Channel\Factory;


use App\Enums\ChannelType;
use App\Lib\Channel\Instagram;
use App\Lib\Channel\News;
use App\Lib\Channel\Twitter;
use App\Lib\Channel\VLive;
use App\Lib\Channel\Youtube;

class ChannelFactory extends ChannelAbstractClass
{
    protected  $channel;


    public function __construct(\App\Crawler $crawler)
    {
        switch ($crawler->type) {
            case ChannelType::CHANNEL_YOUTUBE :
                $this->channel = new Youtube($crawler->auth->apiKey, $crawler->auth->channelId, $crawler->artists_id);
                break;
            case ChannelType::CHANNEL_VLIVE :
                $this-> channel = new VLive($crawler->auth->appId, $crawler->auth->account, $crawler->auth->channelId, $crawler->artists_id);
                break;
            case ChannelType::CHANNEL_TWITTER:
                $this->channel = new Twitter($crawler->auth->screen_name, $crawler->artists_id);
                break;
            case ChannelType::CHANNEL_INSTAGRAM:
                $this->channel = new Instagram($crawler->auth->channelKey, $crawler->artists_id);
                break;
            case ChannelType::CHANNEL_NEWS:
                $this->channel = new NEWS($crawler->artists_id);
        }
        return $this->channel;
    }
//
    public function getChannelContents()
    {
        return $this->channel->getChannelContents();
    }

    protected function isValidation( $channelModel)
    {
        // TODO: Implement isValidation() method.
    }

    protected function setDataFormatting( $class)
    {
        // TODO: Implement setDataFormatting() method.
    }

    /**
     * @description 전체 데이터 가져오기
     * @return mixed
     */
    public function getChannelContentsAll()
    {
        return $this->channel->getChannelContentsAll();
    }
}
