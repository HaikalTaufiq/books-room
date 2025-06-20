<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class GuestLoginController extends Controller
{
    public function login()
    {
        $guest = User::firstOrCreate(
            ['email' => 'guest@example.com'],
            [
                'first_name' => 'Guest User',
                'password' => bcrypt('guest_password'),
                'role' => 'guest',
                'phone' => '00000000',   // tambahkan ini sesuai field phone di DB kamu
            ]
        );


        Auth::login($guest);

        return redirect()->route('dashboard');
    }
}
