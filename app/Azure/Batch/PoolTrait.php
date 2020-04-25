<?php

namespace App\Azure\Batch;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;

trait PoolTrait
{
    /**
     * resize pool in Azure batch
     *
     * @param $params
     * @return bool
     */
    public function resizePool($params)
    {
        $batch_date = $this->getDate();

        $request = [
            'targetDedicatedNodes' => $params['targetDedicatedNodes'],
            'targetLowPriorityNodes' => $params['targetLowPriorityNodes'],
        ];

        $request = json_encode($request);
        $content_len = strlen($request);

        $data = sprintf("POST\n\n\n%d\n\napplication/json;odata=minimalmetadata\n%s\n\n\n\n\n\n/%s/pools/%s/resize\napi-version:%s\ntimeout:20",
            $content_len,
            $batch_date,
            $this->batch_account,
            $params['poolId'],
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/pools/%s/resize?api-version=%s&timeout=20", $this->batch_url, $params['poolId'], $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->withHeader('Content-Type: application/json;odata=minimalmetadata')
            ->withHeader('Content-Length: ' . $content_len)
            ->withData($request)
            ->returnResponseObject()
            ->post();

        if ($response->status == 202) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * get pool details from Azure batch
     *
     * @param $pool_id
     * @return array|mixed
     */
    public function getPool($pool_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/pools/%s\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            urlencode($pool_id),
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/pools/%s?api-version=%s&timeout=20", $this->batch_url, urlencode($pool_id), $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->get();

        if ($response->status == 200) {

            return json_decode($response->content);

        } else {
            return [];
        }
    }

}