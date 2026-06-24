<?php

namespace App\Http\Controllers\dashboard;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\BookingActionService;

class Analytics extends Controller
{

    public function __construct(
        private BookingActionService $bookingActionService
    ) {

    }
    public function index()
    {
        $todaySchedules = Booking::query()
        ->with([
            'consultation:id,booking_id,patient_name,doctor_id,status',
            'consultation.doctor:id,name'
        ])
        ->whereDate('date', today())
        ->where('status', BookingStatus::BOOKED)
        ->orderBy('time')
        ->limit(4)
        ->get([
            'id',
            'booking_code',
            'type',
            'time',
            'status',
        ])->map(fn ($booking) => [
            'booking_code' => $booking->booking_code,
            'time' => $booking->time,
            'name' => $booking->consultation?->patient_name ?? '-',
            'type' => $booking->type->label(),
            'status' => $booking->status->label(),
            'status_class' => $booking->status->badgeClass(),
            'actions' => $this->bookingActionService
            ->getActions($booking, auth()->user()),
        ]);

        $upcomingSchedules = Booking::query()
        ->with([
            'consultation:id,booking_id,patient_name,doctor_id,status',
            'consultation.doctor:id,name',
        ])
        ->whereDate('date', today()->addDay())
        ->orderBy('time')
        ->limit(4)
        ->get([
            'id',
            'time',
            'type',
        ])
        ->map(fn ($booking) => [
            'time' => $booking->time,
            'patient_name' => $booking->consultation?->patient_name ?? '-',
            'type' => $booking->type->label(),
            'doctor_name' => $booking->consultation?->doctor?->name ?? '-',
        ]);
        
        return view('content.dashboard.dashboards-schedule', compact('todaySchedules', 'upcomingSchedules'));
        // return view('content.blank_template.blank_template');
    }
}
