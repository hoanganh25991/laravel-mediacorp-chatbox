<?php
namespace App\Traits;

use Response;

define("WARNING", "we still not handle this situation");

trait ApiResponse{
    public function res($data, $statusMsg = "success", $statusCode = 200){
        /** change format of response */
        /** if 200, only return data */
        if($statusCode == 200){
            return Response::json($data)->setEncodingOptions(JSON_NUMERIC_CHECK);
        }

        /** for error situation, return code|msg|data */
        return Response::json([
            'statusCode' => $statusCode,
            'statusMsg' => $statusMsg,
            'data' => $data
        ], $statusCode)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }
}
