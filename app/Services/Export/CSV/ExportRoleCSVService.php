<?php

namespace App\Services\Export\CSV;

use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\Permission\Models\Role;

class ExportRoleCSVService
{
    public function export()
    {
        $roles = Role::withCount('users')->get();

        $data = $this->transformData($roles);

        $filename = 'role_'.date('Y-m-d_His').'.csv';

        return (new FastExcel($data))->download($filename);
    }

    private function transformData(Collection $roles): Collection
    {
        return $roles->map(function ($role) {
            return [
                'ID' => $role->id,
                'Nama' => $role->name,
                'Guard Name' => $role->guard_name,
                'Jumlah User' => $role->users_count ?? 0,
                'Dibuat Pada' => $role->created_at?->format('Y-m-d H:i:s'),
                'Diupdate Pada' => $role->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }
}
