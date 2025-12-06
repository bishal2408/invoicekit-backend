<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all resources
     *
     * @param  array<mixed>  $columns
     * @return object
     */
    public function all(array $columns = ['*']);

    /**
     * Stores newly created resource
     *
     * @param  array<mixed>  $data
     * @return object
     */
    public function store(array $data);

    /**
     * Update specific resource.
     *
     * @param  int  $id
     * @param  array<mixed>  $data
     * @return bool
     */
    public function update($id, array $data);

    /**
     * Delete specific resource
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Find specific resource
     *
     * @param  int  $id
     * @param  array<mixed>  $columns
     * @return object
     */
    public function find($id, array $columns = ['*']);

    /**
     * Find specific resource by given attribute
     *
     * @param  mixed  $attribute
     * @param  mixed  $value
     * @param  array<mixed>  $columns
     * @return object
     */
    public function findBy($attribute, $value, array $columns = ['*']);

    /**
     * count
     *
     * @return mixed
     */
    public function count();

    /**
     * Update or Create resource
     *
     * @param  array<mixed>  $criteria
     * @param  array<mixed>  $update
     * @return mixed
     */
    public function updateOrCreate(array $criteria, array $update);
}
