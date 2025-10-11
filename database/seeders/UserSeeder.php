<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// Models
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\User\Subrole;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar role
        $roles = ['admin', 'dosen', 'tendik', 'alumni', 'mahasiswa', 'peserta-pmb'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }

        // Daftar subrole (hanya untuk role tertentu)
        $subroles = [
            'tendik' => [
                ['name' => 'baak', 'label' => 'Biro Administrasi Akademik']
            ],
            'dosen'  => [
                ['name' => 'sekprodi', 'label' => 'Sekretaris Program Studi'],
                ['name' => 'kaprodi', 'label' => 'Ketua Program Studi']
            ],
        ];

        foreach ($subroles as $roleName => $subs) {
            $role = Role::where('name', $roleName)->first();

            foreach ($subs as $sub) {
                Subrole::firstOrCreate([
                    'name'    => $sub['name'],
                    'role_id' => $role->id,
                ], [
                    'label'   => $sub['label'],
                    'jobdesk' => null,
                ]);
            }
        }

        // Buat superuser
        $user = User::firstOrCreate([
            'email' => 'superuser@example.com',
        ], [
            'name'     => 'Administrator',
            'photo'    => 'default.jpg',
            'username' => 'superuser',
            'phone'    => '0800000001',
            'code'     => Str::random(6),
            'password' => Hash::make('admin123'),
        ]);

        // Assign beberapa role
        $user->syncRoles(['admin', 'dosen', 'tendik']);

        // Assign subrole otomatis (sekprodi untuk dosen, baak untuk tendik)
        $sekprodi = Subrole::where('name', 'sekprodi')->first();
        $baak     = Subrole::where('name', 'baak')->first();

        $user->subroles()->syncWithoutDetaching([
            $sekprodi->id,
            $baak->id,
        ]);


        // === Tambahkan 1000 user random ===
        // User::factory()->count(100)->create()->each(function ($user) use ($roles) {
        //     // Assign role random dari daftar role
        //     $randomRole = collect($roles)->random();
        //     $user->assignRole($randomRole);

        //     // Kalau role-nya punya subrole, assign subrole random juga
        //     $subroles = Subrole::whereHas('role', fn($q) => $q->where('name', $randomRole))->get();

        //     if ($subroles->isNotEmpty()) {
        //         $user->subroles()->syncWithoutDetaching([$subroles->random()->id]);
        //     }
        // });
    }
}
