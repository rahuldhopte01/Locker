<?php

namespace Workdo\LockerAndSafeDeposit\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Workdo\LockerAndSafeDeposit\Entities\LockerMembership;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LockerMembershipDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['locker_id' , 'customer_id' , 'start_date' , 'duration' , 'membership_fee' ,'status'];
        $dataTable =    (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('locker_id', function (LockerMembership $membership) {
                if(!empty($membership->locker))
                {
                    return ' <a href="#" class="btn btn-outline-primary">'. '#LOC' . sprintf("%05d",$membership->locker->locker_number) .'</a>';                
                }
                else
                {
                    return '-';
                }
            })
            ->filterColumn('locker_id', function ($query, $keyword) {
                $query->whereHas('locker', function ($q) use ($keyword) {
                    $q->where('locker_number', 'like', "%$keyword%");
                });
            })
            ->editColumn('customer_id', function (LockerMembership $membership) {
                return  !empty($membership->customer) ? $membership->customer->name : '-';                
            })
            ->filterColumn('customer_id', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->editColumn('start_date', function (LockerMembership $membership) {
                return company_date_formate($membership->start_date);
            })
            ->editColumn('duration', function (LockerMembership $membership) {
                return  ucfirst($membership->duration);                
            })
            ->editColumn('membership_fee', function (LockerMembership $membership) {
                return currency_format_with_sym($membership->membership_fee);
            })
            ->editColumn('status', function (LockerMembership $membership) {
                $durationMap = [
                    'monthly' => '+1 month',
                    'yearly'  => '+1 year'
                ];
                if (isset($durationMap[$membership->duration])) {
                    $newDate = date('Y-m-d', strtotime($membership->start_date . ' ' . $durationMap[$membership->duration]));
                    if (date('Y-m-d') >= $newDate) {
                        $status = 'Expired' ;
                        $class  = 'bg-danger';
                    }
                    else if(date('Y-m-d') >= $membership->start_date && date('Y-m-d') < $newDate){
                        $status = 'Active';
                        $class  = 'bg-primary';
                    }
                    else {
                        $status = 'Pending';
                        $class  = 'bg-warning';
                    }
                }
                return '<span class="badge fix_badges '.$class.' p-2 px-3">'. $status .'</span>';

            });
            if (\Laratrust::hasPermission('locker_membership edit') || \Laratrust::hasPermission('locker_membership delete') ) {
                $dataTable->addColumn('action', function (LockerMembership $membership) {
                    return view('locker-and-safe-deposit::membership.action', compact('membership'));
                });
                $rowColumn[] = 'action';
            }
            return $dataTable->rawColumns($rowColumn);   
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LockerMembership $model): QueryBuilder
    {
        return $model->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('locker-membership-table')
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
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv me-2"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel me-2"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $column = [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('locker_id')->title(__('Locker Number')),
            Column::make('customer_id')->title(__('Customer')),
            Column::make('start_date')->title(__('Start Date')),
            Column::make('membership_type')->title(__('Membership Type')),
            Column::make('duration')->title(__('Duration')),
            Column::make('membership_fee')->title(__('Membership Fee')),
            Column::computed('status')->title(__('Status')),
        ];
        if (\Laratrust::hasPermission('locker_membership edit') || \Laratrust::hasPermission('locker_membership delete')) {
            $action =[
                Column::computed('action')->title(__('Action'))
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
                    
                ];

            $column = array_merge($column , $action);
        }
        return $column;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Memberships_' . date('YmdHis');
    }
}
