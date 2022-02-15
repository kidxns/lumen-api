<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public function responseSuccessWithData($msg, $data)
    {
        return response()->json(['data' => $data, 'message' => $msg, 'status' => true, 'code' => 200], 200);
    }
}
