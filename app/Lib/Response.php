<?php

namespace App\Lib;

use Carbon\Carbon;

class Response
{
    public function set_response($err_code, $param)
    {
        // Set error code
        $err_data[0] = "Success";
        $err_data[-1001] = 'The given data was invali';

        $err_data[-2001] = "No data";
        $err_data[-2002] = 'Failure to store comment';
        $err_data[-2004] = "Failure to delete comment";

        $err_data[-3001] = "already exists email/nickname";
        $err_data[-3002] = "wrong user data";
        $err_data[-3003] = "not exists email";
        $err_data[-3004] = "invalid access_token";
        $err_data[-3005] = "user already exist";
        $err_data[-3006] = "Not included user";
        $err_data[-3007] = "blacklist";
        $err_data[-3008] = "wrong number";
        $err_data[-3009] = "expired number";
        $err_data[-3010] = 'invalid password';
        $err_data[-3011] = "Non-re-enrollment period";
        $err_data[-3012] = "failed to wechat social login";
        $err_data[-3013] = 'invalid nickname';

        $err_data[-4001] = "comment not exists";
        $err_data[-4002] = "User does not match";
        $err_data[-4003] = "Board not exists";
        $err_data[-4004] = "Duplicate row ";

        $err_data[-5001] = "Not enough item";
        $err_data[-5002] = "already rewarded";

        $err_data[-9001] = 'sms fail';
        // Set data
        if ($err_code==0 && $param != null) {
            $result['data'] = $param;
        } else {
            $result['data'] = (object)[];
        }

        // Set result code
        $result['resultCode'] = [
            "code" => $err_code,
            "message" => $err_data[$err_code],
        ];

        return $result;
    }

}