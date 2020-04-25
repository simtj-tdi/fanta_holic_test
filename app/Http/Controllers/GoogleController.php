<?php
//
//namespace App\Http\Controllers\Google;
//
//use App\Http\Controllers\Controller as BaseController;
//use Illuminate\Http\Request;
//use Carbon\Carbon;
//
//use Google_Client;
//use ReceiptValidator\GooglePlay\Validator as PlayValidator;
//
//
//class GoogleController extends BaseController
//{
//    protected $log;
//    protected $redis;
//
//    protected $client;
//
//
//    public function __construct(Request $request)
//    {
//        $this->redis = app('redis');
//
//        $this->client = new Google_Client();
//        $this->client->setAuthConfigFile('/var/www/html/dev/storage/app/client_secret_303123725080-rs7a1g40lll1cjnnjqlp7q6hifk8m8bu.apps.googleusercontent.com.json');
//        $this->client->setAccessType("offline");        // offline access
////        $this->client->setApprovalPrompt('force');
//        $this->client->setIncludeGrantedScopes(true);   // incremental auth
//        $this->client->setRedirectUri('http://' . $request->getHttpHost() . '/google/oauth2/redirect');
//        $this->client->addScope('https://www.googleapis.com/auth/androidpublisher');
//    }
//
//
//    public function oauth2 (Request $request)
//    {
//        $auth_url = $this->client->createAuthUrl();
//
//        return redirect($auth_url);
//    }
//
//
//    public function redirect (Request $request)
//    {
//        if (empty($request->input('code'))) {
//
//            echo 'Failed to oauth2';
//
//        } else {
//
//            $this->client->authenticate($request->input('code'));
//            $response = $this->client->getAccessToken();
//
//            $this->redis->hset('google_oauth2', 'access_token', $response['access_token']);
//            $this->redis->hset('google_oauth2', 'created_at', Carbon::now()->timestamp);
//
//            if (isset($response['refresh_token'])) {
//                $this->redis->hset('google_oauth2', 'refresh_token', $response['refresh_token']);
//            }
//            echo 'Success to oauth2';
//        }
//    }
//
//
//    public function revoke (Request $request)
//    {
//        $access_token = $this->redis->hget('google_oauth2', 'access_token');
//
//        $client = new Google_Client();
//        $client->setAuthConfigFile('/var/www/html/dev/storage/app/client_secret_303123725080-rs7a1g40lll1cjnnjqlp7q6hifk8m8bu.apps.googleusercontent.com.json');
//        $client->addScope('https://www.googleapis.com/auth/androidpublisher');
//        $client->setAccessToken($access_token);
//
//        $client->revokeToken();
//    }
//
//
//    public function and_validatePurchase (Request $request)
//    {
//        $params = [
//            'package_name' => 'com.tube.celeb.leeseol.v2',
//            'product_id' => 'testproduct2',
//            'purchase_token' => 'bnbocajjndfnfleddnfkbaee.AO-J1OzR2kGI1eLUds9qfRtf4ObQ1iAmrorzLUsHHjf_HJdOna_I1WlwYqXxoNH1mB_UOXkJpUdsTxb7ew7usIi8VbK6AMPr2k6vBCVwx1Qrdo_T7zseuw-ud2uG_hAHKXPHUTlHBQsI',
//        ];
//
//        $access_token = $this->redis->hget('google_oauth2', 'access_token');
//        $refresh_token = $this->redis->hget('google_oauth2', 'refresh_token');
//        $created_at = $this->redis->hget('google_oauth2', 'created_at');
//
//        $this->client->setAccessToken($access_token);
//
//        if (Carbon::now()->timestamp - $created_at >= 0) {
//
//            $response=$this->client->refreshToken($refresh_token);
//
//            $this->redis->hset('google_oauth2', 'access_token', $response['access_token']);
//            $this->redis->hset('google_oauth2', 'created_at', Carbon::now()->timestamp);
//
//            if (isset($response['refresh_token'])) {
//                $this->redis->hset('google_oauth2', 'refresh_token', $response['refresh_token']);
//            }
//            $this->client->setAccessToken($response['access_token']);
//        }
//
//        $validator = new PlayValidator(new \Google_Service_AndroidPublisher($this->client));
//
//        try {
//
//            $response = $validator->setPackageName($params['package_name'])
//                ->setProductId($params['product_id'])
//                ->setPurchaseToken($params['purchase_token'])
//                ->validatePurchase();
//
//            echo print_r($response);
//
//            echo "<br>";
//
//            echo "Success : This purchase is validated";
//
//        } catch (Exception $e){
//
//            var_dump($e->getMessage());
//
//            echo "Failed : There is not this purchase in this application";
//        }
//    }
//
//    public function ios_validatePurchase(){
//        define("VERIFY_URL", "https://sandbox.itunes.apple.com/verifyReceipt"); //개발 테스트시
//        //define("VERIFY_URL", "https://buy.itunes.apple.com/verifyReceipt"); //실제 서비스시
//
//        $s_payload = $_REQUEST['payload'];
//        $post = json_encode( Array( 'receipt-data' => $s_payload ) );
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, VERIFY_URL);
//        curl_setopt($ch, CURLOPT_POST,1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $result=curl_exec($ch);
//        curl_close ($ch);
//        $o_bill = json_decode($result);
//        if($o_bill->status != 0) {
//            echo "invalid_recipt"; return -1;
//        }
//        //option : package name checking
//         if($o_bill->receipt->bundle_id != "com.tube.celeb.leeseol.v2") {
//             echo "invalid_pkg_name"; return -1;
//         }
//         //option : product id checking
//        if($o_bill->receipt->in_app{0}->product_id != "hp_potion") {
//            echo "invalid_pid"; return -1;
//        }
//        return 0;
//    }
//}