<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\ApiService\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class DeviceController extends Controller
{

    public function register(Request $request)
    {
        $validationRules = [
            'udid' => 'required|max:40|unique:devices',
            'os' => 'required',
            'app_id' => 'required|required|exists:applications,id',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        $deviceData = $request->only(['udid', 'app_id', 'os', 'os_version', 'device_model']);

        $clientToken = $this->generateToken();
        $deviceData['client_token'] = $clientToken;

        if ($validator->fails()) {
            $validationErrors = $validator->failed();

            if (isset($validationErrors['udid']['Unique'])) {
                $device = Device::where('udid', $deviceData['udid'])->first();
                Cache::put('device:' . $deviceData['udid'] . ":" . $deviceData['app_id'], $device);

                return Response::success(['device' => $device]);
            }

            if (isset($validationErrors['app_id']['Exists'])) {
                return Response::error('This device already registered this app.');
            }

            return Response::error('Invalid Request');
        }

        $device = Device::create($deviceData);
        Cache::put('device:' . $deviceData['udid'] . ":" . $deviceData['app_id'], $device);

        return ['device' => $device];
    }

    private function generateToken()
    {
        return Str::uuid()->toString();
    }

}
