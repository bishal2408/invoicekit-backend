<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'email' => [
                'required',
                Rule::exists('users', 'email'),
            ],
            'password' => [
                'required',
            ],
        ];
    }

    /**
     * messages
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'email.required' => __('EMAIL_REQUIRED'),
            'email.exists' => __('EMAIL_NOT_FOUND'),
            'password.required' => __('PASSWORD_REQUIRED'),
        ];
    }
}
