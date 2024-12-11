<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AreaLocation;
use App\Models\InitailNo;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Product;
use App\Models\SalePrice;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetail;
use App\Models\SalesReturnLocation;
use App\Models\Transaction;
use App\Models\Account;
use Carbon\Carbon;
use App\Models\LotNo;
use Illuminate\Http\Request;

class SalesReturnController extends Controller
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

            $query = SalesReturn::select('id', 'ref_no', 'date','qty');


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('date', 'like', '%' . $searchLower . '%')
                        ->orWhere('qty', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'ref_no',
                    2 => 'date',
                    3 => 'qty',


                    // Add more columns as needed
                ];
                if($orderByColumnIndex != null){
                    if($orderByColumnIndex == "0"){
                        $orderByColumn = 'created_at';
                        $orderByDirection = 'ASC';
                    }else{
                        $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    }
                }else{
                    $orderByColumn = 'created_at';
                }
                if($orderByDirection == null){
                    $orderByDirection = 'ASC';
                }
                $results = $query->where(function ($q) use ($columnsData) {
                    foreach ($columnsData as $column) {
                        $searchLower = strtolower($column['value']);

                        switch ($column['index']) {
                            case 1:
                                $q->where('ref_no', 'like', '%' . $searchLower . '%');

                                break;
                            case 2:
                                $q->where('date', 'like', '%' . $searchLower . '%');

                                break;
                                case 3:
                                    $q->where('qty', 'like', '%' . $searchLower . '%');


                                    break;


                            default:
                                break;
                        }
                    }
                })->orderBy($orderByColumn, $orderByDirection)->get();

            }

            // type_of_rejection and format the results for DataTables
            $recordsTotal = $results ? $results->count() : 0;

            // Check if there are results before applying skip and take
            if ($results->isNotEmpty()) {
                $uom = $results->skip($start)->take($length)->all();
            } else {
                $uom = [];
            }

            $index = 0;
            foreach ($uom as $row) {
                $row->date = date('d-m-Y',strtotime($row->date));
                $row->sr_no = $start + $index + 1;

                // $status = '';

                // $row->date = Carbon::parse($row->date)->format('d/m/Y');
                // dd($row->status);
                $row->action = '<a class="btn btn-success btn-sm"
                href="' . route('sales_return.view', $row->id) .'"><i
                    class="bi bi-eye"></i></a>
            <a class="btn btn-info btn-sm"
                href="' . route('sales_return.edit', $row->id) .'"><i
                    class="bi bi-pencil"></i></a>
            <a class="btn btn-danger btn-sm"
                href="' . route('sales_return.destroy', $row->id) .'"><i
                    class="bi bi-trash"></i></a>';
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

            $query = SalesReturn::select('id', 'ref_no', 'date','qty');

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('date', 'like', '%' . $searchLower . '%')
                        ->orWhere('qty', 'like', '%' . $searchLower . '%');
                });
            }

            $sortableColumns = [
                1 => 'ref_no',
                2 => 'date',
                3 => 'qty',


                // Add more columns as needed
            ];
            if($orderByColumnIndex != null){
                if($orderByColumnIndex != "0"){
                    $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    $query->orderBy($orderByColumn, $orderByDirection);
                }else{
                    $query->latest('created_at');
                }
            }else{
                $query->latest('created_at');
            }
            $recordsTotal = $query->count();

            $uom = $query
                ->skip($start)
                ->take($length)
                ->get();

            $uom->each(function ($row, $index)  use (&$start) {
                $row->sr_no = $start + $index + 1;
                $row->date = date('d-m-Y',strtotime($row->date));

                $row->action = '<a class="btn btn-success btn-sm"
                href="' . route('sales_return.view', $row->id) .'"><i
                    class="bi bi-eye"></i></a>
            <a class="btn btn-info btn-sm"
                href="' . route('sales_return.edit', $row->id) .'"><i
                    class="bi bi-pencil"></i></a>
            <a class="btn btn-danger btn-sm"
                href="' . route('sales_return.destroy', $row->id) .'"><i
                    class="bi bi-trash"></i></a>';



            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => $uom,
            ]);
        }
    }
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Sales Return List') ||
            Auth::user()->hasPermissionTo('Sales Return Create') ||
            Auth::user()->hasPermissionTo('Sales Return Edit') ||
            Auth::user()->hasPermissionTo('Sales Return View') ||
            Auth::user()->hasPermissionTo('Sales Return Delete')
        ) {
            $sales_returns = SalesReturn::all();
            return view('wms.operations.sales-return.index', compact('sales_returns'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Sales Return Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Sales Return')->first();

        if ($setting) {
            $stock = SalesReturn::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $ref_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $stock = SalesReturn::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'SR/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'SR/1/' . $year;
            }
        }
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        $products = Product::all();
        $customers = Customer::all();
        return view('wms.operations.sales-return.create', compact('ref_no', 'products', 'locations', 'customers'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Sales Return Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'customer_id' => [
                'required'
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $inventoryAccount = Account::where('name', 'Inventory')->first();
        $accountRecievableAccount = Account::where('name', 'Account Receivable')->first();
        if (!$inventoryAccount || !$accountRecievableAccount) {
            return redirect()->back()->with('custom_errors', 'You must create an Inventory or Account Receivable account first in Account Details, e.g. Inventory, Account Receivable');
        }

        $sales_return = new SalesReturn();
        $sales_return->customer_id = $request->customer_id;
        $sales_return->ref_no = $request->ref_no;
        $sales_return->date = $request->date;
        $sales_return->created_by = Auth::user()->id;
        $sales_return->save();

        $total_qty = 0;
        $total_return_amount = 0;

        foreach($request->products as $products){
            $sales_return_product = new SalesReturnDetail();
            $sales_return_product->sales_return_id = $sales_return->id;
            $sales_return_product->product_id = $products['product_id'] ?? null;
            $sales_return_product->reason = $products['reason'] ?? null;
            $sales_return_product->qty = $products['qty'] ?? 0;
            $sales_return_product->save();
            $total_qty += $products['qty'] ?? 0;
            $salePrice = SalePrice::select('price')->where('product_id', $products['product_id'])->first();
            $product_price = $salePrice ? $salePrice->price : 0;
            $return_amount = $product_price * ($products['qty'] ?? 0);
            $total_return_amount += $return_amount;
        }

        $sales_return->update([
            'qty' => $total_qty
        ]);
        

        Transaction::create([
            'account_id' => $inventoryAccount->id,
            'type' => 'debit',
            'amount' => $total_return_amount,
            'description' => 'Increase inventory for Sale Return #' . $sales_return->id,
        ]);

        Transaction::create([
            'account_id' => $accountRecievableAccount->id,
            'type' => 'credit',
            'amount' => $total_return_amount,
            'description' => 'Decrease Account Receivable Sale Return #' . $sales_return->id,
        ]);

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $detail = new SalesReturnLocation();
            $detail->sales_return_id = $sales_return->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->area_id = $value['area'] ?? null;
            $detail->rack_id = $value['rack'] ?? null;
            $detail->level_id = $value['level'] ?? null;
            $detail->lot_no = null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

            $location = Location::where('area_id', $detail->area_id)->where('rack_id', $detail->rack_id)->where('level_id', $detail->level_id)->where('product_id', $detail->product_id)->first();
            if ($location) {
                $location->used_qty += $detail->qty ?? 0;
            } else {
                if($detail->area_id != null && $detail->rack_id != null && $detail->level_id != null){
                    $location = new Location();
                    $location->area_id = $detail->area_id;
                    $location->rack_id = $detail->rack_id;
                    $location->level_id = $detail->level_id;
                    $location->product_id = $detail->product_id;
                    $location->lot_no = null;
                    $location->used_qty = $detail->qty ?? 0;
                }
            }
            if($detail->area_id != null && $detail->rack_id != null && $detail->level_id != null){
                $location->save();
            }
        }

        NotificationController::Notification(
            'Sales Return',
            'Create',
            route('sales_return.index', $sales_return->id)
        );

        return redirect()->route('sales_return.index')->with('custom_success', 'Sales Return has been Created Successfully !');
    }


    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Sales Return Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $sales_return = SalesReturn::find($id);
        $sales_return_products = SalesReturnDetail::where('sales_return_id', $id)->get();
        $sales_return_locations = SalesReturnLocation::where('sales_return_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        $products = Product::all();
        $customers = Customer::all();
        return view('wms.operations.sales-return.edit',compact('sales_return', 'sales_return_products', 'sales_return_locations', 'locations', 'products', 'customers'));
    }

    public function update(Request $request,$id)
    {
        if (!Auth::user()->hasPermissionTo('Sales Return Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'customer_id' => [
                'required'
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $sales_return = SalesReturn::find($id);
        $sales_return->customer_id = $request->customer_id;
        $sales_return->ref_no = $request->ref_no;
        $sales_return->date = $request->date;
        $sales_return->created_by = Auth::user()->id;
        $sales_return->save();

        $details = SalesReturnDetail::where('sales_return_id', '=', $id)->get();
        $detailIds = $details->pluck('product_id')->toArray();
        $existingDetails = SalesReturnLocation::whereIn('product_id', $detailIds)->get();

        foreach ($existingDetails as $existingDetail) {
            if($existingDetail->area_id != null && $existingDetail->rack_id != null && $existingDetail->level_id != null){
                $location = Location::where('area_id', $existingDetail->area_id)->where('rack_id', $existingDetail->rack_id)->where('level_id', $existingDetail->level_id)->where('product_id', $existingDetail->product_id)->first();

                if ($location) {
                    $location->used_qty -= $existingDetail->qty ?? 0;
                    $location->save();
                }
            }
        }

        SalesReturnDetail::where('sales_return_id', $id)->delete();
        SalesReturnLocation::whereIn('product_id', $detailIds)->delete();

        $total_qty = 0;
        foreach($request->products as $products){
            $sales_return_product = new SalesReturnDetail();
            $sales_return_product->sales_return_id = $sales_return->id;
            $sales_return_product->product_id = $products['product_id'] ?? null;
            $sales_return_product->reason = $products['reason'] ?? null;
            $sales_return_product->qty = $products['qty'] ?? 0;
            $sales_return_product->save();
            $total_qty += $products['qty'] ?? 0;
        }

        $sales_return->update([
            'qty' => $total_qty
        ]);

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $detail = new SalesReturnLocation();
            $detail->sales_return_id = $sales_return->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->area_id = $value['area'] ?? null;
            $detail->rack_id = $value['rack'] ?? null;
            $detail->level_id = $value['level'] ?? null;
            $detail->lot_no = null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

            $location = Location::where('area_id', $detail->area_id)->where('rack_id', $detail->rack_id)->where('level_id', $detail->level_id)->where('product_id', $detail->product_id)->first();
            if ($location) {
                $location->used_qty += $detail->qty ?? 0;
            } else {
                if($detail->area_id != null && $detail->rack_id != null && $detail->level_id != null){
                    $location = new Location();
                    $location->area_id = $detail->area_id;
                    $location->rack_id = $detail->rack_id;
                    $location->level_id = $detail->level_id;
                    $location->product_id = $detail->product_id;
                    $location->lot_no = null;
                    $location->used_qty = $detail->qty ?? 0;
                }
            }
            if($detail->area_id != null && $detail->rack_id != null && $detail->level_id != null){
                $location->save();
            }
        }

        return redirect()->route('sales_return.index')->with('custom_success', 'Sales Return has been Updated Successfully !');
    }

    public function view($id){
        if (!Auth::user()->hasPermissionTo('Sales Return View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $sales_return = SalesReturn::find($id);
        $sales_return_products = SalesReturnDetail::where('sales_return_id', $id)->get();
        $sales_return_locations = SalesReturnLocation::where('sales_return_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        $customers = Customer::all();
        return view('wms.operations.sales-return.view',compact('sales_return', 'sales_return_products', 'sales_return_locations', 'locations', 'customers'));
    }

    public function destroy($id){
        if (!Auth::user()->hasPermissionTo('Sales Return Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $sales_return = SalesReturn::find($id);

        $details = SalesReturnDetail::where('sales_return_id', '=', $id)->get();
        $detailIds = $details->pluck('product_id')->toArray();
        $existingDetails = SalesReturnLocation::whereIn('product_id', $detailIds)->get();

        foreach ($existingDetails as $existingDetail) {
            if($existingDetail->area_id != null && $existingDetail->rack_id != null && $existingDetail->level_id != null){
                $location = Location::where('area_id', $existingDetail->area_id)->where('rack_id', $existingDetail->rack_id)->where('level_id', $existingDetail->level_id)->where('product_id', $existingDetail->product_id)->first();

                if ($location) {
                    $location->used_qty -= $existingDetail->qty ?? 0;
                    $location->save();
                }
            }
        }

        SalesReturnDetail::where('sales_return_id', $id)->delete();
        SalesReturnLocation::where('sales_return_id', $id)->delete();
        $sales_return->delete();
        return redirect()->route('sales_return.index')->with('custom_success', 'Sales Return has been Successfully Deleted!');
    }
}
