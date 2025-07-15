<?php

namespace App\Http\Controllers;

use App\Models\DamageReport;
use App\Models\Room;
use App\Models\RoomBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageReportController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $damageReports = DamageReport::with('reporter')->get();
        $usages = RoomBooking::where('status', 'approved')->get();

        $roomUsageData = DB::table('room_bookings')
            ->select('room_name', DB::raw('count(*) as total'))
            ->where('status', 'approved')
            ->groupBy('room_name')
            ->get();

        $latestBooking = RoomBooking::where('user_id', Auth::id())
            ->latest('created_at')
            ->first();

        return view('reports.report', compact(
            'damageReports',
            'rooms',
            'usages',
            'roomUsageData',
            'latestBooking'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'room' => 'required|string',
            'damage_type' => 'required|string',
            'found_date' => 'required|date',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('damage_photos', 'public');
        }

        DamageReport::create([
            'room' => $request->room,
            'damage_type' => $request->damage_type,
            'found_date' => $request->found_date,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'reported_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Successfully reported damage.');
    }

    public function roomUsageData($filter)
    {
        $query = RoomBooking::where('status', 'approved');

        if ($filter === 'weekly') {
            $query->whereBetween('booking_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'monthly') {
            $query->whereMonth('booking_date', now()->month);
        } elseif ($filter === 'yearly') {
            $query->whereYear('booking_date', now()->year);
        }

        $usage = $query->select('room_name', \DB::raw('count(*) as total'))
            ->groupBy('room_name')
            ->get();

        return response()->json($usage);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,on progress,done',
        ]);

        $report = DamageReport::findOrFail($id);
        $report->status = $request->status;
        $report->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }

    public function destroy($id)
    {
        $report = DamageReport::findOrFail($id);
        $report->delete();

        return redirect()->back()->with('success', 'Successfully deleted the damage report.');
    }
}
