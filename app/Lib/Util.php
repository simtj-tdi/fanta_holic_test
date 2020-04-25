<?php

namespace App\Lib;

use App\BannedWord;
use App\User;
use App\Standard;
use App\UserResponseToComment;
use App\Lib\SMS\SMS;
use Carbon\CarbonInterval;
use GeoIp2\Database\Reader;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Carbon\Carbon;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Storage;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use ReceiptValidator\GooglePlay\Validator as PlayValidator;

class Util
{
    public function MakeRandNum($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function ParsingFileName(string $url): string
    {
        $fileUrl = parse_url($url);
        return $fileName = substr($fileUrl['path'], strrpos($fileUrl['path'], '/') + 1);
    }
    // public path에 파일 저장
    // $file = 저장할 파일
    // $filename = 저장할 파일 명
    // $path = 저장 경로 (public 하위 경로)
    public function SaveThumbnail($file, $filename, $path)
    {
        $file->move(public_path($path), $filename);
        $file = fopen(public_path($path) . $filename, "rb");
        fread($file, filesize(public_path($path) . $filename));
        fclose($file);

        return $filename;
    }

    // Azure Blob 저장
    // $file = 저장할 파일
    // $path = 저장할 경로
//    public function SaveThumbnailAzure($file, $path)
//    {
//        $filename = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",  $file->getClientOriginalName());
//        $filename = Carbon::now()->timestamp.'_'.$filename;

//        $this->SaveThumbnail($file, $filename, $path);
//        $image = new ImageResize(public_path($path).$filename);
//        $image->scale(100);
//        $image->resizeToWidth(640);
//        $image->save(public_path($path).$filename);

//        Storage::disk('azure')->put("{$path}/{$filename}", file_get_contents(public_path($path).$filename));
//        Storage::disk('azure')->put("{$path}/{$filename}", $file->get());
//        return $filename;
//    }

    public function SaveThumbnailAzureFixReturnSize($file, $path, $app, $type)
    {
        $filename = preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",
            $file->getClientOriginalName());
        $filename = $app . '_' . $type . '_' . Carbon::now()->timestamp . '_' . $filename;

        Storage::disk('azure')->put("fantaholic/{$path}/{$filename}", file_get_contents($file));
        list($original_width, $original_height) = getimagesize($file);

        $result['filename'] = $filename;
        $result['width'] = 640;
        $result['height'] = $original_height * 640 / $original_width;
        return $result;
    }

    public function SaveThumbnailAzureFixReturnSizeTemp($fileName, $filePath, $path, $app, $type)
    {
        $filename = preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",
            $fileName);
        $filename = $app . '_' . $type . '_' . Carbon::now()->timestamp . '_' . $filename;

        Storage::disk('azure')->put("fantaholic/{$path}_/{$filename}", file_get_contents($filePath));
        list($original_width, $original_height) = getimagesize($filePath);

        $result['filename'] = $filename;
        $result['width'] = 640;
        $result['height'] = $original_height * 640 / $original_width;
        return $result;
    }

    public function FileNameParsing($remoteFileUrl, $prefix = '')
    {
        $fileName = $this->ParsingFileName($remoteFileUrl);
        $fileName = $prefix.Carbon::now()->timestamp . '_' . preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i",
                "", $fileName);
        return $fileName;
    }

    public function AzureUploadImage($remoteFileUrl, $path, $width = 640,  $prefix = '')
    {
        $fileName = $this->FileNameParsing($remoteFileUrl, $prefix);

        Storage::disk('azure')->put(env('APP_NAME')."/{$path}/{$fileName}", file_get_contents($remoteFileUrl));
        list($original_width, $original_height) = getimagesize($remoteFileUrl);
        \Illuminate\Support\Facades\Log::debug(__METHOD__.' - data - '.json_encode([$fileName, $path]));
        \Illuminate\Support\Facades\Log::debug(__METHOD__.' - data - '.json_encode([$original_width, $original_height]));
        $result['fileName'] = $fileName;
        if($original_width !==null && $original_height!==null )
        {
            $result['width'] = $width;
            $result['height'] = $original_height * $width / $original_width;
        }
        $result['path'] = "/{$path}/{$fileName}";

        return $result;
    }

    // 유튜브 썸네일 저장시 위아래 검은공간 제거 후 저장
    public function AzureUploadImageCropped(
        $remoteFileUrl, $uploadPath, $prefix ='', $width =480, $height = 270, $x = 0, $y = 45)
    {
        $fileName = $this->FileNameParsing($remoteFileUrl,$prefix);
        //이미지 편집후 저장
        $image = imagecreatefromjpeg($remoteFileUrl);
        $cropped_image = imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
        ob_start();
        imagejpeg($cropped_image);
        $cropped_contents = ob_get_contents();
        ob_end_clean();
        Storage::disk('azure')->put(env('APP_NAME')."/{$uploadPath}/{$fileName}", $cropped_contents);

        $result['fileName'] = $fileName;
        $result['width'] = $width;
        $result['height'] = $height;
        return $result;
    }

    public function SaveFileAzureTemp($file, $fileName, $path)
    {
        $filename = preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",
            $fileName);

        $filename = Carbon::now()->timestamp . '_' . $filename;

        //laravel 일떄 azure 파일 업로드
        Storage::disk('azure')->put("fantaholic/{$path}/{$filename}", file_get_contents($file));

        // lumen 일때 저장방법
//        $account_name = app('config')['filesystems']['disks']['azure']['name'];
//        $key = app('config')['filesystems']['disks']['azure']['key'];
//        $container_name = app('config')['filesystems']['disks']['azure']['container'];

//        $client = BlobRestProxy::createBlobService("DefaultEndpointsProtocol=https;AccountName={$account_name};AccountKey={$key};");
//        $adapter = new AzureBlobStorageAdapter($client,$container_name);
//        $filesystem = new Filesystem($adapter);
//        $filesystem->put("{$path}/{$filename}",$file->get());

        return $filename;
    }

    public function singleCurl($param)
    {
        $curl = curl_init($param['url']);
        if ($param['method'] == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param['post_fields']);
        } else {
            curl_setopt($curl, CURLOPT_POST, false);
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($param['headers'] != null) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $param['headers']);
        }

        $json_response = curl_exec($curl);
        curl_close($curl);

        return $json_response;
    }

    public function validatedAccessToken(string $app, string $access_token)
    {
        // Redis Connection
        $this->redis = app('redis');
        $user = $this->redis->hgetall("userToken:{$app}:{$access_token}");

        if ($user) {
            return [
                'code' => 0,
                'data' => $user
            ];
        } else {
            return [
                'code' => -3004
            ];
        }
    }

    public function bannedWordsFiltering(string $comment)
    {
        $filter = BannedWord::all();
        if ($filter != null) {
            $ndwords = array();
            foreach ($filter as $rdwords) {
                $r_deny_words = str_repeat("♡", 1);
                $ndwords["$rdwords->name"] = "$r_deny_words";
            }
            $r = strtr("$comment", $ndwords);
        } else {
            $r = $comment;
        }

        return $r;
    }

    public function DownloadImageToAzure($path, $filename, $img_url)
    {
        try {
            Storage::disk('azure')->put("{$path}/{$filename}", file_get_contents($img_url));

            return $filename;
        } catch (\ErrorException $e) {
            return null;
        }
    }

    //2019.02.18 googlecloudvision 이미지 텍스트 체크
    //cdn?
    function detect_text_gcs($path)
    {

        $imageAnnotator = new ImageAnnotatorClient();
        # annotate the image
        $response = $imageAnnotator->textDetection($path);
        $texts = $response->getTextAnnotations();
        $imageAnnotator->close();

        return count($texts);
    }

    function detect_face_gcs($path)
    {
        $imageAnnotator = new ImageAnnotatorClient();
        $image = file_get_contents($path);
        $response = $imageAnnotator->faceDetection($path);
        $faces = $response->getFaceAnnotations();
        $imageAnnotator->close();
        return count($faces);

    }

    //로컬
    function detect_text($path)
    {
        $imageAnnotator = new ImageAnnotatorClient();

        # annotate the image
        $image = file_get_contents($path);
        $response = $imageAnnotator->textDetection($image);
        $texts = $response->getTextAnnotations();

        printf('%d texts found:' . PHP_EOL, count($texts));
        foreach ($texts as $text) {
            print($text->getDescription() . PHP_EOL);

            # get bounds
            $vertices = $text->getBoundingPoly()->getVertices();
            $bounds = [];
            foreach ($vertices as $vertex) {
                $bounds[] = sprintf('(%d,%d)', $vertex->getX(), $vertex->getY());
            }
            print('Bounds: ' . join(', ', $bounds) . PHP_EOL);
        }

        $imageAnnotator->close();
    }

    // sms 발송
    // $phone = 발송할 번호
    public function sendSNS($phone, $app)
    {
        // random 값 생성
        $r = rand(100000, 999999);

        $snd_number = "07041381644"; //trim($s_s_phone);  //보내는 번호
        $rcv_number = $phone;    //받는 번호
        $sms_content = "[ " . $r . " ] 본인확인 인증번호를 입력하세요! [" . $app . "]";

        /******고객님 접속 정보************/
        $sms_id = "nsmg21";            //고객님께서 부여 받으신 sms_id
        $sms_pwd = "106683ab";       //고객님께서 부여 받으신 sms_pwd

        $callbackURL = "sms.tongkni.co.kr";
        $userdefine = $sms_id;         //예약취소를 위해 넣어주는 구분자 정의값, 사용자 임의로 지정해주시면 됩니다. 영문으로 넣어주셔야 합니다. 사용자가 구분할 수 있는 값을 넣어주세요.
        $canclemode = "1";                //예약 취소 모드 1: 사용자정의값에 의한 삭제.  현제는 무조건 1을 넣어주시면 됩니다.
        //구축 테스트 주소와 일반 웹서비스 선택
        if (substr($sms_id, 0, 3) == "bt_") {
            $webService = "http://webservice.tongkni.co.kr/sms.3.bt/ServiceSMS_bt.asmx?WSDL";
        } else {
            $webService = "http://webservice.tongkni.co.kr/sms.3/ServiceSMS.asmx?WSDL";
        }

        $sms = new SMS($webService); //SMS 객체 생성
        $result = $sms->SendSMS($sms_id, $sms_pwd, $snd_number, $rcv_number, $sms_content);// 5개의 인자로 함수를 호출합니다.

        if ($result) {
            return $r;
        } else {
            return -9001;
        }
    }

    // Get IP
    public function getIpFromProxy($request)
    {
//        $ip = "127.0.0.1";

        $ip = $request->ip();

        if (empty($result = $request->header('x-forwarded-for')) === false) {
            return explode(':', $result)[0];
        }

        if (empty($result = $request->header('proxy-client-ip')) === false) {
            return explode(':', $result)[0];
        }

        if (empty($result = $request->header('wl-proxy-client-ip')) === false) {
            return explode(':', $result)[0];
        }

        if (empty($result = $request->header('http_client_ip')) === false) {
            return explode(':', $result)[0];
        }

        if (empty($result = $request->header('http_x_forwarded_for')) === false) {
            return explode(':', $result)[0];
        }

        if (empty($result = $request->header('x-real-ip')) === false) {
            return explode(':', $result)[0];
        }

        if (empty($result = $request->header('x-realip')) === false) {
            return explode(':', $result)[0];
        }

        return $ip;
    }


    // 1 ad_id 가 검수자인가? , 2 ip가 국내인가? 3 검수용 컨텐츠만 내보내는 중인가? app('config')['celeb']['pinxy']['inspection']
    public function check_inspection($request)
    {
        $ip = $this->getIpFromProxy($request);

        try {
            $reader = new Reader(public_path() . '/GeoLite2-Country.mmdb');
            $country = $reader->country($ip);
            $check = $country->country->isoCode;
        } catch (\Exception $e) {
            $check = 'no database';
        }

        if ($check != 'KR') {
            return true;
        }

//        if (app('config')['celeb'][$request->app]['inspection'] == true) {
//            return true;
//        }

        return false;
    }

    // 썸네일 저장
    public function SaveThumbnailAzure($file, $path)
    {
        $filename = preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",
            $file->getClientOriginalName());
        $filename = Carbon::now()->timestamp . '_' . $filename;

        $this->SaveThumbnail($file, $filename, $path);
        $image = new ImageResize(public_path($path) . $filename);
        $image->scale(70);
        $image->resizeToWidth(640);
        $image->save(public_path($path) . $filename);

        Storage::disk('azure')->put("fantaholic/{$path}/{$filename}",
            file_get_contents(public_path($path) . $filename));

        unlink(public_path($path) . $filename);
        return $filename;
    }

    // 유튜브 썸네일 저장시 위아래 검은공간 제거 후 저장
    public function SaveCroppedThumbnaliAzuree($path, $filename, $img_url)
    {

        //이미지 편집후 저장
        $image = imagecreatefromjpeg($img_url);
        $cropped_image = imagecrop($image, ['x' => 0, 'y' => 45, 'width' => 480, 'height' => 270]);
        ob_start();
        imagejpeg($cropped_image);
        $cropped_contents = ob_get_contents();
        ob_end_clean();

        try {
            Storage::disk('azure')->put(env('APP_NAME')."/{$path}/{$filename}", $cropped_contents);

            return $filename;
        } catch (\ErrorException $e) {
            return null;
        }
    }

    //cdn 업로드
    public function SaveFileAzure($file, $path)
    {
        $filename = preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",
            $file->getClientOriginalname());

        $filename = Carbon::now()->timestamp . '_' . $filename;

        //laravel 일떄 azure 파일 업로드
        Storage::disk('azure')->put("fantaholic/{$path}/{$filename}", file_get_contents($file));

        // lumen 일때 저장방법
//        $account_name = app('config')['filesystems']['disks']['azure']['name'];
//        $key = app('config')['filesystems']['disks']['azure']['key'];
//        $container_name = app('config')['filesystems']['disks']['azure']['container'];

//        $client = BlobRestProxy::createBlobService("DefaultEndpointsProtocol=https;AccountName={$account_name};AccountKey={$key};");
//        $adapter = new AzureBlobStorageAdapter($client,$container_name);
//        $filesystem = new Filesystem($adapter);
//        $filesystem->put("{$path}/{$filename}",$file->get());

        return $filename;
    }

//  영상일경우 썸네일 이미지 생성후 저장
    public function SaveVideoPoster($file, $path, $user = null)
    {
        // set storage path to store the file (actual video)
        $destination_path = storage_path() . '/uploads';
        // get file extension
        $extension = $file->getClientOriginalExtension();
        $timestamp = Carbon::now()->timestamp;
        $file_name = $timestamp . '.' . $extension;
        $upload_status = $file->move($destination_path, $file_name);
        if ($upload_status) {
            // file type is video
            // set storage path to store the file (image generated for a given video)
//            $thumbnail_path   = storage_path().'/'.$path;
            $thumbnail_path = storage_path() . '/uploads';
            $video_path = $destination_path . '/' . $file_name;
            // set thumbnail image name
            $poster_image_name = $user->id . '-' . $timestamp . ".jpg";

            // get video length and process it
            // assign the value to time_to_image (which will get screenshot of video at that specified seconds)
            $time_to_image = floor(0); //10초

            $ffmpeg = FFMpeg::create(array(
                'ffmpeg.binaries' => env('FFMPEG_PATH'),
                'ffprobe.binaries' => env('FFPROBE_PATH'),
                'timeout' => 360000, // The timeout for the underlying process
                'ffmpeg.threads' => 16,   // The number of threads that FFMpeg should use
            ));

            $video = $ffmpeg->open($video_path);
            // 영상 포스터 캡쳐후 저장
            $video->frame(TimeCode::fromSeconds($time_to_image))
                ->save($thumbnail_path . '/' . $poster_image_name);

            Storage::disk('azure')->put("fantaholic/{$path}/{$poster_image_name}",
                file_get_contents($thumbnail_path . '/' . $poster_image_name));

            //저장한 로컬 동영상 포스터 이미지 삭제
            unlink($thumbnail_path . '/' . $poster_image_name);
            //저장한 로컬 동영상 삭제,
            unlink($video_path);

            return $poster_image_name;
        } else {//'fail_to_make_thumbnail'
            return '';
        }
    }

    function array_swap(&$array, $swap_a, $swap_b)
    {
        list($array[$swap_a], $array[$swap_b]) = array($array[$swap_b], $array[$swap_a]);
    }

    //해당 년/월의 월요일 배열 뽑는 함수
    public function getMondays($year, $month)
    {
        return new \DatePeriod(
            Carbon::createFromFormat('Y-m', $year . '-' . $month)->firstOfMonth(1),
            CarbonInterval::week(),
            Carbon::createFromFormat('Y-m', $year . '-' . $month)->lastOfMonth()
        );
    }

    public function validatePurchase($param)
    {
        $params = [
            'package_name' => 'com.celeb.tube.fantaholic',
            'product_id' => $param['product_id'],
            'purchase_token' => $param['purchase_token']
        ];
        $client = new \Google_Client();
        $client->setAuthConfig(resource_path('auth/api-5718679171860230498-361327-38bcaa5748a4.json')); //개발서버
        $client->setScopes([\Google_Service_AndroidPublisher::ANDROIDPUBLISHER]);
        $client->setApplicationName('크리사츄');
        $validator = new PlayValidator(new \Google_Service_AndroidPublisher($client));

        try {
            $response = $validator->setPackageName($params['package_name'])
                ->setProductId($params['product_id'])
                ->setPurchaseToken($params['purchase_token'])
                ->validatePurchase();
            return $response; // 0 : 결제완료 , 1: 환불/취소
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error(__METHOD__ . ' - issue - ' . $e->getMessage());
        }
    }
}
