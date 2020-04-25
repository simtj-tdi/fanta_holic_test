<?php

namespace App\Lib\Channel\Factory;

use App\Crawler;
use App\Enums\ChannelType;
use App\Lib\Channel\Twitter;

abstract class ChannelAbstractClass
{
    protected static $maxCnt = 0;
    public static $successCnt = 0;
//    public function __construct(Crawler $crawler)
//    {
//        switch ($crawler->type) {
//            case ChannelType::CHANNEL_VLIVE :
//                return new Vlive();
//            case ChannelType::CHANNEL_INSTAGRAM :
//                return new Instagram();
//            case ChannelType::CHANNEL_YOUTUBE :
//                return new Youtube();
//            case ChannelType::CHANNEL_TWITTER :
//                $this->channel = new Twitter();
//        }
//    }

    /**
     * @description 최신 데이터 가져오기
     * @return mixed
     */
    abstract public function getChannelContents();

    /**
     * @description 전체 데이터 가져오기
     * @return mixed
     */
    abstract public function getChannelContentsAll();

    abstract protected function isValidation( $channelModel);

    abstract protected function setDataFormatting( $class);

    public function saveData(\App\Board $board)
    {
        self::$successCnt++;
        return $board->save();
    }

}