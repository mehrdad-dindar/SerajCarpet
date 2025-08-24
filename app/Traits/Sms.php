<?php

namespace App\Traits;

use Error;
use Exception;
use Filament\Notifications\Notification;
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

    public function getCredit($is_force = false)
    {
        if (session()->has('smsCredit') && !$is_force) {
            return session('smsCredit');
        }

        try {
            $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl');

            $parameters = [
                'username' => settings()->sms_panel_username,
                'password' => settings()->sms_panel_password,
            ];

            $smsPrice = $this->getSmsPrice();
            $response = $sms_client->GetCredit($parameters)->GetCreditResult;
            if ($response > 0) {
                $total = number_format((int) bcmul($smsPrice, $response));
                $result = number_format($response).' عدد پیامک باقیمانده! ' . 'تقریبا معادل ' . $total .' ریال';
                session()->put('smsCredit', $result);
                Notification::make()
                    ->title('اعتبار پنل پیامک به روز شد')
                    ->body($result)
                    ->success()
                    ->send();
                return $result;
            }
            return 'خطا در دریافت اعتبار پنل پیامک !';
        } catch (Exception|Error|HttpException $e) {
            Notification::make()
                ->title('خطا در دریافت اعتبار پنل پیامک !')
                ->body($e->getMessage())
                ->danger()
                ->send();
            return 'خطا در دریافت اعتبار پنل پیامک !';
        }
    }

    public function getSmsPrice()
    {
        try {
            $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl');

            $parameters = [
                'username' => settings()->sms_panel_username,
                'password' => settings()->sms_panel_password,
                'irancellCount' => 0,
                'mtnCount' => 1,
                'from' => '2177492073',
                'text' => 'سلام',
            ];

            $response = $sms_client->GetSmsPrice($parameters)->GetSmsPriceResult;
            if ($response > 0) {
                return $response;
            }
            return 'خطا در دریافت اعتبار پنل پیامک !';
        } catch (Exception|Error|HttpException $e) {
            Notification::make()
                ->title('خطا در دریافت اعتبار پنل پیامک !')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
