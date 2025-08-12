<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Task;
use App\Models\Admin;
use App\Mail\WelcomeMail;
use App\Jobs\SendWelcomeEmail;
use App\Models\EmailVerificationToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserLoginController extends Controller
{
    public function showUserLoginForm()
    {
        // dd(session()->all());
        return view('auth.user-sign-in');
    }
   
    public function userLogin(UserLoginRequest $request)
    {
       
        return redirect()->route('user.dashboard')->with('success', 'Đăng nhập thành công');
        
    }
    
    public function showRegisterForm()
    {
        return view('auth.sign-up');
    }

    public function register(UserRegisterRequest $request)
    {
        
        $minute = Task::MINUTES_TO_EXPIRE;
        $token = Str::random(64);
       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
        ]);
        EmailVerificationToken::create([
            'user_id'=> $user->id,
            'token'=>$token,
        ]);
        SendWelcomeEmail::dispatch($user,$token);
        return redirect()->route('user.login')->with('success', "Đăng ký thành công, vui lòng check email để kích hoạt tài khoản. Yêu cầu xác thực có hiệu lực trong vòng {$minute} phút",);
    }

    public function logout(Request $request)
    {
        
        Auth::guard('web')->logout();
            
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}
