<?php

namespace App\Http\Controllers;

use App\Board;
use App\Crawler;
use App\Lib\Channel\Factory\ChannelFactory;
use App\Lib\Channel\Twitter;
use App\Lib\Util;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InstagramScraper\Instagram;
use InstagramScraper\Model\Media;

class ChannelController extends Controller
{
    public function index()
    {

    }

    public function show()
    {

    }

    public function twitter_store()
    {
        $channel = 'twitter';
        $account = '';

    }

    public function youtube_store()
    {
        $channel = 'google';
        $account = '';

        $crawler = Crawler::find(1);

        $channel = new ChannelFactory($crawler);
        $channel->getChannelContents();
//        $twitter = new Twitter();
//        $twitter->getChannelContents();

//        $youtubeChannel = new YoutubeChannel('AIzaSyBvzo9psEVcg7gIR3BsxPcdToCAjXxuNsc','UCLkAepWjdylmXSltofFvsYQ');
//        $youtubeChannel->getChannelContents();
    }

    public function vlive_store()
    {
        ini_set('memory_limit', '-1');




//        try {
        $account = 'E173B7';
        $client = new Client();
        $params = [
            'gcc' => 'KR',
            'locale' => 'ko',
            'app_id' => '8c6cc7b45d2568fb668be6e05b6e5a3b',
        ];
        $chk = true;
        while ($chk) {

            $btsListURL = 'https://api-vfan.vlive.tv/v2/channel.13/home?' . http_build_query($params);
            $response = $client->get($btsListURL);

            echo $btsListURL;
            echo "<br>";
            if ($response->getStatusCode() !== 200) {
                Log::error(__METHOD__ . ' - get vlive response code is not 200 - ' . json_encode($response));
                throw new \Exception('response code not 200');
            }
            $listResponseBodyContents = json_decode($response->getBody()->getContents());
            $contentList = $listResponseBodyContents->contentList;
            $params['next'] = $listResponseBodyContents->page->next;
            $chk = ((int)$params['next'] > 0) ? true : false;

            Log::debug(__METHOD__.' - key  - '.$params['next']. ' - chk -'.json_encode($chk));

            foreach ($contentList as $content) {


                if (empty($content->type)) {
                    if (empty($content->image_list[0])) {
                        $content->thumbnail = null;
                    } else {
                        $content->thumbnail = $content->image_list[0]->thumb;
                    }

                }

                $boardArray = [
                    'app' => 'bts',
                    'type' => 'vlive',
                    'post' => (!empty($content->type) && strtolower($content->type) == 'video') ? "video/" . $content->videoSeq : $content->post_id,
                    'title' => $content->title,
                    'contents' => (!empty($content->type) && strtolower($content->type) == 'video') ? $content->title : $content->content,
                    'sns_account' => $account,
                    'ori_tag' => [],
                    'gender' => 1,
                    'state' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $dupleCheck = Board::where('post', '=', $boardArray['post'])->count();
                if (!empty($dupleCheck)) {
                    continue;
                }

                if ($content->thumbnail !== null) {
                    $util = new Util();
                    $path = 'videos/images/thumbnail';
                    $response = $util->AzureUploadImage($content->thumbnail, $path);
                    $boardArray['thumbnail_url'] = '/' . $path . '/' . $response['fileName'];
                    $boardArray['thumbnail_w'] = $response['width'];
                    $boardArray['thumbnail_h'] = $response['height'];
                    $boardArray['ori_thumbnail'] = $content->thumbnail;

                    $data = [];
                    if (!empty ($content->type) && strtolower($content->type) == 'video') {
                        $detailResponse = $client->get(sprintf('https://www.vlive.tv/video/%s', $content->videoSeq));
                        $boardArray['recorded_at'] = $content->createdAt;
                        $responseBodyContents = $detailResponse->getBody()->getContents();
                        preg_match('#vlive.video.init\(([\S\s]*?)\);#', $responseBodyContents, $matches);
                        if (isset($matches[1])) {
                            list(, , , , , $videoid, $key,) = explode(',', $matches[1]);
                            $videoid = trim(str_replace('"', '', $videoid));
                            $key = trim(str_replace('"', '', $key));
                            $video_info_url = sprintf('http://global.apis.naver.com/rmcnmv/rmcnmv/vod_play_videoInfo.json?key=%s&pid=&sid=2024&ver=2.0&devt=html5_pc&doct=json&ptc=http&cpt=vtt&cpl=zh_CN&lc=zh_CN&videoId=%s&cc=CN',
                                $key, $videoid);
                        }
                        $response = $client->get($video_info_url);
                        $vliveModel = json_decode($response->getBody()->getContents());

                        if (!empty($vliveModel->videos) && count($vliveModel->videos->list) > 1) {
                            $video = $vliveModel->videos->list[1];
                            $videoSource = $video->source;
                            $fileContents = file_get_contents($videoSource);
                            $fileUrl = parse_url($videoSource);
                            $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
                            $path = 'videos/vlive/';
                            Storage::disk('azure')->put("BTS/{$path}/{$fileName}", $fileContents);

                            $data[0]['video']['src'] = "/" . $path . '/' . $fileName;
                            $data[0]['video']['poster'] = $boardArray['thumbnail_url'];
                        }
                    } else {
                        // 이미지
                        $data[0]['image'] = $boardArray['thumbnail_url'];
                        $boardArray['recorded_at'] = date('Y-m-d H:i:s', substr($content->created_at, 0, 10));
                    }
                    $boardArray['data'] = $data;
                    $boardArray['ori_data'] = [];

                    Board::create($boardArray);
                    Log::info(__METHOD__.' - succescs key - ' .$boardArray['post']);
                    Log::info(__METHOD__.' - succescs data - ' .json_encode($boardArray));
                }
            }

        }
//        } catch (\Exception $e) {
//            echo $e->getTrace();
//        }

    }

    public function store(Request $request)
    {
        $next = false;
        $account = 'bts.bighitofficial';
        $instagram = new Instagram();
//        $nonPrivateAccountMedias = $instagram->getMedias($account,1000);
        $instagramPageObj = $instagram->getPaginateMedias($account);
        while ($instagramPageObj['hasNextPage'] === true) {
            foreach ($instagramPageObj['medias'] as $key => $media) {
//            2147750277683272319
                $mediaId = $media->getId();
                $postCode = '/p/' . $media->getShortCode() . '/';
                $chk = Board::where('post', '=', $postCode)->count();
                if (!empty($chk)) {
                    continue;
                }

                $media = $instagram->getMediaById($mediaId);
                $detailMedias = $media->getSidecarMedias();

                $boardArray = [
                    'app' => 'BTS',
                    'type' => 'instagram',
                    'post' => $postCode,
                    'title' => '',
                    'contents' => $media->getCaption(),
                    'sns_account' => $account,
                    'ori_tag' => [],
                    'gender' => 1,
                    'state' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'recorded_at' => date('Y-m-d H:i:s', $media->getCreatedTime())
                ];

                $data = [];
                $oriData = [];

                $file = file_get_contents($media->getImageLowResolutionUrl());
                $fileUrl = parse_url($media->getImageLowResolutionUrl());
                $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
                $localPath = 'download/' . $fileName;
                Storage::put('download/' . $fileName, $file);
                $storagePath = storage_path() . '/app/' . $localPath;

                $util = new Util();
                $path = 'images/instagram/thumbnail/';
                $thumbnail = $util->SaveThumbnailAzureFixReturnSizeTemp($fileName, $storagePath, $path, 'krieshachu',
                    'instagram');
                $boardArray['thumbnail_url'] = "/" . $path . $thumbnail['filename'];
                $boardArray['thumbnail_w'] = (int)$thumbnail['width'];
                $boardArray['thumbnail_h'] = (int)$thumbnail['height'];
                $boardArray['ori_thumbnail'] = $media->getImageLowResolutionUrl();

                if (count($detailMedias) === 0) {
                    $data[0]['image'] = $boardArray['thumbnail_url'];
                    $boardArray['post_type'] = 'img';
                }


                foreach ($detailMedias as $detailMediaKey => $detailMedia) {
                    $thumbnailUrl = $detailMedia->getImageLowResolutionUrl();

                    if ($detailMedia->getType() === Media::TYPE_IMAGE) {
                        $file = file_get_contents($thumbnailUrl);
                        $fileUrl = parse_url($thumbnailUrl);
                        $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
                        $localPath = 'download/' . $fileName;
                        Storage::put('download/' . $fileName, $file);
                        $storagePath = storage_path() . '/app/' . $localPath;

                        $util = new Util();
                        $path = 'images/instagram/thumbnail/';

                        $thumbnail = $util->SaveThumbnailAzureFixReturnSizeTemp($fileName, $storagePath, $path,
                            'krieshachu',
                            'instagram');
                        $data[$detailMediaKey]['image'] = "/" . $path . $thumbnail['filename'];
                    } else {
                        if ($detailMedia->getType() === Media::TYPE_VIDEO) {
                            $file = file_get_contents($thumbnailUrl);
                            $fileUrl = parse_url($thumbnailUrl);
                            $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
                            $localPath = 'download/' . $fileName;
                            Storage::put('download/' . $fileName, $file);
                            $storagePath = storage_path() . '/app/' . $localPath;

                            $util = new Util();
                            $path = 'images/instagram/thumbnail/';

                            $thumbnail = $util->SaveThumbnailAzureFixReturnSizeTemp($fileName, $storagePath, $path,
                                'krieshachu',
                                'instagram');
                            $data[$detailMediaKey]['video']['poster'] = "/" . $path . $thumbnail['filename'];


                            $videoUrl = $detailMedia->getVideoStandardResolutionUrl();
//                    $file = file_get_contents($videoUrl);
                            $fileUrl = parse_url($videoUrl);
                            $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
//                    $localPath = 'download/'.$fileName;
//                    Storage::put('download/'.$fileName, $file);
//                    $storagePath = storage_path().'/app/'.$localPath;

                            $util = new Util();
                            $path = 'videos/instagram/';

                            $fileName = $util->SaveFileAzureTemp($videoUrl, $fileName, $path, 'krieshachu',
                                'instagram');
                            echo $fileName;

                            $data[$detailMediaKey]['video']['src'] = "/" . $fileName;

                        }
                    }
                }
                $boardArray['data'] = $data;
                $boardArray['ori_data'] = $oriData;

                Board::create($boardArray);
            }

            $instagramPageObj = $instagram->getPaginateMedias($account, $instagramPageObj['maxId']);
        }


    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
