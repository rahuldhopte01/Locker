<?php

namespace Workdo\LockerAndSafeDeposit\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Workdo\LockerAndSafeDeposit\Entities\Locker;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LockerDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['locker_number', 'location', 'size', 'monthly_rate', 'status', 'is_available'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('locker_number', function (Locker $locker) {
                return '<a href="#" class="btn btn-outline-primary">' . e($locker->locker_number) . '</a>';
            })
            ->addColumn('location', function (Locker $locker) {
                return $locker->location ? e($locker->location->building) : '—';
            })
            ->editColumn('size', function (Locker $locker) {
                return Locker::$sizes[$locker->size] ?? e($locker->size);
            })
            ->editColumn('monthly_rate', function (Locker $locker) {
                return currency_format_with_sym($locker->monthly_rate) . ' ' . __('EUR');
            })
            ->editColumn('status', function (Locker $locker) {
                $classes = [
                    'active'     => 'bg-primary',
                    'inactive'   => 'bg-secondary',
                    'reserved'   => 'bg-info',
                    'maintenance'=> 'bg-warning',
                ];
                $class = $classes[$locker->status] ?? 'bg-secondary';
                $label = Locker::$status[$locker->status] ?? $locker->status;
                return '<span class="badge fix_badges ' . $class . ' p-2 px-3">' . e($label) . '</span>';
            })
            ->editColumn('is_available', function (Locker $locker) {
                return $locker->is_available
                    ? '<span class="badge bg-success">' . __('Yes') . '</span>'
                    : '<span class="badge bg-danger">' . __('No') . '</span>';
            });
        if (\Laratrust::hasPermission('locker edit') || \Laratrust::hasPermission('locker delete')) {
            $dataTable->addColumn('action', function (Locker $locker) {
                return view('locker-and-safe-deposit::locker.action', compact('locker'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    public function query(Locker $model): QueryBuilder
    {
        $query = $model->newQuery()->with('location');
        if (function_exists('getActiveWorkSpace')) {
            $query->where('workspace', getActiveWorkSpace());
        }
        if (function_exists('creatorId')) {
            $query->where('created_by', creatorId());
        }
        return $query;
    }

    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('lockers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => "_MENU_" . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

        $exportButtonConfig = [
            'extend' => 'collection',
            'className' => 'btn btn-light-secondary dropdown-toggle',
            'text' => '<i class="ti ti-download me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Export"></i>',
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fas fa-print me-2"></i> ' . __('Print'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 2, 3, 4, 5]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv me-2"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 2, 3, 4, 5]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel me-2"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 2, 3, 4, 5]],
                ],
            ],
        ];

        $buttonsConfig = array_merge([
            $exportButtonConfig,
            [
                'extend' => 'reset',
                'className' => 'btn btn-light-danger',
            ],
            [
                'extend' => 'reload',
                'className' => 'btn btn-light-warning',
            ],
        ]);

        $dataTable->parameters([
            "dom" =>  "
        <'dataTable-top'<'dataTable-dropdown page-dropdown'l><'dataTable-botton table-btn dataTable-search tb-search  d-flex justify-content-end gap-2'Bf>>
        <'dataTable-container'<'col-sm-12'tr>>
        <'dataTable-bottom row'<'col-5'i><'col-7'p>>",
            'buttons' => $buttonsConfig,
            "drawCallback" => 'function( settings ) {
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=tooltip]")
                  );
                  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                  });
                  var popoverTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=popover]")
                  );
                  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                  });
                  var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                  var toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl);
                  });
            }'
        ]);

        $dataTable->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);

        return $dataTable;
    }

    public function getColumns(): array
    {
        $column = [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('locker_number')->title(__('Locker Number')),
            Column::make('location')->title(__('Location'))->orderable(false)->searchable(false),
            Column::make('size')->title(__('Size')),
            Column::make('monthly_rate')->title(__('Monthly Rate') . ' (EUR)'),
            Column::make('status')->title(__('Status')),
            Column::make('is_available')->title(__('Available')),
        ];
        if (\Laratrust::hasPermission('locker edit') || \Laratrust::hasPermission('locker delete')) {
            $column[] = Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(60);
        }
        return $column;
    }

    protected function filename(): string
    {
        return 'Lockers_' . date('YmdHis');
    }
}
