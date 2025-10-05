<?php

namespace App\Services\Manager\Users;

use App\Models\User\Alamat;
use App\Repositories\Manager\Users\AlamatRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlamatService
{
    protected AlamatRepository $alamatRepository;

    public function __construct(AlamatRepository $alamatRepository)
    {
        $this->alamatRepository = $alamatRepository;
    }

    /**
     * Get all alamat
     *
     * @return Collection
     */
    public function getAllAlamat(): Collection
    {
        return $this->alamatRepository->getAll();
    }

    /**
     * Get all trashed alamat
     *
     * @return Collection
     */
    public function getTrashedAlamat(): Collection
    {
        return $this->alamatRepository->getTrashed();
    }

    /**
     * Create new alamat
     *
     * @param array $data
     * @return Alamat
     * @throws \Throwable
     */
    public function createAlamat(array $data): Alamat
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = Auth::id();
            
            return $this->alamatRepository->create($data);
        });
    }

    /**
     * Update alamat
     *
     * @param int $id
     * @param array $data
     * @return Alamat
     * @throws \Throwable
     */
    public function updateAlamat(int $id, array $data): Alamat
    {
        return DB::transaction(function () use ($id, $data) {
            $alamat = $this->alamatRepository->findById($id);
            
            $data['updated_by'] = Auth::id();
            
            $this->alamatRepository->update($alamat, $data);
            
            return $alamat->fresh();
        });
    }

    /**
     * Delete alamat (soft delete)
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function deleteAlamat(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $alamat = $this->alamatRepository->findById($id);
            
            $this->alamatRepository->update($alamat, [
                'deleted_by' => Auth::id()
            ]);
            
            return $this->alamatRepository->delete($alamat);
        });
    }

    /**
     * Restore deleted alamat
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function restoreAlamat(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $alamat = $this->alamatRepository->findTrashedById($id);
            
            $this->alamatRepository->update($alamat, [
                'updated_by' => Auth::id(),
                'deleted_by' => null
            ]);
            
            return $this->alamatRepository->restore($alamat);
        });
    }
}
