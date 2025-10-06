<?php

namespace App\DataTables\Manager\Users;

use App\Models\User\Keluarga;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KeluargaDataTable extends DataTable
{
    protected bool $isTrash = false;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('hubungan', function ($row) {
                return '<span class="badge bg-primary">'.e($row->hubungan).'</span>';
            })
            ->addColumn('nama_display', function ($row) {
                $html = '<div><strong>'.e($row->nama).'</strong>';
                if (! empty($row->telepon)) {
                    $html .= '<small class="d-block text-muted"><i class="fas fa-phone"></i> '.e($row->telepon).'</small>';
                }
                $html .= '</div>';

                return $html;
            })
            ->addColumn('pengguna', function ($row) {
                return '<div><strong>'.e($row->user->name).'</strong><small class="d-block text-muted">'.(e($row->user->role ?? '-')).'</small></div>';
            })
            ->addColumn('pekerjaan', function ($row) {
                return $row->pekerjaan ?? '-';
            })
            ->addColumn('penghasilan', function ($row) {
                return $row->penghasilan ?? '-';
            })
            ->addColumn('deleted_by_name', function ($row) {
                if ($this->isTrash) {
                    return '<div class="d-flex align-items-center"><i class="fas fa-user-times text-danger me-2"></i><span>'.($row->deletedBy ? e($row->deletedBy->name) : 'Tidak diketahui').'</span></div>';
                }

                return '-';
            })
            ->addColumn('deleted_at_formatted', function ($row) {
                if ($this->isTrash && $row->deleted_at) {
                    return '<div class="d-flex align-items-center"><i class="fas fa-calendar-times text-danger me-2"></i><div><small class="d-block">'.$row->deleted_at->format('d/m/Y H:i').'</small><small class="text-muted">'.$row->deleted_at->diffForHumans().'</small></div></div>';
                }

                return '-';
            })
            ->addColumn('action', function ($row) {
                $activeRole = session('active_role') ?? 'admin';

                if ($this->isTrash) {
                    return '<form action="'.route($activeRole.'.users.keluarga-restore', $row->id).'" method="POST" class="d-inline me-1">'
                        .csrf_field().
                        '<button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Keluarga">'
                        .'<i class="fas fa-undo me-1"></i> Restore</button></form>'
                        .'<form action="'.route($activeRole.'.users.keluarga-force-delete', $row->id).'" method="POST" class="d-inline" id="force-delete-form-'.$row->id.'">'.csrf_field().method_field('DELETE')
                        .'<button type="button" class="btn btn-sm btn-danger" onclick="confirmForceDelete(\''.$row->id.'\')" data-bs-toggle="tooltip" title="Hapus Permanen Keluarga">'
                        .'<i class="fas fa-trash-alt me-1"></i> Force Delete</button></form>';
                }

                return '<a href="#" data-bs-toggle="modal" data-bs-target="#editData'.$row->id.'" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Edit Keluarga">'
                    .'<i class="fas fa-edit me-1"></i> Edit</a>'
                    .'<form action="'.route($activeRole.'.users.keluarga-destroy', $row->id).'" method="POST" class="d-inline" id="delete-form-'.$row->id.'">'.csrf_field().method_field('DELETE')
                    .'<button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(\''.$row->id.'\')" data-bs-toggle="tooltip" title="Hapus Keluarga">'
                    .'<i class="fas fa-trash me-1"></i> Delete</button></form>';
            })
            ->filterColumn('hubungan', function ($query, $keyword) {
                $query->where('hubungan', 'like', "%{$keyword}%");
            })
            ->filterColumn('nama_display', function ($query, $keyword) {
                $query->where('nama', 'like', "%{$keyword}%");
            })
            ->filterColumn('pengguna', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['hubungan', 'nama_display', 'pengguna', 'deleted_by_name', 'deleted_at_formatted', 'action'])
            ->setRowId('id');
    }

    public function query(Keluarga $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['user', 'deletedBy']);

        if ($this->isTrash) {
            $query->onlyTrashed();
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        $columns = $this->getColumns();

        return $this->builder()
            ->setTableId('keluarga-table')
            ->columns($columns)
            ->minifiedAjax()
            ->dom('<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>')
            ->orderBy(0, 'asc')
            ->responsive(true)
            ->autoWidth(true)
            ->pageLength(10)
            ->lengthMenu([[10, 20, 30, 50, 100, 250, -1], [10, 20, 30, 50, 100, 250, 'Semua']])
            ->parameters([
                'language' => [
                    'emptyTable' => 'Tidak ada data keluarga',
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                    'infoFiltered' => '(disaring dari _MAX_ total data)',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'loadingRecords' => 'Memuat...',
                    'processing' => 'Memproses...',
                    'search' => 'Cari:',
                    'zeroRecords' => 'Data tidak ditemukan',
                    'paginate' => [
                        'first' => 'Pertama',
                        'last' => 'Terakhir',
                        'next' => 'Selanjutnya',
                        'previous' => 'Sebelumnya',
                    ],
                ],
                'columnDefs' => [
                    [
                        'targets' => 0,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "No");
                        }',
                    ],
                    [
                        'targets' => 1,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Hubungan");
                        }',
                    ],
                    [
                        'targets' => 2,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Nama");
                        }',
                    ],
                    [
                        'targets' => 3,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Pengguna");
                        }',
                    ],
                    [
                        'targets' => 4,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Pekerjaan");
                        }',
                    ],
                    [
                        'targets' => 5,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Penghasilan");
                        }',
                    ],
                    [
                        'targets' => -1,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Aksi");
                        }',
                    ],
                ],
                'buttons' => [
                    [
                        'extend' => 'excel',
                        'className' => 'btn btn-sm btn-success',
                        'text' => '<i class="fas fa-file-excel me-1"></i> Excel',
                        'exportOptions' => [
                            'columns' => ':not(.no-export)',
                        ],
                    ],
                    [
                        'extend' => 'pdf',
                        'className' => 'btn btn-sm btn-danger',
                        'text' => '<i class="fas fa-file-pdf me-1"></i> PDF',
                        'exportOptions' => [
                            'columns' => ':not(.no-export)',
                        ],
                    ],
                    [
                        'extend' => 'print',
                        'className' => 'btn btn-sm btn-info',
                        'text' => '<i class="fas fa-print me-1"></i> Print',
                        'exportOptions' => [
                            'columns' => ':not(.no-export)',
                        ],
                    ],
                ],
            ])
            ->buttons([
                Button::make('excel')->className('btn btn-sm btn-success')->text('<i class="fas fa-file-excel me-1"></i> Excel'),
                Button::make('pdf')->className('btn btn-sm btn-danger')->text('<i class="fas fa-file-pdf me-1"></i> PDF'),
                Button::make('print')->className('btn btn-sm btn-info')->text('<i class="fas fa-print me-1"></i> Print'),
            ]);
    }

    public function getColumns(): array
    {
        $columns = [
            Column::computed('DT_RowIndex')
                ->title('No')
                ->searchable(false)
                ->orderable(false),
            Column::computed('hubungan')
                ->title('Hubungan')
                ->searchable(true)
                ->orderable(true),
            Column::computed('nama_display')
                ->title('Nama')
                ->searchable(true)
                ->orderable(false),
            Column::computed('pengguna')
                ->title('Pengguna')
                ->searchable(true)
                ->orderable(false),
            Column::computed('pekerjaan')
                ->title('Pekerjaan')
                ->searchable(true)
                ->orderable(true),
            Column::computed('penghasilan')
                ->title('Penghasilan')
                ->searchable(true)
                ->orderable(true),
        ];

        if ($this->isTrash) {
            $columns[] = Column::computed('deleted_by_name')
                ->title('Dihapus Oleh')
                ->searchable(false)
                ->orderable(false)
                ->addClass('no-export');
            $columns[] = Column::computed('deleted_at_formatted')
                ->title('Dihapus Pada')
                ->searchable(false)
                ->orderable(false)
                ->addClass('no-export');
        }

        $columns[] = Column::computed('action')
            ->title('Aksi')
            ->exportable(false)
            ->printable(false)
            ->searchable(false)
            ->orderable(false)
            ->addClass('text-center no-export');

        return $columns;
    }

    public function setTrash(bool $isTrash)
    {
        $this->isTrash = $isTrash;

        return $this;
    }

    protected function filename(): string
    {
        $prefix = $this->isTrash ? 'Keluarga_Trash_' : 'Keluarga_';

        return $prefix.date('YmdHis');
    }
}
