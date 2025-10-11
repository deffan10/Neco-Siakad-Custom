<?php

namespace App\DataTables\Manager\Users;

use App\Models\User\Alamat;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AlamatDataTable extends DataTable
{
    protected $isTrash = false;

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('tipe', function ($row) {
                $badgeClass = $row->tipe == 'ktp' ? 'primary' : 'info';

                return '<span class="badge bg-'.$badgeClass.'">'.ucfirst($row->tipe).'</span>';
            })
            ->addColumn('pemilik', function ($row) {
                return '<div>
                    <strong>'.$row->user->name.'</strong>
                    <small class="d-block text-muted">'.($row->user->email ?? '-').'</small>
                </div>';
            })
            ->addColumn('alamat', function ($row) {
                return Str::limit($row->alamat_lengkap, 50);
            })
            ->addColumn('kota', function ($row) {
                return $row->kota_kabupaten ?? '-';
            })
            ->addColumn('provinsi', function ($row) {
                return $row->provinsi ?? '-';
            })
            ->addColumn('deleted_by_name', function ($row) {
                if ($this->isTrash) {
                    return '<div class="d-flex align-items-center">
                        <i class="fas fa-user-times text-danger me-2"></i>
                        <span>'.($row->deletedBy ? $row->deletedBy->name : 'Tidak diketahui').'</span>
                    </div>';
                }

                return '-';
            })
            ->addColumn('deleted_at_formatted', function ($row) {
                if ($this->isTrash && $row->deleted_at) {
                    return '<div class="d-flex align-items-center">
                        <i class="fas fa-calendar-times text-danger me-2"></i>
                        <div>
                            <small class="d-block">'.$row->deleted_at->format('d/m/Y H:i').'</small>
                            <small class="text-muted">'.$row->deleted_at->diffForHumans().'</small>
                        </div>
                    </div>';
                }

                return '-';
            })
            ->addColumn('action', function ($row) {
                $activeRole = session('active_role') ?? 'admin';

                if ($this->isTrash) {
                    return '<form action="'.route($activeRole.'.users.alamat-restore', $row->id).'" method="POST" class="d-inline me-1">
                        '.csrf_field().'
                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Alamat">
                            <i class="fas fa-undo me-1"></i> Restore
                        </button>
                    </form>
                    <form action="'.route($activeRole.'.users.alamat-force-delete', $row->id).'" method="POST" class="d-inline" id="force-delete-form-'.$row->id.'">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmForceDelete(\''.$row->id.'\')" data-bs-toggle="tooltip" title="Hapus Permanen Alamat">
                            <i class="fas fa-trash-alt me-1"></i> Force Delete
                        </button>
                    </form>';
                } else {
                    return '<a href="#" data-bs-toggle="modal" data-bs-target="#editData'.$row->id.'" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Edit Alamat">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="'.route($activeRole.'.users.alamat-destroy', $row->id).'" method="POST" class="d-inline" id="delete-form-'.$row->id.'">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(\''.$row->id.'\')" data-bs-toggle="tooltip" title="Hapus Alamat">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>';
                }
            })
            // Filter tipe alamat (searchable)
            ->filterColumn('tipe', function ($query, $keyword) {
                $query->where('tipe', 'like', "%{$keyword}%");
            })
            // Filter pemilik (search by user name or email)
            ->filterColumn('pemilik', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            // Filter alamat (search in alamat_lengkap)
            ->filterColumn('alamat', function ($query, $keyword) {
                $query->where('alamat_lengkap', 'like', "%{$keyword}%");
            })
            // Filter kota (search in kota_kabupaten)
            ->filterColumn('kota', function ($query, $keyword) {
                $query->where('kota_kabupaten', 'like', "%{$keyword}%")
                    ->orWhere('provinsi', 'like', "%{$keyword}%")
                    ->orWhere('kecamatan', 'like', "%{$keyword}%")
                    ->orWhere('kelurahan', 'like', "%{$keyword}%");
            })
            ->rawColumns(['tipe', 'pemilik', 'alamat', 'kota', 'deleted_by_name', 'deleted_at_formatted', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Alamat $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['user', 'deletedBy']);

        if ($this->isTrash) {
            $query->onlyTrashed();
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $columns = $this->getColumns();

        return $this->builder()
            ->setTableId('alamat-table')
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
                    'emptyTable' => 'Tidak ada data alamat',
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
                            $(td).attr("data-label", "Tipe");
                        }',
                    ],
                    [
                        'targets' => 2,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Pemilik");
                        }',
                    ],
                    [
                        'targets' => 3,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Alamat");
                        }',
                    ],
                    [
                        'targets' => 4,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Kota");
                        }',
                    ],
                    [
                        'targets' => 5,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Provinsi");
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
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::computed('DT_RowIndex')
                ->title('No')
                ->searchable(false)
                ->orderable(false),
            Column::computed('tipe')
                ->title('Tipe')
                ->searchable(true)
                ->orderable(true),
            Column::computed('pemilik')
                ->title('Pemilik')
                ->searchable(true)
                ->orderable(false),
            Column::computed('alamat')
                ->title('Alamat')
                ->searchable(true)
                ->orderable(false),
            Column::computed('kota')
                ->title('Kota')
                ->searchable(true)
                ->orderable(true),
            Column::computed('provinsi')
                ->title('Provinsi')
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

    /**
     * Set trash mode
     */
    public function setTrash(bool $isTrash)
    {
        $this->isTrash = $isTrash;

        return $this;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        $prefix = $this->isTrash ? 'Alamat_Trash_' : 'Alamat_';

        return $prefix.date('YmdHis');
    }
}
