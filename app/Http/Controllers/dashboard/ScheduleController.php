<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Booking;
use App\Models\Patient;
use App\Models\User;
use App\Services\ScheduleCreationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\BookingActionService;

class ScheduleController extends Controller
{
    public function __construct(
        private BookingActionService $bookingActionService
    ) {

    }
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

    public function list()
    {
        return view('content.booking.list');
    }

    // public function data(Request $request)
    // {
    //     $counts = [
    //         'all' => Booking::count(),
    //         'today' => Booking::whereDate('date', today())->count(),
    //         'upcoming' => Booking::whereDate('date', '>', today())->count(),
    //     ];

    //     $query = Booking::query()
    //         ->when($view === 'today', fn ($q) => $q->whereDate('date', today()))
    //         ->when($view === 'upcoming', fn ($q) => $q->whereDate('date', '>', today()))
    //         ->when(filled($types), fn ($q) => $q->whereIn('type', $types))
    //         ->when(filled($statuses), fn ($q) => $q->whereIn('status', $statuses));

    //     $types = $request->input('types', []);
    //     $statuses = $request->input('statuses', []);

    //     $search = $request->string('search')->trim();
    //     $view = $request->string('view')->trim()->value();

    //     $bookings = Booking::query()
    //         ->with([
    //             'consultation:id,patient_id,booking_id,doctor_id,status',
    //             'consultation.patient:id,name,phone',
    //             'consultation.doctor:id,name',
    //         ])
    //         ->when($search, function ($query) use ($search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('booking_code', 'like', "%{$search}%")
    //                     ->orWhereHas('consultation.patient', function ($q) use ($search) {
    //                         $q->where('name', 'like', "%{$search}%")
    //                         ->orWhere('phone', 'like', "%{$search}%");
    //                     })
    //                     ->orWhereHas('consultation.doctor', function ($q) use ($search) {
    //                         $q->where('name', 'like', "%{$search}%");
    //                     });
    //             });
    //         })
    //         // 👇 Put it here
    //         ->when($view === 'today', function ($query) {
    //             $query->whereDate('date', today());
    //         })
    //         ->when($view === 'upcoming', function ($query) {
    //             $query->whereDate('date', '>', today());
    //         })
    //         ->when(
    //             filled($types),
    //             fn ($query) => $query->whereIn('type', $types)
    //         )
    //         ->when(
    //             filled($statuses),
    //             fn ($query) => $query->whereIn('status', $statuses)
    //         )
    //         ->orderByDesc('date')
    //         ->orderByDesc('time')
    //         ->paginate(15);

    //     $bookings->through(function ($booking) {
    //         return [
    //             'id' => $booking->id,
    //             'code' => $booking->booking_code,
    //             'patient' => $booking->consultation?->patient?->name,
    //             'phone' => $booking->consultation?->patient?->phone,
    //             'type' => $booking->type->label(),
    //             'date' => $booking->date->format('d M Y'),
    //             'time' => Carbon::parse($booking->time)->format('h:i A'),
    //             'doctor' => $booking->consultation?->doctor?->name,
    //             'status' => $booking->status->label(),
    //             'status_badge' => $booking->status->badgeClass(),
    //             'type_badge' => $booking->type->badgeClass(),
    //             'actions' => $this->bookingActionService
    //             ->getActions($booking, auth()->user()),
    //         ];
    //     });

    //     return response()->json([
    //         'counts' => $counts,
    //         'current_page' => $bookings->currentPage(),
    //         'last_page' => $bookings->lastPage(),
    //         'per_page' => $bookings->perPage(),
    //         'total' => $bookings->total(),
    //         'data' => $bookings->items(),
    //     ]);
    // }

    public function data(Request $request)
    {
        $types = $request->input('types', []);
        $statuses = $request->input('statuses', []);

        $search = $request->string('search')->trim();
        $view = $request->string('view')->trim()->value();

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        | This becomes the single source of truth for:
        | - Summary cards
        | - Table data
        */
        $query = Booking::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('booking_code', 'like', "%{$search}%")
                        ->orWhereHas('consultation.patient', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('consultation.doctor', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($view === 'today', function ($query) {
                $query->whereDate('date', today());
            })
            ->when($view === 'upcoming', function ($query) {
                $query->whereDate('date', '>', today());
            })
            ->when(
                filled($types),
                fn ($query) => $query->whereIn('type', $types)
            )
            ->when(
                filled($statuses),
                fn ($query) => $query->whereIn('status', $statuses)
            );

        /*
        |--------------------------------------------------------------------------
        | Summary Cards
        |--------------------------------------------------------------------------
        | Reflects whatever is currently displayed in the table.
        */
        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 0)->count(),
            'completed' => (clone $query)->where('status', 2)->count(),
            'cancelled' => (clone $query)->where('status', -1)->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Quick View Counts
        |--------------------------------------------------------------------------
        | Navigation counts. Independent from filters.
        */
        $counts = [
            'all' => Booking::count(),
            'today' => Booking::whereDate('date', today())->count(),
            'upcoming' => Booking::whereDate('date', '>', today())->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Table Data
        |--------------------------------------------------------------------------
        */
        $bookings = (clone $query)
            ->with([
                'consultation:id,patient_id,booking_id,doctor_id,status',
                'consultation.patient:id,name,phone',
                'consultation.doctor:id,name',
            ])
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate(15);

        $bookings->through(function ($booking) {
            return [
                'id' => $booking->id,
                'code' => $booking->booking_code,
                'patient' => $booking->consultation?->patient?->name,
                'phone' => $booking->consultation?->patient?->phone,
                'type' => $booking->type->label(),
                'date' => $booking->date->format('d M Y'),
                'time' => Carbon::parse($booking->time)->format('h:i A'),
                'doctor' => $booking->consultation?->doctor?->name,
                'status' => $booking->status->label(),
                'status_badge' => $booking->status->badgeClass(),
                'type_badge' => $booking->type->badgeClass(),
                'actions' => $this->bookingActionService
                    ->getActions($booking, auth()->user()),
            ];
        });

        return response()->json([
            'stats' => $stats,
            'counts' => $counts,
            'current_page' => $bookings->currentPage(),
            'last_page' => $bookings->lastPage(),
            'per_page' => $bookings->perPage(),
            'total' => $bookings->total(),
            'data' => $bookings->items(),
        ]);
    }
}
