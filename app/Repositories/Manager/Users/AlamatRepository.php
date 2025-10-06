<?php

namespace App\Repositories\Manager\Users;

use App\Models\User\Alamat;
use Illuminate\Database\Eloquent\Collection;

class AlamatRepository
{
    /**
     * Get all alamat with user relation
     */
    public function getAll(): Collection
    {
        return Alamat::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all trashed alamat with user relation
     */
    public function getTrashed(): Collection
    {
        return Alamat::onlyTrashed()
            ->with(['user'])
            ->get();
    }

    /**
     * Find alamat by ID
     */
    public function findById(int $id): Alamat
    {
        return Alamat::findOrFail($id);
    }

    /**
     * Find trashed alamat by ID
     */
    public function findTrashedById(int $id): Alamat
    {
        return Alamat::onlyTrashed()->findOrFail($id);
    }

    /**
     * Create new alamat
     */
    public function create(array $data): Alamat
    {
        return Alamat::create($data);
    }

    /**
     * Update alamat
     */
    public function update(Alamat $alamat, array $data): bool
    {
        return $alamat->update($data);
    }

    /**
     * Soft delete alamat
     */
    public function delete(Alamat $alamat): ?bool
    {
        return $alamat->delete();
    }

    /**
     * Restore alamat
     */
    public function restore(Alamat $alamat): ?bool
    {
        return $alamat->restore();
    }

    /**
     * Force delete alamat
     */
    public function forceDelete(Alamat $alamat): ?bool
    {
        return $alamat->forceDelete();
    }
}
