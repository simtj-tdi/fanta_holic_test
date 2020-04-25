<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

use Exception;

class NaverService
{
    /**
     * 참고 경로
     * https://github.com/naver/searchad-apidoc/blob/master/php-sample/restapi.php
     */

    protected $keyword;

    protected $apiKey = '0100000000a14f3ebd7763ffcba219c61a9830c623a15ceccb900f0a8b54b2a5d8f787b8fd';
    protected $customerId = '1351215';
    protected $secretKey = 'AQAAAAChTz69d2P/y6IZxhqYMMYjc2d6naqlO9OL5h27i24NqQ==';
    protected $baseUrl = 'https://api.naver.com';

    protected $client;
    protected $textReplace = [" ", ",", "★", "[", "]"];

    public function __construct()
    {
        /** @var Client client */
        $this->client = new Client();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTrend($keyword)
    {
        $this->keyword = $keyword;

        return $this->getNaverKeyword();
    }


    /**
     * 네이버 키워드 조회 및 등록
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNaverKeyword()
    {
        /** @var \stdClass $naverKeywords */
        $naverKeywords = $this->getData();

        $relationKeywords = [];
        $keyword = null;


        if( $naverKeywords !== null) {
            foreach ($naverKeywords->keywordList as $naverKeyword) {
                if( $naverKeyword->relKeyword == str_replace($this->textReplace, "", $this->keyword)  ){
                    $keyword = $naverKeyword;
                } else {
                    $relationKeywords[] = $naverKeyword->relKeyword;
                }

            }

            if( $keyword !== null ){
                $keyword->monthlyPcQcCnt = trim(str_replace("< ", "", $keyword->monthlyPcQcCnt));
                $keyword->monthlyMobileQcCnt = trim(str_replace("< ", "", $keyword->monthlyMobileQcCnt));
                //월별 조회수 -> 일별 조회수 구하
                $daliyPcQcCnt =  (int) $keyword->monthlyPcQcCnt != 0 ? ($keyword->monthlyPcQcCnt / 30) : 0;
                $daliyMobileQcCnt = (int) $keyword->monthlyMobileQcCnt != 0 ? ($keyword->monthlyMobileQcCnt / 30) : 0;


//                $keyword->_index = $this->elasticService->getEsLogsNaverKeywordIndex();
//                $keyword->_id = $this->_getEsId($keyword);
//                $keyword->reg_date = $this->daily;
                $keyword->relations = $relationKeywords;
                $keyword->daliyPcQcCnt = $daliyPcQcCnt;
                $keyword->daliyMobileQcCnt = $daliyMobileQcCnt;
//                $keyword->keywordType = $this->keywordType;
                $keyword->keyword = $this->keyword;

            } else {
//                echo 'naver keyword not match: '.$this->keyword.PHP_EOL;
            }
        } else {
//            echo 'naver keyword not search: '.$this->keyword.PHP_EOL;
        }

        return $keyword;
    }


    private function getTimestamp()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * 네이버 api 조회시 header 구성
     * @param $method
     * @param $uri
     * @return array
     */
    private function getHeader($method, $uri)
    {
        $timestamp = $this->getTimestamp();


        $header = [
            'Content-Type' => 'application/json; charset=UTF-8',
            'X-Timestamp' => $timestamp,
            'X-API-KEY' => $this->apiKey,
            'X-Customer' => $this->customerId,
            'X-Signature' => $this->generateSignature($timestamp, $method, $uri),
        ];
        return $header;
    }

    /**
     * 네이버 header 암호화 된 부분 처리
     * @param $timestamp
     * @param $method
     * @param $path
     * @return string
     */
    private function generateSignature($timestamp, $method, $path)
    {
        $sign = $timestamp . "." . $method . "." . $path;

        $signature = hash_hmac('sha256', $sign, $this->secretKey, true);
        return base64_encode($signature);
    }


    /**
     * 네이버 키워드 조회 - https://searchad.naver.com/ 광고시스템 api 이용
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData()
    {
        try {
            $uri = '/keywordstool';

            $response = $this->client->request('GET', $this->baseUrl . $uri, [
                'query' => [
                    'hintKeywords' => str_replace($this->textReplace, "", $this->keyword),
                    'showDetail' => 1

                ],
                'headers' => $this->getHeader('GET', $uri),
//                'debug' => true
            ]);

            return json_decode($response->getBody()->getContents(), false);

        } catch (ClientException | ConnectException | RequestException | BadResponseException | ServerException $e) {

            Log::error($e->getMessage());

            return null;

        }
    }

    /**
     * 네이버 조회 시 기본 모양 예시
    [relKeyword] => SB  //연관키워드
    [monthlyPcQcCnt] => 3090 //월간검색수(PC)
    [monthlyMobileQcCnt] => 4570 //월간검색수(모바일)
    [monthlyAvePcClkCnt] => 0.6 //월평균클리수(pc)
    [monthlyAveMobileClkCnt] => 3.3 //월평균클릭수(모바일)
    [monthlyAvePcCtr] => 0.03 //월평균 클릴률(pc)
    [monthlyAveMobileCtr] => 0.08 //월평균 클릴률(모바일)
    [plAvgDepth] => 15 //월평균노출 광고수
    [compIdx] => 높음 //경쟁정도

     */

}
