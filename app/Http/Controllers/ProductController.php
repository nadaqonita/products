<?php

namespace App\Http\Controllers;

use App\DataTables\BaseDataTable;
use App\DataTables\Helper\Button;
use App\DataTables\Helper\Column;
use App\DataTables\Helper\Form;
use App\DataTables\Helper\Modal;
use App\DataTables\Helper\Script;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function index(Request $request)
    {
        $orderColumn = $this->setOrder();
        $filterColumn = $this->setFilter();
        $filename = 'Products ' . Carbon::now();
        $datatables = new BaseDataTable($this->setQuery(), $this->setColumns(), $this->setButtons(), $orderColumn, $filterColumn, $filename);
        $title = 'Products';
        $modal = $this->setModal();
        $content = $this->setForm();
        $script = $this->setScript();

        return $datatables->render('products.table.index', compact('title', 'modal', 'script'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name"          => "required",
                "description"   => "required",
                "price"         => "required",
                "qty"           => "required",
            ], [
                "name.required"         => "Name is required",
                "description.required"  => "Description is required",
                "price.required"        => "Price is required",
                "qty.required"          => "Qty is required",
            ]);

            if ($validator->stopOnFirstFailure()->fails())
                return response()->json(['status' => false, 'code' => 400, 'message' => $validator->errors()->first(), 'data' => []], 400);

            $data = [
                "name"          => $request->name,
                "description"   => $request->description,
                "price"         => $request->price,
                "qty"           => $request->qty,
                "updated_at"    => null,
            ];

            Product::create($data);

            return response()->json(['status' => true, 'code' => 200, 'message' => 'Success', 'data' => []], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'code' => 500, 'message' => $th->getMessage(), 'data' => []], 500);
        }
    }

    public function setQuery()
    {
        return Product::select([
            'id',
            'name',
            'description',
            'price',
            'qty',
            'created_at',
            'updated_at'
        ]);
    }

    public function setColumns()
    {
        return array(
            Column::make('DT_RowIndex', ['name' => 'id', 'orderable' => false, 'searchable' => false, "title" => 'No', 'exportable' => false]),
            Column::make('name', [
                'name' => 'name',
                'title' => 'Name'
            ]),
            Column::make('description', [
                'content' => function ($row) {
                    return isset($row->description) ? $row->description : '-';
                },
                'columnType' => 'edit',
                'name' => 'description',
                'title' => 'Description'
            ]),
            Column::make('price', [
                'content' => function ($row) {
                    return isset($row->price) ? 'Rp' . number_format($row->price, 0, ',', '.') : '-';
                },
                'columnType' => 'edit',
                'name' => 'price',
                'title' => 'Price'
            ]),
            Column::make('qty', [
                'content' => function ($row) {
                    return isset($row->qty) ? number_format($row->qty, 0, ',', '.') : '-';
                },
                'columnType' => 'edit',
                'name' => 'qty',
                'title' => 'Qty'
            ]),
        );
    }

    public function setButtons()
    {
        $button = [
            Button::make("Choose Column", ['extend' => 'colvis', 'attr' => ['id' => 'btn-colvis', 'class' => 'btn btn-sm rounded ms-1 mb-1 btn-secondary']]),
            Button::make("Filter", ['type' => 'filter', 'attr' => ['id' => 'btn-filter', 'class' => 'btn rounded ms-1 mb-1 btn-sm btn-secondary']]),
            Button::make("Add", [
                'position' => 'right',
                'attr' => [
                    'id' => 'btn-create',
                    'class' => 'btn btn-sm rounded ms-1 btn-primary',
                    'data-bs-target' => '#addModal',
                    'data-bs-toggle' => 'modal'
                ]
            ])
        ];

        return $button;
    }

    public function setForm($option = null)
    {
        $data = [];
        switch ($option) {
            default:
                $data = [
                    Form::make('input', 'name', ['class' => 'form-control', 'label' => 'Name', 'spanClass' => 'text-danger', 'span' => '*']),
                    Form::make('textarea', 'description', ['class' => 'form-control', 'label' => 'Description', 'spanClass' => 'text-danger', 'span' => '*']),
                    Form::make('input', 'price', ['class' => 'form-control NumberOnly', 'label' => 'Price', 'spanClass' => 'text-danger', 'span' => '*']),
                    Form::make('input', 'qty', ['class' => 'form-control NumberOnly', 'label' => 'Qty', 'spanClass' => 'text-danger', 'span' => '*']),
                ];
                break;
        }
        return $data;
    }

    public function setModal()
    {
        return [
            Modal::make('addModal', 'Add Product')->setForm('formAdd')->setContent($this->setForm())->setBtn([
                Button::make('Cancel', ['attr' => ['data-bs-dismiss' => 'modal', 'class' => 'btn btn-secondary btn-sm rounded']]),
                Button::make('Save', ['type' => 'submit', 'attr' => ['class' => 'btn btn-primary btn-sm rounded']])
            ])->render()
        ];
    }

    public function setScript()
    {
        $script = '';
        $script .= Script::make('submit', ['url' => route('products.store'), 'formId' => 'formAdd', 'modalId' => 'addModal']);

        return $script;
    }

    public function setFilter()
    {
        return function ($query) {
            if (request('columns')[1]['search']['value']) {
                $query->where(request('columns')[1]['name'], 'ILIKE', '%' . request('columns')[1]['search']['value'] . '%');
            }

            if (request('columns')[2]['search']['value']) {
                $query->where(request('columns')[2]['name'], 'ILIKE', '%' . request('columns')[2]['search']['value'] . '%');
            }

            if (request('columns')[3]['search']['value']) {
                $query->where(DB::raw('price::text'), 'ILIKE', '%' . request('columns')[3]['search']['value'] . '%');
            }

            if (request('columns')[4]['search']['value']) {
                $query->where(DB::raw('qty::text'), 'ILIKE', '%' . request('columns')[4]['search']['value'] . '%');
            }

            if (request('search')['value']) {
                $query->where(request('columns')[1]['name'], 'ILIKE', '%' . request('search')['value'] . '%')
                    ->orWhere(request('columns')[2]['name'], 'ILIKE', '%' . request('search')['value'] . '%')
                    ->orWhere(DB::raw('price::text'), 'ILIKE', '%' . request('search')['value'] . '%')
                    ->orWhere(DB::raw('qty::text'), 'ILIKE', '%' . request('search')['value'] . '%');
            }
        };
    }

    public function setOrder()
    {
        return [
            "firstOrder" => [
                'column' => '0',
                'direction' => 'ASC'
            ],
            "function" => function ($query) {
                $order = request('order')[0];
                $query->orderBy(request('columns')[$order['column']]['name'], $order['dir']);
            }
        ];
    }
}
