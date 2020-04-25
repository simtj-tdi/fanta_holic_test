<?php

namespace App\Azure\Batch;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;

trait JobScheduleTrait
{
    /**
     * update job schedule to Azure batch
     *
     * @param $params
     * @return bool
     */
    public function updateJobschedule($params)
    {
        $batch_date = $this->getDate();
        $request = [
            'schedule' => [
                'recurrenceInterval' => $params['interval']
            ],
            'jobSpecification' => [
                'jobManagerTask' => [
                    'id' => $params['jobId'],
                    'commandLine' => $params['commandLine'],
                    'constraints' => [
                        'retentionTime' => 'PT2H'
                    ],
                    'userIdentity'  =>  [
                        'autoUser'  =>  [
                            'scope' =>  "task",
                            'elevationLevel'    =>  'admin'
                        ]
                    ]
                ],
                'poolInfo' => [
                    'poolId' => $params['poolId'],
                ]
            ],
        ];
        $request = json_encode($request);
        $content_len = strlen($request);

        $data = sprintf("PUT\n\n\n%d\n\napplication/json;odata=minimalmetadata\n%s\n\n\n\n\n\n/%s/jobschedules/%s\napi-version:%s\ntimeout:20",
            $content_len,
            $batch_date,
            $this->batch_account,
            $params['scheduleId'],
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobschedules/%s?api-version=%s&timeout=20", $this->batch_url,$params['scheduleId'], $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->withHeader('Content-Type: application/json;odata=minimalmetadata')
            ->withHeader('Content-Length: ' . $content_len)
            ->withData($request)
            ->returnResponseObject()
            ->put();

        if ($response->status == 200) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * add job schedule to Azure batch
     *
     * @param $params
     * @return bool
     */
    public function addJobschedule($params)
    {
        $batch_date = $this->getDate();

        $request = [
            'id' => $params['scheduleId'],
            'jobSpecification' => [
                'priority' => 0,
                'jobManagerTask' => [
                    'id' => $params['jobId'],
                    'commandLine' => $params['commandLine'],
                    'constraints' => [
                        'retentionTime' => 'PT2H'
                    ],
                    'userIdentity' => [
                        'autoUser' => [
                            'scope' => 'task',
                            'elevationLevel' => 'admin',
                        ],
                    ]
                ],
                'poolInfo' => [
                    'poolId' => $params['poolId'],
                ],
            ],
            'schedule' => [
                'recurrenceInterval' => $params['interval']
            ],
        ];

        $request = json_encode($request);
        $content_len = strlen($request);

        $data = sprintf("POST\n\n\n%d\n\napplication/json;odata=minimalmetadata\n%s\n\n\n\n\n\n/%s/jobschedules\napi-version:%s\ntimeout:20",
            $content_len,
            $batch_date,
            $this->batch_account,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobschedules?api-version=%s&timeout=20", $this->batch_url, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->withHeader('Content-Type: application/json;odata=minimalmetadata')
            ->withHeader('Content-Length: ' . $content_len)
            ->withData($request)
            ->returnResponseObject()
            ->post();
            dump($response);
//        Log::debug(print_r($response, true));

        if ($response->status == 201) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get list of job schedules from Azure batch
     *
     * @return array
     */
    public function listJobschedule()
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobschedules\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobschedules?api-version=%s&timeout=20", $this->batch_url, $this->api_version))
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
     * get list of jobs from job schedule from Azure batch
     *
     * @return array
     */
    public function listJobFromSchedule($jobschedule_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("GET\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobschedules/%s/jobs\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $jobschedule_id,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobschedules/%s/jobs?api-version=%s&timeout=20", $this->batch_url, $jobschedule_id, $this->api_version))
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
     * change state job schedule in Azure batch
     *
     * @param $job_id
     * @return bool
     */
    public function stateJobschedule($jobschedule_id, $state)
    {
        $batch_date = $this->getDate();

        $data = sprintf("POST\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobschedules/%s/%s\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $jobschedule_id,
            $state,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobschedules/%s/%s?api-version=%s&timeout=20", $this->batch_url, $jobschedule_id, $state, $this->api_version))
            ->withHeader(sprintf("Authorization: SharedKey %s:%s", $this->batch_account, $signature))
            ->withHeader('Date: ' . $batch_date)
            ->returnResponseObject()
            ->post();

        if ($response->status == 202) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * delete job schedule in Azure batch
     *
     * @param $job_id
     * @return bool
     */
    public function deleteJobschedule($jobschedule_id)
    {
        $batch_date = $this->getDate();

        $data = sprintf("DELETE\n\n\n\n\n\n%s\n\n\n\n\n\n/%s/jobschedules/%s\napi-version:%s\ntimeout:20",
            $batch_date,
            $this->batch_account,
            $jobschedule_id,
            $this->api_version
        );

        $signature = $this->signatureString($data);

        $response = Curl::to(sprintf("%s/jobschedules/%s?api-version=%s&timeout=20", $this->batch_url, $jobschedule_id, $this->api_version))
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
}