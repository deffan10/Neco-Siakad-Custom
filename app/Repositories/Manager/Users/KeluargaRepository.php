<?php

namespace App\Repositories\Manager\Users;

use App\Models\User\Keluarga;
use Illuminate\Database\Eloquent\Collection;

class KeluargaRepository
{
    public function query(bool $withTrashed = false)
    {
        $q = Keluarga::query()->with(['user']);
        if ($withTrashed) {
            $q = Keluarga::withTrashed()->with(['user']);
        }

        return $q;
    }

    public function create(array $data): Keluarga
    {
        return Keluarga::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $model = Keluarga::findOrFail($id);

        return $model->update($data);
    }

    public function softDelete(int $id, ?int $userId = null): void
    {
        $model = Keluarga::findOrFail($id);
        if ($userId) {
            $model->update(['deleted_by' => $userId]);
        }
        $model->delete();
    }

    public function restore(int $id, ?int $userId = null): void
    {
        $model = Keluarga::onlyTrashed()->findOrFail($id);
        if ($userId) {
            $model->update(['updated_by' => $userId, 'deleted_by' => null]);
        }
        $model->restore();
    }

    public function forceDelete(int $id): void
    {
        $model = Keluarga::onlyTrashed()->findOrFail($id);
        $model->forceDelete();
    }

    public function getAll(): Collection
    {
        return Keluarga::with(['user'])->orderBy('created_at', 'desc')->get();
    }
}
