<?php

namespace App\Repositories\Manager\Users;

use App\Models\User\Role;

class RoleRepository
{
    protected Role $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->newQuery();
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Role
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

    public function findById(int $id): Role
    {
        return $this->model->findOrFail($id);
    }

    public function findTrashedById(int $id): Role
    {
        return $this->model->onlyTrashed()->findOrFail($id);
    }

    public function canDelete(int $id): bool
    {
        $role = $this->model->findOrFail($id);

        // Check if role has users
        if ($role->users()->count() > 0) {
            return false;
        }

        // Check if role has subroles
        if ($role->subroles()->count() > 0) {
            return false;
        }

        return true;
    }
}
