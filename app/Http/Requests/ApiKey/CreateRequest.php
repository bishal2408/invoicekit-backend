<?php

namespace App\Http\Requests\ApiKey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'environment' => [
                'required',
                Rule::in(config('settings.api_key.environments')),
            ],
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'plan_id' => [
                'required',
                Rule::exists('plans', 'id'),
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
            'name.required' => __('NAME_REQUIRED'),
            'name.string' => __('NAME_STRING'),
            'name.max' => __('NAME_MAX'),
            'environment.required' => __('ENVIRONMENT_REQUIRED'),
            'environment.in' => __('ENVIRONMENT_INVALID'),
            'user_id.required' => __('USER_REQUIRED'),
            'user_id.exists' => __('USER_INVALID'),
            'plan_id.required' => __('PLAN_REQUIRED'),
            'plan_id.exists' => __('PLAN_INVALID'),
        ];
    }
}
