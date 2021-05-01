<?php


namespace App\Services\AEON;


use App\Traits\HasResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class AeonService
{
    use HasResponse;
    /**
     * Holds the open socket connection
     */
    public static $socket = false;

    /**
     * Opens a persistant socket connection
     */
    public function __construct()
    {
        // Connect
        if (!static::$socket = pfsockopen(config('services.aeon.ip_address'), config('services.aeon.port'), $errno, $errstr)) {
            return $this->serverErrorResponse("Unable to connect to " . config('services.aeon.ip_address') . ' on port ' . config('services.aeon.port'));
        }

        // FuelPHP logger
        Log::debug('SOCKET OPEN');
    }

    /**
     * Writes to the socket
     */
    public function write($xmlPostString)
    {
        // FuelPHP logger
        Log::debug('SOCKET: ' . trim($xmlPostString));
        fwrite(static::$socket, $xmlPostString);
    }

    /**
     * Gets the results of the socket
     */
    public function get()
    {
        //dd(fgets(static::$socket, 1024));
        while ($buffer = fgets(static::$socket, 1024)) {
            $response = isset($response) ? $response . $buffer : $buffer;
            if (preg_match('/<\/response>/', $buffer)) {
                break;
            }
        }

        // Check for complete response
        if (isset($response)) {

            // FuelPHP logger
            Log::debug('SOCKET: ' . trim($response));

            // Check for error code
            if (preg_match('/<EventCode>(.*)<\/EventCode>/', $response, $error)) {
                if ($error[1] !== '0') {

                    // Find error code and text
                    preg_match('/<ErrorCode>(.*)<\/ErrorCode>/', $response, $errorCode);
                    preg_match('/<ErrorText>(.*)<\/ErrorText>/', $response, $errorText);

                    // Throw exception
                    return $this->failedResponse($errorText[1]);
                }

            }

            return $response;
        } else {
            return $this->serverErrorResponse('No data returned from server');
        }
    }

    /**
     * Closes the socket
     */
    public function close()
    {
        fclose(static::$socket);

        static::$socket = false;

        // FuelPHP logger
        Log::debug('SOCKET CLOSED');
    }
}
