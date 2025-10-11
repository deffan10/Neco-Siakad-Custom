<?php

namespace App\Services\Import\Excel;

use App\Models\User;
use App\Models\User\Keluarga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportKeluargaExcelService
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

                    // Prepare keluarga data
                    $keluargaData = [
                        'user_id' => $user->id,
                        'hubungan' => $row['Hubungan'] ?? null,
                        'nama' => $row['Nama'] ?? null,
                        'pekerjaan' => $row['Pekerjaan'] ?? null,
                        'telepon' => $row['Telepon'] ?? null,
                        'tempat_lahir' => $row['Tempat Lahir'] ?? null,
                        'tanggal_lahir' => $row['Tanggal Lahir'] ?? null,
                        'penghasilan' => $row['Penghasilan'] ?? null,
                        'alamat' => $row['Alamat'] ?? null,
                        'created_by' => auth()->id(),
                    ];

                    // Validate data
                    $validator = Validator::make($keluargaData, [
                        'user_id' => 'required|exists:users,id',
                        'hubungan' => 'required|string',
                        'nama' => 'required|string',
                        'tanggal_lahir' => 'nullable|date',
                        'penghasilan' => 'nullable|numeric',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception('Baris '.$rowNumber.': '.implode(', ', $validator->errors()->all()));
                    }

                    // Create new keluarga
                    Keluarga::create($keluargaData);

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
