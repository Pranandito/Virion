<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\HumidaConfig;
use App\Models\SiramConfig;
use App\Models\Device;
use App\Models\DevicesLog;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function editMode(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|integer',
            'device_id' => 'required|integer',
            'mode' => 'required|in:Auto,On,Off',
            'name' => 'required|string'
        ]);

        $deviceCheck = Device::where('id', $validated['device_id'])
            ->where('owner_id', $validated['owner_id'])->first();

        if ($deviceCheck == null) {
            return response()->json([
                'status' => 'gagal',
                'message' => 'Pemilik device tidak valid'
            ]);
        }

        $configModels = [
            'Siram' => SiramConfig::class,
            'Humida' => HumidaConfig::class,
            // 'feed' => FeedConfig::class
        ];

        if (isset($configModels[$deviceCheck->virdi_type])) {
            $update = $configModels[$deviceCheck->virdi_type]::where('device_id', $validated['device_id'])
                ->update(['mode' => $validated['mode']]);
        }

        $log = $this->logging($validated['device_id'], 'mode', $validated['name']);

        return response()->json([
            'status_update' => $update,
            'status_logging' => $log
        ]);
    }

    public function editThreshold(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|integer',
            'device_id' => 'required|integer',
            'upper_threshold' => 'required|decimal:2',
            'lower_threshold' => 'required|decimal:2',
            'name' => 'required|string'
        ]);

        $deviceCheck = Device::where('id', $validated['device_id'])
            ->where('owner_id', $validated['owner_id'])->first();

        if ($deviceCheck == null) {
            return response()->json([
                'status' => 'gagal',
                'message' => 'Pemilik device tidak valid'
            ]);
        }

        $configModels = [
            'Siram' => SiramConfig::class,
            'Humida' => HumidaConfig::class,
            // 'feed' => FeedConfig::class
        ];

        if (isset($configModels[$deviceCheck->virdi_type])) {
            $update = $configModels[$deviceCheck->virdi_type]::where('device_id', $validated['device_id'])
                ->update([
                    'upper_threshold' => $validated['upper_threshold'],
                    'lower_threshold' => $validated['lower_threshold']
                ]);
        }

        $log = $this->logging($validated['device_id'], 'config', $validated['name']);

        return response()->json([
            'status_update' => $update,
            'status_logging' => $log
        ]);
    }

    public function logging($device_id, $activity, $data)
    {
        $message = [
            'config' => "Konfigurasi $data telah diubah",
            'mode' => "Mode penggunaan $data telah diubah",
            'name' => "Nama Perangkat $data berhasil diubah",
            'online' => "Koneksi perangkat $data terhubung",
            'offline' => "Koneksi perangkat $data terputus",
        ];

        $log = DevicesLog::insert([
            'device_id' => $device_id,
            'activity' => $message[$activity],
        ]);

        return $log;
    }
}
