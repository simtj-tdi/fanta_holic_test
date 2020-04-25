<?php

namespace App\Azure\Batch;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;

trait JobTrait
{
    /**
     * add job to Azure batch
     *
     * @param $params
     * @return bool
     */
    public function addJob($params)
    {
        $batch_date = $this->getDate();

        $request = [
            'id' => $params['jobId'],
            'priority' => 0,
            'poolInfo' => [
                'poolId' => $params['poolId'],
            ],
            'jobManagerTask' => [
                'id'    =>  $params['task_id'],
                'commandLine' => $params['commandLine'],
                'killJobOnCompletion' => true,
                'userIdentity' => [
                    'autoUser' => [
                        'scope' => 'task',
                        'elevationLevel' => 'admin',
                    ],
                ]
            ]
        ];

        $request = json_encode($request);
        $content_len = strlen($request);

        $data = sprintf("POST\n\n\n%d\n\napplication/json;odata=minimalmetadata\n%s\n\n\n\n\n\n/%s/jobs\napi-version:%s\ntimeout:20",
            $content_len,
            $batch_date,
            $this->batch_account,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs?api-version=%s&timeout=20", $this->batch_url, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->withHeader('Content-Type: application/json;odata=minimalmetadata')
            ->withHeader('Content-Length: ' . $content_len)
            ->withData($request)
            ->returnResponseObject()
            ->post();

//        Log::debug(print_r($response, true));

        if ($response->status == 201) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * delete job in Azure batch
     *
     * @param $job_id
     * @return bool
     */
    public function deleteJob($job_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("DELETE\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobs/%s\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $job_id,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs/%s?api-version=%s&timeout=20", $this->batch_url, $job_id, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->delete();

        if ($response->status == 202) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * get list of jobs from Azure batch
     *
     * @return array
     */
    public function listJob()
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobs\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs?api-version=%s&timeout=20", $this->batch_url, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->get();

        if ($response->status == 200) {

            $contents = json_decode($response->content);
            $jobs = $contents->value;

            return $jobs;

        } else {
            return [];
        }
    }


    /**
     * get job details from Azure batch
     *
     * @param $job_id
     * @return array|mixed
     */
    public function getJob($job_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobs/%s\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $job_id,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs/%s?api-version=%s&timeout=20", $this->batch_url, $job_id, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->get();

//        print_r($response);
//        exit;

        if ($response->status == 200) {

            $job = json_decode($response->content);

            return $job;

        } else {
            return [];
        }
    }


    /**
     * get tasks's state in jobs from Azure batch
     *
     * @param $job_id
     * @return array|mixed
     */
    public function getTaskCount($job_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobs/%s/taskcounts\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $job_id,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs/%s/taskcounts?api-version=%s&timeout=20", $this->batch_url, $job_id, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->get();

//        Log::debug(print_r($response, true));

        if ($response->status == 200) {

            $jobs = json_decode($response->content);

            return $jobs;

        } else {
            return [];
        }
    }


    public function isCompletedJob($job_id)
    {
        $job = $this->getJob($job_id);

        if (empty($job)) {
            return false;
        }

        $task_state = $this->getTaskCount($job->id);

        if (empty($task_state)) {
            return false;
        }

        if ($task_state->active == 0 && $task_state->running == 0) {

            return true;
        } else {

            return false;
        }
    }
}