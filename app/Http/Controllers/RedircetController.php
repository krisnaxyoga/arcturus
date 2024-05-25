<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedircetController extends Controller
{
    public function cek(Request $request) {
        // dd(auth()->user()->role_id);
        if (auth()->user()->role_id == 1) {
            return redirect('/admin');
        } else if (auth()->user()->role_id == 2) {
            return redirect('/vendordashboard');
        } else{
            return redirect('/agentdashboard');
        }
    }

    public function redirect_admin(Request $request)
    {
        Auth::logout();

        $user_super_admin = User::query()->where('role_id', 1)->first();

        Auth::loginUsingId($user_super_admin->id);

        // Redirect ke halaman admin
        if ($request->page == 'hotel') {
            return redirect()->route('dashboard.hotel');
        }

        if ($request->page == 'agent') {
            return redirect()->route('dashboard.agent');
        }

        return redirect()->route('dashboard.index');
    }
}
