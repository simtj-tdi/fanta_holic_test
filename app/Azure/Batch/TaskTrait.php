<?php

namespace App\Azure\Batch;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;

trait TaskTrait
{
    /**
     * add task to job in Azure batch
     *
     * @param $params
     * @return bool
     */
    public function addTask($params)
    {
        $batch_date = $this->getDate();

        $request = [
            'id' => $params['taskId'],
            'commandLine' => $params['commandLine'],
            'userIdentity' => [
                'autoUser' => [
                    'elevationLevel' => 'admin'
                ],
            ]
        ];

//        Log::debug(print_r($request, true));
//        return true;

        $request = json_encode($request);
        $content_len = strlen($request);

        $data = sprintf("POST\n\n\n%d\n\napplication/json;odata=minimalmetadata\n%s\n\n\n\n\n\n/%s/jobs/%s/tasks\napi-version:%s\ntimeout:20",
            $content_len,
            $batch_date,
            $this->batch_account,
            $params['jobId'],
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs/%s/tasks?api-version=%s&timeout=20", $this->batch_url, $params['jobId'], $this->api_version))
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
     * delete task in job of Azure batch
     *
     * @param $params
     * @return bool
     */
    public function deleteTask($params)
    {
        $batch_date = $this->getDate();

        $data = sprintf("DELETE\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobs/%s/tasks/%s\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $params['jobId'],
            $params['taskId'],
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs/%s/tasks/%s?api-version=%s&timeout=20", $this->batch_url, $params['jobId'], $params['taskId'], $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->delete();

        if ($response->status == 200) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * get list of jobs in Azure batch
     *
     * @param $job_id
     * @return array
     */
    public function listTask($job_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobs/%s/tasks\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $job_id,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs/%s/tasks?api-version=%s&timeout=20", $this->batch_url, $job_id, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->get();

        if ($response->status == 200) {

            $contents = json_decode($response->content);
            $tasks = $contents->value;

            return $tasks;

        } else {
            return [];
        }
    }


    /**
     * get task details
     *
     * @param $job_id
     * @param $task_id
     * @return array|mixed
     */
    public function getTask($job_id, $task_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobs/%s/tasks/%s\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $job_id,
            $task_id,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobs/%s/tasks/%s?api-version=%s&timeout=20", $this->batch_url, $job_id, $task_id, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->get();

//        print_r($response);
//        exit;

        if ($response->status == 200) {

            $task = json_decode($response->content);

            return $task;

        } else {
            return [];
        }
    }
}