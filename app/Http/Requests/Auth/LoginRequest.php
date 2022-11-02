<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'string', Rule::exists('users', 'email')],
            'password' => ['required', 'min:6', 'max:26', 'string', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password must include minimum 1 lowercase, 1 uppercase and 1 number.',
            'email.exists' => 'No user found with the provided email, Please sure to enter a valid email address'
        ];
    }
}
