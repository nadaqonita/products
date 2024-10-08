<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as Eloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class BaseDataTable extends DataTable
{
    protected $query, $columns, $raw = [], $buttons, $datatables, $orderColumn, $filterColumn, $isFixed, $dom, $filename, $tableId, $url;

    public function __construct($query, array $columns,  array $buttons, $orderColumn = null, $filterColumn = null, $filename = null, $dom = null, $url = "", $tableId = null, bool $isFixed = false)
    {
        $this->query = $query;
        $this->columns = $columns;
        $this->buttons = $buttons;
        $this->orderColumn = $orderColumn;
        $this->filterColumn = $filterColumn;
        $this->isFixed = $isFixed;
        $this->dom = $dom;
        $this->filename = $filename;
        $this->tableId = $tableId;
        $this->url = $url;
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        if ($query instanceof Builder) {
            $this->datatables = datatables()->query($query);

            if ($this->orderColumn) {
                $this->datatables->order($this->orderColumn['function']);
            }
            if ($this->filterColumn) {
                $this->datatables->filter($this->filterColumn);
            }
        } else if ($query instanceof Eloquent) {
            $this->datatables = datatables()->eloquent($query);

            if ($this->orderColumn) {
                $this->datatables->order($this->orderColumn['function']);
            }
            if ($this->filterColumn) {
                $this->datatables->filter($this->filterColumn);
            }
        } else {
            $this->datatables = datatables()->collection($query);
        }

        $this->datatables->addIndexColumn();

        $this->setActionColumn();
        $this->setEditColumn();
        if ($this->raw) $this->datatables->rawColumns($this->raw);

        return $this->datatables;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Base $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId($tableId ?? 'base-table')
            ->setTableAttributes([
                'width' => '100%'
            ])
            ->columns($this->getColumns())
            ->deferRender()
            ->fixedColumns($this->isFixed)
            // ->responsive()
            ->postAjax($this->url)
            ->processing(false)
            // ->stateSave()
            ->colReorder()
            ->orderBy($this->orderColumn['firstOrder']['column'] ?? 1, $this->orderColumn['firstOrder']['direction'] ?? 'asc')
            ->dom($this->getDom())
            ->buttons($this->getButton())
            // ->language($this->getLanguage())
            ->preDrawCallback($this->getPreDrawCallback())
            ->drawCallback($this->draw())
            ->initComplete($this->getInitComplete());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $column = [];
        foreach ($this->columns as $k => $v) :
            if ($v['columnType'] == 'action') {
                array_push($column, Column::computed($v['data'], $v['title'])
                    ->exportable(false)
                    ->printable(false)
                    ->visible($v['visibility'])
                    ->addClass('text-center'));
            } else {
                array_push(
                    $column,
                    Column::make($v['data'], $v['name'])
                        ->className($v['className'])
                        ->title($v['title'])
                        ->searchable($v['searchable'])
                        ->orderable($v['orderable'])
                        ->type($v['type'])
                        ->visible($v['visibility'])
                        ->option($v['option'])
                        ->exportable($v['exportable'])
                );
            }
        endforeach;

        return $column;
    }


    public function setActionColumn()
    {
        foreach ($this->columns as $k => $v) :
            if ($v['columnType'] == 'action') {
                $this->datatables->addColumn($v['name'], $v['content']);
                array_push($this->raw, $v['name']);
            }
        endforeach;
    }

    public function setEditColumn()
    {
        foreach ($this->columns as $k => $v) :
            if ($v['columnType'] == 'edit') {
                $this->datatables->addColumn($v['name'], $v['content']);
                array_push($this->raw, $v['name']);
            }
        endforeach;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return $this->filename ? $this->filename . '' . date('YmdHis') : 'Base_' . date('YmdHis');
    }

    protected function getDom()
    {
        return $this->dom ?? '<"d-flex"<"mt-1"f><"mt-md-1 mx-3"B><"ms-auto"<"d-flex"<l><"#dlc-btn.ms-md-3 ">>>><"#toolbar.d-flex my-3">rt<"d-flex justify-content-between my-3"<i><p>>';
    }

    protected function getButton()
    {

        return Button::raw($this->buttons);
    }

    protected function getLanguage()
    {
        return [
            "lengthMenu" => "Tampilkan _MENU_ Baris",
            "search" => '',
            "searchPlaceholder" => 'Cari...',
            "emptyTable" => "Tidak ada data di dalam tabel",
            "info" =>           "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty" =>      "Menampilkan 0 sampai 0 dari 0 data",
            "paginate" => [
                "first" => "Awal",
                "last" => "Akhir",
                "next" =>  "Selanjutnya",
                "previous" => "Sebelumnya"
            ],
            "thousands" => '.',
            "emptyTable" => 'Tidak ada data di dalam tabel',
            "infoFiltered" =>   "(disaring dari _MAX_ total data)"

        ];
    }

    protected function getPreDrawCallback()
    {
        return 'function(){
            $(`table`).addClass(`pb-5 overflow-hidden`);
            Notiflix.Block.circle("tbody")
                 let api = this.api()
                 let list = ``
                 $.map(api.init().buttons[0],(v,i)=>{
                     if(v.position == "right"){
                         $(`button#${v.attr.id}`).appendTo("#dlc-btn")
                        }

                        if(v.extend == "colvis"){
                            list += `<li class="dropdown-item" id="${v.attr.id}" data-bs-toggle="dropdown">${v.text}</li>`
                        }else{
                            list += `<li class="dropdown-item" id="${v.attr.id}" data-bs-target="${v.attr["data-bs-target"]}" data-bs-toggle="${v.attr["data-bs-toggle"]}">${v.text}</li>`

                        }
                })
                }';
    }

    protected function getInitComplete()
    {
        return 'function(){

                    let api = this.api()
                    let btnId = "";
                    let btnText = "";
                    let columns = api.init().columns;
                     $.map(api.init().buttons[0],(v,i)=>{
                    if(v.type == "filter"){
                       btnId = v.attr.id;
                       btnText = v.text;
                    }
                })

                     $("body").on("click",`#${btnId}`,function(){
                         $("#toolbar").empty()
                         $(`#${btnId}`).addClass("btn-icon-text btn-success text-white").html(`Reset <i class="mdi mdi-close fs-6 btn-icon-append "></i>`)

                         if(!$(`#${btnId}`).hasClass("active")){
                            $(`#${btnId}`).addClass("active")
                            $("#toolbar").removeClass("d-none").addClass("row gap-1 g-0")
                            api.columns().every(function(index){
                            let column = this
                            let input = ``;
                            let select = ``;
                            let date = ``;


                            if(columns[index].type == `select`){
                                // select += `<select id="${(columns[index].data).replace(".","_")}" class="form-control mx-1" placeholder="${column.header(this).title}" name="${columns[index].name}"><select>`

                                select+= `<div class="col-lg-3 col-md-4 col-12">
                                    <div class="form-floating mx-1 w-100">
                                        <select class="form-control mx-1" id="${(columns[index].data).replace(".","_")}"
                                            name="${columns[index].name}" placeholder="${column.header(this).title}">
                                            <option selected disabled>Pilih salah satu</option>
                                        </select>
                                        <label for="" class="p-3">${column.header(this).title}</label>
                                    </div>
                                </div>`

                            }else if(columns[index].type == `date`){
                                // date += `<input class="form-control mx-1" placeholder="${column.header(this).title}" name="${columns[index].name}" type="${columns[index].type}">`;
                                date+= `<div class="col-lg-3 col-md-4 col-12">
                                    <div class="form-floating mx-1  w-100">
                                        <input type="date" class="form-control mx-1"
                                                name="${columns[index].name}"
                                                placeholder="${column.header(this).title}" />
                                            <label for="" class="p-3">${column.header(this).title}</label>
                                        </div>
                                    </div>`

                            }else if(columns[index].type == `date-range`){
                                date+= `<div class="col-lg-3 col-md-4 col-12">
                                    <div class="form-floating mx-1  w-100">
                                        <input type="${columns[index].type}" class="form-control mx-1"
                                                name="${columns[index].name}"
                                                placeholder="${column.header(this).title}" />
                                            <label for="" class="p-3">${column.header(this).title}</label>
                                        </div>
                                    </div>`
                            }else{
                                // input += `<input class="form-control mx-1" placeholder="${column.header(this).title}" name="${columns[index].name}" type="${columns[index].type}">`;
                                input += `<div class="col-lg-3 col-md-4 col-12">
                                    <div class="form-floating mx-1  w-100">
                                        <input type="columns[index].type" class="form-control mx-1"
                                            name="${columns[index].name}"
                                            placeholder="${column.header(this).title}" >
                                        <label for="" class="p-3">${column.header(this).title}</label>
                                    </div>
                                </div>`
                            }

                           if(columns[index].searchable === true)
                           $(input).appendTo($("#toolbar")).on("keyup", function () {
                               if($(this).children().children().val() != null){

                                 column.search($(this).children().children().val(), false, false, true).draw();
                                }
                            });
                           $(select).appendTo($("#toolbar")).on("change", function () {
                               if($(this).children().children().val() != null){
                                 column.search($(this).children().children().val(), false, false, true).draw();
                                }
                            });
                           $(date).appendTo($("#toolbar")).on("change", function () {
                               if($(this).children().children().val() != null){
                                 column.search($(this).children().children().val(), false, false, true).draw();
                                }
                            });

                            $(`#toolbar input[type=date-range]`).flatpickr({
                                mode:`range`,
                                dateFormat:`Y-m-d`,
                                theme:`material_blue`,
                                locale: {
                                    rangeSeparator:` s/d `
                                },
                                onChange: function(selectedDates, dateStr, instance) {
                                    if (selectedDates.length < 2) {
                                        instance.element.value = null
                                    }
                                },
                            })

                            $(`#toolbar input[type=date]`).flatpickr({
                                dateFormat:`Y-m-d`,
                                theme:`material_blue`
                            })
                        })
                            api.columns().every(function(index){
                                  $.map(columns[index].option,(v,i)=>{
                                    $(`#${(columns[index].data).replace(".","_")}`).append(`<option value="${v.value}">${v.text}</option>`)

                                })
                            })

                        }else{

                            $(`#base-table`).DataTable().search(``).draw();
                            $(`#base-table`).DataTable().columns().search(``).draw();
                            $(`#${btnId}`).removeClass("active btn-success btn-icon-text text-white").text(`${btnText}`)
                            $("#toolbar").addClass("d-none")
                        }
                     })
                }';
    }

    public function draw()
    {
        return 'function(){
            //  Notiflix.Block.remove(`tbody`)
             $(`table`).removeClass(`pb-5 overflow-hidden`);
        }';
    }
}
