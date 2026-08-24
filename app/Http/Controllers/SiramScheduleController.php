<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\ConfigController;
use App\Models\SiramSchedule;
use Illuminate\Http\Request;

class SiramScheduleController extends Controller
{
    public function changeStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'device_id' => 'required|integer',
            'active_status' => 'required|integer',
        ]);

        $update = SiramSchedule::where('id', $validated['id'])->update(['active_status' => $validated['active_status']]);

        return back();
    }

    public function add_schedule(Request $request, $device_id)
    {
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'time' => 'required|date_format:H:i',
            'duration' => 'required|numeric',
            'name' => 'required|string'
        ]);

        $validated['device_id'] = $device_id;
        $validated['duration'] = $validated['duration'] * 60;
        $validated['days'] = implode(",", $validated['days']);

        $logger = new ConfigController;
        $logging = $logger->logging($device_id, 'siram_add_schedule', $validated['name']);
        unset($validated['name']);

        $insert = SiramSchedule::insert($validated);


        // return response()->json([
        //     'status_penyimpanan' => $insert,
        //     'data' => $validated,
        //     'today' => today()
        // ]);

        return back();
    }


    public function delete($schedule_id)
    {
        $delete = SiramSchedule::where('id', $schedule_id)->delete();
        return back();
    }
}
