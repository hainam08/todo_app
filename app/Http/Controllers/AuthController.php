<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Task;
class AuthController extends Controller
{
    public function verify($token)
    {
        $minute = Task::MINUTES_TO_EXPIRE;
        $tokenRecord = EmailVerificationToken::where('token',$token)->first();
        

        if (!$tokenRecord) {
            return redirect()->route('user.login')->with('error', 'Token không hợp lệ hoặc đã được sử dụng.');
        }

        $created = $tokenRecord->created_at;
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        if ($now->diffInMinutes($created) > $minute) {
            return redirect()->route('user.login')->with('error', 'Xác minh đã hết hạn. Vui lòng yêu cầu gửi lại mã xác minh.');
        }
        $user= $tokenRecord->user;
        if($user->email_verified_at){
            return redirect()->route('user.login')->with('success','Tài khoản đã được xác minh thành công');
        }
        $user->email_verified_at = now('Asia/Ho_Chi_Minh');
        $user->save();

        return redirect()->route('user.login')->with('success', 'Tài khoản của bạn đã được xác minh thành công!');
    }
}
