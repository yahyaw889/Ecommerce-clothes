<?php

namespace App\Traits;

trait ResponseTrait
{
    public function ok($data = null, $message = 'OK' ,$status = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], 200);
    }
    public function responseJson($data , $message = 'OK' , $code = 200){
        return response()->json([
            'status' => $code == 200 ? 'success' : ( $code == 404 ? 'error' : 'warning'),
            'message' => $message,
            'data' => $data
        ], $code);
    }
    public function ErrorResponse($message = 'Error', $code = 500 , $errors = null){
        return response()->json([
            'status' => $code,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    public function success($data = null,  $code = 200 ,$message = null )
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
