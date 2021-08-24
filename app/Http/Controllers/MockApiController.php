<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MockApiController extends Controller
{
    //

    public function google(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'client_token' => 'required|string',
            'reciept' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failure',
                'error' => 'Invalid Request',
            ]);
        }

        $lastCharacter = intval(substr($request->post('reciept'), -1));
        $status = $lastCharacter % 2 === 0 ? false : true;

        $response = [
            'status' => $status,
        ];

        if ($status) {
            $response['expire-date'] = Carbon::now()->addDays(rand(1, 365))->utcOffset(-360)->format('Y-m-d H:i:s');
        }

        return response()->json($response);
    }

    public function apple(Request $request)
    {
        //If client_token is not stable we have change this request with device_id or udid
        $validator = Validator::make(request()->all(), [
            'client_token' => 'required|string',
            'reciept' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failure',
                'error' => 'Invalid Request',
            ]);
        }

        $lastCharacter = intval(substr($request->post('reciept'), -1));
        $status = $lastCharacter % 2 === 0 ? false : true;

        $response = [
            'status' => $status,
        ];

        if ($status) {
            $response['expire-date'] = Carbon::now()->addDays(rand(1, 365))->utcOffset(-360)->format('Y-m-d H:i:s');
        }

        return response()->json($response);
    }
}
