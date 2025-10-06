<?php

namespace App\Services\Manager\Users;

use App\Models\User\Role;
use App\Repositories\Manager\Users\RoleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleService
{
    protected RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = Auth::id();

            return $this->repository->create($data);
        });
    }

    public function updateRole(int $id, array $data): Role
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $this->repository->findById($id);

            $data['updated_by'] = Auth::id();

            $this->repository->update($id, $data);

            return $role->fresh();
        });
    }

    public function deleteRole(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            if (! $this->repository->canDelete($id)) {
                throw new \Exception('Role tidak dapat dihapus karena masih digunakan oleh pengguna atau memiliki subrole terkait.');
            }

            $role = $this->repository->findById($id);

            $this->repository->update($id, [
                'deleted_by' => Auth::id(),
            ]);

            $this->repository->softDelete($id);

            return true;
        });
    }

    public function restoreRole(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $role = $this->repository->findTrashedById($id);

            $this->repository->update($id, [
                'updated_by' => Auth::id(),
                'deleted_by' => null,
            ]);

            $this->repository->restore($id);

            return true;
        });
    }

    public function forceDeleteRole(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $role = $this->repository->findTrashedById($id);

            $this->repository->forceDelete($id);

            return true;
        });
    }
}
