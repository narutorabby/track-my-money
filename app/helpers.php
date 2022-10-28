<?php

if(!function_exists("successResponse")){
    function successResponse($msg, $data = null) {
        return response()->json([
            'response' => "success",
            'message' => $msg,
            'data' => $data
        ], 200, [], JSON_NUMERIC_CHECK);
    }
}

if(!function_exists("errorResponse")){
    function errorResponse($msg, $data = null) {
        return response()->json([
            'response' => "error",
            'message' => $msg,
            'data' => $data
        ], 200, [], JSON_NUMERIC_CHECK);
    }
}

if(!function_exists("warningResponse")){
    function warningResponse($msg, $data = null, $errCode = 200) {
        return response()->json([
            'response' => "warning",
            'message' => $msg,
            'data' => $data
        ], $errCode, [], JSON_NUMERIC_CHECK);
    }
}

if(!function_exists("getChartColor")){
    function getChartColor()
    {
        return '#' . str_pad(dechex(rand(0x111111, 0xEEEEEE)), 6, 0, STR_PAD_LEFT) . 'AA';
    }
}
