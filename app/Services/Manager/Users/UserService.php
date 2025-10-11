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
     * Update user
     *
     * @throws \Throwable
     */
    public function updateUser(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->userRepository->findById($id);

            // Handle password update
            if (isset($data['current_password']) && ! empty($data['current_password'])) {
                // Verify current password
                if (! Hash::check($data['current_password'], $user->password)) {
                    throw new \Exception('Password lama tidak sesuai.');
                }

                // Validate new password and confirmation
                if (! isset($data['new_password']) || empty($data['new_password'])) {
                    throw new \Exception('Password baru wajib diisi.');
                }

                if (! isset($data['new_password_confirmation']) || $data['new_password'] !== $data['new_password_confirmation']) {
                    throw new \Exception('Konfirmasi password baru tidak sesuai.');
                }

                $data['password'] = Hash::make($data['new_password']);
            }

            // Remove password-related fields from data
            unset($data['current_password'], $data['new_password'], $data['new_password_confirmation']);

            // Remove password if not set
            if (! isset($data['password'])) {
                unset($data['password']);
            }

            $data['updated_by'] = Auth::id();

            // Extract roles before updating user
            $roleIds = $data['roles'] ?? null;
            unset($data['roles']);

            // Extract alamat data
            $alamatKtp = $data['alamat_ktp'] ?? null;
            $alamatDomisili = $data['alamat_domisili'] ?? null;
            unset($data['alamat_ktp'], $data['alamat_domisili']);

            // Extract pendidikan data
            $pendidikanData = $data['pendidikan'] ?? null;
            unset($data['pendidikan']);

            // Extract keluarga data
            $keluargaData = $data['keluarga'] ?? null;
            unset($data['keluarga']);

            // Handle photo upload
            if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $photoPath = $data['photo']->store('photos/users', 'public');
                $data['photo'] = '/storage/'.$photoPath;
            } else {
                unset($data['photo']);
            }

            // Update user
            $this->userRepository->update($user, $data);

            // Sync roles if provided
            if ($roleIds !== null) {
                $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
                $user->syncRoles($roles);
            }

            // Update or create alamat KTP
            if ($alamatKtp !== null) {
                $alamatKtp['tipe'] = 'ktp';
                $alamatKtp['user_id'] = $user->id;
                if (isset($alamatKtp['id']) && ! empty($alamatKtp['id'])) {
                    $user->alamats()->where('id', $alamatKtp['id'])->update($alamatKtp);
                } else {
                    $user->alamats()->create($alamatKtp);
                }
            }

            // Update or create alamat domisili
            if ($alamatDomisili !== null) {
                $alamatDomisili['tipe'] = 'domisili';
                $alamatDomisili['user_id'] = $user->id;
                if (isset($alamatDomisili['id']) && ! empty($alamatDomisili['id'])) {
                    $user->alamats()->where('id', $alamatDomisili['id'])->update($alamatDomisili);
                } else {
                    $user->alamats()->create($alamatDomisili);
                }
            }

            // Update pendidikan data
            if ($pendidikanData !== null && is_array($pendidikanData)) {
                foreach ($pendidikanData as $pendidikan) {
                    $pendidikan['user_id'] = $user->id;
                    if (isset($pendidikan['id']) && ! empty($pendidikan['id'])) {
                        $user->pendidikans()->where('id', $pendidikan['id'])->update($pendidikan);
                    } else {
                        unset($pendidikan['id']);
                        $user->pendidikans()->create($pendidikan);
                    }
                }
            }

            // Update keluarga data
            if ($keluargaData !== null && is_array($keluargaData)) {
                foreach ($keluargaData as $keluarga) {
                    $keluarga['user_id'] = $user->id;
                    if (isset($keluarga['id']) && ! empty($keluarga['id'])) {
                        $user->keluargas()->where('id', $keluarga['id'])->update($keluarga);
                    } else {
                        unset($keluarga['id']);
                        $user->keluargas()->create($keluarga);
                    }
                }
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
