<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.sign-in');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('web')->attempt($credentials)) {
            if (Auth::user()->is_locked) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is locked']);
            }
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function showRegisterForm()
    {
        return view('auth.sign-up');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|unique:admins',
            'password' => 'required|string|min:8|max:50|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $route = 'login';
        } else {
            Auth::guard('web')->logout();
            $route = 'login';
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($route);
    }
}
