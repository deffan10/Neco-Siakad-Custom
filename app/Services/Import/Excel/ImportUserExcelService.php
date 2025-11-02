<?php

namespace App\Services\Import\Excel;

use App\Models\User;
use App\Models\User\Keluarga;
use App\Models\User\Alamat;
use App\Models\User\Pendidikan;
use App\Models\Referensi\Agama;
use App\Models\Referensi\GolonganDarah;
use App\Models\Referensi\JenisKelamin;
use App\Models\Referensi\Kewarganegaraan;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class ImportUserExcelService
{
    protected $errors = [];
    protected $successCount = 0;
    protected $skipCount = 0;
    protected $options = [];

    public function import($filePath, $options = [])
    {
        $this->errors = [];
        $this->successCount = 0;
        $this->skipCount = 0;

        // Default options
        $this->options = array_merge([
            'skip_duplicates' => true,
            'send_welcome' => false
        ], $options);

        try {
            DB::beginTransaction();

            // Import dari sheet Users
            $this->importUsers($filePath);
            
            DB::commit();

            return [
                'success' => true,
                'message' => "Import berhasil! {$this->successCount} data berhasil diimport, {$this->skipCount} data dilewati.",
                'errors' => $this->errors,
                'success_count' => $this->successCount,
                'skip_count' => $this->skipCount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage(),
                'errors' => $this->errors
            ];
        }
    }

    protected function importUsers($filePath)
    {
        $collection = (new FastExcel)->import($filePath, function ($line) {
            
            // Skip jika data wajib kosong
            if (empty($line['Nama']) || empty($line['Email']) || empty($line['Phone'])) {
                $this->skipCount++;
                $this->errors[] = "Baris dilewati: Nama, Email, dan Phone harus diisi.";
                return null;
            }

            // Cek apakah user sudah ada
            $existingUser = User::where('email', $line['Email'])
                               ->orWhere('phone', $line['Phone'])
                               ->first();

            if ($existingUser) {
                if ($this->options['skip_duplicates']) {
                    $this->skipCount++;
                    $this->errors[] = "User {$line['Nama']} sudah ada (email atau phone duplikat) - dilewati.";
                    return null;
                } else {
                    // Jika tidak skip, update user yang sudah ada
                    $user = $existingUser;
                    $this->errors[] = "User {$line['Nama']} sudah ada - data diperbarui.";
                }
            } else {
                // Create new user
                $user = new User();
            }

            try {
                // Generate unique code
                $code = $this->generateUniqueCode();

                // Cari referensi data
                $agama = $this->findReferensi(Agama::class, $line['Agama'] ?? null);
                $golDarah = $this->findReferensi(GolonganDarah::class, $line['Golongan Darah'] ?? null);
                $jenisKelamin = $this->findReferensi(JenisKelamin::class, $line['Jenis Kelamin'] ?? null);
                $kewarganegaraan = $this->findReferensi(Kewarganegaraan::class, $line['Kewarganegaraan'] ?? null);

                // Data untuk user (create atau update)
                $userData = [
                    'name' => $line['Nama'],
                    'username' => $line['Username'] ?? null,
                    'email' => $line['Email'],
                    'phone' => $line['Phone'],
                    'link_ig' => $line['Instagram'] ?? null,
                    'link_fb' => $line['Facebook'] ?? null,
                    'link_in' => $line['LinkedIn'] ?? null,
                    'nomor_kk' => $line['Nomor KK'] ?? null,
                    'nomor_ktp' => $line['Nomor KTP'] ?? null,
                    'nomor_npwp' => $line['Nomor NPWP'] ?? null,
                    'agama_id' => $agama?->id,
                    'golongan_darah_id' => $golDarah?->id,
                    'jenis_kelamin_id' => $jenisKelamin?->id,
                    'kewarganegaraan_id' => $kewarganegaraan?->id,
                    'tinggi_badan' => $line['Tinggi Badan'] ?? null,
                    'berat_badan' => $line['Berat Badan'] ?? null,
                    'tempat_lahir' => $line['Tempat Lahir'] ?? null,
                    'tanggal_lahir' => $this->parseDate($line['Tanggal Lahir'] ?? null),
                    'is_active' => $this->parseBoolean($line['Status Aktif'] ?? 'Aktif'),
                    'fst_setup' => $this->parseBoolean($line['First Setup'] ?? 'Tidak'),
                    'tfa_setup' => $this->parseBoolean($line['Two Factor Auth'] ?? 'Tidak'),
                ];

                // Tambahkan password dan code hanya untuk user baru
                if (!isset($user->id)) {
                    $userData['code'] = $this->generateUniqueCode();
                    $userData['password'] = Hash::make($line['Password'] ?? 'password123');
                }

                // Create atau update user
                if (isset($user->id)) {
                    $user->update($userData);
                } else {
                    $user = User::create($userData);
                }

                // Assign default role jika tidak ada
                $defaultRole = Role::where('name', 'User')->first();
                if ($defaultRole) {
                    $user->assignRole($defaultRole);
                }

                $this->successCount++;
                return $user;

            } catch (\Exception $e) {
                $this->errors[] = "Error import user {$line['Nama']}: " . $e->getMessage();
                return null;
            }
        });

        return $collection->filter(); // Remove null values
    }

    public function importKeluarga($filePath)
    {
        return (new FastExcel)->import($filePath, 'Keluarga', function ($line) {
            if (empty($line['User ID']) || empty($line['Nama'])) {
                return null;
            }

            $user = User::find($line['User ID']);
            if (!$user) {
                $this->errors[] = "User dengan ID {$line['User ID']} tidak ditemukan untuk keluarga {$line['Nama']}.";
                return null;
            }

            return Keluarga::create([
                'user_id' => $line['User ID'],
                'hubungan' => $line['Hubungan'] ?? 'Lainnya',
                'nama' => $line['Nama'],
                'pekerjaan' => $line['Pekerjaan'] ?? null,
                'telepon' => $line['Telepon'] ?? null,
                'tempat_lahir' => $line['Tempat Lahir'] ?? null,
                'tanggal_lahir' => $this->parseDate($line['Tanggal Lahir'] ?? null),
                'penghasilan' => is_numeric($line['Penghasilan'] ?? 0) ? (int)$line['Penghasilan'] : 0,
                'alamat' => $line['Alamat'] ?? null,
            ]);
        });
    }

    public function importAlamat($filePath)
    {
        return (new FastExcel)->import($filePath, 'Alamat', function ($line) {
            if (empty($line['User ID']) || empty($line['Alamat Lengkap'])) {
                return null;
            }

            $user = User::find($line['User ID']);
            if (!$user) {
                $this->errors[] = "User dengan ID {$line['User ID']} tidak ditemukan untuk alamat.";
                return null;
            }

            return Alamat::create([
                'user_id' => $line['User ID'],
                'tipe' => strtolower($line['Tipe'] ?? 'ktp'),
                'alamat_lengkap' => $line['Alamat Lengkap'],
                'kelurahan' => $line['Kelurahan'] ?? null,
                'kecamatan' => $line['Kecamatan'] ?? null,
                'kota_kabupaten' => $line['Kota/Kabupaten'] ?? null,
                'provinsi' => $line['Provinsi'] ?? null,
                'kode_pos' => $line['Kode Pos'] ?? null,
                'rt' => $line['RT'] ?? null,
                'rw' => $line['RW'] ?? null,
            ]);
        });
    }

    public function importPendidikan($filePath)
    {
        return (new FastExcel)->import($filePath, 'Pendidikan', function ($line) {
            if (empty($line['User ID']) || empty($line['Nama Institusi'])) {
                return null;
            }

            $user = User::find($line['User ID']);
            if (!$user) {
                $this->errors[] = "User dengan ID {$line['User ID']} tidak ditemukan untuk pendidikan.";
                return null;
            }

            return Pendidikan::create([
                'user_id' => $line['User ID'],
                'jenjang' => $line['Jenjang'] ?? 'SMA',
                'nama_institusi' => $line['Nama Institusi'],
                'jurusan' => $line['Jurusan'] ?? null,
                'tahun_masuk' => is_numeric($line['Tahun Masuk'] ?? null) ? (int)$line['Tahun Masuk'] : null,
                'tahun_lulus' => is_numeric($line['Tahun Lulus'] ?? null) ? (int)$line['Tahun Lulus'] : null,
                'ipk' => $line['IPK'] ?? null,
                'alamat' => $line['Alamat'] ?? null,
            ]);
        });
    }

    protected function generateUniqueCode()
    {
        do {
            $code = 'USR' . date('Y') . strtoupper(Str::random(6));
        } while (User::where('code', $code)->exists());

        return $code;
    }

    protected function findReferensi($model, $nama)
    {
        if (empty($nama)) return null;
        
        return $model::where('nama', 'like', '%' . $nama . '%')->first();
    }

    protected function parseDate($dateString)
    {
        if (empty($dateString)) return null;

        try {
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        
        $value = strtolower(trim($value));
        return in_array($value, ['ya', 'yes', '1', 'true', 'aktif', 'active']);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getStats()
    {
        return [
            'success_count' => $this->successCount,
            'skip_count' => $this->skipCount,
            'error_count' => count($this->errors)
        ];
    }
}
