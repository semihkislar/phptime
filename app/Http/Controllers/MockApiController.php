<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class MockApiController extends Controller
{
    //

    public function google(Request $request){
        $data  = $request->all();
        if(isset($data['client_token']) && isset($data['reciept'])){
            $lastCharacter = intval(substr($data['reciept'], -1));
            $status =  $lastCharacter % 2 === 0 ? false : true;
            if ($status == true){
                $random = Carbon::today()->addDays(rand(0, 365));
                return array(
                    'status' => $status,
                    'expire-date' => Carbon::parse($random)->format('Y-m-d H:i:s')
                );
            }else{
                return array(
                    'status' => $status
                );
            }

        }
    }

    public function apple(Request $request){
        $data  = $request->all();
        if(isset($data['client_token']) && isset($data['reciept'])){
            $lastCharacter = intval(substr($data['reciept'], -1));
            $status =  $lastCharacter % 2 === 0 ? false : true;
            if ($status == true){
                $random = Carbon::today()->addDays(rand(0, 365));
                return array(
                    'status' => $status,
                    'expire-date' => Carbon::parse($random)->format('Y-m-d H:i:s')
                );
            }else{
                return array(
                    'status' => $status
                );
            }

        }
    }
}
