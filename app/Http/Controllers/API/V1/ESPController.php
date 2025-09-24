<?php

namespace App\Http\Controllers\API\V1;

use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\FeedConfig;
use App\Models\FeedStorage;
use App\Models\HumidaConfig;
use App\Models\HumidaSensor;
use App\Models\SiramConfig;
use App\Models\SiramSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ESPController
{

    private function mapValue($value, $fromLow, $fromHigh, $toLow, $toHigh)
    {
        return ($value - $fromLow) * ($toHigh - $toLow) / ($fromHigh - $fromLow) + $toLow;
    }

    public function store(Request $request, $virdiType, $device_id)
    {
        $validated = $request->validate([
            'temperature' => 'sometimes|required|decimal:2',  // kalau 44.40 kirim string "44.40"
            'humidity' => 'sometimes|required|decimal:2',
            'turbidity' => 'sometimes|required|decimal:2',
            'pH' => 'sometimes|required|decimal:2',
            'oxygen' => 'sometimes|required|decimal:2',
            'batt_voltage' => 'sometimes|required|decimal:3',
            'storage' => 'sometimes|required|decimal:1',
            'refill' => 'sometimes|required|boolean',
        ]);

        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
            'Feed' => FeedStorage::class
        ];

        $lastData = $sensorModels[$virdiType]::where('device_id', $device_id)
            ->whereDate('created_at', Carbon::today())
            ->latest()->first();

        if (empty($lastData)) {
            $validated['online_duration'] = Carbon::createFromTimestamp(120);
            $lastOnDuration = 0;
            $selisihWaktu = 0;
        } else {
            $lastOnDuration = Carbon::parse($lastData->online_duration);

            $selisihWaktu = $lastData->created_at->diffInSeconds(Carbon::now());
            if ($selisihWaktu > 300 || $selisihWaktu == 0) {
                $selisihWaktu = 120;
            }

            $validated['online_duration'] = $lastOnDuration->addSecond($selisihWaktu);
        }

        if (!empty($validated['batt_voltage'])) {
            //mapping
            $validated['batt_capacity'] = $this->mapValue($validated['batt_voltage'], 9, 12.6, 0, 99.99);
        }

        $refill = 2;
        if (!empty($validated['refill'])) {
            if ($validated['refill'] == true) {
                $refill = FeedConfig::where('device_id', $device_id)->update(['last_refill' => now()]);
            }
        }

        unset($validated['refill']);

        $validated['device_id'] = $device_id;
        $store = $sensorModels[$virdiType]::insert($validated);

        return response()->json([
            'validated' => $validated,
            'data' => $lastData,
            'lastOn' => $lastOnDuration,
            'status_lastReffill' => $refill,
            'selisih' => $selisihWaktu,
            'lastData' => empty($lastData),
            'status' => $store
        ]);
    }

    public function getConfig($virdiType, $device_id)
    {
        $configModels = [
            'Siram' => SiramConfig::class,
            'Feed' => FeedConfig::class,
            'Humida' => HumidaConfig::class,
        ];

        if ($virdiType == "Feed") {
            $config = $configModels[$virdiType]::select('pelet_size', 'mode')
                ->where('device_id', $device_id)->first();
        } else {
            $config = $configModels[$virdiType]::select('upper_threshold', 'lower_threshold', 'mode')
                ->where('device_id', $device_id)->first();
        }

        return response()->json([
            'config' => $config
        ]);
    }

    public function tes()
    {
        $data = Device::find(18)->first();

        $selisih = $data->created_at->diffInMinutes(Carbon::now());

        if ($selisih > 5) {
            $selisih = 2;
        }

        return response()->json($data->created_at == Carbon::today());
    }
}
