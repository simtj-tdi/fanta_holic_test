<?php

namespace App\Lib\SMS;

use App\Lib\SMS\nusoap_client;

class SMS
{
    function __construct($_webService)
    {
        $this->webService = $_webService;
    }

    function SendSMS($sms_id,$sms_pwd,$snd_number,$rcv_number,$sms_content)
    {
        $snd_number=str_replace("-","",$snd_number);
        $rcv_number=str_replace("-","",$rcv_number);
        $hash_value=md5($sms_id.$sms_pwd.$rcv_number);//해쉬값을 생성한다.
        //통큰아이 웹서비스 웹 접속 포인트를 설정한다.

        if(preg_match("'^[0-9,]+$'",$snd_number.$rcv_number)){
            $soapclient = new nusoap_client($this->webService,true);
            $soapclient->xml_encoding = "UTF-8";
            $soapclient->soap_defencoding = "UTF-8";
            $soapclient->decode_utf8 = false;
            $parameters[] = array(
                'smsID'=>$sms_id,
                'hashValue'=>$hash_value,
                'senderPhone'=>$snd_number,
                'receivePhone'=>$rcv_number,
                'smsContent'=>$sms_content
            );
            $result = $soapclient->call('SendSMS',$parameters); //실제적으로 전송을 실시하고 결과 값을 받습니다.
            return $result['SendSMSResult'];
        }
        else {
            return "-50";
        }
    }
    function SendSMSReserve($sms_id,$sms_pwd,$snd_number,$rcv_number,$sms_content,$reserve_date,$reserve_time,$userdefine)
    {
        $snd_number=str_replace("-","",$snd_number);
        $rcv_number=str_replace("-","",$rcv_number);
        $hash_value=md5($sms_id.$sms_pwd.$rcv_number);//해쉬값을 생성한다.
        //통큰아이 웹서비스 웹 접속 포인트를 설정한다.

        if(preg_match("'^[0-9,]+$'",$snd_number.$rcv_number)){
            $soapclient = new nusoap_client($this->webService,true);
            $soapclient->xml_encoding = "UTF-8";
            $soapclient->soap_defencoding = "UTF-8";
            $soapclient->decode_utf8 = false;
            $parameters[] = array(
                'smsID'=>$sms_id,
                'hashValue'=>$hash_value,
                'senderPhone'=>$snd_number,
                'receivePhone'=>$rcv_number,
                'smsContent'=>$sms_content,
                'reserveDate'=>$reserve_date,
                'reserveTime'=>$reserve_time,
                'userDefine'=>$userdefine
            );
            $result = $soapclient->call('SendSMSReserve',$parameters); //실제적으로 전송을 실시하고 결과 값을 받습니다.
            return $result['SendSMSReserveResult'];
        }
        else {
            return "-50";
        }
    }

    function GetRemainCount($sms_id,$sms_pwd)
    {
        $hash_value=md5($sms_id.$sms_pwd);
        $soapclient = new nusoap_client($this->webService,true);
        $soapclient->xml_encoding = "UTF-8";
        $soapclient->soap_defencoding = "UTF-8";
        $soapclient->decode_utf8 = false;
        $parameters[] = array(
            'smsID'=>$sms_id,
            'hashValue'=>$hash_value
        );
        $result = $soapclient->call('GetRemainCount',$parameters);
        return $result['GetRemainCountResult'];
    }

    function GetRemainDay($sms_id,$sms_pwd)
    {
        $hash_value=md5($sms_id.$sms_pwd);
        $soapclient = new nusoap_client($this->webService,true);
        $soapclient->xml_encoding = "UTF-8";
        $soapclient->soap_defencoding = "UTF-8";
        $soapclient->decode_utf8 = false;
        $parameters[] = array(
            'smsID'=>$sms_id,
            'hashValue'=>$hash_value
        );
        $result = $soapclient->call('GetRemainDay',$parameters);
        return $result['GetRemainDayResult'];
    }

    function GetWeeklyLimit($sms_id,$sms_pwd)
    {
        $hash_value=md5($sms_id.$sms_pwd);
        $soapclient = new nusoap_client($this->webService,true);
        $parameters[] = array(
            'smsID'=>$sms_id,
            'hashValue'=>$hash_value
        );
        $result = $soapclient->call('GetWeeklyLimit',$parameters);
        return $result['GetWeeklyLimitResult'];
    }

    function ReserveCancle($sms_id,$sms_pwd,$userdefine,$canclemode)
    {
        $hash_value=md5($sms_id.$sms_pwd.$userdefine);
        $soapclient = new nusoap_client($this->webService,true);
        $soapclient->xml_encoding = "UTF-8";
        $soapclient->soap_defencoding = "UTF-8";
        $soapclient->decode_utf8 = false;
        $parameters[] = array(
            'smsID'=>$sms_id,
            'hashValue'=>$hash_value,
            'searchValue'=>$userdefine,
            'mode'=>$canclemode
        );
        $result = $soapclient->call('ReserveCancle',$parameters);
        return $result['ReserveCancleResult'];
    }

}
