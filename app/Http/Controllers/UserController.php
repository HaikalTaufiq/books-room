<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Tambah fitur search jika ada query search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Ambil data yang sudah difilter dan dipaginate
        $users = $query->orderBy('created_at', 'asc')->paginate(5);

        return view('dashboard.user', compact('users'));
    }


    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'role'      => $request->role,
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', ' user deleted successfully.');
    }
}
