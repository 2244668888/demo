<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\TypeOfProduct;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Unit;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class ProductController extends Controller
{
    public function Data(Request $request)
    {



        // dd($query);
        if ($request->ajax()) {

            $query = Product::select(
                'products.id',
                'products.part_no',
                'products.part_name',
                'products.type_of_product',
                'products.model',
                'products.category',
                'products.variance',
                'products.description',
                'products.moq',
                'products.unit',
                'products.part_weight',
                'products.customer_product_code',
                'products.supplier_product_code',
                'products.supplier_name',
                'products.customer_name',
                'products.standard_packaging'
            )
            ->with(['type_of_products', 'categories', 'customers', 'suppliers', 'units', 'amortization']);


// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('supplier_name', function($row){
                    return $row->suppliers->name ?? '-';
                })
                ->addColumn('customer_name', function($row){
                    return $row->customers->name ?? '-';
                })
                ->addColumn('unit', function($row){
                    return $row->units->name ?? '-';
                })
                ->addColumn('type_of_product', function($row){
                    return $row->type_of_products->type ?? '-';
                })
                ->addColumn('category', function($row){
                    return $row->categories->name ?? '-';
                })
                ->addColumn('amortization_qty', function($row) {
                    return $row->amortization->amortization_qty ?? '-';
                })
                ->addColumn('delivered_qty', function($row) {
                    return $row->amortization->delivered_qty ?? '-';
                })
                ->addColumn('balance_amortization', function($row) {
                    return $row->amortization->balance_amortization ?? '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a title="Edit" class="btn btn-info btn-sm mx-2" href="' .
                    route('product.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <div class="d-flex"><a title="Amortization" class="btn btn-warning btn-sm mx-2" href="' .
                    route('product.amortization.edit', $row->id) .
                    '"><i class="bi bi-graph-down-arrow"></i></a>
                    <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                    route('product.view', $row->id) .
                    '"><i class="bi bi-eye"></i></a>
                     <button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="' . $row->id . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel' . $row->id . '" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel' . $row->id . '">Delete Problem</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this problem?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form method="POST" action="' . route('product.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                    return $btn;
                })
                ->rawColumns(['action', 'supplier_name', 'category', 'customer_name', 'unit', 'type_of_product', 'amortization_qty', 'delivered_qty', 'balance_amortization'])
                // dd($request->search['value']);

                ->filterColumn('supplier_name', function($query, $keyword) {
                    $query->whereHas('suppliers', function($q) use ($keyword) {
                        $q->where('suppliers.name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('customer_name', function($query, $keyword) {
                    $query->whereHas('customers', function($q) use ($keyword) {
                        $q->where('customers.name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('unit', function($query, $keyword) {
                    $query->whereHas('units', function($q) use ($keyword) {
                        $q->where('units.name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('category', function($query, $keyword) {
                    $query->whereHas('categories', function($q) use ($keyword) {
                        $q->where('categories.name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('amortization_qty', function($query, $keyword) {
                    $query->whereHas('amortization', function($q) use ($keyword) {
                        $q->where('amortization_qty', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('delivered_qty', function($query, $keyword) {
                    $query->whereHas('amortization', function($q) use ($keyword) {
                        $q->where('delivered_qty', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('type_of_product', function($query, $keyword) {
                    $query->whereHas('type_of_products', function($q) use ($keyword) {
                        $q->where('type_of_products.type', 'like', "%{$keyword}%");
                    });
                })
                
                ->filterColumn('balance_amortization', function($query, $keyword) {
                    $query->whereHas('amortization', function($q) use ($keyword) {
                        $q->where('balance_amortization', 'like', "%{$keyword}%");
                    });
                });


                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('part_no') && !is_null($request->part_no)) {
                        $query->where('part_no', 'like', "%{$request->part_no}%");
                    }
                    if ($request->has('part_name') && !is_null($request->part_name)) {
                        $query->where('part_name', 'like', "%{$request->part_name}%");
                    }
                    if ($request->has('amortization_qty') && !is_null($request->amortization_qty)) {
                        $query->whereHas('amortization', function($q) use ($request) {
                            $q->where('amortization_qty', 'like', "%{$request->amortization_qty}%");
                        });
                    }
                    if ($request->has('delivered_qty') && !is_null($request->delivered_qty)) {
                        $query->whereHas('amortization', function($q) use ($request) {
                            $q->where('delivered_qty', 'like', "%{$request->delivered_qty}%");
                        });
                    }
                    if ($request->has('balance_amortization') && !is_null($request->balance_amortization)) {
                        $query->whereHas('amortization', function($q) use ($request) {
                            $q->where('balance_amortization', 'like', "%{$request->balance_amortization}%");
                        });
                    }
                    if ($request->has('type_of_product') && !is_null($request->type_of_product)) {
                        $query->whereHas('type_of_products', function($q) use ($request) {
                            $q->where('type_of_products.type', 'like', "%{$request->type_of_product}%");
                        });
                    }
                    if ($request->has('model') && !is_null($request->model)) {
                        $query->where('model', 'like', "%{$request->model}%");
                    }
                    if ($request->has('variance') && !is_null($request->variance)) {
                        $query->where('variance', 'like', "%{$request->variance}%");
                    }
                    if ($request->has('category') && !is_null($request->category)) {
                        $query->whereHas('categories', function($q) use ($request) {
                            $q->where('categories.name', 'like', "%{$request->category}%");
                        });
                    }
                    if ($request->has('supplier_name') && !is_null($request->supplier_name)) {
                        $query->whereHas('suppliers', function($q) use ($request) {
                            $q->where('suppliers.name', 'like', "%{$request->supplier_name}%");
                        });
                    }
                    if ($request->has('customer_name') && !is_null($request->customer_name)) {
                        $query->whereHas('customers', function($q) use ($request) {
                            $q->where('customers.name', 'like', "%{$request->customer_name}%");
                        });
                    }
                    if ($request->has('customer_product_code') && !is_null($request->customer_product_code)) {
                        $query->where('customer_product_code', 'like', "%{$request->customer_product_code}%");
                    }
                    if ($request->has('supplier_product_code') && !is_null($request->supplier_product_code)) {
                        $query->where('supplier_product_code', 'like', "%{$request->supplier_product_code}%");
                    }
                    if ($request->has('part_weight') && !is_null($request->part_weight)) {
                        $query->where('part_weight', 'like', "%{$request->part_weight}%");
                    }
                    if ($request->has('description') && !is_null($request->description)) {
                        $query->where('description', 'like', "%{$request->description}%");
                    }
                    if ($request->has('unit') && !is_null($request->unit)) {
                        $query->whereHas('units', function($q) use ($request) {
                            $q->where('units.user_name', 'like', "%{$request->unit}%");
                        });
                    }
                    if ($request->has('moq') && !is_null($request->moq)) {
                        $query->where('moq', 'like', "%{$request->moq}%");
                    }
                    if ($request->has('standard_packaging') && !is_null($request->standard_packaging)) {
                        $query->where('standard_packaging', 'like', "%{$request->standard_packaging}%");
                    }
                });
            }

               return $datatable->make(true);
        }
    }

    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Product List') ||
            Auth::user()->hasPermissionTo('Product Create') ||
            Auth::user()->hasPermissionTo('Product Edit') ||
            Auth::user()->hasPermissionTo('Product View') ||
            Auth::user()->hasPermissionTo('Product Delete')
        ) {
            $products = product::all();
            return view('database.product.index', compact('products'));
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }
    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Product Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $type_of_products = TypeOfProduct::all();
        $units = Unit::all();
        $categories = Category::all();
        return view(
            'database.product.create',
            compact('customers', 'suppliers', 'type_of_products', 'units', 'categories')
        );
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Product Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $validated = $request->validate([
            'part_no' => [
                'required',
                Rule::unique('products', 'part_no')->whereNull('deleted_at'),
            ],
            'part_name' => ['required'],
            'type_of_product' => ['required'],
            'unit' => ['required'],
        ]);

        $product = new Product();
        $product->part_no = $request->part_no ?? '';
        $product->part_name = $request->part_name ?? '';
        $product->type_of_product = $request->type_of_product ?? '';
        $product->model = $request->model ?? '';
        $product->category = $request->category ?? '';
        $product->variance = $request->variance ?? '';
        $product->description = $request->description ?? '';
        $product->moq = $request->moq ?? '';
        $product->unit = $request->unit ?? '';
        $product->part_weight = $request->part_weight ?? '';
        $product->standard_packaging = $request->standard_packaging ?? '';
        $product->customer_supplier = $request->customer_supplier ?? '';
        if ($request->customer_supplier) {
            $product->supplier_name = $request->supplier_name ?? '';
            $product->supplier_product_code =
                $request->supplier_product_code ?? '';
        } else {
            $product->customer_name = $request->customer_name ?? '';
            $product->customer_product_code =
                $request->customer_product_code ?? '';
        }
        $product->have_bom = $request->have_bom ?? '';
        $product->save();

        return redirect()
            ->route('product.index')
            ->with('custom_success', ' Product Created Successfully.');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Product Edit')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $product = product::find($id);
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $type_of_products = TypeOfProduct::all();
        $units = Unit::all();
        $categories = Category::all();
        return view(
            'database.product.edit',
            compact(
                'product',
                'customers',
                'suppliers',
                'type_of_products',
                'units',
                'categories'
            )
        );
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Product Edit')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $validated = $request->validate([
            'part_no' => [
                'required',
                Rule::unique('products', 'part_no')
                    ->whereNull('deleted_at')
                    ->ignore($id),
            ],
            'part_name' => ['required'],
            'type_of_product' => ['required'],
            'unit' => ['required'],
        ]);

        $product = Product::find($id);
        $product->part_no = $request->part_no ?? '';
        $product->part_name = $request->part_name ?? '';
        $product->type_of_product = $request->type_of_product ?? '';
        $product->model = $request->model ?? '';
        $product->category = $request->category ?? '';
        $product->variance = $request->variance ?? '';
        $product->description = $request->description ?? '';
        $product->moq = $request->moq ?? '';
        $product->unit = $request->unit ?? '';
        $product->part_weight = $request->part_weight ?? '';
        $product->standard_packaging = $request->standard_packaging ?? '';
        $product->customer_supplier = $request->customer_supplier ?? '';
        if ($request->customer_supplier) {
            $product->supplier_name = $request->supplier_name ?? '';
            $product->supplier_product_code = $request->supplier_product_code ?? '';
            $product->customer_name = null;
            $product->customer_product_code = null;
        } else {
            $product->customer_name = $request->customer_name ?? '';
            $product->customer_product_code = $request->customer_product_code ?? '';
            $product->supplier_name = null;
            $product->supplier_product_code = null;
        }
        $product->have_bom = $request->have_bom;
        $product->save();
        return redirect()
            ->route('product.index')
            ->with('custom_success', 'Product Updated Successfully.');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Product View')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $product = product::find($id);
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $type_of_products = TypeOfProduct::all();
        $units = Unit::all();
        return view(
            'database.product.view',
            compact(
                'product',
                'customers',
                'suppliers',
                'type_of_products',
                'units'
            )
        );
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Product Delete')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }

        $product = product::find($id);
        $product->delete();
        return redirect()
            ->route('product.index')
            ->with('custom_success', 'Product Deleted Successfully.');
    }
}
