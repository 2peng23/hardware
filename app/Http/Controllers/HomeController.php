<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->usertype == 0) {
                return view('staff.home');
            } else {
                $users = User::where('usertype', '!=', 0)->count();
                return view('admin.Home', compact('users'));
            }
        }
        return redirect('/login');
    }
}
