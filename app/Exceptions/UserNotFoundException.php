<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use illuminate\Http\JsonResponse;

class UserNotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response when UserNotFoundException is thrown.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'errors' => [
                'code' => 404,
                'title' => 'User Not Found',
                'detail' => 'Unable to locate the user with the given information.',
            ]
        ], 404);
    }
}
