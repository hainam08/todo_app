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
use App\Models\User;
use App\Models\EmailVerificationToken;
class VerificationController extends Controller
{
    public function resend(Request $request)
    {
        $minute = Task::MINUTES_TO_EXPIRE;
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user->email_verified_at) {
            return redirect()->route('user.dashboard')->with('success', 'Tài khoản của bạn đã được xác minh');
        }
        $latestToken = $user->emailVerificationTokens;
        if ($latestToken) {
            $createdAt = $latestToken->created_at;
            $now = Carbon::now('Asia/Ho_Chi_Minh');

            if ($now->diffInMinutes($createdAt) < $minute) {
                $minutesLeft = $minute - $now->diffInMinutes($createdAt);
                return back()->withErrors(['email' => "Vui lòng chờ thêm {$minutesLeft} phút để gửi lại email xác minh."]);
            }

            // Hết hạn thì xóa token cũ
            $latestToken->delete();
        }

        // Tạo token mới
        $newToken = Str::random(64);

        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token' => $newToken,
        ]);
        SendWelcomeEmail::dispatch($user,$newToken);

        return back()->with('success', 'Email xác minh mới đã được gửi thành công!');
    }
}

