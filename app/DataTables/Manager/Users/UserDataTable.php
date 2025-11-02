<?php

namespace App\DataTables\Manager\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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
            ->addColumn('name', function ($row) {
                return '<div>
                    <strong>'.$row->name.'</strong>
                    <small class="d-block text-muted">'.($row->username ?? '-').'</small>
                </div>';
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('phone', function ($row) {
                return $row->phone ?? '-';
            })
            ->addColumn('roles', function ($row) {
                $badges = '';
                foreach ($row->roles as $role) {
                    $badges .= '<span class="badge bg-info me-1">'.$role->name.'</span>';
                }

                return $badges ?: '-';
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
                    return '<form action="'.route($activeRole.'.users.user-restore', $row->id).'" method="POST" class="d-inline me-1">
                        '.csrf_field().'
                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Pengguna">
                            <i class="fas fa-undo me-1"></i> Restore
                        </button>
                    </form>
                    <form action="'.route($activeRole.'.users.user-force-delete', $row->id).'" method="POST" class="d-inline" id="force-delete-form-'.$row->id.'">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmForceDelete(\''.$row->id.'\')" data-bs-toggle="tooltip" title="Hapus Permanen Pengguna">
                            <i class="fas fa-trash-alt me-1"></i> Force Delete
                        </button>
                    </form>';
                } else {
                    return '<a href="'.route($activeRole.'.users.user-view', $row->id).'" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Lihat Pengguna">
                        <i class="fas fa-eye me-1"></i> Lihat
                    </a>
                    <form action="'.route($activeRole.'.users.user-destroy', $row->id).'" method="POST" class="d-inline" id="delete-form-'.$row->id.'">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(\''.$row->id.'\')" data-bs-toggle="tooltip" title="Hapus Pengguna">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>';
                }
            })
            // Filter by name
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('username', 'like', "%{$keyword}%");
            })
            // Filter by email
            ->filterColumn('email', function ($query, $keyword) {
                $query->where('email', 'like', "%{$keyword}%");
            })
            // Filter by phone
            ->filterColumn('phone', function ($query, $keyword) {
                $query->where('phone', 'like', "%{$keyword}%");
            })
            // Filter by roles
            ->filterColumn('roles', function ($query, $keyword) {
                $query->whereHas('roles', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['name', 'email', 'phone', 'roles', 'deleted_by_name', 'deleted_at_formatted', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['roles', 'deletedBy']);

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
            ->setTableId('user-table')
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
                    'emptyTable' => 'Tidak ada data pengguna',
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
                            $(td).attr("data-label", "Nama");
                        }',
                    ],
                    [
                        'targets' => 2,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Email");
                        }',
                    ],
                    [
                        'targets' => 3,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "No Telepon");
                        }',
                    ],
                    [
                        'targets' => 4,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Roles");
                        }',
                    ],
                    [
                        'targets' => -1,
                        'createdCell' => 'function (td, cellData, rowData, row, col) {
                            $(td).attr("data-label", "Aksi");
                        }',
                    ],
                ],
                'buttons' => [],
            ])
            ->buttons([
                // Custom buttons will be added via JavaScript to call existing export routes
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
            Column::computed('name')
                ->title('Nama')
                ->searchable(true)
                ->orderable(true),
            Column::computed('email')
                ->title('Email')
                ->searchable(true)
                ->orderable(true),
            Column::computed('phone')
                ->title('No Telepon')
                ->searchable(true)
                ->orderable(true),
            Column::computed('roles')
                ->title('Roles')
                ->searchable(true)
                ->orderable(false),
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
        $prefix = $this->isTrash ? 'User_Trash_' : 'User_';

        return $prefix.date('YmdHis');
    }
}
