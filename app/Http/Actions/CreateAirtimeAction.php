<?php


namespace App\Http\Actions;


use App\Http\Resources\AeonResource;
use App\Services\AEON\AeonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateAirtimeAction
{
    protected $sessionId, $reference, $request;

    public function execute()
    {
        //mock request
        $request = new \Illuminate\Http\Request([
            'productCode' => 1459,
            'transType' => 'MTNBundles',
            'phoneNumber' => '983001200001'
        ]);
        $this->reference = Str::random(16);
        $this->request = $request;
        $aeon = new AeonService();
        $aeon->write($this->authenticationRequest());
        $result = $aeon->get();
        return $result;
        /*// Find session id field
        preg_match('/<SessionId>(.*)<\/SessionId>/', $result, $this->sessionId);
        Log::info($this->voucherListRequest());
        $aeon->write($this->voucherListRequest());
        $result2 = $aeon->get();
        $xmlResponse = simplexml_load_string($result2);
        $response = json_decode(json_encode($xmlResponse, JSON_PRETTY_PRINT), 1);
        return new AeonResource($response);*/
    }

    //Sample Request
    private function authenticationRequest(): string
    {
        return '<request><EventType>DoBundleTopup</EventType><event><UserPin>'.config('services.aeon.user_pin').'</UserPin><DeviceId>'.config('services.aeon.dev_id').'</DeviceId><DeviceSer>'.config('services.aeon.dev_ser').'</DeviceSer><TransType>'.$this->request->transType.'</TransType><Reference>'.$this->reference.'</Reference><PhoneNumber>'.$this->request->phoneNumber.'</PhoneNumber><ProductCode>'.$this->request->productCode.'</ProductCode><Recon batchNumber="" terminalId="1" merchantId="2" transNumber="123" transReference="456" sysReference="789" transDateTime="2011-01-01 13:01:01" businessDate="2011-01-01" transType="01" accountNumber="" productId="0" amount="1000" authoriser="Supplier" productName="Test"/></event></request>'.PHP_EOL;
    }

    private function voucherListRequest(): string
    {
        return '<request><sessionId>' . $this->sessionId[0] . '</sessionId><EventType>MNOValidation</EventType><event><Reference>abcd1234</Reference><PhoneNumber>0820012345</PhoneNumber><Amount>20.0</Amount><ProductCode>0</ProductCode></event></request>' . PHP_EOL;
    }
}
