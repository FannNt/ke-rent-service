<?php

namespace App\Classes;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    public static function rollback($e, $message ="Something went wrong! Process not completed"){
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message ="Something went wrong! Process not completed"){
        Log::info($e);
        throw new HttpResponseException(response()->json(["message"=> $message], 500));
    }
    public static function sendErrorResponse($message, $code = 500)
    {
        $response = [
            'code' => $code,
            'success' => false,
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response,$code);
    }

    public static function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'code' => $code,
            'success' => true,
            'data' => $result,
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response,$code);
    }

    public static function sendResponseWithToken($result,$token,$message,$code = 200)
    {
        $response = [
            'code' => $code,
            'success' => true,
            'data' => $result,
            'token' => $token,
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()
            ->json($response, $code)
            ->header('Authorization', 'Bearer ' . $token);

    }
}
