<?php

namespace App\Http\Requests\ApiKey;

use App\Constants\ApiKey\ApiKeyConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListRequest extends FormRequest
{
    /**
     * defaultSort
     *
     * @var string
     */
    private $defaultSort = 'id';

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
            'order_by_dir' => [
                'nullable',
                Rule::in(['asc', 'desc']),
            ],
            'page' => 'nullable|numeric',
            'per_page' => 'nullable|numeric',
            'order_by' => [
                'nullable',
                Rule::in($this->validSort()),
            ],
            'filters' => 'nullable|array',
            'filters.status' => [
                'nullable',
                Rule::in($this->validStatus()),
            ],
            'filters.user_id' => [
                'nullable',
                Rule::exists('users', 'id'),
            ],
            'filters.environment_id' => [
                'nullable',
                Rule::exists('api_key_environments', 'id'),
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
            'order_by.in' => __('ORDER_BY_INVALID'),
            'order_by_dir.in' => __('ORDER_BY_DIR_INVALID'),
            'page.numeric' => __('PAGE_INVALID'),
            'per_page.numeric' => __('PER_PAGE_INVALID'),
            'filters.array' => __('FILTERS_MUST_BE_ARRAY'),
            'filters.status.in' => __('FILTER_STATUS_INVALID'),
            'filters.user_id.exists' => __('FILTER_USER_ID_INVALID'),
            'filters.environment_id.exists' => __('FILTER_ENVIRONMENT_INVALID'),
        ];
    }

    /**
     * prepareForValidation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $requestOrderBy = $this->get('order_by');
        $requestOrderByDir = $this->get('order_by_dir');
        $filters = $this->get('filters', []);

        // if not valid; default order by dir
        if (! in_array($requestOrderByDir, ['asc', 'desc'])) {
            $requestOrderByDir = 'asc';
        }

        // if not valid; default sort
        if (! in_array($requestOrderBy, $this->validSort())) {
            $requestOrderBy = $this->defaultSort;
        }

        $this->merge([
            'order_by' => $requestOrderBy,
            'order_by_dir' => $requestOrderByDir,
            'filters' => $filters,
        ]);
    }

    /**
     * validSort
     *
     * @return array<string>
     */
    private function validSort()
    {
        return [
            'id',
            'name',
            'key_prefix',
            'environment_name',
            'user_id',
            'user_name',
            'user_email',
            'is_active',
            'created_at',
            'expires_at',
            'last_used_at',
        ];
    }

    /**
     * validStatus
     *
     * @return array<string>
     */
    private function validStatus()
    {
        return [
            ApiKeyConstant::KEY_STATUS_ACTIVE,
            ApiKeyConstant::KEY_STATUS_INACTIVE,
        ];
    }
}
