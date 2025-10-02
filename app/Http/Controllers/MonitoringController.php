<?php

namespace App\Http\Controllers;

use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\HumidaSensor;
use App\Models\SiramSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class MonitoringController extends Controller
{

    private function isWithinRange($value, $range)
    {
        return $value >= $range['low'] && $value <= $range['high'];
    }

    private function checkStatus($humidity, $temperature, $threshold)
    {
        return $this->isWithinRange($humidity, $threshold['humidity']) &&
            $this->isWithinRange($temperature, $threshold['temperature']);
    }

    public function create($serial_number)
    {

        // --------------------------
        $virdi_type = $serial_number[0];
        $device_id = (int) explode('-', $serial_number)[1];

        $config_table_map = [
            'S' => 'siram_config',
            'H' => 'humida_config',
            // 'F' => FeedConfig::class
        ];

        $config_table = $config_table_map[$virdi_type];

        $device = Device::select('name', 'status', 'id', 'virdi_type')->with([$config_table, 'devices_logs' => function ($query) {
            $query->latest()->limit(5);
        }])->where('id', $device_id)->first();


        // ------------------------
        $sensorModels = [
            'S' => SiramSensor::class,
            'H' => HumidaSensor::class,
            'A' => AquaSensor::class,
        ];

        $colomnList = [
            'S' => ['temperature', 'humidity'],
            'H' => ['temperature', 'humidity'],
            'A' => ['temperature', 'turbidity', 'pH', 'oxygen'],
        ];

        // pake relasi with ke $device
        $latest = $sensorModels[$virdi_type]::where('device_id', $device_id)->latest()->first();

        $index = [];
        $weekly = null;
        $daily = null;

        if ($latest) {

            $baseQuerryDaily = $sensorModels[$virdi_type]::whereDate('created_at', $latest->created_at->toDateString())->where('device_id', $device_id);
            $baseQuerryWeekly = $sensorModels[$virdi_type]::whereBetween('created_at', [$latest->created_at->subWeek(), $latest->created_at])->where('device_id', $device_id);

            foreach ($colomnList[$virdi_type] as $kolom) {
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


            // --------------------------
            $threshold = [
                'humidity' => ['low' => 50.0, 'high' => 70.0],
                'temperature' => ['low' => 20.0, 'high' => 30.0],
            ];

            $index_map =
                [
                    'H' => [
                        0 => 'Suhu dan kelembapan udara greenhouse anda sedang tidak berada direntang yang aman',
                        1 => 'Suhu dan kelembapan udara greenhouse anda sedang berada direntang yang aman'
                    ],
                    'S' => [
                        0 => 'Kelembapan tanah rata rata lahan pertanian anda sedang tidak berada direntang yang aman',
                        1 => 'Kelembapan tanah rata rata lahan pertanian anda sedang berada direntang yang aman'
                    ]
                ];

            $current_index = $this->checkStatus($latest->humidity, $latest->temperature, $threshold);
            $daily_index   = $this->checkStatus($daily->avg_daily_humidity, $daily->avg_daily_temperature, $threshold);
            $weekly_index  = $this->checkStatus($weekly->avg_weekly_humidity, $weekly->avg_weekly_temperature, $threshold);

            $indexText = fn($index) => $index ? 'Baik' : 'Buruk';

            $index = [
                'current' => $indexText($current_index),
                'daily'   => $indexText($daily_index),
                'weekly'  => $indexText($weekly_index),
                'insight' => $index_map[$virdi_type][$current_index],
            ];
        }

        // -----------------
        $devices = Device::select('id', 'owner_id', 'serial_number', 'name', 'virdi_type')->where('owner_id', Auth::user()->id)->get();

        // ------------------
        $view_map = [
            'H' => 'monitoring.humida',
            'S' => 'monitoring.siram',
        ];

        // 'latest', 'weekly', 'daily' jaddin 1
        return view($view_map[$virdi_type], compact('index', 'device', 'config_table', 'latest', 'weekly', 'daily', 'devices'));
    }

    public function createFeed($serial_number)
    {
        // $virdi_type = $serial_number[0];
        $device_id = (int) explode('-', $serial_number)[1];

        $data = Device::with([
            'feed_config:id,device_id,feed_size,last_refill,mode,total_daily,total_weekly,success_daily,success_weekly,manual_daily,manual_weekly',
            'feed_schedules' => function ($query) {
                $query->select('id', 'device_id', 'active_status', 'time', 'days', 'portion')->orderBy('active_status', 'desc');
            },
            'devices_logs' => function ($query) {
                $query->latest()->limit(5);
            },
            'feed_storages' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->find($device_id);


        foreach ($data->feed_schedules as $schedule) {
            if (substr_count($schedule->days, ",") == 6) {
                $dayCrop = ["Setiap hari"];
            } else {
                $dayList = explode(",", $schedule->days);
                $dayCrop = [];
                foreach ($dayList as $day) {
                    $day = substr($day, 0, 3);
                    array_push($dayCrop, $day);
                }
            }
            $schedule->dayCrop = $dayCrop;
        }

        $devices = Device::select('id', 'owner_id', 'serial_number', 'name', 'virdi_type')->where('owner_id', Auth::user()->id)->get();

        return view('monitoring.Feed', compact('data', 'devices'));
    }
}
