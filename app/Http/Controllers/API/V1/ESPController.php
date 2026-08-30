<?php

namespace App\Http\Controllers\API\V1;

use App\Models\AquaSensor;
use App\Models\Device;
use App\Models\FeedConfig;
use App\Models\FeedSchedule;
use App\Models\FeedStorage;
use App\Models\HumidaConfig;
use App\Models\HumidaSensor;
use App\Models\SiramAcitivtyLog;
use App\Models\SiramActivityLog;
use App\Models\SiramConfig;
use App\Models\SiramSchedule;
use App\Models\SiramSensor;
use App\Services\LoggingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ESPController
{
    public function __construct(
        protected LoggingService $logService
    ) {}

    private function mapValue($value, $fromLow, $fromHigh, $toLow, $toHigh)
    {
        return ($value - $fromLow) * ($toHigh - $toLow) / ($fromHigh - $fromLow) + $toLow;
    }

    public function store(Request $request, $virdiType, $device_id)
    {
        $validated = $request->validate([
            // Humida & Siram
            'temperature' => 'sometimes|required|decimal:2',  // kalau 44.40 kirim string "44.40"
            'humidity' => 'sometimes|required|decimal:2',

            // Aqua
            'turbidity' => 'sometimes|required|decimal:2',
            'pH' => 'sometimes|required|decimal:2',
            'oxygen' => 'sometimes|required|decimal:2',
            'batt_voltage' => 'sometimes|required|decimal:3',

            // Feed
            'storage' => 'sometimes|required|decimal:1',
            'refill' => 'sometimes|required|boolean',
        ]);

        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
            'Feed' => FeedStorage::class
        ];

        $dataRecord = $sensorModels[$virdiType]::where('device_id', $device_id)
            ->with(['device' => function ($querry) {
                $querry->select('id', 'status', 'name');
            }])->latest()->limit(10)->get();

        $now = now()->timezone('Asia/Jakarta');
        $lastData = $dataRecord
            ->filter(function ($data) use ($now) {
                return $data->created_at
                    ->timezone('Asia/Jakarta')
                    ->isSameDay($now);
            })->first();

        // $lastData = $sensorModels[$virdiType]::where('device_id', $device_id)
        //     ->whereDate('created_at', $now->toDateString())
        //     ->with(['device' => function ($querry) {
        //         $querry->select('id', 'status', 'name');
        //     }])->latest()->first();

        if (empty($lastData)) {
            $validated['online_duration'] = Carbon::createFromTimestamp(120);
            $lastOnDuration = 0;
            $selisihWaktu = 0;
        } else {
            $lastOnDuration = Carbon::parse($lastData->online_duration);

            $selisihWaktu = $lastData->created_at->diffInSeconds($now);
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

        if ($validated['humidity']) {
            $humidity = ($dataRecord->sum('humidity') + $validated['humidity']) / 11;
            $validated['humidity'] = $humidity;
        }

        $validated['device_id'] = $device_id;
        $validated['created_at'] = $now;
        $store = $sensorModels[$virdiType]::insert($validated);

        // $logging = null;
        // $statusUpdate = null;

        if ($lastData->device->status == 0) {
            $statusUpdate = Device::where('id', $device_id)->update([
                "status" => 1,
            ]);
            $logging = $this->logService->logging($lastData->device->id, 'online', $lastData->device->name);
        }

        $config = $this->getConfig($virdiType, $device_id);

        return response()->json([
            // 'validated' => $validated,
            // 'data' => $lastData,
            // 'lastOn' => $lastOnDuration,
            // 'status_lastReffill' => $refill,
            // 'selisih' => $selisihWaktu,
            // 'lastData' => empty($lastData),
            // 'log' => $logging,
            // 'status update' => $statusUpdate,
            'saving_status' => $store,
            'config' => $config
        ]);
    }

    public function storeRecover(Request $request, $virdiType, $device_id)
    {
        $validated = $request->validate([

            'data' => 'required|array',

            // Humida & Siram
            'data.*.temperature' => 'sometimes|required|decimal:2',  // kalau 44.40 kirim string "44.40"
            'data.*.humidity' => 'sometimes|required|decimal:2',

            // Aqua
            'data.*.turbidity' => 'sometimes|required|decimal:2',
            'data.*.pH' => 'sometimes|required|decimal:2',
            'data.*.oxygen' => 'sometimes|required|decimal:2',
            'data.*.batt_voltage' => 'sometimes|required|decimal:3',

            // Feed
            'data.*.storage' => 'sometimes|required|decimal:1',

            'data.*.created_at' => 'sometimes|required|date',
        ]);

        $sensorModels = [
            'Siram' => SiramSensor::class,
            'Humida' => HumidaSensor::class,
            'Aqua' => AquaSensor::class,
            'Feed' => FeedStorage::class
        ];

        $records = [];
        foreach ($validated['data'] as $rowData) {
            if ($virdiType == 'Feed') {
                $records[] = [
                    "device_id" => $device_id,
                    "storage" => $rowData['storage'],
                    "online_duration" => Carbon::createFromTimestamp(0),
                    "created_at" => $rowData['created_at']
                ];
            } else {
                $records[] = [
                    "device_id" => $device_id,
                    "temperature" => $rowData['temperature'],
                    "humidity" => $rowData['humidity'],
                    "online_duration" => Carbon::createFromTimestamp(0),
                    "created_at" => $rowData['created_at']
                ];
            }
        }
        $store = $sensorModels[$virdiType]::insert($records);

        // $config = $this->getConfig($virdiType, $device_id);

        return response()->json([
            // 'validated' => $validated,
            // 'data' => $lastData,
            // 'lastOn' => $lastOnDuration,
            // 'status_lastReffill' => $refill,
            // 'selisih' => $selisihWaktu,
            // 'lastData' => empty($lastData),
            'saving_status' => $store,
            'data' => $records

            // 'config' => $config
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
            $config = $configModels[$virdiType]::select('feed_size', 'mode')
                ->where('device_id', $device_id)->first();
        } else {
            $config = $configModels[$virdiType]::select('upper_threshold', 'lower_threshold', 'mode')
                ->where('device_id', $device_id)->first();
        }

        return $config;
    }

    public function getSchedule($virdiType, $device_id)
    {
        $dayMap = [
            'minggu' => 0,
            'senin'  => 1,
            'selasa' => 2,
            'rabu'   => 3,
            'kamis'  => 4,
            'jumat'  => 5,
            'sabtu'  => 6,
        ];

        if ($virdiType == 'Siram') {
            $schedules = SiramSchedule::select('id', 'device_id', 'active_status', 'time', 'days', 'duration')
                ->where('device_id', $device_id)->where('active_status', 1)->get();
        } else {
            $schedules = FeedSchedule::select('id', 'device_id', 'active_status', 'time', 'days', 'portion')
                ->where('device_id', $device_id)->where('active_status', 1)->get();
        }

        foreach ($schedules as $schedule) {
            $dayNames = explode(",", $schedule->days);

            // Bitmask: bit ke-N = 1 kalau hari N aktif (0=Minggu ... 6=Sabtu)
            $bitmask = 0;
            foreach ($dayNames as $d) {
                $d = strtolower(trim($d));
                if (isset($dayMap[$d])) {
                    $bitmask |= (1 << $dayMap[$d]);
                }
            }
            $schedule->days = $dayNames;              // tetap disertakan untuk readability/debug
            $schedule->days_bitmask = $bitmask;        // dipakai ESP32

            // Detik sejak 00:00:00
            [$h, $m, $s] = array_map('intval', explode(':', $schedule->time));
            $schedule->time_seconds = $h * 3600 + $m * 60 + $s;
        }

        return response()->json($schedules);
    }

    public function feedSuccess($mode, $device_id)
    {
        if ($mode == "Auto") {
            $update_success = FeedConfig::where('device_id', $device_id)
                ->update([
                    'success_daily' => DB::raw('success_daily + 1'),
                    'success_weekly' => DB::raw('success_weekly + 1')
                ]);
        } else {
            $update_success = FeedConfig::where('device_id', $device_id)
                ->update([
                    'manual_daily' => DB::raw('manual_daily + 1'),
                    'manual_weekly' => DB::raw('manual_weekly + 1'),
                ]);
        }

        $logger = new ConfigController();
        $device = Device::where('id', $device_id)->select('id', 'name')->first();

        $logging = $logger->logging($device_id, 'feed_success_' . $mode, $device->name);

        return response()->json(['status_update' => $update_success]);
    }

    public function siramSuccess(Request $request)
    {
        $validated = $request->validate([
            "device_id" => 'required|integer|exists:devices,id',
            "mode" => 'required|in:Manual,Otomatis,Jadwal',
            "duration" => 'required|integer|min:0'
        ]);

        $log = SiramActivityLog::create($validated);

        return $log;
    }
}
