<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\WelcomeMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendWelcomeEmail;
use App\Models\Task;
class VerificationController extends Controller
{
    public function resend(Request $request)
    {
        $minute = Task::MINUTES_TO_EXPIRE;
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user->is_active) {
            return redirect()->route('user.dashboard')->with('success', 'Tài khoản của bạn đã được xác minh');
        }
        $createdAt = Carbon::parse($user->verification_token_created_at, 'Asia/Ho_Chi_Minh');
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        if ($now->diffInMinutes($createdAt) < $minute) {
            $minutesLeft = $minute - $now->diffInMinutes($createdAt);
            return back()->withErrors(['email' => "Vui lòng chờ thêm {$minutesLeft} phút để gửi lại email xác minh."]);
        }

        // Tạo token mới
        $newToken = Str::random(64);
        $user->update([
            'verification_token' => $newToken,
            'verification_token_created_at' => $now,
        ]);
        $user = $user->fresh();
        SendWelcomeEmail::dispatch($user);

        return back()->with('success', 'Email xác minh mới đã được gửi thành công!');
    }
}

