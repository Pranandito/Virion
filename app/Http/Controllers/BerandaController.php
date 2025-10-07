<?php

namespace App\Http\Controllers;

use App\Models\AquaSensor;
use App\Models\DevicesLog;
use App\Models\FeedStorage;
use App\Models\HumidaSensor;
use App\Models\SiramSensor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function create()
    {
        $user = User::select('id')
            ->with([
                'users_logs:last_location,last_login,user_id',
                'devices' => function ($query) {
                    $query->select('id', 'name', 'serial_number', 'virdi_type', 'status', 'owner_id')->orderBy('status', 'desc');
                }
                // 'devices:id,name,serial_number,virdi_type,status,owner_id'
            ])
            ->where('email',  Auth::user()->email)->first();


        $devices_id = $user->devices->pluck('id');
        $logs = DevicesLog::with('device:id,name,virdi_type,serial_number')
            ->whereIn('device_id', $devices_id)
            ->latest()
            ->limit(6)
            ->get();

        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
            'Feed' => FeedStorage::class,
        ];

        $iconMap = [
            'Humida' => 'humida-logo',
            'Siram' => 'siram-logo',
            'Feed' => 'feed-logo',
        ];

        return view('dashboard', compact('user', 'logs', 'iconMap'));
    }

    // public function dataStream(){
    //     $dataStream = [];

    //     $dataSizeRow = [    // ukuran data pada tabel sensor per baris (byte)
    //         'Siram' => 2,
    //         'Humida' => 2,
    //         'Aqua' => 1,
    //         'Feed' => 1,
    //     ];

    //     foreach ($user->devices as $device) {
    //         $dates = $sensorModels[$device->virdi_type]::where('device_id', $device->id)
    //             ->selectRaw('DATE(created_at) as dates')
    //             ->distinct()->limit(30)->get();

    //         foreach ($dates as $date) {
    //             $row = $sensorModels[$device->virdi_type]::select('id', 'created_at')
    //                 ->whereDate('created_at', $date->dates)->count();

    //             $dataSize = $dataSizeRow[$device->virdi_type] * $row;
    //             $dataStream[$date->dates] = strtoupper($dataSize);
    //         }
    //     }
    // }
}
