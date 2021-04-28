<?php


namespace App\Http\Actions;


use App\Http\Resources\AeonResource;
use App\Services\AEON\AeonService;
use Illuminate\Support\Facades\Log;

class CreateAirtimeAction
{
    protected $sessionId;

    public function execute()
    {
        $aeon = new AeonService();
        $aeon->write($this->authenticationRequest());
        $result = $aeon->get();
        // Find session id field
        preg_match('/<SessionId>(.*)<\/SessionId>/', $result, $this->sessionId);
        Log::info($this->voucherListRequest());
        $aeon->write($this->voucherListRequest());
        $result2 = $aeon->get();
        $xmlResponse = simplexml_load_string($result2);
        $response = json_decode(json_encode($xmlResponse, JSON_PRETTY_PRINT), 1);
        return new AeonResource($response);
    }

    private function authenticationRequest(): string
    {
        return '<request><EventType>Authentication</EventType><event><UserPin>' . config('services.aeon.user_pin') . '</UserPin><DeviceId>' . config('services.aeon.dev_id') . '</DeviceId><DeviceSer>' . config('services.aeon.dev_ser') . '</DeviceSer><TransType>Vodacom</TransType></event></request>' . PHP_EOL;
    }

    private function voucherListRequest(): string
    {
        return '<request><sessionId>' . $this->sessionId[0] . '</sessionId><EventType>MNOValidation</EventType><event><Reference>abcd1234</Reference><PhoneNumber>0820012345</PhoneNumber><Amount>20.0</Amount><ProductCode>0</ProductCode></event></request>' . PHP_EOL;
    }
}
