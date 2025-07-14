<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Task;
use App\Models\Admin;
use App\Mail\WelcomeMail;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showUserLoginForm()
    {
        // dd(session()->all());
        return view('auth.user-sign-in');
    }
    public function showAdminLoginForm()
    {
        return view('auth.admin-sign-in');
    }
    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();
            if (Auth::user()->is_locked) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is locked']);
            }
            if (!$user->is_active) {
                Auth::logout();
                return back()->withInput()->withErrors(['email' => 'Tài khoản của bạn chưa được xác minh. Vui lòng kiểm tra email.']);
            }
            return redirect()->route('user.dashboard')->with('success', 'Đăng nhập thành công');
        }

        return back()->withInput()->withErrors(['email' => 'Invalid credentials']);
    }
    public function adminLogin(Request $request)
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
        $minute = Task::MINUTES_TO_EXPIRE;
        $token = Str::random(64);
        $tokenCreatedAt = Carbon::now('Asia/Ho_Chi_Minh');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => $token,
            'verification_token_created_at' => $tokenCreatedAt,
            'is_active' => false,
        ]);
        SendWelcomeEmail::dispatch($user);
        return redirect()->route('user.login')->with('success', "Đăng ký thành công, vui lòng check email để kích hoạt tài khoản. Yêu cầu xác thực có hiệu lực trong vòng {$minute} phút",);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $route = 'admin.login';
        } else {
            Auth::guard('web')->logout();
            $route = 'user.login';
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($route);
    }
}
