<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Task;
class AuthController extends Controller
{
    public function verify($token)
    {
        $minute = Task::MINUTES_TO_EXPIRE;
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Token không hợp lệ hoặc đã được sử dụng.');
        }

        $created = Carbon::parse($user->verification_token_created_at, 'Asia/Ho_Chi_Minh');
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        if ($now->diffInMinutes($created) > $minute) {
            return redirect()->route('user.login')->with('error', 'Token đã hết hạn. Vui lòng yêu cầu gửi lại token.');
        }

        $user->update([
            'is_active' => true,
            'verification_token' => null,
            'verification_token_created_at' => null,
        ]);

        return redirect()->route('user.login')->with('success', 'Tài khoản của bạn đã được xác minh thành công!');
    }
}
