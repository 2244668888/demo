<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\ProductReordering;
use App\Models\SstPercentage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductReorderingController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax() && $request->input('columnsData') != null) {
            $columnsData = $request->input('columnsData');
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
            $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

            $query = ProductReordering::select(
                'id',
                'product_id',
                'critical_min',
                'min_qty',
                'max_qty'
            )->with('products');

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->whereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'part_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'part_name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products.units', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products.type_of_products', function (
                            $query
                        ) use ($searchLower) {
                            $query->where(
                                'type',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'model',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'variance',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhere(
                            'critical_min',
                            'like',
                            '%' . $searchLower . '%'
                        )
                        ->orWhere('min_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('max_qty', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {
                $sortableColumns = [
                    1 => 'product_id',
                    2 => 'product_id',
                    3 => 'product_id',
                    4 => 'product_id',
                    5 => 'product_id',
                    6 => 'product_id',
                    7 => 'critical_min',
                    8 => 'min_qty',
                    9 => 'max_qty',

                    // Add more columns as needed
                ];
                if ($orderByColumnIndex != null) {
                    if ($orderByColumnIndex == '0') {
                        $orderByColumn = 'created_at';
                        $orderByDirection = 'ASC';
                    } else {
                        $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    }
                } else {
                    $orderByColumn = 'created_at';
                }
                if ($orderByDirection == null) {
                    $orderByDirection = 'ASC';
                }
                $results = $query
                    ->where(function ($q) use ($columnsData) {
                        foreach ($columnsData as $column) {
                            $searchLower = strtolower($column['value']);

                            switch ($column['index']) {
                                case 1:
                                    $q->whereHas('products', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'part_no',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });

                                    break;
                                case 2:
                                    $q->whereHas('products', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'part_name',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });

                                    break;
                                case 3:
                                    $q->whereHas('products.units', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'name',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });

                                    break;
                                case 4:
                                    $q->whereHas(
                                        'products.type_of_products',
                                        function ($query) use ($searchLower) {
                                            $query->where(
                                                'type',
                                                'like',
                                                '%' . $searchLower . '%'
                                            );
                                        }
                                    );
                                    break;

                                case 5:
                                    $q->whereHas('products', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'model',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });
                                    break;
                                case 6:
                                    $q->whereHas('products', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'variance',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });

                                    break;
                                case 7:
                                    $q->where(
                                        'critical_min',
                                        'like',
                                        '%' . $searchLower . '%'
                                    );

                                    break;
                                case 8:
                                    $q->where(
                                        'min_qty',
                                        'like',
                                        '%' . $searchLower . '%'
                                    );
                                    break;
                                case 9:
                                    $q->Where(
                                        'max_qty',
                                        'like',
                                        '%' . $searchLower . '%'
                                    );
                                    break;

                                default:
                                    break;
                            }
                        }
                    })
                    ->orderBy($orderByColumn, $orderByDirection)
                    ->get();
            }

            // type_of_rejection and format the results for DataTables
            $recordsTotal = $results ? $results->count() : 0;

            // Check if there are results before applying skip and take
            if ($results->isNotEmpty()) {
                $uom = $results
                    ->skip($start)
                    ->take($length)
                    ->all();
            } else {
                $uom = [];
            }

            $index = 0;
            foreach ($uom as $row) {
                $row->sr_no = $start + $index + 1;

                $row->unit = $row->products->units->name;
                $row->type = $row->products->type_of_products->type;

                $row->action =
                    '<div class="d-flex"><a class="btn btn-success btn-sm"
                href="' .
                    route('product_reordering.view', $row->product_id) .
                    '"><i
                    class="bi bi-eye"></i></a>
            <a class="btn btn-info btn-sm mx-2"
                href="' .
                    route('product_reordering.edit', $row->product_id) .
                    '"><i
                    class="bi bi-pencil"></i></a>
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
                                                <form method="POST" action="' . route('product_reordering.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                $index++;
            }

            // // Continue with your response
            $uomsWithoutAction = array_map(function ($row) {
                return $row;
            }, $uom);

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => array_values($uomsWithoutAction),
            ]);
        } elseif ($request->ajax()) {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
            $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

            $query = ProductReordering::select(
                'id',
                'product_id',
                'critical_min',
                'min_qty',
                'max_qty'
            )->with('products');

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->whereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'part_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'part_name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products.units', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products.type_of_products', function (
                            $query
                        ) use ($searchLower) {
                            $query->where(
                                'type',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'model',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('products', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'variance',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhere(
                            'critical_min',
                            'like',
                            '%' . $searchLower . '%'
                        )
                        ->orWhere('min_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('max_qty', 'like', '%' . $searchLower . '%');
                });
            }

            $sortableColumns = [
                1 => 'product_id',
                2 => 'product_id',
                3 => 'product_id',
                4 => 'product_id',
                5 => 'product_id',
                6 => 'product_id',
                7 => 'critical_min',
                8 => 'min_qty',
                9 => 'max_qty',

                // Add more columns as needed
            ];
            if ($orderByColumnIndex != null) {
                if ($orderByColumnIndex != '0') {
                    $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    $query->orderBy($orderByColumn, $orderByDirection);
                } else {
                    $query->latest('created_at');
                }
            } else {
                $query->latest('created_at');
            }
            $recordsTotal = $query->count();

            $uom = $query
                ->skip($start)
                ->take($length)
                ->get();

            $uom->each(function ($row, $index) use (&$start) {
                $row->sr_no = $start + $index + 1;

                $row->unit = $row->products->units->name;
                $row->type = $row->products->type_of_products->type;

                $row->action =
                    '<div class="d-flex"><a class="btn btn-success btn-sm"
                                        href="' .
                    route('product_reordering.view', $row->product_id) .
                    '"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-info btn-sm mx-2"
                                        href="' .
                    route('product_reordering.edit', $row->product_id) .
                    '"><i
                                            class="bi bi-pencil"></i></a>
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
                                                <form method="POST" action="' . route('product_reordering.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => $uom,
            ]);
        }
    }
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Product Reordering List') ||
            Auth::user()->hasPermissionTo('Product Reordering Create') ||
            Auth::user()->hasPermissionTo('Product Reordering Edit') ||
            Auth::user()->hasPermissionTo('Product Reordering View') ||
            Auth::user()->hasPermissionTo('Product Reordering Delete')
        ) {
            $product_reorderings = ProductReordering::all();
            return view(
                'wms.operations.product-reordering.index',
                compact('product_reorderings')
            );
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }
    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Product Reordering Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $products = Product::whereNotIn('id', function ($query) {
            $query
                ->select('product_id')
                ->from('product_reorderings')
                ->whereNull('deleted_at');
        })->get();
        return view(
            'wms.operations.product-reordering.create',
            compact('products')
        );
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Product Reordering Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $validated = $request->validate([
            'products' => ['required'],
        ]);

        foreach ($request->products as $products) {
            $product_reordering = new ProductReordering();
            $product_reordering->product_id = $products['product_id'];
            $product_reordering->critical_min = $products['critical_min'] ?? 0;
            $product_reordering->min_qty = $products['min_qty'] ?? 0;
            $product_reordering->max_qty = $products['max_qty'] ?? 0;
            $product_reordering->save();
        }

        return redirect()
            ->route('product_reordering.index')
            ->with(
                'custom_success',
                'Product Reordering Created Successfully.'
            );
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Product Reordering Edit')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $product_reordering = ProductReordering::where('product_id', $id)
            ->whereNull('deleted_at')
            ->first();
        return view(
            'wms.operations.product-reordering.edit',
            compact('product_reordering')
        );
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Product Reordering Edit')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }

        $product_reordering = ProductReordering::where(
            'product_id',
            $id
        )->first();
        $product_reordering->critical_min = $request->critical_min;
        $product_reordering->min_qty = $request->min_qty;
        $product_reordering->max_qty = $request->max_qty;
        $product_reordering->save();

        return redirect()
            ->route('product_reordering.index')
            ->with(
                'custom_success',
                'Product Reordering Updated Successfully.'
            );
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Product Reordering View')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $product_reordering = ProductReordering::where('product_id', $id)
            ->whereNull('deleted_at')
            ->first();
        return view(
            'wms.operations.product-reordering.view',
            compact('product_reordering')
        );
    }

    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Product Reordering Delete')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $product_reordering = ProductReordering::find($id);
        $product_reordering->delete();
        return redirect()
            ->route('product_reordering.index')
            ->with(
                'custom_success',
                'Product Reordering Deleted Successfully.'
            );
    }
}
