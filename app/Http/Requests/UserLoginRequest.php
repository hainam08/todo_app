<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'remember'=>'nullable|boolean',
        ];
    }

    public function passedValidation():void
    {
        $credentials = $this->only('email','password');
        $remember = $this->boolean('remember');

        if(!Auth::guard('web')->attempt($credentials,$remember)){
            throw ValidationException::withMessages([
                'email'=>'Email hoặc mật khẩu không đúng'
            ]);
        }

        $user = Auth::user();

        if($user->is_locked){
            Auth::logout();
            throw ValidationException::withMessages([
                'email'=>'Tài khoản của bạn đã bị khóa'
            ]);
        }
        if(!$user->email_verified_at){
            Auth::logout();
            throw ValidationException::withMessages([
                'email'=>'Tài khoản của bạn chưa được xác minh, vui lòng xác minh tài khoản'
            ]);
        }
    }
}
