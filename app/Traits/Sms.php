<?php

namespace App\Traits;


use Error;
use Exception;
use HttpException;
use SoapClient;

trait Sms
{
    public function sendPattern($user,$patternCode,$arr)
    {
//        $client = new Client('d5c81d78-1a21-4aa7-ac42-e1bdbe5a144c');

        try {
            $phone_num = "9191903665";
//            dd($phone_num);
            /*$pattern = $client->sendPattern($patternCode, "+983000505", $phone_num, $arr);*/

//            $url = "https://api.payamak-panel.com/post/Smartsms.asmx?wsdl";
//            $data = array(
//                "username" => "9363432406",
//                "password" => "d5c81d78-1a21-4aa7-ac42-e1bdbe5a144c",
//                "bodyId" => intval($patternCode),
//                "to" => $phone_num,
//                "text" => "1234",
//            );
            $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', array('encoding'=>'UTF-8'));

            $parameters['username'] = "9363432406";
            $parameters['password'] = "d5c81d78-1a21-4aa7-ac42-e1bdbe5a144c";
            $parameters['to'] = $phone_num;
            $parameters['bodyId'] = intval($patternCode);
            $parameters['text'] ="1234";


            $response = $sms_client->SendByBaseNumber2($parameters)->SendByBaseNumber2Result;
            return $response;
        } catch (Exception|Error|HttpException $e) {
            info($e->getMessage());
            /*dd($e->unwrap());*/
            /*return $e->unwrap() ;
            echo $e->getCode();*/
            // TODO Sms Error
            /*if ($e->code() == ResponseCodes::ErrUnprocessableEntity) {
                echo "Unprocessable entity";
            }*/
        }

        return true;
    }
}
