<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function checkMock(Request $request)
    {

        $validationRules = [
            'client_token' => 'required|max:36|exists:devices,client_token',
            'reciept' => 'required',
            'os' => 'required',
            'app_id' => 'required|exists:applications,id'
        ];


        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $validationErrors = $validator->failed();

            if (isset($validationErrors['client_token']['Exists'])) {
                return response()->json([
                    'status' => 'failure',
                    'error' => 'Client Token Doesnt Exists',
                ]);
            }

            if (isset($validationErrors['app_id']['Exists'])) {
                return response()->json([
                    'status' => 'failure',
                    'error' => 'Application Doesnt Exists',
                ]);
            }

            return response()->json([
                'status' => 'failure',
                'error' => 'Invalid Request',
            ]);
        }

        $recieptData = $request->only(['client_token', 'reciept', 'app_id', 'os']);

        if (Subscription::where('client_token', $recieptData['client_token'])->where('app_id', $recieptData['app_id'])->exists()) {

            $subscription = Subscription::where('client_token', $recieptData['client_token'])->where('app_id', $recieptData['app_id'])->first();
            return response()->json([
                'status' => 'success',
                'subscription' => ([
                    'client_token' => $subscription->client_token,
                    'app_id' => $subscription->app_id,
                    'expire_date' => $subscription->expire_date,
                ]),
            ]);
        }

        $requestResponse = Http::post('http://nginx:80/api/google-mock-api', [
            'client_token' => $recieptData['client_token'],
            'reciept' => $recieptData['reciept'],
        ]);

        $response['status'] = $requestResponse['status'];

        if ($requestResponse['status']) {
            Subscription::create([
                'app_id' => $recieptData['app_id'],
                'client_token' => $recieptData['client_token'],
                'expire_date' => $requestResponse['expire-date'],
                'os' => $recieptData['os']
            ]);

            $response['client_token'] = $recieptData['client_token'];
            $response['expire_date'] = $requestResponse['expire-date'];
        }

        return response()->json($response);
    }
}
