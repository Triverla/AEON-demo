<?php


namespace App\Http\Actions;


use App\Services\AEON\AeonService;
use Illuminate\Support\Facades\Log;

class CreateAirtimeAction
{

    public function execute()
    {
        /*$aeon = new AeonService();
        $aeon->write($this->authenticationRequest());
        $response = $aeon->get();
        return $response;*/

        $sock=socket_create(AF_INET,SOCK_STREAM,0) or die("Cannot create a socket");
        socket_connect($sock,'102.134.128.70','7800') or die("Could not connect to the socket");
        socket_write($sock, 'kjkjkjk');
        Log::info('After Write');
        $read=socket_read($sock,1024);
        Log::info($read);
        echo $read;
        socket_close($sock);
    }

    private function authenticationRequest()
    {
        return '<request>
                <EventType>Authentication<</EventType>
                <event>
                 <UserPin>' . config('services.aeon.user_pin') . '</UserPin>
                <DeviceId>' . config('services.aeon.dev_id') . '</DeviceId>
                <DeviceSer>' . config('services.aeon.dev_ser') . '</DeviceSer>
                <TransType>AccountInfo</TransType>
                <Reference>' . 123459 . '</Reference>
                </event>
                </request>';
    }
}
