<?php


namespace App\Http\Controllers\Api\Translation;


use App\Http\Controllers\Controller as baseController;
use App\Lib\Response;
use App\Translation;
use Illuminate\Http\Request;


class Controller extends baseController
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function translation(Request $request)
    {
        $params = [
            'board_id' => $request->input('board_id'),
            'targetLanguage' => $request->input('targetLanguage', 'vi'),
            'text' => $request->input('text'),
        ];

        if (is_null($params['board_id']) || is_null($params['text'])) {
            return $this->response->set_response(-2001, null);
        }

        $translation_info = Translation::where('board_id', $params['board_id'])
                        ->where('targetLanguage', $params['targetLanguage'])
                        ->get();


        if ($translation_info->count() > 0) {
            // 있으면
            return $this->response->set_response(0, ['text' => $translation_info[0]->text]);
        } else {
            // 없으면
            $client_id = "ppIJZ0_cK7YM6BM93eHd"; // 네이버 개발자센터에서 발급받은 CLIENT ID
            $client_secret = "mhU3TEUUys";// 네이버 개발자센터에서 발급받은 CLIENT SECRET
            $encText = urlencode($params['text']);
            $postvars = "source=ko&target=".$params['targetLanguage']."&text=".$encText;
            $url = "https://openapi.naver.com/v1/papago/n2mt";
            $is_post = true;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, $is_post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
            $headers = array();
            $headers[] = "X-Naver-Client-Id: ".$client_id;
            $headers[] = "X-Naver-Client-Secret: ".$client_secret;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec ($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            //echo "status_code:".$status_code."<br>";
            curl_close ($ch);

            if($status_code == 200) {
                $curl_response = json_decode($response);
                $translation = new Translation;
                $translation->board_id = $params['board_id'];
                $translation->targetLanguage = $params['targetLanguage'];
                $translation->text = $curl_response->message->result->translatedText;
                $translation->save();

                return $this->response->set_response(0, ['text' => $curl_response->message->result->translatedText]);
            } else {
                return $this->response->set_response(-2001, null);
            }
        }

        return $this->response->set_response(-2001, null);
    }

}