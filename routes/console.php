<?php

use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\HumidaSensor;
use App\Models\SiramSensor;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\API\V1\ConfigController;
use App\Models\FeedConfig;
use App\Models\FeedStorage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::call(function () {
    $devices = Device::select('id', 'status', 'virdi_type', 'name')->get();

    $sensorModels = [
        'Siram' => SiramSensor::class,
        'Humida' => HumidaSensor::class,
        'Aqua' => AquaSensor::class,
        'Feed' => FeedStorage::class
    ];

    $data = [];
    $last = [];
    $status = 0;
    $logger = new ConfigController();

    foreach ($devices as $device) {
        $lastSeen = $sensorModels[$device->virdi_type]::where('device_id', $device->id)->select('device_id', 'created_at')->latest()->first();

        if ($lastSeen) {
            $diff = $lastSeen->created_at->diffInMinutes(now());
            if ($diff < 5 && $device->status == 0) {
                $update = Device::find($device->id);
                $status = 1;
                $update->status = $status;
                $update->save();
                $logging = $logger->logging($device->id, 'online', $device->name);
            } elseif ($diff > 5 && $device->status == 1) {
                $update = Device::find($device->id);
                $status = 0;
                $update->status = $status;
                $update->save();
                $logging = $logger->logging($device->id, 'offline', $device->name);
            }
            $data[$lastSeen->device_id] = strtoupper($status);
            $last[$lastSeen->device_id] = strtoupper($diff);
        }
    }

    return response()->json([$data, $last]);
})->everyMinute();

Schedule::call(function () {
    $reset_daily = FeedConfig::query()->update(['success_daily' => 0, 'manual_daily' => 0]);

    return $reset_daily;
})->daily();


Schedule::call(function () {
    $reset_weekly = FeedConfig::query()->update(['success_weekly' => 0, 'manual_weekly' => 0]);

    return $reset_weekly;
})->weekly();
