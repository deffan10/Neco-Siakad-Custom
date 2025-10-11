<?php

namespace App\Services\Import\Excel;

use App\Models\User;
use App\Models\User\Alamat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportAlamatExcelService
{
    public function import(string $filePath, array $options = []): array
    {
        $skipDuplicates = $options['skip_duplicates'] ?? true;

        $results = [
            'success' => false,
            'message' => '',
            'success_count' => 0,
            'skip_count' => 0,
            'errors' => [],
        ];

        try {
            $rows = (new FastExcel)->import($filePath);

            if ($rows->isEmpty()) {
                $results['message'] = 'File Excel kosong atau tidak valid';

                return $results;
            }

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because Excel starts at 1 and has header

                try {
                    // Find user by email
                    $user = User::where('email', $row['Email User'] ?? '')->first();

                    if (! $user) {
                        if ($skipDuplicates) {
                            $results['skip_count']++;

                            continue;
                        }
                        throw new \Exception("User dengan email '{$row['Email User']}' tidak ditemukan");
                    }

                    // Prepare alamat data
                    $alamatData = [
                        'user_id' => $user->id,
                        'tipe' => strtolower($row['Tipe'] ?? 'ktp'),
                        'alamat_lengkap' => $row['Alamat Lengkap'] ?? null,
                        'rt' => $row['RT'] ?? null,
                        'rw' => $row['RW'] ?? null,
                        'kelurahan' => $row['Kelurahan'] ?? null,
                        'kecamatan' => $row['Kecamatan'] ?? null,
                        'kota_kabupaten' => $row['Kota/Kabupaten'] ?? null,
                        'provinsi' => $row['Provinsi'] ?? null,
                        'kode_pos' => $row['Kode Pos'] ?? null,
                        'created_by' => auth()->id(),
                    ];

                    // Validate data
                    $validator = Validator::make($alamatData, [
                        'user_id' => 'required|exists:users,id',
                        'tipe' => 'required|in:ktp,domisili',
                        'alamat_lengkap' => 'nullable|string',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception('Baris '.$rowNumber.': '.implode(', ', $validator->errors()->all()));
                    }

                    // Check for duplicate
                    $existingAlamat = Alamat::where('user_id', $user->id)
                        ->where('tipe', $alamatData['tipe'])
                        ->first();

                    if ($existingAlamat) {
                        if ($skipDuplicates) {
                            $results['skip_count']++;

                            continue;
                        }
                        // Update existing
                        $existingAlamat->update($alamatData);
                    } else {
                        // Create new
                        Alamat::create($alamatData);
                    }

                    $results['success_count']++;

                } catch (\Exception $e) {
                    $results['errors'][] = "Baris {$rowNumber}: {$e->getMessage()}";
                }
            }

            DB::commit();

            $results['success'] = true;
            $results['message'] = "Import berhasil! {$results['success_count']} data berhasil diimport";

            if ($results['skip_count'] > 0) {
                $results['message'] .= ", {$results['skip_count']} data dilewati";
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $results['message'] = 'Terjadi kesalahan: '.$e->getMessage();
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }
}
