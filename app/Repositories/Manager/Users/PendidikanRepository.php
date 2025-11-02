<?php

namespace App\Repositories\Manager\Users;

use App\Models\User\Pendidikan;

class PendidikanRepository
{
    protected Pendidikan $model;

    public function __construct(Pendidikan $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->newQuery();
    }

    public function create(array $data): Pendidikan
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Pendidikan
    {
        $entity = $this->model->findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function softDelete(int $id): void
    {
        $entity = $this->model->findOrFail($id);
        $entity->delete();
    }

    public function restore(int $id): void
    {
        $entity = $this->model->onlyTrashed()->findOrFail($id);
        $entity->restore();
    }

    public function forceDelete(int $id): void
    {
        $entity = $this->model->onlyTrashed()->findOrFail($id);
        $entity->forceDelete();
    }

    public function getAll($withTrashed = false)
    {
        $query = $this->model->newQuery();
        if ($withTrashed) {
            return $query->withTrashed()->get();
        }

        return $query->get();
    }
}
