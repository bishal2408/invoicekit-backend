<?php

namespace App\Http\Requests\ApiKey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegenerateRequest extends FormRequest
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
        $this->merge(['id' => $this->route('api_key')]);

        return [
            'id' => [
                'required',
                Rule::exists('api_keys', 'id')
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
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
            'id.required' => __('KEY_ID_REQUIRED'),
            'id.exists' => __('INVALID_KEY_ID'),
        ];
    }
}
