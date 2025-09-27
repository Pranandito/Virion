<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\HumidaSensor;
use App\Models\SiramSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function getConfig($serialNumber)
    {
        $virdiType = $serialNumber[0];
        $device_id = (int) explode('-', $serialNumber)[1];

        $configTable = [
            'S' => 'siram_config',
            'H' => 'humida_config',
            // 'F' => FeedConfig::class
        ];

        // $config = $configModels[$virdiType]::where('device_id', $device_id)->first();

        $config = Device::select('name', 'status', 'id')->with([$configTable[$virdiType], 'devices_logs'])->where('id', $device_id)->first();

        return response()->json([
            'device' => $config
        ]);
    }

    public function dashboardData($virdiType, $device_id, $periode = "now")
    {
        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
        ];

        switch ($periode) {
            case "now":
                $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->latest()->first();
                break;

            case "daily":
                $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->whereDate('created_at', Carbon::today())->get();
                break;

            case "weekly":
                $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->whereBetween('created_at', [now()->subWeek(), now()])->get();
                break;

            case "monthly":
                $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->whereBetween('created_at', [now()->subMonth(), now()])->get();
                break;
        }
        return response()->json([
            'data' => $sensorData,
            'periode' => $periode,
        ]);
    }

    public function dataStream($virdiType, $device_id)
    {
        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
        ];

        $date = $sensorModels[$virdiType]::where('device_id', $device_id)->selectRaw('DATE(created_at) as date')->get();

        return response()->json($date);
    }

    public function avgData($virdiType, $device_id)
    {
        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
        ];

        $colomnList = [
            'Siram' => ['temperature', 'humidity'],
            'Humida' => ['temperature', 'humidity'],
            'Aqua' => ['temperature', 'turbidity', 'pH', 'oxygen'],
        ];

        $latest = $sensorModels[$virdiType]::where('device_id', $device_id)->latest()->first();

        $baseQuerryDaily = $sensorModels[$virdiType]::whereDate('created_at', $latest->created_at->toDateString())->where('device_id', $device_id);
        $baseQuerryWeekly = $sensorModels[$virdiType]::whereBetween('created_at', [$latest->created_at->subWeek(), $latest->created_at])->where('device_id', $device_id);

        foreach ($colomnList[$virdiType] as $kolom) {
            $weekly = $baseQuerryWeekly->addSelect(
                DB::raw(
                    "AVG($kolom) as avg_weekly_$kolom"
                )
            )->first();

            $daily = $baseQuerryDaily->addSelect(
                DB::raw(
                    "AVG($kolom) as avg_daily_$kolom"
                )
            )->first();
        }

        return response()->json([
            'avg_daily' => $daily,
            'avg_weekly' => $weekly,
            'latest' => $latest,
        ]);
    }
}
