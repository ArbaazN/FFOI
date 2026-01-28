<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    //
    public function index()
    {
        try {
            return view('admin.auth.login');
        } catch (\Exception $e) {
            Log::error('Error loading admin login page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'An error occurred while loading the login page.');
        }
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();
                return redirect()->route('home');
            }

            Log::warning('Admin login failed - invalid credentials', [
                'email' => $request->email,
                'at' => now()
            ]);
            return redirect()->route('login')->with('error', 'Invalid credentials');
        } catch (\Exception $e) {
            Log::error('Error during admin authentication', [
                'error' => $e->getMessage(),
                'email' => $request->email ?? null,
                'at' => now()
            ]);
            return redirect()->route('login')->with('error', 'An error occurred during authentication.');
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $userId = $user ? $user->id : null;
            $userEmail = $user ? $user->email : null;

            Auth::logout();

            Log::info('Admin user logged out', [
                'user_id' => $userId,
                'email' => $userEmail,
                'at' => now(),
            ]);

            return redirect()->route('login');
        } catch (\Exception $e) {
            Log::error('Error during admin logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip(),
                'at' => now(),
            ]);
            return redirect()->route('login')->with('error', 'An error occurred while logging out.');
        }
    }
}
