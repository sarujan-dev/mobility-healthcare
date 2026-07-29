<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public function index()
    {
        $doctor = auth()->user()->doctor;
        $schedules = $doctor->schedules()->get()->keyBy('day_of_week');
        $days = $this->days;

        return view('doctor.schedule.index', compact('days', 'schedules'));
    }

    public function update(Request $request)
    {
        $doctor = auth()->user()->doctor;

        foreach ($this->days as $day) {
            $isOff = $request->has("is_off_$day");
            $startTime = $request->input("start_time_$day");
            $endTime = $request->input("end_time_$day");

            if ($isOff) {
                // Day explicitly marked as off — save as off
                DoctorSchedule::updateOrCreate(
                    ['doctor_id' => $doctor->id, 'day_of_week' => $day],
                    ['start_time' => null, 'end_time' => null, 'is_off' => true]
                );
            } elseif (!empty($startTime) && !empty($endTime)) {
                // Valid times entered — save schedule
                DoctorSchedule::updateOrCreate(
                    ['doctor_id' => $doctor->id, 'day_of_week' => $day],
                    ['start_time' => $startTime, 'end_time' => $endTime, 'is_off' => false]
                );
            } else {
                // No time entered and not marked as off — delete existing schedule (treat as closed)
                DoctorSchedule::where('doctor_id', $doctor->id)
                    ->where('day_of_week', $day)
                    ->delete();
            }
        }

        return back()->with('success', 'Weekly schedule updated successfully.');
    }
}
