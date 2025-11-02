<?php

namespace App\Services\Manager\Users;

use App\Repositories\Manager\Users\KeluargaRepository;

class KeluargaService
{
    protected KeluargaRepository $repo;

    public function __construct(KeluargaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createKeluarga(array $data): void
    {
        $userId = auth()->id();
        $data['created_by'] = $userId;
        $this->repo->create($data);
    }

    public function updateKeluarga(int $id, array $data): void
    {
        $data['updated_by'] = auth()->id();
        $this->repo->update($id, $data);
    }

    public function deleteKeluarga(int $id): void
    {
        $this->repo->softDelete($id, auth()->id());
    }

    public function restoreKeluarga(int $id): void
    {
        $this->repo->restore($id, auth()->id());
    }

    public function forceDeleteKeluarga(int $id): void
    {
        $this->repo->forceDelete($id);
    }
}
