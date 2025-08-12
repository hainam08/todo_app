<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\PseudoTypes\True_;

class AdminLoginRequest extends FormRequest
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
        ];
    }
    
    public function passedValidation():void
    {
        $credentials = $this->only('email','password');
        if(!Auth::guard('admin')->attempt($credentials)){
            throw ValidationException::withMessages([
                'email'=>'Email hoặc mật khẩu không đúng'
            ]);
        }
    }
}
