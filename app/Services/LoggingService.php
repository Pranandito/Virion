<?php

namespace App\Services;

use App\Models\DevicesLog;

class LoggingService
{
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
            'siram_add_schedule' => "Menambahkan jadwal penyiraman $data",
            'feed_add_schedule' => "Menambahkan jadwal pemberian pakan $data",
        ];

        $log = DevicesLog::insert([
            'device_id' => $device_id,
            'activity' => $message[$activity],
        ]);

        return $log;
    }
}
