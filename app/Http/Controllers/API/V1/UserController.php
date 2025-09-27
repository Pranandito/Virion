<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\DevicesLog;
use App\Models\HumidaSensor;
use App\Models\SiramSensor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userProfile($email)
    {
        $user = User::select('id')
            ->with(['users_logs:last_location,last_login,user_id', 'devices:id,name,serial_number,virdi_type,status,owner_id'])
            ->where('email', $email)->first();


        $devices_id = $user->devices->pluck('id');
        $logs = DevicesLog::with('device:id,name')
            ->whereIn('device_id', $devices_id)
            ->latest()
            ->limit(6)
            ->get();

        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
        ];

        $dataStream = [];

        $dataSizeRow = [    // ukuran data pada tabel sensor per baris (byte)
            'Siram' => 2,
            'Humida' => 2,
            'Aqua' => 1,
        ];

        foreach ($user->devices as $device) {
            $dates = $sensorModels[$device->virdi_type]::where('device_id', $device->id)
                ->selectRaw('DATE(created_at) as dates')
                ->distinct()->limit(30)->get();

            foreach ($dates as $date) {
                $row = $sensorModels[$device->virdi_type]::select('id', 'created_at')
                    ->whereDate('created_at', $date->dates)->count();

                $dataSize = $dataSizeRow[$device->virdi_type] * $row;
                $dataStream[$date->dates] = strtoupper($dataSize);
            }
        }

        return response()->json([
            'user_data' => $user,
            'device_logs' => $logs,
            'data_stream' => $dataStream,
        ]);
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'profile_pict' => 'nullable|image|extensions:jpg,png',
            'password' => 'required|string',
            'nomor_hp' => 'required|string',
            'job' => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_pict')) {
            $pict_path = $request->file('profile_pict')->store('profile_picture', 'public');
        }

        $validated['profile_pict'] = $pict_path ?? null;

        $user = User::create($validated);

        return response()->json([
            'message' => 'data berhasil disimpan',
            'data' => $user
        ]);
    }
}
