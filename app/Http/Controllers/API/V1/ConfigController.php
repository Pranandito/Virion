<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\HumidaConfig;
use App\Models\SiramConfig;
use App\Models\Device;
use App\Models\DevicesLog;
use App\Models\FeedConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function editMode(Request $request)
    {
        $validated = $request->validate([
            // 'owner_id' => 'required|integer',
            'device_id' => 'required|integer',
            'mode' => 'required|in:Auto,On,Off',
            'name' => 'required|string',
            'virdi_type' => 'required|string',
        ]);

        // $deviceCheck = Device::where('id', $validated['device_id'])
        //     ->where('owner_id', $validated['owner_id'])->first();

        // if ($deviceCheck == null) {
        //     return response()->json([
        //         'status' => 'gagal',
        //         'message' => 'Pemilik device tidak valid'
        //     ]);
        // }

        $configModels = [
            'Siram' => SiramConfig::class,
            'Humida' => HumidaConfig::class,
            'Feed' => FeedConfig::class
        ];

        $update = $configModels[$validated['virdi_type']]::where('device_id', $validated['device_id'])
            ->update(['mode' => $validated['mode']]);

        $log = $this->logging($validated['device_id'], 'mode', $validated['name']);

        // return response()->json([
        //     'status_update' => $update,
        //     'status_logging' => $log
        // ]);

        return back();
    }

    public function editFeedSize(Request $request)
    {
        $validated = $request->validate([
            // 'owner_id' => 'required|integer',
            'device_id' => 'required|integer',
            'feed_size' => 'required|numeric',
            'name' => 'required|string',
            'serial_number' => 'required|string',
        ]);

        $validated['feed_size'] = number_format($validated['feed_size'], 1, '.');

        $update = FeedConfig::where('device_id', $validated['device_id'])
            ->update([
                'feed_size' => $validated['feed_size'],
            ]);

        $log = $this->logging($validated['device_id'], 'config', $validated['name']);

        // return response()->json([
        //     'status_update' => $update,
        //     'status_logging' => $log
        // ]);

        return redirect()->route('monitoring.Feed', ['serial_number' => $validated['serial_number']]);
    }

    public function editThreshold(Request $request)
    {
        $validated = $request->validate([
            // 'owner_id' => 'required|integer',
            'device_id' => 'required|integer',
            'upper_threshold' => 'required|numeric',
            'lower_threshold' => 'required|numeric',
            'name' => 'required|string',
            'serial_number' => 'required|string',
            'virdi_type' => 'required|string',
        ]);

        // $deviceCheck = Device::where('id', $validated['device_id'])
        //     ->where('owner_id', Auth::user()->id)->first();

        // if ($deviceCheck == null) {
        //     return response()->json([
        //         'status' => 'gagal',
        //         'message' => 'Pemilik device tidak valid'
        //     ]);
        // }

        $validated['lower_threshold'] = number_format($validated['lower_threshold'], 2, '.');
        $validated['upper_threshold'] = number_format($validated['upper_threshold'], 2, '.');

        $configModels = [
            'Siram' => SiramConfig::class,
            'Humida' => HumidaConfig::class,
        ];

        $update = $configModels[$validated['virdi_type']]::where('device_id', $validated['device_id'])
            ->update([
                'upper_threshold' => $validated['upper_threshold'],
                'lower_threshold' => $validated['lower_threshold']
            ]);

        $log = $this->logging($validated['device_id'], 'config', $validated['name']);

        // return response()->json([
        //     'status_update' => $update,
        //     'status_logging' => $log
        // ]);

        return redirect()->route('monitoring.' . $validated['virdi_type'], ['serial_number' => $validated['serial_number']]);
    }

    public function logging($device_id, $activity, $data)
    {
        $message = [
            'config' => "Konfigurasi $data telah diubah",
            'mode' => "Mode penggunaan $data telah diubah",
            'name' => "Nama Perangkat $data berhasil diubah",
            'online' => "Koneksi perangkat $data terhubung",
            'offline' => "Koneksi perangkat $data terputus",
            'feed_success_Auto' => "$data memberi pakan sesuai jadwal",
            'feed_success_Manual' => "$data memberi pakan melalui mode manual",
            'add_schedule' => "Menambahkan jadwal pemberian pakan $data",
        ];

        $log = DevicesLog::insert([
            'device_id' => $device_id,
            'activity' => $message[$activity],
        ]);

        return $log;
    }
}
