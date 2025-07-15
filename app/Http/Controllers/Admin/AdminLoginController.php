<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
   
    
    public function logout(Request $request)
    {
       
        Auth::guard('admin')->logout();
           
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
