<?php

namespace App\Repositories\ApiKey;

use App\Constants\ApiKey\ApiKeyConstant;
use App\Models\ApiKey\ApiKey;
use App\Repositories\Repository;

class ListRepository extends Repository
{
    /**
     * Get model name with namespace
     *
     * @return string
     */
    public function getModel()
    {
        return ApiKey::class;
    }

    /**
     * list
     *
     * @param  mixed  $perPage
     * @param  mixed  $orderBy
     * @param  mixed  $orderByDir
     * @param  mixed  $filters
     * @param  mixed  $search
     * @return mixed
     */
    public function list($perPage, $orderBy, $orderByDir, $filters, $search)
    {
        $query = $this->baseQuery(
            $this->listSelect(),
            $filters,
            $search
        );

        return $query
            ->orderBy($orderBy, $orderByDir)
            ->when(
                $perPage,
                fn ($query, $perPage) => $query->paginate($perPage),
                fn ($query) => $query->get()

            );
    }

    /**
     * baseQuery
     *
     * @param  array<mixed>  $select
     * @param  array<mixed, mixed>  $filters
     * @param  mixed  $search
     * @return mixed
     */
    private function baseQuery($select, $filters, $search)
    {
        $query = $this->model
            ->select($select)
            ->join(
                'api_key_environments',
                'api_key_environments.id',
                '=',
                'api_keys.environment_id'
            )
            ->leftJoin('users', 'users.id', '=', 'api_keys.user_id');

        // search query
        $query = $this->searchQuery($query, $search);

        // filter query
        return $this->filterQuery($query, $filters);
    }

    /**
     * filterQuery
     *
     * @param  mixed  $query
     * @param  mixed  $filters
     * @return mixed
     */
    private function filterQuery($query, $filters)
    {
        // filter by user_id
        if (isset($filters['user_id'])) {
            $query->where('users.id', $filters['user_id']);
        }

        // filter by environment_id
        if (isset($filters['environment_id'])) {
            $query->where('api_key_environments.id', $filters['environment_id']);
        }

        // filter by status
        if (isset($filters['status'])) {
            // filter by active
            if ($filters['status'] == ApiKeyConstant::KEY_STATUS_ACTIVE) {
                $query->where('api_keys.is_active', true);

                // filter by inactive
            } elseif ($filters['status'] == ApiKeyConstant::KEY_STATUS_INACTIVE) {
                $query->whereNull('api_keys.is_active');
            }
        }

        return $query;
    }

    /**
     * searchQuery
     *
     * @param  mixed  $query
     * @param  mixed  $search
     * @return mixed
     */
    private function searchQuery($query, $search)
    {
        if (! empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('api_keys.name', 'like', '%' . $search . '%')
                    ->orWhere('api_keys.key_prefix', 'like', '%' . $search . '%')
                    ->orWhere('api_keys.id', 'like', '%' . $search . '%')
                    ->orWhere('api_keys.environment_id', 'like', '%' . $search . '%')
                    ->orWhere('api_keys.last_used_at', 'like', '%' . $search . '%')
                    ->orWhere('api_keys.expires_at', 'like', '%' . $search . '%')
                    ->orWhere('api_key_environments.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('users.name', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    /**
     * listSelect
     *
     * @return array<mixed>
     */
    private function listSelect()
    {
        return [
            'api_keys.id',
            'api_keys.name',
            'api_keys.key_prefix',
            'api_key_environments.name as environment_name',
            'users.id as user_id',
            'users.name as user_name',
            'users.email as user_email',
            'api_keys.is_active',
            'api_keys.expires_at',
            'api_keys.last_used_at',
            'api_keys.created_at',
        ];
    }
}
