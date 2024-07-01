<?php

namespace App\Traits;


use Error;
use Exception;
use HttpException;
use Illuminate\Support\Str;
use SoapClient;

trait Sms
{
    public function sendPattern($phone, $patternCode, $arr)
    {
        try {
            $phone_num = intval($phone);

            $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl');

            $parameters['username'] = "9363432406";
            $parameters['password'] = "d5c81d78-1a21-4aa7-ac42-e1bdbe5a144c";

            $parameters['to'] = $phone_num;
            $parameters['bodyId'] = (string)$patternCode;
            $parameters['text'] = $arr;
            $response = $sms_client->SendByBaseNumber($parameters)->SendByBaseNumberResult;
            return $response;
        } catch (Exception|Error|HttpException $e) {
            info($e->getMessage());
            info($e->getTraceAsString());
        }
        return true;
    }
}
