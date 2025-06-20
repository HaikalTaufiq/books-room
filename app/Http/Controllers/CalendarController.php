<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomBooking;

class CalendarController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard');
    }
    public function getEvents()
    {
        $bookings = RoomBooking::all();

        $events = $bookings->map(function ($booking) {
            $start = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time);
            $end = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time);

            $colorMap = [
                'pending' => ['color' => '#facc15', 'text' => '#000'],   // Kuning
                'approved' => ['color' => '#4ade80', 'text' => '#000'],  // Hijau
                'decline' => ['color' => '#f87171', 'text' => '#000'],   // Merah
            ];

            $colors = $colorMap[$booking->status] ?? ['color' => '#d1d5db', 'text' => '#000']; // Default abu-abu

            return [
                'title' => "{$booking->applicant_name} / " .
                    $start->format('H:i') . ' - ' .
                    $end->format('H:i') . ' / ' .
                    $booking->room_name,
                'start' => $start->toIso8601String(),
                'end'   => $end->toIso8601String(),
                'classNames' => ['event-' . $booking->status],
            ];
        });

        return response()->json($events);
    }
}
