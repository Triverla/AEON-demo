<?php


namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

trait HasResponse
{
    /**
     * Set failed response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function failedResponse($message, array $data = []): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.failed'),
            'statuscode' => config('aeon.code.failed'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, 301);
    }

    /**
     * Set success response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function successResponse($message, array $data = []): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.success'),
            'statuscode' => config('aeon.code.success'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response);
    }

    /**
     * Set success response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function successResponseWithResource($message, object $data = null): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.success'),
            'statuscode' => config('aeon.code.success'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response);
    }


    /**
     * Set server error response
     *
     * @param $message
     * @param Exception|null $exception
     * @return JsonResponse
     */
    public function serverErrorResponse($message, Exception $exception = null): JsonResponse
    {
        if ($exception !== null) {
            Log::error(
                "{$exception->getMessage()} on line {$exception->getLine()} in {$exception->getFile()}"
            );
        }

        $response = [
            'status' => config('aeon.status.failed'),
            'statuscode' => config('aeon.code.server_error'),
            'message' => $message
        ];

        return Response::json($response, 500);
    }

    /**
     * Set not found response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function notFoundResponse($message, array $data = []): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.failed'),
            'statuscode' => config('aeon.code.not_found'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, 404);
    }

    /**
     * Set not allowed response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function notAllowedResponse($message, array $data = []): JsonResponse
    {
        $response = [
            'status' =>config('aeon.status.failed'),
            'statuscode' => config('aeon.code.not_allowed'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, 403);
    }

    /**
     * Set form validation errors
     *
     * @param $errors
     * @param array $data
     * @return JsonResponse
     */
    public function formValidationResponse($errors, array $data = []): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.failed'),
            'statuscode' => config('aeon.code.failed'),
            'message' => 'Whoops. Validation failed',
            'validationErrors' => $errors,
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, 406);
    }

    /**
     * Set not exist response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function notExistResponse($message, array $data = []): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.failed'),
            'statuscode' => config('aeon.code.notexist'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, 406);
    }

    /**
     * Set exist response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function existsResponse($message, array $data = []): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.failed'),
            'statuscode' => config('aeon.code.exists'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, 406);
    }

    /**
     * Set network error response
     *
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    public function networkErrorResponse($message, array $data = []): JsonResponse
    {
        $response = [
            'status' => config('aeon.status.failed'),
            'statuscode' => config('aeon.code.network_error'),
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, 503);
    }
}
