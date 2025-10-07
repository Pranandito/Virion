<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\FeedConfig;
use App\Models\FeedSchedule;
use App\Models\FeedStorage;
use App\Models\HumidaSensor;
use App\Models\SiramSensor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

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

    public function chartData($virdiType, $device_id, $periode = "now")
    {
        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
            'Feed' => FeedStorage::class
        ];

        switch ($periode) {
            case "now":
                $sensorData = Device::select('id', 'status')->where('id', $device_id)
                    ->with([
                        strtolower($virdiType) . '_sensors' => function ($query) {
                            $query->latest()->first();
                        }
                    ])->latest()->first();
                // $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->latest()->first();
                break;

            case "daily":
                $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->whereDate('created_at', Carbon::today())->get();
                break;

            case "weekly":
                $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->whereBetween('created_at', [now()->subWeek(), now()])->get();
                break;

            case "monthly":
                $sensorData = $sensorModels[$virdiType]::where('device_id', $device_id)->whereBetween('created_at', [now()->subMonth(), now()])->orderBy('created_at', 'asc')->get();
                break;
        }
        return response()->json(
            $sensorData,
        );
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

    public function fetch_schedule($device_id)
    {
        $schedules = FeedSchedule::select('id', 'device_id', 'active_status', 'time', 'days', 'portion')
            ->where('device_id', $device_id)->get();

        foreach ($schedules as $schedule) {
            if (substr_count($schedule->days, ",") == 6) {
                $dayCrop = "Setiap hari";
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

        return response()->json([$schedules]);
    }

    public function delete_schedule($id)
    {
        $delete_status = FeedSchedule::destroy($id);

        return response()->json(['delete_status' => $delete_status]);
    }


    public function tes($device_id)
    {
        // $schedules = FeedSchedule::select('device_id', 'days', 'active_status')->where('active_status', 1)->get();

        // FeedConfig::query()->update(['total_daily' => 0, 'total_daily' => 0]);


        // update setiap nambah / hapus jadwal
        $days = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];
        $todayIndex = Carbon::today()->dayOfWeek; // 0 = Minggu, 6 = Sabtu
        $remainingDays = array_slice($days, $todayIndex);

        $schedules = FeedSchedule::select('days', 'active_status', 'device_id')->where('active_status', 1)->where('device_id', $device_id)->get();

        $weekly_feed_remaining = 0;
        foreach ($schedules as $schedule) {
            foreach ($days as $day) {
                if (str_contains($schedule->days, $day)) {
                    $weekly_feed_remaining++;
                }
            }
        }


        // return response()->json([$schedules, strtolower(Carbon::now()->isoFormat('dddd'))]);
        return response()->json([
            'remainingD' => $remainingDays,
            'jadwal' => $schedule,
            'total_mingguan' => $weekly_feed_remaining
        ]);
    }


    public function supdate_weekly()
    {
        $data = FeedSchedule::select('days', 'active_status', 'device_id', 'time')->where('active_status', 1)->get()->groupBy('device_id');

        foreach ($data as $device_id => $schedules) {
            foreach ($schedules as $schedule) {
                $schedule_total = str_word_count($schedule->days) + 1;
            }

            // $update = FeedSchedule::where('device_id', $device_id)->update(['weekly_total' => $schedule_total]);
        }

        return response()->json($data);
    }

    public function update_daily($device_id)
    {
        $schedules = FeedSchedule::select('days', 'active_status', 'device_id', 'id')->where('active_status', 1)->where('device_id', $device_id)->get();

        $total_daily = 0;
        foreach ($schedules as $schedule) {
            if (stripos($schedule->days, now()->dayName) !== false) {
                $total_daily++;
            }
        }

        $update = FeedConfig::where('device_id', $device_id)->update(["total_daily" => $total_daily]);

        return response()->json([$total_daily, now()->dayName, $update]);
    }

    public function update_weekly($device_id)
    {
        $schedules = FeedSchedule::select('days', 'active_status', 'device_id', 'id')->where('active_status', 1)->where('device_id', $device_id)->get();

        $total_weekly = 0;
        foreach ($schedules as $schedule) {
            $total_weekly += (substr_count($schedule->days, ",") + 1);
        }

        $update = FeedConfig::where('device_id', $device_id)->update(["total_weekly" => $total_weekly]);

        return response()->json([$total_weekly, $update, $schedule->days]);
    }

    public function feed($device_id)
    {
        $data = Device::with([
            'feed_config:id,device_id,feed_size,last_refill,mode,total_daily,total_weekly,success_daily,success_weekly,manual_daily,manual_weekly',
            'feed_schedules' => function ($query) {
                $query->orderBy('active_status', 'desc');
            },
            'devices_logs' => function ($query) {
                $query->latest()->limit(6);
            },
            'feed_storages' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->where('id', $device_id)->first();


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

        return response()->json($data->feed_storages[0]->created_at);
    }
}
