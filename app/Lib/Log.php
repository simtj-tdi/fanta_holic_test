<?php

namespace App\Lib;

use Illuminate\Support\Facades\Log as BaseLog;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

use Exception;

class Log
{
    public function __construct()
    {
    }

    /**
     * 시스템에 디버깅 로그 저장
     *
     * @param $message
     */

    public static function debug($file, $line, $message)
    {
        BaseLog::debug(sprintf("[File:%s][Line:%s][IP:%s] %s",
            $file,
            $line,
            Request::ip(),
            $message
        ));
    }

    /**
     * 시스템에 에러 로그 저장
     *
     * @param $message
     */
    public static function error($file, $line, $message)
    {
        BaseLog::error(sprintf("[File:%s][Line:%s][IP:%s] %s",
            $file,
            $line,
            Request::ip(),
            $message
        ));
    }
}