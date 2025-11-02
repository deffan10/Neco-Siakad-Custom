<?php

namespace App\Repositories\Manager\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Get all users with roles relation
     */
    public function getAll(): Collection
    {
        return User::with(['roles'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all trashed users with roles relation
     */
    public function getTrashed(): Collection
    {
        return User::onlyTrashed()
            ->with(['roles', 'deletedBy'])
            ->get();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Find trashed user by ID
     */
    public function findTrashedById(int $id): User
    {
        return User::onlyTrashed()->findOrFail($id);
    }

    /**
     * Create new user
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update user
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Soft delete user
     */
    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    /**
     * Restore user
     */
    public function restore(User $user): ?bool
    {
        return $user->restore();
    }

    /**
     * Force delete user
     */
    public function forceDelete(User $user): ?bool
    {
        return $user->forceDelete();
    }
}
