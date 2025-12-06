<?php

namespace App\Repositories;

abstract class Repository implements RepositoryInterface
{
    /**
     * model
     *
     * @var mixed
     */
    protected $model;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->model = app($this->getModel());
    }

    /**
     * Get model name with namespace
     *
     * @return string
     */
    abstract public function getModel();

    /**
     * Get all resources
     *
     * @return object
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * Stores newly created resource
     *
     * @param  array<mixed>  $data
     * @return object
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update specific resource.
     *
     * @param  int  $id
     * @param  array<mixed>  $data
     * @return bool
     */
    public function update($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    /**
     * Delete specific resource
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Find specific resource
     *
     * @param  int  $id
     * @param  array<mixed>  $columns
     * @return object
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find specific resource by given attribute
     *
     * @param  mixed  $attribute
     * @param  mixed  $value
     * @param  array<mixed>  $columns
     * @return object
     */
    public function findBy($attribute, $value, array $columns = ['*'])
    {
        return $this->model->where($attribute, $value)->first($columns);
    }

    /**
     * count
     *
     * @return mixed
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Update or Create resource
     *
     * @param  array<mixed>  $criteria
     * @param  array<mixed>  $update
     * @return object
     */
    public function updateOrCreate(array $criteria, array $update)
    {
        return $this->model->updateOrCreate($criteria, $update);
    }
}
