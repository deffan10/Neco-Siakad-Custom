<?php

namespace App\Services\Import\Excel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\Permission\Models\Role;

class ImportRoleExcelService
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
                    // Check if role already exists
                    $existingRole = Role::where('name', $row['Nama'] ?? '')->first();

                    if ($existingRole) {
                        if ($skipDuplicates) {
                            $results['skip_count']++;

                            continue;
                        }
                        throw new \Exception("Role '{$row['Nama']}' sudah ada");
                    }

                    // Prepare role data
                    $roleData = [
                        'name' => $row['Nama'] ?? null,
                        'guard_name' => $row['Guard Name'] ?? 'web',
                    ];

                    // Validate data
                    $validator = Validator::make($roleData, [
                        'name' => 'required|string|unique:roles,name',
                        'guard_name' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception('Baris '.$rowNumber.': '.implode(', ', $validator->errors()->all()));
                    }

                    // Create new role
                    Role::create($roleData);

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
