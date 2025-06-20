<?php

namespace App\Http\Controllers;

use App\Models\RoomBooking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingCreated;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingCancelled;
use Carbon\Carbon;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'room_name' => 'required|string|exists:rooms,name',
            'activity_type' => 'required|string|max:255',
            'usage_capacity' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to book a room.');
        }

        // Cek konflik jadwal
        $conflict = RoomBooking::where('room_name', $request->room_name)
            ->where('booking_date', $request->borrow_date)
            ->where('status', 'approved') // hanya cek jadwal yang approved
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        $status = $conflict ? 'decline' : 'approved';

        // Simpan data ke database
        RoomBooking::create([
            'user_id' => $user->id,
            'applicant_name' => $request->applicant_name,
            'room_name' => $request->room_name,
            'activity_type' => $request->activity_type,
            'capacity' => $request->usage_capacity,
            'booking_date' => $request->borrow_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $status, // status otomatis sesuai logika
        ]);



        // Pesan notifikasi sesuai status
        switch ($status) {
            case 'approved':
                $title = "Approved";
                $message = "Your booking has been approved.";
                break;
            case 'pending':
                $title = "Pending";
                $message = "Your booking is pending approval.";
                break;
            case 'decline':
                $title = "Declined";
                $message = "Sorry, your booking has been declined due to a scheduling conflict.";
                break;
            default:
                $title = ucfirst($status);
                $message = "Your Booking: $status";
                break;
        }

        // Kirim notifikasi ke user yang login
        Notification::send($user, new BookingCreated($message, $status));


        return redirect()->back()->with('success', 'Booking request submitted successfully. Please wait for approval.');
    }


    public function myStatus(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $query = RoomBooking::query();

        $query->where('user_id', $user->id) // Filter utama: hanya data user ini
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('room_name', 'like', "%{$search}%")
                        ->orWhere('booking_date', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'asc');

        $bookings = $query->paginate(5)->appends(['search' => $search]);

        return view('rooms.borrow-status', compact('bookings', 'search'));
    }



    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,decline'
        ]);

        $booking = RoomBooking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function index(Request $request)
    {
        $query = RoomBooking::query();

        // Tambah fitur search jika ada query search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('room_name', 'like', "%{$search}%")
                    ->orWhere('applicant_name', 'like', "%{$search}%")
                    ->orWhere('activity_type', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'asc')->paginate(5);
        $rooms = Room::all();

        return view('rooms.status', compact('bookings', 'rooms'));
    }


    public function edit($id)
    {
        $booking = RoomBooking::findOrFail($id);
        $rooms = Room::all();
        return view('rooms.status', compact('booking', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $booking = RoomBooking::findOrFail($id);
        $originalRoomName = $booking->room_name;
        $originalStatus = $booking->status;

        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'activity_type' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required|string',
        ]);

        $booking->update($validated);

        // Ambil user pemilik booking
        $user = User::find($booking->user_id);

        if ($user) {
            // Cek perubahan room_name
            if ($validated['room_name'] !== $originalRoomName) {
                $status = 'room_changed'; // status custom bebas kamu definisikan
                $message = "Sorry, \"$originalRoomName\" Cannot be used. We are directing you to use \"{$validated['room_name']}\".";
                Notification::send($user, new BookingCreated($message, $status));
            }

            // Cek perubahan status
            if ($validated['status'] !== $originalStatus) {
                $status = $validated['status'];  // sudah assign disini
                switch ($status) {
                    case 'approved':
                        $message = "Your Booking has been approved.";
                        break;
                    case 'pending':
                        $message = "Your Booking is pending approval.";
                        break;
                    case 'decline':
                        $message = "Your Booking has been declined due to a scheduling conflict.";
                        break;
                    default:
                        $message = "Your Booking Status is: $status";
                        break;
                }
                Notification::send($user, new BookingCreated($message, $status));
            }
        }


        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }


    public function destroy($id)
    {
        RoomBooking::findOrFail($id)->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted.');
    }

    public function destroyFromMyBorrow($id)
    {
        $booking = RoomBooking::findOrFail($id);
        $user = Auth::user();

        $bookingDetails = "{$user->first_name} has canceled \"{$booking->room_name}\" at " . Carbon::parse($booking->booking_date)->format('d-m-Y') . ".";

        // Ambil semua admin
        $admins = \App\Models\User::where('role', 'admin')->get();

        // Kirim notifikasi ke semua admin (via database)
        Notification::send($admins, new BookingCancelled($bookingDetails));

        // Hapus booking
        $booking->delete();

        return redirect()->back()->with('success', 'Booking canceled successfully.');
    }
}
