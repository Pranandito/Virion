<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\ConfigController;
use App\Models\FeedConfig;
use App\Models\FeedSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function changeStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'device_id' => 'required|integer',
            'active_status' => 'required|integer',
        ]);

        $update = FeedSchedule::where('id', $validated['id'])->update(['active_status' => $validated['active_status']]);

        $this->update_daily($validated['device_id']);
        $this->update_weekly($validated['device_id']);

        return back();
    }

    public function add_schedule(Request $request, $device_id)
    {
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'time' => 'required|date_format:H:i',
            'portion' => 'required|numeric',
            'name' => 'required|string'
        ]);

        $validated['device_id'] = $device_id;
        $validated['portion'] = number_format($validated['portion'], 2, ".");
        $validated['days'] = implode(",", $validated['days']);

        $logger = new ConfigController;
        $logging = $logger->logging($device_id, 'add_schedule', $validated['name']);
        unset($validated['name']);

        $insert = FeedSchedule::insert($validated);

        $update_daily = $this->update_daily($device_id);
        $update_weekly = $this->update_weekly($device_id);

        // return response()->json([
        //     'status_penyimpanan' => $insert,
        //     'data' => $validated,
        //     'today' => today()
        // ]);

        return back();
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

        // return response()->json([$total_daily, now()->dayName, $update]);
        return $update;
    }

    public function update_weekly($device_id)
    {
        $schedules = FeedSchedule::select('days', 'active_status', 'device_id', 'id')->where('active_status', 1)->where('device_id', $device_id)->get();

        $total_weekly = 0;
        foreach ($schedules as $schedule) {
            $total_weekly += (substr_count($schedule->days, ",") + 1);
        }

        $update = FeedConfig::where('device_id', $device_id)->update(["total_weekly" => $total_weekly]);

        // return response()->json([$total_weekly, $update, $schedule->days]);
        return $update;
    }
}
