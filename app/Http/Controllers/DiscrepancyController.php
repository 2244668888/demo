<?php

namespace App\Http\Controllers;

use App\Models\AreaLocation;
use App\Models\Discrepancy;
use App\Models\InitailNo;
use App\Models\Location;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionDetails;
use App\Models\MaterialRequisitionProductDetails;
use App\Models\Product;
use App\Models\TransferRequest;
use App\Models\TransferRequestDetails;
use App\Models\TransferRequestIssue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscrepancyController extends Controller
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

            $query = Discrepancy::select('id', 'ref_no','date', 'order_no','mrf_tr_id','order_no','product_id', 'issue_qty','rcv_qty','status')->with(['mrf','tr','products']);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                    ->where(function ($query) use ($searchLower) {
                        if (\Carbon\Carbon::hasFormat($searchLower, 'd-m-Y')) {
                            $searchDate = \Carbon\Carbon::createFromFormat('d-m-Y', $searchLower)->format('Y-m-d');
                        } else {
                            $searchDate = $searchLower;
                        }
                        $query->where('date', 'like', '%' . $searchDate . '%');
                    })
                        // ->orWhere('order_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('tr', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('mrf', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('order_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('products', function ($query) use ($searchLower) {
                            $query->where('part_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('products', function ($query) use ($searchLower) {
                            $query->where('part_name', 'like', '%' . $searchLower . '%');
                        })

                        ->orWhere('issue_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('rcv_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'date',
                    2 => 'ref_no',
                    3 => 'mrf_tr_id',
                    4 => 'product_id',
                    5 => 'product_id',
                    6 => 'issue_qty',
                    7 => 'rcv_qty',
                    8 => 'status',


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
                                
                                    if (\Carbon\Carbon::hasFormat($searchLower, 'd-m-Y')) {
                                        $searchDate = \Carbon\Carbon::createFromFormat('d-m-Y', $searchLower)->format('Y-m-d');
                                    } else {
                                        $searchDate = $searchLower;
                                    }
                                    $q->where('date', 'like', '%' . $searchDate . '%');
                                break;
                            case 2:
                                $q->where('ref_no', 'like', '%' . $searchLower . '%');
                                break;
                            case 3:
                                $q->whereHas('mrf', function ($query) use ($searchLower) {
                                    $query->where('ref_no', 'like', '%' . $searchLower . '%');
                                });
                                break;
                            case 4:
                                $q->whereHas('products', function ($query) use ($searchLower) {
                                    $query->where('part_no', 'like', '%' . $searchLower . '%');
                                });
                                break;
                            case 5:
                                $q->whereHas('products', function ($query) use ($searchLower) {
                                    $query->where('part_name', 'like', '%' . $searchLower . '%');
                                });
                                break;
                            case 6:
                                $q->where('issue_qty', 'like', '%' . $searchLower . '%');
                                break;
                            case 7:
                                $q->where('rcv_qty', 'like', '%' . $searchLower . '%');
                                break;
                            case 8:
                                $q->where('status', 'like', '%' . $searchLower . '%');
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
                $row->sr_no = $start + $index + 1;
                $row->date = Carbon::parse(time: $row->date)->format('d-m-Y');
                if ($row->order_no == null || $row->order_no == '') {
                    $row->tr_mrf_number = $row->tr->ref_no;
                    $check = 'tr';
                }
                elseif ($row->order_no != null || $row->order_no != '') {
                    $row->tr_mrf_number = $row->mrf->ref_no;
                    $check = 'mrf';
                }

                $row->action .= '  <div class="d-flex"><a class="btn btn-success btn-sm mx-2" href="' . route('discrepancy.view', [$row->id,$check]) .'"><i
                                           class="bi bi-eye"></i></a>';
               // $row->status = $status;
               if ($row->status == 'Pending'){
                   $row->action .= ' <a class="btn btn-info btn-sm" href="' . route('discrepancy.edit', [$row->id,$check])  .'"><i
                                               class="bi bi-pencil"></i></a></div>';
               }

                if ($row->status == "Pending") {
                    $row->status = '<span class="badge border border-dark text-dark">Pending</span>';
                } elseif ($row->status == 'Issuer') {
                    $row->status = '  <span class="badge border border-primary text-primary">Added to Issuer</span>';
                }
                elseif ($row->status == 'Reciever') {
                    $row->status = '<span class="badge border border-primary text-primary">Added to Reciever</span>';
                }
                elseif ($row->status == 'Deduct') {
                    $row->status = '<span class="badge border border-success text-success">Deduct from Issuer</span>';
                }
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

            $query = Discrepancy::select('id','ref_no', 'date','mrf_tr_id','order_no','product_id', 'issue_qty','rcv_qty','status')->with(['mrf','tr','products']);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                    ->where(function ($query) use ($searchLower) {
                        if (\Carbon\Carbon::hasFormat($searchLower, 'd-m-Y')) {
                            $searchDate = \Carbon\Carbon::createFromFormat('d-m-Y', $searchLower)->format('Y-m-d');
                        } else {
                            $searchDate = $searchLower;
                        }
                        $query->where('date', 'like', '%' . $searchDate . '%');
                    })
                        ->orWhere('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('tr', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('mrf', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        // ->orWhere('order_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('products', function ($query) use ($searchLower) {
                            $query->where('part_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('products', function ($query) use ($searchLower) {
                            $query->where('part_name', 'like', '%' . $searchLower . '%');
                        })

                        ->orWhere('issue_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('rcv_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $sortableColumns = [
                1 => 'date',
                2 => 'ref_no',
                3 => 'mrf_tr_id',
                4 => 'product_id',
                5 => 'product_id',
                6 => 'issue_qty',
                7 => 'rcv_qty',
                8 => 'status',


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

                if ($row->order_no == null || $row->order_no == '') {
                    $row->tr_mrf_number = $row->tr->ref_no;
                    $check = 'tr';
                }
                elseif ($row->order_no != null || $row->order_no != '') {
                    $row->tr_mrf_number = $row->mrf->ref_no;
                    $check = 'mrf';
                }
                $row->date = Carbon::parse(time: $row->date)->format('d-m-Y');

               $row->action .= '  <div class="d-flex"><a class="btn btn-success btn-sm mx-2" href="' . route('discrepancy.view', [$row->id,$check]) .'"><i
                                           class="bi bi-eye"></i></a>';
               // $row->status = $status;
               if ($row->status == 'Pending'){
                   $row->action .= ' <a class="btn btn-info btn-sm" href="' . route('discrepancy.edit', [$row->id,$check])  .'"><i
                                               class="bi bi-pencil"></i></a></div>';
               }

               if ($row->status == "Pending") {
                $row->status = '<span class="badge border border-dark text-dark">Pending</span>';
                } elseif ($row->status == 'Issuer') {
                    $row->status = '  <span class="badge border border-primary text-primary">Added to Issuer</span>';
                }
                elseif ($row->status == 'Reciever') {
                    $row->status = '<span class="badge border border-primary text-primary">Added to Reciever</span>';
                }
                elseif ($row->status == 'Deduct') {
                    $row->status = '<span class="badge border border-success text-success">Deduct from Issuer</span>';
                }

            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => $uom,
            ]);
        }
    }
    static public function create($discrepancy){
        $setting = InitailNo::where('screen', 'Discrepancy')->first();
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        if ($setting) {
            $Dis_no = Discrepancy::orderBy('id', 'DESC')->first();
            if ($Dis_no) {
                // Extract running_no from $Dis_no->Dis_no_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $Dis_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $Dis_no_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $Dis_no_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $Dis_no = Discrepancy::orderBy('id', 'DESC')->first();
            if ($Dis_no) {
                // Extract running_no from $Dis_no->ref_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $Dis_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $Dis_no_no = 'DCR/' . $running_no . '/' . $year;
            } else {
                $Dis_no_no = 'DCR/1/' . $year;
            }
        }
        $new_discrepancy = new Discrepancy();
        $new_discrepancy->product_id = $discrepancy['product_id'] ?? null;
        $new_discrepancy->ref_no = $Dis_no_no;
        $new_discrepancy->mrf_tr_id = $discrepancy['mrf_tr_id'] ?? null;
        $new_discrepancy->order_no = $discrepancy['order_no'] ?? null;
        $new_discrepancy->issue_qty = $discrepancy['issue_qty'] ?? null;
        $new_discrepancy->rcv_qty = $discrepancy['rcv_qty'] ?? null;
        $new_discrepancy->date = $discrepancy['date'] ?? null;
        $new_discrepancy->save();
        $check = '';
        if ($new_discrepancy->tr) {
            $check = 'tr';
        }elseif ($new_discrepancy->mrf) {
            $check = 'mrf';
        }
        // dd($new_discrepancy->id,$check);

        NotificationController::Notification(
    'Discrepancy',
    'Edit',
    route('discrepancy.view', ['id' => $new_discrepancy->id, 'check' => $check])

);
    }

    public function index(){
        if (!Auth::user()->hasPermissionTo('Discrepancy List') ||
            !Auth::user()->hasPermissionTo('Discrepancy Edit') ||
            !Auth::user()->hasPermissionTo('Discrepancy View'))
            {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        // Fetch discrepancies related to MRF
        $discrepanciesWithMrf = Discrepancy::with('mrf','products')->where('order_no', '!=', '')->get();

        // Fetch discrepancies related to TR
        $discrepanciesWithTr = Discrepancy::with('tr','products')->where('order_no', '=', '')->get();

        // Merge the two collections
        $discrepancies = $discrepanciesWithMrf->merge($discrepanciesWithTr);
        return view('wms.operations.discrepancy.index', compact('discrepancies'));

    }

    public function edit($id,$check){
        if (!Auth::user()->hasPermissionTo('Discrepancy Edit')){
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        if($check == 'mrf'){
            $discrepancy = Discrepancy::with('mrf','products.units','products.type_of_products')->where('order_no', '!=', null)->Where('order_no', '!=', '')->where('id', $id)->first();
            $mrf_id = $discrepancy->mrf->id;
            $mrf_details = MaterialRequisitionDetails::where('material_requisition_id', $mrf_id)->get();
            $mrf_details_ids = $mrf_details->pluck('id');
            $locations = MaterialRequisitionProductDetails::with('area','rack','level')->whereIn('mrf_detail_id',$mrf_details_ids)->get();
        }
        if($check == 'tr'){
            $discrepancy = Discrepancy::with('tr.mrf','products.units','products.type_of_products')->where('order_no', '=', null)->orWhere('order_no', '=', '')->where('id', $id)->first();
            if(!is_null($discrepancy->tr->mrf)){
                $mrf_id = $discrepancy->tr->mrf->id;
                $mrf_details = MaterialRequisitionDetails::where('material_requisition_id', $mrf_id)->get();
                $mrf_details_ids = $mrf_details->pluck('id');
                $locations = MaterialRequisitionProductDetails::with('area','rack','level')->whereIn('mrf_detail_id',$mrf_details_ids)->get();
            }else{
                $locations = Location::with('area','rack','level')->get();
            }
        }
        return view('wms.operations.discrepancy.edit', compact('discrepancy','locations', 'check'));
    }

    public function update(Request $request,$id){
        $discrepancy = Discrepancy::find($id);
        $discrepancy->remarks = $request->remarks;
        $discrepancy->status = $request->action;
        $discrepancy->save();

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();



        foreach ($newArray as $key => $product) {

            $location = Location::where('area_id',$product['area'])
                                ->where('rack_id',$product['rack'])
                                ->where('level_id',$product['level'])
                                ->where('lot_no',$product['lot_no'])
                                ->first();
            if ($location) {
                // if($location == 'Deduct')

                // {
                //     $location->used_qty -= $product['qty'];
                // } else{
                //     $location->used_qty += $product['qty'];
                // }
                $action = $request->input('action');

                if ($action == 'Deduct') {
                    $location->used_qty -= $product['qty']; 
                } else {
                    $location->used_qty += $product['qty']; 
                }

                $location->save();
            } else {
                $new_location = new Location();
                $new_location->area_id = $product['area'];
                $new_location->rack_id = $product['rack'];
                $new_location->level_id = $product['level'];
                $new_location->lot_no = $product['lot_no'];
                $new_location->used_qty = $product['qty'];
                $new_location->product_id = $product['hiddenId'];
                $new_location->save();
            }
        }
        return redirect()->route('discrepancy.index')->with('success', 'Discrepancy Updated Succefully!');
    }

    public function view($id,$check){
        if (!Auth::user()->hasPermissionTo('Discrepancy Edit')){
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        if($check == 'mrf'){
            $discrepancy = Discrepancy::with('mrf','products.units','products.type_of_products')->where('order_no', '!=', '')->find($id);
        }
        if($check == 'tr'){
            $discrepancy = Discrepancy::with('tr','products.units','products.type_of_products')->where('order_no', '=', '')->find($id);
        }

        $locations = AreaLocation::with('area','rack','level')->get();
        return view('wms.operations.discrepancy.view', compact('discrepancy','locations','check'));
    }

}
