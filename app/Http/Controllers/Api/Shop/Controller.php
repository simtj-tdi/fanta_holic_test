<?php

namespace App\Http\Controllers\Api\Shop;

use App\Board;
use App\GooglePayment;
use App\Lib\UserManagement;
use App\Lib\Util;
use App\ShopItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Lib\Response;
use App\Lib\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller as baseController;
use InstagramScraper\Instagram;
use InstagramScraper\Model\Media;
use Laravel\Dusk\Dusk;
use ReceiptValidator\GooglePlay\PurchaseResponse;
use ReceiptValidator\GooglePlay\Validator as PlayValidator;

class Controller extends baseController
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
        $this->redis = app('redis');
        $this->cache = app('cache');
    }

    public function index(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
            ]);


            $result['list'] = [
                [
                    'id' => 'google.android.krieshachu.heart.50',
                    'heart' => 50,
                    'price' => 1100,
                ],
                [
                    'id' => 'google.android.krieshachu.heart.100',
                    'heart' => 100,
                    'price' => 2100,
                ],
                [
                    'id' => 'google.android.krieshachu.heart.300',
                    'heart' => 300,
                    'price' => 6000,
                ],
                [
                    'id' => 'google.android.krieshachu.heart.500',
                    'heart' => 500,
                    'price' => 9700,
                ],
                [
                    'id' => 'google.android.krieshachu.heart.1000',
                    'heart' => 1000,
                    'price' => 19000,
                ],
            ];
            $result['etc'] = "\n가입만해도 ❤ 하트 300개!!\n출석하면 매일 ❤ 하트 50개! \n친구 초대하면 ❤ 하트 300개!! \n생일 선물 ❤ 하트 300개!!";

            return $this->response->set_response(0, $result);
        } catch (ValidationException $e) {
            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }
    }

    public function test()
    {

        $account = 'krieshachu_official';
        $instagram = new Instagram();
        $nonPrivateAccountMedias = $instagram->getMedias($account,10);

        foreach($nonPrivateAccountMedias as $key => $media)
        {
//            2147750277683272319
            $mediaId = $media->getId();
            $postCode = '/p/'.$media->getShortCode().'/';
            $chk = Board::where('post','=',$postCode)->count();
            if(!empty($chk)){
                continue;
            }

            $media = $instagram->getMediaById($mediaId);
            $detailMedias = $media->getSidecarMedias();

            $boardArray = [
                'app' =>'krieshachu'
                ,'type' => 'instagram'
                ,'post' => $postCode
                ,'title' => ''
                ,'contents' => $media->getCaption()
                ,'sns_account' => $account
                ,'ori_tag' => []
                ,'gender' => 1
                ,'state' => 0
                ,'recorded_at'=> date('Y-m-d H:i:s')
                ,'created_at'=> date('Y-m-d H:i:s',$media->getCreatedTime())
            ];

            $data = [];
            $oriData = [];

            $file = file_get_contents($media->getImageLowResolutionUrl());
            $fileUrl = parse_url($media->getImageLowResolutionUrl());
            $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
            $localPath = 'download/'.$fileName;
            Storage::put('download/'.$fileName, $file);
            $storagePath = storage_path().'/app/'.$localPath;

            $util = new Util();
            $path = 'images/instagram/thumbnail/';
            $thumbnail = $util->SaveThumbnailAzureFixReturnSizeTemp($fileName, $storagePath, $path, 'krieshachu',
                'instagram');
            $boardArray['thumbnail_url'] = "/" . $path . $thumbnail['filename'];
            $boardArray['thumbnail_w'] = (int)$thumbnail['width'];
            $boardArray['thumbnail_h'] = (int)$thumbnail['height'];
            $boardArray['ori_thumbnail'] = $media->getImageLowResolutionUrl();

            if(count($detailMedias) === 0)
            {
                $data[0]['image'] = $boardArray['thumbnail_url'];
                $boardArray['post_type'] ='img';
            }


            foreach($detailMedias as $detailMediaKey => $detailMedia)
            {
                $thumbnailUrl = $detailMedia->getImageLowResolutionUrl();

                if( $detailMedia->getType() === Media::TYPE_IMAGE)
                {
                    $file = file_get_contents($thumbnailUrl);
                    $fileUrl = parse_url($thumbnailUrl);
                    $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
                    $localPath = 'download/'.$fileName;
                    Storage::put('download/'.$fileName, $file);
                    $storagePath = storage_path().'/app/'.$localPath;

                    $util = new Util();
                    $path = 'images/instagram/thumbnail/';

                    $thumbnail = $util->SaveThumbnailAzureFixReturnSizeTemp($fileName, $storagePath, $path, 'krieshachu',
                        'instagram');
                    $data[$detailMediaKey]['image'] = "/" . $path . $thumbnail['filename'];
                }
                else if($detailMedia->getType() === Media::TYPE_VIDEO)
                {
                    $file = file_get_contents($thumbnailUrl);
                    $fileUrl = parse_url($thumbnailUrl);
                    $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
                    $localPath = 'download/'.$fileName;
                    Storage::put('download/'.$fileName, $file);
                    $storagePath = storage_path().'/app/'.$localPath;

                    $util = new Util();
                    $path = 'images/instagram/thumbnail/';

                    $thumbnail = $util->SaveThumbnailAzureFixReturnSizeTemp($fileName, $storagePath, $path, 'krieshachu',
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

                    $fileName = $util->SaveFileAzureTemp($videoUrl,$fileName, $path, 'krieshachu',
                        'instagram');
                    echo $fileName;

                    $data[$detailMediaKey]['video']['src'] = "/" .$fileName;

                }
            }
            $boardArray['data'] =$data;
            $boardArray['ori_data'] =$oriData;

            Board::create($boardArray);
//            $board->save($boardArray);
        }


//        Dusk::register();

        exit;
        $boards = Board::all();


        echo $boards->count();




        foreach($boards as $boardKey=> $boardVal)
        {


            $board = Board::find($boardVal->id);

//
//            $boardData = $board->data;
//            if(!empty($boardData)) {
//                foreach ($boardData as $dataKey => $dataVal) {
//                    if (!property_exists($dataVal, 'image') ) {
//                        $boardData[$dataKey]->video->src = str_replace('/krieshachu/', '/',$dataVal->video->src);
//                        $boardData[$dataKey]->video->poster = str_replace('/krieshachu/', '/',$dataVal->video->poster);
//                    }
//                    else {
//                        $boardData[$dataKey]->image = str_replace('/krieshachu/', '/',$dataVal->image);
//                    }
//                }
//            }

//            $board->data= $boardData;
//            $board->save();

        }

        exit;
        dd($boards[0]->data);









        exit;
        $client = new \Google_Client();
        $client->setAuthConfig(resource_path('auth/api-5718679171860230498-361327-38bcaa5748a4.json')); //개발서버
        $client->setScopes([\Google_Service_AndroidPublisher::ANDROIDPUBLISHER]);
        $client->setApplicationName('크리사츄');
        $google_client = new \Google_Service_AndroidPublisher($client);

//        dd( $google_client->getListings());

        $productList = $google_client->inappproducts->listInappproducts('com.celeb.tube.krieshachu') ;

        echo count($productList);
        foreach($productList as $productKey => $productInfo)
        {
            $googleProduct = new \Google_Service_AndroidPublisher_InAppProduct($productInfo);

            dd($productInfo->getDefaultPrice());
        }




    }

    public function store(Request $request)
    {

        try {
            \Illuminate\Support\Facades\Log::debug(__METHOD__ . ' - shop request getContents -' . json_encode($request->getContent()));

            $params = $request->all(['product_id', 'purchase_token']);
//            $params = [ 'package_name' => 'com.celeb.tube.krieshachu',
//                'product_id' => 'google.android.krieshachu.heart.50',
//                'purchase_token' => 'jjjnoijiclkgnkdjmihakmch.AO-J1Ozx0qpURhGb1KBCYFpjTjyMgsuLanKpx43smg-F3aSCbSBz9EPnMtQe7ckjA6f6F9Id3QjguMBXE9o0TieaSD5e3_qfxss6RsT-7LkmrhkyepdFefotO5NZKBOy8g2M6zNk0Co09swAekmMg_DFTx4d9Coe0Q'];
            $params['package_name'] = 'com.celeb.tube.krieshachu';
            \Illuminate\Support\Facades\Log::debug(__METHOD__ . ' - shop params -' . json_encode($params));
            $util = new Util();
            $validationObj = $util->validatePurchase($params);
            // 0: 결제완료, 1: 취소, 2: 대기중
            if ($validationObj->getPurchaseState() !== PurchaseResponse::PURCHASE_STATE_PURCHASED) {
                \Illuminate\Support\Facades\Log::error(__METHOD__ . ' - state is not payment success - ' . $validationObj->getPurchaseState(),
                    -4003);
                throw new \Exception('state is not payment success');
            }

            $list = [
                'google.android.krieshachu.heart.50' =>
                    [
                        'heart' => 50,
                        'price' => 1100,
                    ],
                'google.android.krieshachu.heart.100' =>
                    [
                        'heart' => 100,
                        'price' => 2100,
                    ],
                'google.android.krieshachu.heart.300' =>
                    [
                        'heart' => 300,
                        'price' => 6000,
                    ],
                'google.android.krieshachu.heart.500' =>
                    [
                        'heart' => 500,
                        'price' => 9700,
                    ],
                'google.android.krieshachu.heart.1000' =>
                    [
                        'heart' => 1000,
                        'price' => 19000,
                    ],
            ];

            if (empty($list[$params['product_id']])) {
                \Illuminate\Support\Facades\Log::error(__METHOD__ . ' - product_id does not exist  - ' . json_encode($params));
                throw new \Exception('product_id does not exist', 500);
            }

            $googlePayment = GooglePayment::wherePurchaseToken($params['purchase_token'])->get();

            if ($googlePayment->count() !== 0) {
                \Illuminate\Support\Facades\Log::error(__METHOD__ . ' - duplicate row - ' . $googlePayment->toJson());
                throw new \Exception('duplicate row', -4004);
            }

            $userManagement = new UserManagement();
            $userManagement->additem('krieshachu', Auth::id(), $list[$params['product_id']]['heart']);

            GooglePayment::create(
                [
                    'product_id' => $params['product_id'],
                    'purchase_token' => $params['purchase_token'],
                    'product_id' => $params['product_id'],
                    'state' => $validationObj->getPurchaseState(),
                    'order_id' => $validationObj->getRawResponse()->orderId,
                    'user_id' => Auth::user()->id
                ]
            );

            return $this->response->set_response(0, ['item'=>Auth::user()->item_count, 'buy_item'=>$list[$params['product_id']]['heart']]);
        } catch (\Exception $e) {

            \Illuminate\Support\Facades\Log::error(__METHOD__.' - throw exception - '.$e->getTraceAsString());
            $e->getTrace();
            return $this->response->set_response($e->getCode(), Auth::user()->toArray());
        }
    }

    public function shop_banner(Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'app' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            Log::error(__FILE__, __LINE__, $e->getMessage());

            return response()->json([
                'data' => new \stdClass(),
                'resultCode' => [
                    'code' => -1001,
                    'message' => $e->getMessage(),
                ]
            ], 200);
        }
        $params = [
            'app' => $request->input('app'),
        ];

        $shop_items = ShopItem::where('app', $params['app'])
            ->orderby('created_at', 'desc')
            ->limit(10)
            ->get();

        $result['cdn_url'] = config('celeb')[$params['app']]['cdn'];
        $result['body'] = $shop_items;

        return $this->response->set_response(0, $result);

    }
}
