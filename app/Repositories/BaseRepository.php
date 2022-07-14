<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /** @var Model */
    protected $model;

    abstract public function model();

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    /**
     * Create a model instance
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Update a model instance
     *
     * @param int $modelId
     * @param array $attributes
     *
     * @return Model
     */
    public function update(int $modelId, array $attributes): Model
    {
        return tap($this->find($modelId))->update($attributes);
    }

    /**
     * Find a model for a given ID
     *
     * @param int $modelId
     * @return Model|null
     */
    public function find(int $modelId): ?Model
    {
        return $this->model->find($modelId);
    }

    /**
     * Delete a model
     *
     * @param int $modelId
     */
    public function delete(int $modelId): void
    {
        $this->find($modelId)->delete();
    }

    /**
     * Return all models
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Return a model defined on model method
     *
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function resolveModel()
    {
        if (!method_exists($this, 'model')) {
            throw new EntityNotDefined();
        }

        return app($this->model());
    }
}
