<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Use System
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// Use Models
use App\Models\User;
use Spatie\Permission\Models\Role;
// Use Plugins

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $roles = ['admin', 'dosen', 'tendik', 'alumni','mahasiswa' ,'peserta-pmb'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $user = User::create([
            'name' => 'Administrator',
            'photo' => 'default.jpg',
            'username' => 'superuser',
            'phone' => '0800000001',
            'email' => 'superuser@example.com',
            'code' => Str::random(6),
            'password' => Hash::make('admin123'),
    
            // nullable foreign keys
            'agama_id' => null,
            'golongan_darah_id' => null,
            'jenis_kelamin_id' => null,
            'kewarganegaraan_id' => null,
        ]);

        $user->assignRole('admin', 'dosen', 'tendik');
    }
}
