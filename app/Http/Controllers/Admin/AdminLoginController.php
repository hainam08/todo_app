<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;



class AdminLoginController extends Controller
{
    public function showAdminLoginForm()
    {
        return view('auth.admin-sign-in');
    }
    

    public function adminLogin(AdminLoginRequest $request)
    {
        return redirect()->route('admin.dashboard');
    }
   
    
    public function logout(Request $request)
    {
       
        Auth::guard('admin')->logout();
           
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
