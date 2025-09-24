<?php

// namespace App\Http\Controllers;

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\DevicesLog;
use App\Models\FeedConfig;
use App\Models\HumidaConfig;
use App\Models\HumidaSensor;
use App\Models\SiramConfig;
use App\Models\SiramSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function registerDevice(Request $request)
    {
        $validated = $request->validate([
            // 'name' => 'required|string',
            // 'serial_number' => 'required|string',
            'firmware_version' => 'required|string',
            // 'status' => 'required|boolean',
            'virdi_type' => 'required|in:Feed,Humida,Aqua,Siram'
        ]);

        $lastDevice = Device::latest()->first();
        $lastId = $lastDevice->id ?? 0;
        $deviceId = str_pad($lastId + 1, 5, "0", STR_PAD_LEFT);

        $validated['name'] = $validated['virdi_type'] . "-" . $deviceId;
        $validated['serial_number'] = $validated['virdi_type'][0] . "-" . $deviceId;
        $validated['status'] = 0;

        $device = Device::create($validated);

        $configModels = [
            'Humida' => HumidaConfig::class,
            'Siram' => SiramConfig::class,
            'Feed' => FeedConfig::class,

            // 'Aqua' => AquaConfig::class,
        ];

        if (isset($configModels[$validated['virdi_type']])) {
            $configModels[$validated['virdi_type']]::insert([
                'device_id' => $device->id
            ]);
        }

        return response()->json($device);
    }

    public function addOwner(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|integer',
            'serial_number' => 'required|string'
        ]);


        $device = Device::where('serial_number', $validated['serial_number'])->first();

        if (!$device) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'Serial number salah atau alat belum diregistrasikan',
            ]);
        }

        if ($device->owner_id != null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'Alat sudah dimiliki sesorang',
            ]);
        }

        $owner = Device::where('serial_number', $validated['serial_number'])->update(['owner_id' => $validated['owner_id']]);

        return response()->json($owner);
    }

    public function editDevice(Request $request)
    { // edit nama atau hapus owner
        $validated = $request->validate([
            'devices' => 'required|array',
            'devices.*.owner_id' => 'required|integer',
            'devices.*.device_id' => 'required|integer',
            'devices.*.name' => 'sometimes|nullable|string',
        ]);

        $results = [];

        foreach ($validated['devices'] as $device_data) {
            $device = Device::findOrFail($device_data['device_id']);

            if ($device->owner_id !== $device_data['owner_id']) {
                $results[] = [
                    'device_id' => $device->id,
                    'status' => 0,
                    'message' => 'Device bukan milik anda'
                ];
                continue;
            }

            if ($device_data['name'] == null) {
                $device->update(['owner_id' => null]);
                $results[] = [
                    'device_id' => $device->id,
                    'status' => 1,
                    'message' => 'Kepemilikan device berhasil dihapus'
                ];
            } else {
                $device->update(['name' => $device_data['name']]);
                $results[] = [
                    'device_id' => $device->id,
                    'status' => 1,
                    'message' => 'Nama device berhasil diubah'
                ];
            }
        }

        return response()->json([
            'data' => $results
        ]);
    }

    public function tss()
    {
        $data = Device::find(18)->first();

        $selisih = $data->created_at->diffInMinutes(Carbon::now());

        if ($selisih > 5) {
            $selisih = 2;
        }

        return response()->json($data->created_at == Carbon::today());
    }

    public function statusCheck()
    {
        $devices = Device::select('id', 'status', 'virdi_type')->get();

        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
        ];

        $data = [];
        $last = [];
        $status = 0;

        foreach ($devices as $device) {
            $lastSeen = $sensorModels[$device->virdi_type]::where('device_id', $device->id)->select('device_id', 'created_at')->latest()->first();

            if ($lastSeen) {
                $diff = $lastSeen->created_at->diffInMinutes(now());
                if ($diff < 5 && $device->status == 0) {
                    $update = Device::find($device->id);
                    $status = 1;
                    $update->status = $status;
                    $update->save();
                } elseif ($diff > 5 && $device->status == 1) {
                    $update = Device::find($device->id);
                    $status = 0;
                    $update->status = $status;
                    $update->save();
                }
                $data[$lastSeen->device_id] = strtoupper($status);
                $last[$lastSeen->device_id] = strtoupper($diff);
            }
        }

        return response()->json([$data, $last]);
    }
}
