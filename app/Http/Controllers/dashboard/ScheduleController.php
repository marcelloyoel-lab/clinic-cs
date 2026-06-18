<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Patient;
use App\Models\User;
use App\Services\ScheduleCreationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index()
    {
        $patients = Patient::select(
            'id',
            'name',
            'dob',
            'gender',
            'phone',
            'phone_alternative',
            'email'
        )->get();

        $doctors = User::where('role_id', 4)->select('id', 'name')->get();
        return view('content.dashboard.new-schedule.index', compact('patients', 'doctors'));
    }

    public function store(StoreScheduleRequest $request, ScheduleCreationService $scheduleService){
        // dd($request->all());

        try {
            $scheduleService->create($request->validated());
            Log::info('Schedule created successfully', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
            ]);

            return redirect()
                ->route('dashboard-schedule')
                ->with('success', 'Schedule created successfully.');
        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create schedule. Please try again.');
        }
    }
}
