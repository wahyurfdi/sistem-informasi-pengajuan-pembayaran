<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseJson($status='OK', $message='', $results=[], $statusCode=200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'results' => $results
        ], $statusCode);
    }
}
