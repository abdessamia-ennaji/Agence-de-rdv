<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 1) {
                // Admin user - redirect to admin dashboard
                return view('admin.dashboard');
            } else {
                // Regular user - redirect to user home page
                return view('users.home');
            }
        } else {
            // Redirect to login if not authenticated
            return redirect()->route('login');
        }
    }
}