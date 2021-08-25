<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\ApiService\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SubscriptionController extends Controller
{
    public function checkMock(Request $request)
    {
        //If client_token is not stable we have change this request with device_id or udid
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
                return Response::error('Client Token Doesnt Exists');
            }

            if (isset($validationErrors['app_id']['Exists'])) {
                return Response::error('Application Doesnt Exists');
            }

            return Response::error('Invalid Request');
        }

        $recieptData = $request->only(['client_token', 'reciept', 'app_id', 'os']);

        $subscription = Subscription::where('client_token', $recieptData['client_token'])
            ->where('app_id', $recieptData['app_id'])
            ->first();

        if ($subscription) {
            return Response::success(['subscription' => $subscription]);
        }

        //If client_token is not stable we have change this request with device_id or udid
        $requestResponse = Http::post('http://nginx:80/api/google-mock-api', [
            'client_token' => $recieptData['client_token'],
            'reciept' => $recieptData['reciept'],
        ]);

        $response['status'] = $requestResponse['status'];

        if ($requestResponse['status']) {
            $subscription = Subscription::create([
                'app_id' => $recieptData['app_id'],
                'client_token' => $recieptData['client_token'],
                'expire_date' => $requestResponse['expire-date'],
                'os' => $recieptData['os']
            ]);

            Cache::put("subscription:" . $recieptData['client_token'] . ":" . $recieptData['app_id'], $subscription);

            $response['client_token'] = $recieptData['client_token'];
            $response['expire_date'] = $requestResponse['expire-date'];
        }

        return Response::success($response);
    }

    public function checkSubscription(Request $request)
    {
        //If client_token is not stable we have change this request with device_id or udid
        $validationRules = [
            'client_token' => 'required|max:36|exists:devices,client_token',
            'app_id' => 'required|exists:applications,id'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $validationErrors = $validator->failed();

            if (isset($validationErrors['client_token']['Exists'])) {
                return Response::error('Client Token Doesnt Exists');
            }

            return Response::error('Invalid Request');
        }

        $requestData = $request->only(['client_token', 'app_id']);
        $subscription = Subscription::where('client_token', $requestData['client_token'])->where('app_id', $requestData['app_id'])
            ->whereDate('expire_date', '>', Carbon::now()->utcOffset(-360)->format('Y-m-d H:i:s'))
            ->first();

        if ($subscription) {
            return Response::success(['subscription' => $subscription]);
        } else {
            return Response::error('There is no subscription');
        }

    }

}
