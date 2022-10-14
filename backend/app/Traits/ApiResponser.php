<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{

    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = null, $code)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }

    protected function unauthorizedResponse($message = null)
    {
        return response()->json([
            'status' => 'Unauthorized',
            'message' => $message,
            'data' => null
        ], 401);
    }

    protected function fieldErrorResponse($json = null, $code = 400)
    {
        return response()->json([
            'status' => 'FieldError',
            'message' => 'Some fields were incorrect',
            'errors' => $json
        ], $code);
    }

}