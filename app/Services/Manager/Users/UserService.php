<?php

namespace App\Services\Manager\Users;

use App\Mail\welcomeMail;
use App\Models\Pengaturan\Kampus;
use App\Models\User;
use App\Models\User\Role;
use App\Repositories\Manager\Users\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create new user
     *
     * @throws \Throwable
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Generate code if not provided
            if (! isset($data['code'])) {
                $data['code'] = uniqid();
            }

            // Set username from phone if not provided
            if (! isset($data['username']) || empty($data['username'])) {
                $data['username'] = $data['phone'];
            }

            // Generate temporary password if not provided
            $temporaryPassword = $data['password'] ?? $data['code'];
            $data['password'] = Hash::make($temporaryPassword);

            // Set created_by
            $data['created_by'] = Auth::id();

            // Extract roles before creating user
            $roleIds = $data['roles'] ?? [];
            unset($data['roles']);

            // Create user
            $user = $this->userRepository->create($data);

            // Assign roles
            if (! empty($roleIds)) {
                $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
                if (count($roles) > 0) {
                    $user->assignRole($roles);
                }
            }

            // Send welcome email
            try {
                $academy = Kampus::first();
                Mail::to($user->email)->send(new welcomeMail($user, $academy, $temporaryPassword));
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email: '.$e->getMessage());
            }

            return $user->fresh();
        });
    }

    
    /**
     * Delete user (soft delete)
     *
     * @throws \Throwable
     */
    public function deleteUser(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $user = $this->userRepository->findById($id);

            // Prevent deleting self
            if ($user->id === Auth::id()) {
                throw new \Exception('Anda tidak dapat menghapus diri sendiri.');
            }

            $this->userRepository->update($user, [
                'deleted_at' => now(),
                'deleted_by' => Auth::id(),
            ]);

            // Soft delete related data
            $user->alamats()->delete();
            $user->keluargas()->delete();
            $user->pendidikans()->delete();

            return $this->userRepository->delete($user);
        });
    }

    /**
     * Restore deleted user
     *
     * @throws \Throwable
     */
    public function restoreUser(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $user = $this->userRepository->findTrashedById($id);

            $this->userRepository->update($user, [
                'updated_by' => Auth::id(),
                'deleted_by' => null,
            ]);

            // Restore related data
            $user->alamats()->restore();
            $user->keluargas()->restore();
            $user->pendidikans()->restore();

            return $this->userRepository->restore($user);
        });
    }

    /**
     * Force delete user permanently
     *
     * @throws \Throwable
     */
    public function forceDeleteUser(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $user = $this->userRepository->findTrashedById($id);

            // Force delete related data
            $user->alamats()->forceDelete();
            $user->keluargas()->forceDelete();
            $user->pendidikans()->forceDelete();

            return $this->userRepository->forceDelete($user);
        });
    }

    /**
     * Delete pendidikan
     */
    public function deletePendidikan(int $pendidikanId): bool
    {
        return DB::transaction(function () use ($pendidikanId) {
            $pendidikan = \App\Models\User\Pendidikan::findOrFail($pendidikanId);

            return $pendidikan->delete();
        });
    }

    /**
     * Delete keluarga
     */
    public function deleteKeluarga(int $keluargaId): bool
    {
        return DB::transaction(function () use ($keluargaId) {
            $keluarga = \App\Models\User\Keluarga::findOrFail($keluargaId);

            return $keluarga->delete();
        });
    }
}
