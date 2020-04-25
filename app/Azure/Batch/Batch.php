<?php

namespace App\Azure\Batch;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Azure\Batch\PoolTrait;
use App\Azure\Batch\JobTrait;
use App\Azure\Batch\TaskTrait;

class Batch
{
    use PoolTrait, JobTrait, TaskTrait, JobScheduleTrait;

    private $subscription_id;
    private $batch_url;
    private $batch_key;
    private $batch_account;
    private $api_version;


    public static function create()
    {
        return new static();
    }

    public function setSubId($subscription_id)
    {
        $this->subscription_id = $subscription_id;

        return $this;
    }

    public function setUrl($batch_url)
    {
        $this->batch_url = $batch_url;

        return $this;
    }

    public function setKey($batch_key)
    {
        $this->batch_key = $batch_key;

        return $this;
    }

    public function setAccount($batch_account)
    {
        $this->batch_account = $batch_account;

        return $this;
    }


    public function setApiVersion($api_version)
    {
        $this->api_version = $api_version;

        return $this;
    }


    protected function getDate()
    {
        return Carbon::now('UTC')->toRfc7231String();
    }


    protected function signatureString($data)
    {
        return base64_encode(hash_hmac('sha256', utf8_encode($data), base64_decode($this->batch_key), true));
    }
}