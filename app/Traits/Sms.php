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

            $parameters['username'] = settings()->sms_panel_username;
            $parameters['password'] = settings()->sms_panel_password;

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
