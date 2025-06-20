<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\RoomBooking;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index(Request $request)
    {

        $query = Room::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('location', 'like', "%$search%")
                ->orWhere('capacity', 'like', "%$search%");
        }


        $rooms = $query->paginate(5);

        return view('rooms.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'capacity' => 'required|integer',
            'available' => 'required|boolean',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Successfully added new room');
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'available' => 'required|boolean',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully');
    }

    public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return redirect()->route('rooms.index')->with('error', 'No data found');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', ' room deleted successfully');
    }


    public function list()
    {
        $rooms = Room::all();
        return view('rooms.list-room', compact('rooms'));
    }

    public function apply()
    {
        $rooms = Room::all();

        return view('rooms.apply', compact('rooms'));
    }


    public function dashboard()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('available', 1)->count();

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $bookings = RoomBooking::whereDate('booking_date', $today)->count();

        return view('dashboard.dashboard', compact('totalRooms', 'availableRooms', 'bookings'));
    }
}
