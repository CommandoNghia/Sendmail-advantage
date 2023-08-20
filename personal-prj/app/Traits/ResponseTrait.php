<?php

namespace App\Traits;

use App\Exceptions\CustomValidationException;
use App\Utils\HttpCodeTransform;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

trait ResponseTrait
{
    /**
     * @param mixed $data
     * @param int   $status
     *
     * @return JsonResponse
     */
    public function success(mixed $data, int $status = 200): JsonResponse
    {
        if (isset($data->resource) && $data instanceof AnonymousResourceCollection) {
            $data = $data->resource;
        }

        if ($data instanceof LengthAwarePaginator) {
            $paginationData = $data->toArray();
            return response()->json([
                'message' => HttpCodeTransform::STATUS_CODE[$status],
                'data' => $data->items(),
                'pagination' => array_diff_key($paginationData, array_flip(['data', 'links'])),
                'links' => $data->linkCollection()->toArray()
            ], $status);
        }

        return response()->json(['data' => $data], $status);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    public function successWithMessage(string $message = 'OK'): JsonResponse
    {
        return response()->json([
            'data' => [
                'message' => $message,
                'code' => 'OK'
            ]
        ]);
    }

    /**
     * @param string $errorCode
     * @param string $message
     * @param int    $status
     * @param array  $error
     *
     * @return JsonResponse
     */
    public function error(string $errorCode, string $message, int $status = 500, array $error = []): JsonResponse
    {
        $res = [
            'error' => [
                'status_code' => $status,
                'code' => HttpCodeTransform::STATUS_CODE[$status],
                'message' => $message,
                'error_code' => $errorCode,
                'errors' => $error
            ],
        ];

        return response()->json($res, $status);
    }

    /**
     * @param Throwable|Exception $e
     * @param int                 $status
     * @param array               $error
     *
     * @return JsonResponse
     */
    public function errorException(Throwable|Exception $e, int $status = 500, array $error = []): JsonResponse
    {
        $res = [
            'error' => [
                'status_code' => $status,
                'code' => HttpCodeTransform::STATUS_CODE[$status],
                'message' => $e->getMessage(),
                'error_code' => "Err-$status",
                'errors' => $error
            ],
        ];

        return response()->json($res, $status);
    }


    /**
     * @param HttpException $e
     *
     * @return JsonResponse
     */
    public function httpException(HttpException $e): JsonResponse
    {
        return $this->errorException($e, $e->getStatusCode());
    }

    /**
     * @param CustomValidationException $validator
     *
     * @return JsonResponse
     */
    public function validationException(CustomValidationException $validator): JsonResponse
    {
        $errors = [];
        foreach ($validator->errors() as $field => $message) {
            $errors[] = [
                'field' => $field,
                'error_code' => Arr::last($message),
                'message' => Arr::first($message)
            ];
        }

        return $this->errorException($validator, $validator->status, $errors);
    }
}
