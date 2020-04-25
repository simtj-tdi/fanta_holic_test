<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Elasticsearch\ClientBuilder;

use Exception;
use Elasticsearch\Common\Exceptions\Conflict409Exception;
use Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\TransportException;

class ElasticService
{
    protected $esAnalysis;
    protected $esApp;
    protected $esWifi;
    protected $esBluetooth;
    protected $esLocation;
    protected $esExternal;

    protected $maxRetry;

    public $size;
    public $scroll;
    public $scrollId;
    public $composite;
    public $afterKey;


    public function __construct()
    {
        // Research9 Elasticsearch 연결
        $this->esAnalysis = ClientBuilder::create()
            ->setHosts([
                [
                    'host' => config('elasticsearch.analysis.host'),
                    'port' => config('elasticsearch.analysis.port'),
                    'schema' => config('elasticsearch.analysis.schema'),
                    'user' => config('elasticsearch.analysis.username'),
                    'pass' => config('elasticsearch.analysis.password'),
                ]
            ])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();

        // Target9 APP Elasticsearch 연결
        $this->esApp = ClientBuilder::create()
            ->setHosts([
                [
                    'host' => config('elasticsearch.app.host'),
                    'port' => config('elasticsearch.app.port'),
                    'schema' => config('elasticsearch.app.schema'),
                    'user' => config('elasticsearch.app.username'),
                    'pass' => config('elasticsearch.app.password'),
                ]
            ])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();

        // Target9 WIFI Elasticsearch 연결
        $this->esWifi = ClientBuilder::create()
            ->setHosts([
                [
                    'host' => config('elasticsearch.wifi.host'),
                    'port' => config('elasticsearch.wifi.port'),
                    'schema' => config('elasticsearch.wifi.schema'),
                    'user' => config('elasticsearch.wifi.username'),
                    'pass' => config('elasticsearch.wifi.password'),
                ]
            ])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();

        // Target9 Bluetooth Elasticsearch 연결
        $this->esBluetooth = ClientBuilder::create()
            ->setHosts([
                [
                    'host' => config('elasticsearch.bluetooth.host'),
                    'port' => config('elasticsearch.bluetooth.port'),
                    'schema' => config('elasticsearch.bluetooth.schema'),
                    'user' => config('elasticsearch.bluetooth.username'),
                    'pass' => config('elasticsearch.bluetooth.password'),
                ]
            ])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();

        // Target9 Location Elasticsearch 연결
        $this->esLocation = ClientBuilder::create()
            ->setHosts([
                [
                    'host' => config('elasticsearch.location.host'),
                    'port' => config('elasticsearch.location.port'),
                    'schema' => config('elasticsearch.location.schema'),
                    'user' => config('elasticsearch.location.username'),
                    'pass' => config('elasticsearch.location.password'),
                ]
            ])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();

        // Target9 External Elasticsearch 연결
        $this->esExternal = ClientBuilder::create()
            ->setHosts([
                [
                    'host' => config('elasticsearch.external.host'),
                    'port' => config('elasticsearch.external.port'),
                    'schema' => config('elasticsearch.external.schema'),
                    'user' => config('elasticsearch.external.username'),
                    'pass' => config('elasticsearch.external.password'),
                ]
            ])
            ->setSSLVerification(false)
            ->setRetries(3)
            ->build();

        $this->maxRetry = 3;
        $this->size = 50;
        $this->scroll = '10m';
        $this->scrollId = null;
        $this->composite = null;
        $this->afterKey = null;
    }


    /**
     * Elasticsearch Search Query
     *
     * @param array $params
     * @param $type
     * @return array|null
     */
    public function search(array $params, $type)
    {
        $i = 0;
        $response = null;
        $isScroll = false;

        $es = $this->getClient($type);

        if (empty($es)) {
            return $response;
        }

        if (empty($params['scroll']) === false &&
            empty($params['size']) === false
        ) {
            $isScroll = true;
        }

        while ($i < $this->maxRetry && $response === null) {
            try {

                $response = $es->search($params);

                if ($isScroll &&
                    empty($response['hits']['hits']) === false &&
                    count($response['hits']['hits']) > 0
                ) {
                    $this->scrollId = $response['_scroll_id'];
                }

            } catch (Missing404Exception $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'Missing404Exception');
            } catch (Conflict409Exception $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'Conflict409Exception');
            } catch (CouldNotConnectToHost $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'CouldNotConnectToHost');
            } catch (TransportException $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'TransportException');
            } catch (Exception $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'Exception');
            }

            $i++;
        }

        return $response;
    }


    /**
     * Elasticsearch Scroll Query
     *
     * @param $type
     * @return array|null
     */
    public function scroll($type)
    {
        $i = 0;
        $response = null;

        $es = $this->getClient($type);

        if (empty($es)) {
            return $response;
        }

        if (empty($this->scrollId)) {
            return $response;
        }

        $params = [
            'scroll_id' => $this->scrollId,
            'scroll' => $this->scroll,
        ];

        while ($i < $this->maxRetry && $response === null) {
            try {

                $response = $es->scroll($params);

                if (empty($response['hits']['hits']) === false &&
                    count($response['hits']['hits']) > 0
                ) {
                    $this->scrollId = $response['_scroll_id'];
                } else {
                    $this->scrollId = null;
                }

            } catch (Missing404Exception $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'Missing404Exception');
            } catch (Conflict409Exception $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'Conflict409Exception');
            } catch (CouldNotConnectToHost $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'CouldNotConnectToHost');
            } catch (TransportException $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'TransportException');
            } catch (Exception $e) {
                $this->logError(__METHOD__, $e->getMessage(), 'Exception');
            }

            $i++;
        }

        return $response;
    }


    /**
     * Elasticsearch composite 설정
     *
     * @param $composite
     */
    public function setComposite($composite)
    {
        $this->composite = $composite;
    }


    /**
     * Elasticsearch composite 리턴
     * @return null
     */
    public function getComposite()
    {
        if (empty($this->composite) === false &&
            empty($this->afterKey) === false
        ) {
            $this->composite['after'] = $this->afterKey;
        }

        return $this->composite;
    }


    /**
     * Elasticsearch composite query 시 after_key 설정
     *
     * @param $afterKey
     */
    public function setAfterKey($afterKey)
    {
        $this->afterKey = $afterKey;
    }


    /**
     * Elasticsearch composite query 시 after_key 리턴
     *
     * @return null
     */
    public function getAfterKey()
    {
        return $this->afterKey;
    }


    /**
     * Target9 Elasticsearch inddex 리턴
     *
     * @param $type
     * @param $year
     * @param $month
     * @return string|null
     */
    public function getIndex($type, $year, $month)
    {
        switch ($type) {
            case 'app':
                return sprintf("%s_%s%02d*", config('elasticsearch.app.index'), $year, (int)$month);

            case 'wifi':
                return sprintf("%s_%s%02d*", config('elasticsearch.wifi.index'), $year, (int)$month);

            case 'bluetooth':
                return sprintf("%s_%s%02d*", config('elasticsearch.bluetooth.index'), $year, (int)$month);

            case 'location':
                return sprintf("%s_%s%02d*", config('elasticsearch.location.index'), $year, (int)$month);

            case 'external':
                return sprintf("%s_%s%02d*", config('elasticsearch.external.index'), $year, (int)$month);

            case 'analysis':
                return sprintf("%s_%s%02d*", config('elasticsearch.analysis.index'), $year, (int)$month);

            default:
                return null;
                break;
        }
    }


    /**
     * Target9 데이터별 Elasticsearch client instance 리턴
     *
     * @param $type
     * @return \Elasticsearch\Client|null
     */
    protected function getClient($type)
    {
        switch ($type) {
            case 'app':
                return $this->esApp;

            case 'wifi':
                return $this->esWifi;

            case 'bluetooth':
                return $this->esBluetooth;

            case 'location':
                return $this->esLocation;

            case 'external':
                return $this->esExternal;

            case 'analysis':
                return $this->esAnalysis;

            default:
                return null;
                break;
        }
    }


    /**
     * batch error log 등록 함수
     * @param $action
     * @param $message
     * @param string $exceptionType
     */
    protected function logError($method, $message, $exceptionType='')
    {
        Log::error($message);
    }
}
