<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
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

    public function register(Request $request)
    {
        $validationRules = [
            'udid' => 'required|max:40|unique:devices',
            'os' => 'required',
            'app_id' => 'required|required|exists:applications,id',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $validationErrors = $validator->failed();

            if (isset($validationErrors['udid']['Unique'])) {
                return "bu udid var";
            }

            if (isset($validationErrors['app_id']['Exists'])) {
                return "böyle bir uygulama yok";
            }

            return "eksiz parametre";
        }

        $clientToken = $this->generateToken();
        $deviceData = $request->only(['udid', 'app_id', 'os', 'os_version', 'device_model']);
        $deviceData['client_token'] = $clientToken;
        $device = Device::create($deviceData);
        return ['device' => $device];
    }

    public function checkMock(){
        $response = Http::post('/api/google-mock-api', [
            'client_token' => 'c8595464-bd53-481f-be7b-3e6de0fdebad',
            'reciept' => 'denemedenem1',
        ]);
        return $response;
    }

    private function generateToken()
    {
        return Str::uuid()->toString();
    }
}
