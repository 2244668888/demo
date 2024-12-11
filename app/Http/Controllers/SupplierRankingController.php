<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierRanking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SupplierRankingController extends Controller
{
    public function Data(Request $request)
    {
        // if ($request->ajax() && $request->input('columnsData') != null) {
        //     $columnsData = $request->input('columnsData');
        //     $draw = $request->input('draw');
        //     $start = $request->input('start');
        //     $length = $request->input('length');
        //     $search = $request->input('search.value');
        //     $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
        //     $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

        //     $query = SupplierRanking::select('id', 'ranking_date', 'ranking', 'supplier_id', 'created_by', 'date')->with(['supplier', 'user']);

        //     // Apply search if a search term is provided
        //     if (!empty($search)) {
        //         $searchLower = strtolower($search);
        //         $query->where(function ($q) use ($searchLower) {
        //             $q
        //                 ->WhereHas('supplier', function ($query) use ($searchLower) {
        //                     $query->where('name', 'like', '%' . $searchLower . '%');
        //                 })
        //                 ->orWhere('ranking_date', 'like', '%' . $searchLower . '%')
        //                 ->orWhere('ranking', 'like', '%' . $searchLower . '%')
        //                 ->orWhereHas('user', function ($query) use ($searchLower) {
        //                     $query->where('user_name', 'like', '%' . $searchLower . '%');
        //                 })
        //                 ->orWhere('date', 'like', '%' . $searchLower . '%');
        //         });
        //     }
        //     $results = null;

        //     if (!empty($columnsData)) {

        //         $sortableColumns = [
        //             1 => 'supplier_id',
        //             2 => 'ranking_date',
        //             3 => 'ranking',
        //             4 => 'created_by',
        //             5 => 'date',





        //             // Add more columns as needed
        //         ];
        //         if ($orderByColumnIndex != null) {
        //             if ($orderByColumnIndex == "0") {
        //                 $orderByColumn = 'created_at';
        //                 $orderByDirection = 'ASC';
        //             } else {
        //                 $orderByColumn = $sortableColumns[$orderByColumnIndex];
        //             }
        //         } else {
        //             $orderByColumn = 'created_at';
        //         }
        //         if ($orderByDirection == null) {
        //             $orderByDirection = 'ASC';
        //         }
        //         $results = $query->where(function ($q) use ($columnsData) {
        //             foreach ($columnsData as $column) {
        //                 $searchLower = strtolower($column['value']);

        //                 switch ($column['index']) {
        //                     case 1:
        //                         $q->WhereHas('supplier', function ($query) use ($searchLower) {
        //                             $query->where('name', 'like', '%' . $searchLower . '%');
        //                         });


        //                         break;
        //                     case 2:
        //                         $q->where('ranking_date', 'like', '%' . $searchLower . '%');

        //                         // dd($q->get());
        //                         break;
        //                     case 3:

        //                         $q->where('ranking', 'like', '%' . $searchLower . '%');


        //                         break;
        //                     case 4:
        //                         $q->WhereHas('user', function ($query) use ($searchLower) {
        //                             $query->where('user_name', 'like', '%' . $searchLower . '%');
        //                         });



        //                         break;
        //                     case 5:
        //                         $q->where('date', 'like', '%' . $searchLower . '%');
        //                         // dd($q->get());

        //                         break;
        //                     default:
        //                         break;
        //                 }
        //             }
        //         })->orderBy($orderByColumn, $orderByDirection)->get();
        //     }

        //     // type_of_rejection and format the results for DataTables
        //     $recordsTotal = $results ? $results->count() : 0;

        //     // Check if there are results before applying skip and take
        //     if ($results->isNotEmpty()) {
        //         $uom = $results->skip($start)->take($length)->all();
        //     } else {
        //         $uom = [];
        //     }

        //     $index = 0;
        //     foreach ($uom as $row) {
        //         $row->sr_no = $start + $index + 1;

        //         // $status = '';

        //         $row->date = Carbon::parse($row->date)->format('d/m/Y');
        //         try {
        //             $row->ranking_date = Carbon::createFromFormat('m/Y', $row->ranking_date)->format('m/Y');
        //         } catch (\Exception $e) {
        //             $row->ranking_date = $row->ranking_date;
        //         }

        //         if ($row->ranking == 'A') {
        //             $row->ranking = ' <span class="badge bg-success">A</span>';
        //         } elseif ($row->ranking == 'B') {
        //             $row->ranking = ' <span class="badge bg-warning">B</span>';
        //         } elseif ($row->ranking == 'C') {
        //             $row->ranking = ' <span class="badge bg-danger">C</span>';
        //         }


        //         // $row->status = $status;

        //         $row->action = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2"
        //                                 href="' . route('supplier_ranking.edit', $row->id) . '"><i
        //                                     class="bi bi-pencil"></i></a>
        //                             <a class="btn btn-success btn-sm mx-2"
        //                                 href="' . route('supplier_ranking.view', $row->id) . '"><i
        //                                     class="bi bi-eye"></i></a>
        //                             <button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
        //                             <i class="bi bi-trash"></i>
        //                         </button>

        //                         <!-- Delete Modal -->
        //                         <div class="modal fade" id="' . $row->id . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel' . $row->id . '" aria-hidden="true">
        //                             <div class="modal-dialog">
        //                                 <div class="modal-content">
        //                                     <div class="modal-header">
        //                                         <h5 class="modal-title" id="staticBackdropLabel' . $row->id . '">Delete Problem</h5>
        //                                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        //                                     </div>
        //                                     <div class="modal-body">
        //                                         Are you sure you want to delete this problem?
        //                                     </div>
        //                                     <div class="modal-footer">
        //                                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        //                                         <form method="POST" action="' . route('supplier_ranking.destroy', $row->id) . '">
        //                                             ' . csrf_field() . '
        //                                             ' . method_field('DELETE') . '
        //                                             <button type="submit" class="btn btn-danger">Delete</button>
        //                                         </form>
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div></div>';


        //         $index++;
        //     }

        //     // // Continue with your response
        //     $uomsWithoutAction = array_map(function ($row) {
        //         return $row;
        //     }, $uom);

        //     return response()->json([
        //         'draw' => $draw,
        //         'recordsTotal' => $recordsTotal,
        //         'recordsFiltered' => $recordsTotal, // Total records after filtering
        //         'data' => array_values($uomsWithoutAction),
        //     ]);
        // } elseif ($request->ajax()) {

        //     $draw = $request->input('draw');
        //     $start = $request->input('start');
        //     $length = $request->input('length');
        //     $search = $request->input('search.value');
        //     $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
        //     $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

        //     $query = SupplierRanking::select('id', 'ranking_date', 'ranking', 'supplier_id', 'created_by', 'date')->with(['supplier', 'user']);
        //     // Apply search if a search term is provided
        //     if (!empty($search)) {
        //         $searchLower = strtolower($search);
        //         $query->where(function ($q) use ($searchLower) {
        //             $q
        //                 ->WhereHas('supplier', function ($query) use ($searchLower) {
        //                     $query->where('name', 'like', '%' . $searchLower . '%');
        //                 })
        //                 ->orWhere('ranking_date', 'like', '%' . $searchLower . '%')
        //                 ->orWhere('ranking', 'like', '%' . $searchLower . '%')
        //                 ->orWhereHas('user', function ($query) use ($searchLower) {
        //                     $query->where('user_name', 'like', '%' . $searchLower . '%');
        //                 })
        //                 ->orWhere('date', 'like', '%' . $searchLower . '%');
        //         });
        //     }


        //     $sortableColumns = [
        //         1 => 'supplier_id',
        //         2 => 'ranking_date',
        //         3 => 'ranking',
        //         4 => 'created_by',
        //         5 => 'date',





        //         // Add more columns as needed
        //     ];

        //     if ($orderByColumnIndex != null) {
        //         if ($orderByColumnIndex != "0") {
        //             $orderByColumn = $sortableColumns[$orderByColumnIndex];
        //             $query->orderBy($orderByColumn, $orderByDirection);
        //         } else {
        //             $query->latest('created_at');
        //         }
        //     } else {
        //         $query->latest('created_at');
        //     }
        //     $recordsTotal = $query->count();

        //     $uom = $query
        //         ->skip($start)
        //         ->take($length)
        //         ->get();

        //     $uom->each(function ($row, $index)  use (&$start) {
        //         $row->sr_no = $start + $index + 1;

        //         $row->date = Carbon::parse($row->date)->format('d/m/Y');
        //         try {
        //             $row->ranking_date = Carbon::createFromFormat('m/Y', $row->ranking_date)->format('m/Y');
        //         } catch (\Exception $e) {
        //             $row->ranking_date = $row->ranking_date;
        //         }

        //         if ($row->ranking == 'A') {
        //             $row->ranking = ' <span class="badge bg-success">A</span>';
        //         } elseif ($row->ranking == 'B') {
        //             $row->ranking = ' <span class="badge bg-warning">B</span>';
        //         } elseif ($row->ranking == 'C') {
        //             $row->ranking = ' <span class="badge bg-danger">C</span>';
        //         }


        //         // $row->status = $status;

        //         $row->action = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2"
        //         href="' . route('supplier_ranking.edit', $row->id) . '"><i
        //             class="bi bi-pencil"></i></a>
        //     <a class="btn btn-success btn-sm mx-2"
        //         href="' . route('supplier_ranking.view', $row->id) . '"><i
        //             class="bi bi-eye"></i></a>
        //     <button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
        //                             <i class="bi bi-trash"></i>
        //                         </button>

        //                         <!-- Delete Modal -->
        //                         <div class="modal fade" id="' . $row->id . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel' . $row->id . '" aria-hidden="true">
        //                             <div class="modal-dialog">
        //                                 <div class="modal-content">
        //                                     <div class="modal-header">
        //                                         <h5 class="modal-title" id="staticBackdropLabel' . $row->id . '">Delete Problem</h5>
        //                                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        //                                     </div>
        //                                     <div class="modal-body">
        //                                         Are you sure you want to delete this problem?
        //                                     </div>
        //                                     <div class="modal-footer">
        //                                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        //                                         <form method="POST" action="' . route('supplier_ranking.destroy', $row->id) . '">
        //                                             ' . csrf_field() . '
        //                                             ' . method_field('DELETE') . '
        //                                             <button type="submit" class="btn btn-danger">Delete</button>
        //                                         </form>
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div></div>';
        //     });

        //     return response()->json([
        //         'draw' => $draw,
        //         'recordsTotal' => $recordsTotal,
        //         'recordsFiltered' => $recordsTotal, // Total records after filtering
        //         'data' => $uom,
        //     ]);
        // }


        if ($request->ajax()) {

            $query = SupplierRanking::select('supplier_rankings.id', 'supplier_rankings.ranking_date', 'supplier_rankings.ranking', 'supplier_rankings.supplier_id', 'supplier_rankings.created_by', 'supplier_rankings.date')->with(['supplier', 'user']);



            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                // ->editColumn('date', function ($row) {
                //     return Carbon::parse($row->date)->format('d/m/Y');
                // })
                ->editColumn('ranking_date', function ($row) {
                    try {
                        return Carbon::createFromFormat('m/Y', $row->ranking_date)->format('m/Y');
                    } catch (\Exception $e) {
                        return $row->ranking_date;
                    }
                })
                ->editColumn('ranking', function ($row) {
                    if ($row->ranking == 'A') {
                        return '<span class="badge bg-success">A</span>';
                    } elseif ($row->ranking == 'B') {
                        return '<span class="badge bg-warning">B</span>';
                    } elseif ($row->ranking == 'C') {
                        return '<span class="badge bg-danger">C</span>';
                    }
                    return $row->ranking;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex">
                                <a class="btn btn-info btn-sm mx-2" href="' . route('supplier_ranking.edit', $row->id) . '">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-success btn-sm mx-2" href="' . route('supplier_ranking.view', $row->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#modal-' . $row->id . '">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <!-- Delete Modal -->
                                <div class="modal fade" id="modal-' . $row->id . '" tabindex="-1" aria-labelledby="modalLabel-' . $row->id . '" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel-' . $row->id . '">Delete Confirmation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this item?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form method="POST" action="' . route('supplier_ranking.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                })
                ->rawColumns(['ranking', 'action']);
            // dd($request->search['value']);




            if ($request->search['value'] == null) {

                $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('supplier') && !is_null($request->supplier)) {
                        $query->whereHas('supplier', function($q) use ($request) {
                            $q->where('suppliers.name', 'like', "%{$request->supplier}%");
                        });
                    }
                    if ($request->has('ranking_date') && !is_null($request->ranking_date)) {
                        $query->where('ranking_date', 'like', "%{$request->ranking_date}%");
                    }
                    if ($request->has('ranking') && !is_null($request->ranking)) {
                        $query->where('ranking', 'like', "%{$request->ranking}%");
                    }
                    if ($request->has('created_by') && !is_null($request->created_by)) {
                        $query->whereHas('user', function($q) use ($request) {
                            $q->where('users.user_name', 'like', "%{$request->created_by}%");
                        });
                    }
                    if ($request->has('date') && !is_null($request->date)) {
                        $query->where('date', 'like', "%{$request->date}%");
                    }
                });
            }

            return $datatable->make(true);
        }
    }
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Supplier Ranking List') ||
            Auth::user()->hasPermissionTo('Supplier Ranking Create') ||
            Auth::user()->hasPermissionTo('Supplier Ranking Edit') ||
            Auth::user()->hasPermissionTo('Supplier Ranking View') ||
            Auth::user()->hasPermissionTo('Supplier Ranking Delete')
        ) {
            $supplierrankings = SupplierRanking::with('supplier', 'user')->get();
            return view('erp.pvd.supplier-ranking.index', compact('supplierrankings'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Supplier Ranking Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $Suppliers = Supplier::all();
        return view('erp.pvd.supplier-ranking.create', compact('Suppliers'));
    }
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Supplier Ranking Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $request->validate([
            'supplier_id' => ['required'],
            'date' => ['required'],
            'ranking' => ['required'],
            'ranking_date' => ['required'],
        ]);
        $Supplierrankings = new SupplierRanking();
        $Supplierrankings->created_by = Auth::user()->id;
        $Supplierrankings->supplier_id = $request->supplier_id;
        $Supplierrankings->date = $request->date;
        $Supplierrankings->ranking = $request->ranking;
        $Supplierrankings->ranking_date = $request->ranking_date;


        $Supplierrankings->save();
        NotificationController::Notification('Supplier Ranking', 'Create', '' . route('supplier_ranking.view', $Supplierrankings->id) . '');

        return redirect()->route('supplier_ranking.index')->with('custom_success', 'supplier Ranking Created Successfully.');
    }
    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Supplier Ranking View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $Supplierranking = SupplierRanking::with('supplier', 'user')->find($id);
        return view('erp.pvd.supplier-ranking.view', compact('Supplierranking'));
    }
    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Supplier Ranking Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $Suppliers = Supplier::all();
        $Supplierranking = SupplierRanking::with('supplier', 'user')->find($id);
        return view('erp.pvd.supplier-ranking.edit', compact('Supplierranking', 'Suppliers'));
    }
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Supplier Ranking Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $request->validate([
            'supplier_id' => ['required'],
            'date' => ['required'],
            'ranking' => ['required'],
            'ranking_date' => ['required'],
        ]);
        $Supplierrankings = SupplierRanking::find($id);
        $Supplierrankings->created_by = Auth::user()->id;
        $Supplierrankings->supplier_id = $request->supplier_id;
        $Supplierrankings->date = $request->date;
        $Supplierrankings->ranking = $request->ranking;
        $Supplierrankings->ranking_date = $request->ranking_date;
        $Supplierrankings->save();
        return redirect()->route('supplier_ranking.index')->with('custom_success', 'supplier Ranking Updated Successfully.');
    }
    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Supplier Ranking Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $Supplierrankings = SupplierRanking::find($id);
        $Supplierrankings->delete();
        return redirect()->route('supplier_ranking.index')->with('custom_success', 'supplier Ranking Deleted Successfully.');
    }
}
