<?php


namespace App\Services\AEON;


use Exception;
use Illuminate\Support\Facades\Log;

class AeonService
{
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
            throw new Exception("Unable to connect to " . config('services.aeon.ip_address') . ' on port ' . config('services.aeon.port'));
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
    {;
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
                    throw new \Exception('Error code ' . $errorCode[1] . ': ' . $errorText[1]);
                }

            }

            return $response;
        } else {
            throw new \Exception('No data returned from server');
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
