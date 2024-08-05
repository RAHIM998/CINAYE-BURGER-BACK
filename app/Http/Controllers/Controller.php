<?php

namespace App\Http\Controllers;

abstract class Controller
{

    protected function jsonResponse(bool $success, string $message = null, $data = [], int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }
}
