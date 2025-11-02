<?php

namespace App\Services\Manager\Users;

use App\Repositories\Manager\Users\PendidikanRepository;

class PendidikanService
{
    protected PendidikanRepository $repository;

    public function __construct(PendidikanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createPendidikan(array $data)
    {
        return $this->repository->create($data);
    }

    public function updatePendidikan(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deletePendidikan(int $id)
    {
        return $this->repository->softDelete($id);
    }

    public function restorePendidikan(int $id)
    {
        return $this->repository->restore($id);
    }

    public function forceDeletePendidikan(int $id)
    {
        return $this->repository->forceDelete($id);
    }
}
