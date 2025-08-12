<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Jobs\SendResetPasswordEmail;
use App\Models\ResetPassWordToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại']);
        }
        $checkToken = ResetPassWordToken::where('email',$user->email)
            ->where('use_at',null)
            ->where('expries_at','>',now())
            ->first();
        if($checkToken){
            return back()-> with('error','Bạn đã gửi yêu cầu, yêu cầu vẫn còn hiệu lực. Vui lòng check email để xác nhận');
        }
        $token = Str::random(64);
        ResetPassWordToken::create([
            'email' => $user->email,
            'token' => $token,
            'expries_at' => now()->addMinutes(5),
        ]);
        SendResetPasswordEmail::dispatch($user, $token);

        return back()->with('success', 'Mã xác minh đã được gửi đến email, vui lòng xác nhận');
    }

    public function showResetForm(Request $request)
    {
       
        $token = $request->query('token');

        $record = ResetPasswordToken::where('token', $token)->first();
        
        if (
            !$record ||
            $record->expries_at->isPast() ||
            $record->use_at !== null
        ) {
            return redirect()->route('user.login')->with('error', 'Token không hợp lệ hoặc đã hết hạn .');
        }

        return view('auth.reset-password', [
            'token' => $record->token,
            'email' => $record->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = ResetPasswordToken::where('email', $request->email)
            ->where('token', $request->token)
            ->whereNull('use_at')
            ->where('expries_at', '>', now())
            ->first();

        if (!$record) {
            return redirect()->route('login')->with('error', 'Token không hợp lệ hoặc đã hết hạn .');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Tài khoản không tồn tại']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        $record->update(['use_at' => now()]);

        return redirect()->route('user.login')->with('success', 'Đặt lại mật khẩu thành công!');
    }
}
