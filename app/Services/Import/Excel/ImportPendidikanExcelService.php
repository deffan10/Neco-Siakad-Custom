<?php

namespace App\Services\Import\Excel;

use App\Models\User;
use App\Models\User\Pendidikan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportPendidikanExcelService
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

                    // Prepare pendidikan data
                    $pendidikanData = [
                        'user_id' => $user->id,
                        'jenjang' => $row['Jenjang'] ?? null,
                        'nama_sekolah' => $row['Nama Sekolah'] ?? null,
                        'jurusan' => $row['Jurusan'] ?? null,
                        'tahun_masuk' => $row['Tahun Masuk'] ?? null,
                        'tahun_lulus' => $row['Tahun Lulus'] ?? null,
                        'nilai_akhir' => $row['Nilai Akhir'] ?? null,
                        'keterangan' => $row['Keterangan'] ?? null,
                        'created_by' => auth()->id(),
                    ];

                    // Validate data
                    $validator = Validator::make($pendidikanData, [
                        'user_id' => 'required|exists:users,id',
                        'jenjang' => 'required|string',
                        'nama_sekolah' => 'required|string',
                        'tahun_masuk' => 'nullable|integer',
                        'tahun_lulus' => 'nullable|integer',
                        'nilai_akhir' => 'nullable|numeric',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception('Baris '.$rowNumber.': '.implode(', ', $validator->errors()->all()));
                    }

                    // Create new pendidikan
                    Pendidikan::create($pendidikanData);

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
