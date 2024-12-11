<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\BomCrushing;
use App\Models\BomProcess;
use App\Models\BomPurchasePart;
use App\Models\BomSubPart;
use App\Models\BomVerification;
use App\Models\Customer;
use App\Models\InitailNo;
use App\Models\MachineTonnage;
use App\Models\Process;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\BomReportExport;
use Maatwebsite\Excel\Facades\Excel;


class BomController extends Controller
{
    // BOM CRUD

    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Bom::select([
                'boms.id',
                'boms.ref_no',
                'boms.created_date',
                'boms.status',
                'boms.product_id',
                'boms.description',
                'products.part_no',
                'products.part_name',
                'products.customer_product_code',
                'products.model',
                'products.variance',
                'products.part_weight',
                'products.type_of_product',
                'products.unit',
                'products.customer_name',
                'products.supplier_name',
            ])
            ->join('products', 'boms.product_id', '=', 'products.id') // Join products table
            ->leftJoin('customers', 'products.customer_name', '=', 'customers.id') // Add necessary joins
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->leftJoin('suppliers', 'products.supplier_name', '=', 'suppliers.id')
            ->with([
                'products.type_of_products',
                'products.units',
                'products.customers',
                'products.suppliers'
            ])
            ->orderBy('boms.created_date','DESC');

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('created_date', function($row){
                    return Carbon::parse($row->created_date)->format('d-m-Y');
                })
                ->addColumn('status', function($row){
                    if ($row->status == 'Submitted'){
                        return '<span class="badge border border-light text-light">Submitted</span>';
                    }
                    elseif ($row->status == 'Verified'){
                        return '<span class="badge border border-primary text-primary">Verified</span>';
                    }
                    elseif ($row->status == 'Declined'){
                        return '<span class="badge border border-secondary text-secondary">Declined</span>';
                    }
                    elseif ($row->status == 'Cancelled'){
                        return '<span class="badge border border-Danger text-Danger">Cancelled</span>';
                    }
                    elseif ($row->status == 'Inactive'){
                        return '<span class="badge border border-dark text-dark">Inactive</span>';
                    }
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-success btn-sm mx-2" title="View" href="'. route('bom.view', $row->id) .'"><i class="bi bi-eye"></i></a>';
                    if($row->status != 'Inactive'){
                        if ($row->status != 'Verified' && $row->status != 'Cancelled'){
                            $btn .= '<a class="btn btn-info btn-sm mx-2" title="Edit" href="'.route('bom.edit', $row->id).'"><i
                                        class="bi bi-pencil"></i></a>
                                    <a class="btn btn-success btn-sm mx-2" title="Verify" href="'.route('bom.verification', $row->id).'"><i
                                        class="bi bi-check2-all"></i></a>';
                        }
                        $btn .= '<a class="btn btn-danger btn-sm mx-2" title="In Active" href="'.route('bom.inactive', $row->id).'"><i
                                    class="bi bi-x-circle"></i></a>';
                        if ($row->status != 'Verified'){
                            $btn .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                <form method="POST" action="' . route('bom.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action','status']);
                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('ref_no') && !is_null($request->ref_no)) {
                        $query->where('ref_no', 'like', "%{$request->ref_no}%");
                    }
                    if ($request->has('part_no') && !is_null($request->part_no)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('part_no', 'like', "%{$request->part_no}%");
                        });
                    }
                    if ($request->has('part_name') && !is_null($request->part_name)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('part_name', 'like', "%{$request->part_name}%");
                        });
                    }
                    if ($request->has('customers') && !is_null($request->customers)) {
                        $query->whereHas('products.customers', function($q) use ($request) {
                            $q->where('name', 'like', "%{$request->customers}%");
                        });
                    }
                    if ($request->has('customer_product_code') && !is_null($request->customer_product_code)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('products.customer_product_code', 'like', "%{$request->customer_product_code}%");
                        });
                    }
                    if ($request->has('type_of_products') && !is_null($request->type_of_products)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('type_of_products.type', 'like', "%{$request->type_of_products}%");
                        });
                    }
                    if ($request->has('units') && !is_null($request->units)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('units.name', 'like', "%{$request->units}%");
                        });
                    }
                    if ($request->has('model') && !is_null($request->model)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('products.model', 'like', "%{$request->model}%");
                        });
                    }
                    if ($request->has('variance') && !is_null($request->variance)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('products.variance', 'like', "%{$request->variance}%");
                        });
                    }
                    if ($request->has('part_weight') && !is_null($request->part_weight)) {
                        $query->whereHas('products', function($q) use ($request) {
                            $q->where('products.part_weight', 'like', "%{$request->part_weight}%");
                        });
                    }
                    if ($request->has('created_date') && !is_null($request->created_date)) {
                        $query->where('created_date', 'like', "%{$request->created_date}%");
                    }
                    if ($request->has('status') && !is_null($request->status)) {
                        $query->where('status', 'like', "%{$request->status}%");
                    }
                });
            }

               return $datatable->make(true);
        }



    }
    public function index(){
        if (!Auth::user()->hasPermissionTo('BOM List') ||
        !Auth::user()->hasPermissionTo('BOM Create') ||
        !Auth::user()->hasPermissionTo('BOM Edit') ||
        !Auth::user()->hasPermissionTo('BOM Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $boms = Bom::with([
            'products.type_of_products',
            'products.units',
            'products.customers',
            'products.suppliers',
            'products.locations'
        ])->orderBy('created_at', 'DESC')->get();
        return view('mes.engineering.index',compact ('boms'));
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('BOM Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $products = Product::with('type_of_products','units','customers', 'categories')->get();
        $customers = Customer::all();
        $processes = Process::all();
        $suppliers = Supplier::all();
        $machine_tonnages = MachineTonnage::all();
        return view('mes.engineering.create',compact ('products','customers','processes','suppliers','machine_tonnages'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'ref_no' => ['required'],
            'product_id' => ['required'],
          ]);

        $bom = new Bom();
        $bom->rev_no = $request->rev_no;
        $bom->ref_no = $request->ref_no;
        $bom->product_id = $request->product_id;
        $bom->created_date = $request->created_date;
        $bom->description = $request->description;
        $bom->created_by = Auth::user()->id;
        if($request->file('attachment1')){
            $file = $request->file('attachment1');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('bom-attachments', $filename);
            $bom->attachment1 =  $filename;
        }
        if($request->file('attachment2')){
            $file = $request->file('attachment2');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('bom-attachments', $filename);
            $bom->attachment2 =  $filename;
        }
        if($request->file('attachment3')){
            $file = $request->file('attachment3');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('bom-attachments', $filename);
            $bom->attachment3 =  $filename;
        }
        $bom->save();
        $bom_id = $bom->id;
        if($request->material_pruchase_part){
            foreach ($request->material_pruchase_part as $pruchase_part) {
                $material_pruchase_part_bom = new BomPurchasePart();
                $material_pruchase_part_bom->bom_id = $bom_id;
                $material_pruchase_part_bom->product_id = $pruchase_part['product_id'] ?? '';
                $material_pruchase_part_bom->qty = $pruchase_part['qty'] ?? 0;
                $material_pruchase_part_bom->remarks = $pruchase_part['remarks'] ?? '';
                $material_pruchase_part_bom->save();
            }
        }

        if($request->crushing_material){
            foreach ($request->crushing_material as $crushing) {
                $crushing_material_bom = new BomCrushing();
                $crushing_material_bom->bom_id = $bom_id;
                $crushing_material_bom->product_id = $crushing['product_id'] ?? '';
                $crushing_material_bom->remarks = $crushing['remarks'] ?? '';
                $crushing_material_bom->save();
            }
        }
        if($request->sub_part){
            foreach ($request->sub_part as $sub_part) {
                $sub_part_bom = new BomSubPart();
                $sub_part_bom->bom_id = $bom_id;
                $sub_part_bom->product_id = $sub_part['product_id'] ?? '';
                $sub_part_bom->remarks = $sub_part['remarks'] ?? '';
                $sub_part_bom->qty = $sub_part['qty'] ?? 0;
                $sub_part_bom->save();
            }
        }

        if($request->process){
            foreach ($request->process as $process) {
                $process_bom = new BomProcess();
                $process_bom->bom_id = $bom_id;
                $process_bom->process_id = $process['process_id'] ?? '';
                $process_bom->process_no = $process['process_no'] ?? '';
                $process_bom->raw_part_ids = isset($process['raw_part_ids']) ? json_encode($process['raw_part_ids']) : '';
                $process_bom->sub_part_ids = isset($process['sub_part_ids']) ? json_encode($process['sub_part_ids']) : '';
                $process_bom->supplier_id = $process['supplier_id'] ?? '';
                $process_bom->machine_tonnage_id = $process['machine_tonnage_id'] ?? '';
                $process_bom->cavity = $process['cavity'] ?? '';
                $process_bom->ct = $process['ct'] ?? '';
                $process_bom->save();
            }
        }
        return redirect()->route('bom')->with('custom_success', 'BOM Created Successfully.');

    }

    public function destroy($id){

        if (!Auth::user()->hasPermissionTo('BOM Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
       // Delete all purchase parts associated with the BOM
        $purchaseParts = BomPurchasePart::where('bom_id', $id)->get();
        if ($purchaseParts) {
            $purchaseParts->each(function ($part) {
                $part->delete();
            });
        }

        // Delete all crushing records associated with the BOM
        $crushing = BomCrushing::where('bom_id', $id)->get();
        if ($crushing) {
            $crushing->each(function ($item) {
                $item->delete();
            });
        }

        // Delete all subparts associated with the BOM
        $subparts = BomSubPart::where('bom_id', $id)->get();
        if ($subparts) {
            $subparts->each(function ($subpart) {
                $subpart->delete();
            });
        }

        // Delete all processes associated with the BOM
        $processes = BomProcess::where('bom_id', $id)->get();
        if ($processes) {
            $processes->each(function ($process) {
                $process->delete();
            });
        }

        // Finally, delete the BOM itself
        $bom = Bom::find($id);
        if ($bom) {
            $bom->delete();
        }
        return redirect()->route('bom')->with('custom_success', 'BOM Deleted Successfully.');
    }

    public function view($id){
        if (!Auth::user()->hasPermissionTo('BOM View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $bom = Bom::with('products.customers')->find($id);
        $created_by = User::find($bom->created_by);
        $purchase_parts = BomPurchasePart::with('product.units','product.type_of_products','product.categories')->where('bom_id','=',$id)->get();
        $crushings = BomCrushing::with('product.units','product.type_of_products', 'product.categories')->where('bom_id','=',$id)->get();
        $sub_parts = BomSubPart::with('product.units','product.type_of_products', 'product.categories')->where('bom_id','=',$id)->get();
        $processes = BomProcess::with('process')->where('bom_id','=',$id)->get();
        $products = Product::all();
        $suppliers = Supplier::all();
        $machine_tonnages = MachineTonnage::all();
        $bom_verifications = BomVerification::where('bom_id','=',$id)->get();
        return view('mes.engineering.view',compact('bom','purchase_parts','crushings','sub_parts','processes','created_by','products','suppliers','machine_tonnages','bom_verifications'));
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('BOM Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $bom = Bom::with('products.customers')->find($id);
        $created_by = User::find($bom->created_by);
        $purchase_parts = BomPurchasePart::with('product.units','product.type_of_products','product.categories')->where('bom_id','=',$id)->get();
        $crushings = BomCrushing::with('product.units','product.type_of_products', 'product.categories')->where('bom_id','=',$id)->get();
        $sub_parts = BomSubPart::with('product.units','product.type_of_products', 'product.categories')->where('bom_id','=',$id)->get();
        $process = BomProcess::with('process')->where('bom_id','=',$id)->get();
        $processes = Process::all();
        $products = Product::all();
        $suppliers = Supplier::all();
        $machine_tonnages = MachineTonnage::all();
        return view('mes.engineering.edit',compact('bom','purchase_parts','crushings','sub_parts','processes','created_by','products','suppliers','machine_tonnages','process'));
    }

    public function update(Request $request,$id){
        $bom = Bom::find($id);
        $bom->status = "Submitted";
        $bom->rev_no = $request->rev_no;
        $bom->ref_no = $request->ref_no;
        $bom->product_id = $request->product_id;
        $bom->created_date = $request->created_date;
        $bom->description = $request->description;
        $bom->created_by =  $request->created_by;
        if($request->file('attachment1')){
            $file = $request->file('attachment1');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('bom-attachments', $filename);
            $bom->attachment1 =  $filename;
        }
        if($request->file('attachment2')){
            $file = $request->file('attachment2');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('bom-attachments', $filename);
            $bom->attachment2 =  $filename;
        }
        if($request->file('attachment3')){
            $file = $request->file('attachment3');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('bom-attachments', $filename);
            $bom->attachment3 =  $filename;
        }
        $bom->save();

        BomPurchasePart::where('bom_id', $id)->delete();
        if($request->material_pruchase_part){
            foreach ($request->material_pruchase_part as $pruchase_part) {
                $material_pruchase_part_bom = new BomPurchasePart();
                $material_pruchase_part_bom->bom_id = $id;
                $material_pruchase_part_bom->product_id = $pruchase_part['product_id'] ?? '';
                $material_pruchase_part_bom->qty = $pruchase_part['qty'] ?? 0;
                $material_pruchase_part_bom->remarks = $pruchase_part['remarks'] ?? '';
                $material_pruchase_part_bom->save();
            }
        }

        BomCrushing::where('bom_id', $id)->delete();
        if($request->crushing_material){
            foreach ($request->crushing_material as $crushing) {
                $crushing_material_bom = new BomCrushing();
                $crushing_material_bom->bom_id = $id;
                $crushing_material_bom->product_id = $crushing['product_id'] ?? '';
                $crushing_material_bom->remarks = $crushing['remarks'] ?? '';
                $crushing_material_bom->save();
            }
        }

        BomSubPart::where('bom_id', $id)->delete();
        if($request->sub_part){
            foreach ($request->sub_part as $sub_part) {
                $sub_part_bom = new BomSubPart();
                $sub_part_bom->bom_id = $id;
                $sub_part_bom->product_id = $sub_part['product_id'] ?? '';
                $sub_part_bom->remarks = $sub_part['remarks'] ?? '';
                $sub_part_bom->qty = $sub_part['qty'] ?? 0;
                $sub_part_bom->save();
            }
        }

        BomProcess::where('bom_id', $id)->delete();
        if($request->process){
            foreach ($request->process as $process) {
                $process_bom = new BomProcess();
                $process_bom->bom_id = $id;
                $process_bom->process_id = $process['process_id'] ?? '';
                $process_bom->process_no = $process['process_no'] ?? '';
                $process_bom->raw_part_ids = isset($process['raw_part_ids']) ? json_encode($process['raw_part_ids']) : '';
                $process_bom->sub_part_ids = isset($process['sub_part_ids']) ? json_encode($process['sub_part_ids']) : '';
                $process_bom->supplier_id = $process['supplier_id'] ?? '';
                $process_bom->machine_tonnage_id = $process['machine_tonnage_id'] ?? '';
                $process_bom->cavity = $process['cavity'] ?? '';
                $process_bom->ct = $process['ct'] ?? '';
                $process_bom->save();
            }
        }
        $bom_verification = new BomVerification();
        $bom_verification->bom_id = $id;
        $bom_verification->status = "Submitted";
        $bom_verification->date = Carbon::now();
        $bom_verification->approved_by = Auth::user()->id;
        $bom_verification->department_id = Auth::user()->department_id;
        $bom_verification->save();
        return redirect()->route('bom')->with('custom_success', 'BOM Updated Successfully.');

    }

    //BOM VERIFICATION
    public function verification($id){
        if (!Auth::user()->hasPermissionTo('BOM Verification')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $bom = Bom::with('products.customers')->find($id);
        $created_by = User::find($bom->created_by);
        $purchase_parts = BomPurchasePart::with('product.units','product.type_of_products', 'product.categories')->where('bom_id','=',$id)->get();
        $crushings = BomCrushing::with('product.units','product.type_of_products', 'product.categories')->where('bom_id','=',$id)->get();
        $sub_parts = BomSubPart::with('product.units','product.type_of_products', 'product.categories')->where('bom_id','=',$id)->get();
        $processes = BomProcess::with('process')->where('bom_id','=',$id)->get();
        $products = Product::all();
        $suppliers = Supplier::all();
        $machine_tonnages = MachineTonnage::all();
        return view('mes.engineering.verification',compact('bom','purchase_parts','crushings','sub_parts','processes','created_by','products','suppliers','machine_tonnages'));
    }

    public function verify(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('BOM Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $bom = Bom::find($id);
        $bom->status = $request->status;
        $bom->save();
        $bom_verification = new BomVerification();
        $bom_verification->bom_id = $id;
        $bom_verification->status = $request->status;
        $bom_verification->date = Carbon::now();
        $bom_verification->approved_by = $request->approved_by;
        $bom_verification->department_id = $request->department_id;
        $bom_verification->save();
        return redirect()->route('bom')->with('custom_success', 'BOM Status Updated Successfully.');
    }

    public function decline(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('BOM Decline')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $bom = Bom::find($id);
        $bom->status = $request->status;
        $bom->save();
        $bom_verification = new BomVerification();
        $bom_verification->bom_id = $id;
        $bom_verification = new BomVerification();
        $bom_verification->bom_id = $id;
        $bom_verification->status = $request->status;
        $bom_verification->date = Carbon::now();
        $bom_verification->approved_by = $request->approved_by;
        $bom_verification->department_id = $request->department_id;
        $bom_verification->save();
        return redirect()->route('bom')->with('custom_success', 'BOM Status Updated Successfully.');
    }

    public function Cancel(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('BOM Cancel')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $bom = Bom::find($id);
        $bom->status = $request->status;
        $bom->save();
        $bom_verification = new BomVerification();
        $bom_verification->bom_id = $id;
        $bom_verification = new BomVerification();
        $bom_verification->bom_id = $id;
        $bom_verification->status = $request->status;
        $bom_verification->date = Carbon::now();
        $bom_verification->approved_by = $request->approved_by;
        $bom_verification->department_id = $request->department_id;
        $bom_verification->save();
        return redirect()->route('bom')->with('custom_success', 'BOM Status Updated Successfully.');
    }

    function modifyString($input) {
        if (strpos($input, '-') !== false) {
            $lastDashPos = strrpos($input, '-');

            $lastPart = substr($input, $lastDashPos + 1);

            if (is_numeric($lastPart)) {
                $newNumber = (int)$lastPart + 1;
                $result = substr($input, 0, $lastDashPos + 1) . $newNumber;
            } else {
                $result = $input . '-1';
            }
        } else {
            $result = $input . '-1';
        }

        return $result;
    }

    public function inactive($id){
        if (!Auth::user()->hasPermissionTo('BOM Inactive')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $bom = Bom::find($id);
        $bom->status = "Inactive";
        $bom->save();
        $new_bom = new Bom();
        $rev_no = $bom->rev_no;
        $new_rev_no = $rev_no + 1;
        $new_bom->rev_no = $new_rev_no;
        $new_bom->ref_no = $this->modifyString($bom->ref_no);
        $new_bom->product_id = $bom->product_id;
        $new_bom->created_date = $bom->created_date;
        $new_bom->description = $bom->description;
        $new_bom->created_by = Auth::user()->id;
        if($bom->attachment1){
            $new_bom->attachment1 =  $bom->attachment1;
        }
        if($bom->attachment2){
            $new_bom->attachment2 =  $bom->attachment2;
        }
        if($bom->attachment3){
            $new_bom->attachment3 =  $bom->attachment3;
        }
        $new_bom->save();
        $new_bom_id = $new_bom->id;

        $purchase_parts = BomPurchasePart::where('bom_id','=',$id)->get();
        $crushings = BomCrushing::where('bom_id','=',$id)->get();
        $sub_parts = BomSubPart::where('bom_id','=',$id)->get();
        $processes = BomProcess::where('bom_id','=',$id)->get();

        if($purchase_parts){
            foreach ($purchase_parts as $pruchase_part) {
                $material_pruchase_part_bom = new BomPurchasePart();
                $material_pruchase_part_bom->bom_id = $new_bom_id;
                $material_pruchase_part_bom->product_id = $pruchase_part['product_id'] ?? '';
                $material_pruchase_part_bom->qty = $pruchase_part['qty'] ?? 0;
                $material_pruchase_part_bom->remarks = $pruchase_part['remarks'] ?? '';
                $material_pruchase_part_bom->save();
            }
        }

        if($crushings){
            foreach ($crushings as $crushing) {
                $crushing_material_bom = new BomCrushing();
                $crushing_material_bom->bom_id = $new_bom_id;
                $crushing_material_bom->product_id = $crushing['product_id'] ?? '';
                $crushing_material_bom->remarks = $crushing['remarks'] ?? '';
                $crushing_material_bom->save();
            }
        }
        if($sub_parts){
            foreach ($sub_parts as $sub_part) {
                $sub_part_bom = new BomSubPart();
                $sub_part_bom->bom_id = $new_bom_id;
                $sub_part_bom->product_id = $sub_part['product_id'] ?? '';
                $sub_part_bom->remarks = $sub_part['remarks'] ?? '';
                $sub_part_bom->qty = $sub_part['qty'] ?? 0;
                $sub_part_bom->save();
            }
        }

        if($processes){
            foreach ($processes as $process) {
                $process_bom = new BomProcess();
                $process_bom->bom_id = $new_bom_id;
                $process_bom->process_id = $process['process_id'] ?? '';
                $process_bom->process_no = $process['process_no'] ?? '';
                $process_bom->raw_part_ids = $process['raw_part_ids'] ?? '';
                $process_bom->sub_part_ids = $process['sub_part_ids'] ?? '';
                $process_bom->supplier_id = $process['supplier_id'] ?? '';
                $process_bom->machine_tonnage_id = $process['machine_tonnage_id'] ?? '';
                $process_bom->cavity = $process['cavity'] ?? '';
                $process_bom->ct = $process['ct'] ?? '';
                $process_bom->save();
            }
        }
        return redirect()->route('bom')->with('custom_success', 'BOM Revised Successfully.');

    }

    //BOM REPORT
    public function bom_report(){
        if (!Auth::user()->hasPermissionTo('BOM Report')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $products = Product::all();
        return view('mes.engineering.report',compact ('products'));
    }
    public function bom_report_generate(Request $request){

        $validated = $request->validate([
            'product_id' => 'required'
          ]);
        $bom_trees = [];
        foreach($request->product_id as $product_id){
            $bom_trees[] = BomController::loadBomTree($product_id);
        }
        $productId = $request->product_id;
        $products = Product::all();
        $units = Unit::all();

        $selected_product_ids = $request->product_id;
        return view('mes.engineering.report',compact ('products','bom_trees','units','selected_product_ids'));
    }

    static public function loadBomTree($productId, &$visitedProducts = [])
    {
        // Avoid infinite loops by tracking visited products
        if (in_array($productId, $visitedProducts)) {
            return null;
        }

        $bom = Bom::where('product_id', $productId)->where('status','Verified')
                ->with(['products', 'products.type_of_products', 'products.units','purchaseParts.product', 'purchaseParts.product.type_of_products', 'purchaseParts.product.units', 'purchaseParts.product.categories','crushings.product', 'subParts.product', 'subParts.product.type_of_products', 'subParts.product.units', 'subParts.product.categories'])
                ->first();



        $subParts = [];

        // Check if the current product has subparts
        if ($bom && $bom->subParts->isNotEmpty()) {
            foreach ($bom->subParts as $subPart) {
                $subPartTree = BomController::loadBomTree($subPart->product_id, $visitedProducts);
                $hasBom = $subPartTree !== null; // Check if subpart has a BOM
                $subParts[] = [
                    'subPart' => $subPart,
                    'hasBom' => $hasBom,
                    'bomTree' => $subPartTree,
                ];
            }
        }

        if ($bom) {
            $visitedProducts[] = $productId;  // Mark this product as visited
            $bomTree = [
                'bom' => $bom,
                'purchaseParts' => $bom->purchaseParts,
                'crushings' => $bom->crushings,
                'subParts' => $subParts, // Include subparts with BOM status
            ];

            return $bomTree;
        }

        return null;
    }


    public static function boomReportTree($productId, $boms)
    {
        $booms = Bom::where('product_id', $productId)
            ->where('status', 'Verified')
            ->with(
                'products.type_of_products',
                'subParts.product.units',
                'purchaseParts.product.units'
            )
            ->first();

        $counter = 1;

        if ($booms) {
            $boms[] = ['', '', '', '', '', ''];
            $boms[] = [
                'Part No',
                $booms->products->part_no ?? '-',
                'Type of product',
                $booms->products->type_of_products->type ?? '-',
                'Rev No',
                $booms->rev_no ?? '-',
            ];
            $boms[] = [
                'Part Name',
                $booms->products->part_name ?? '-',
                'Model',
                $booms->products->model ?? '-',
                'Variance',
                $booms->variance ?? '-',
            ];
            $boms[] = ['', '', '', '', '', ''];
            $boms[] = ['#Sr.', 'Part No', 'Part Name', 'Type', 'Unit', 'Qty'];

            if ($booms->purchaseParts && count($booms->purchaseParts) > 0) {
                foreach ($booms->purchaseParts as $bom) {
                    $boms[] = [
                        $counter,
                        $bom->product->part_no ?? '-',
                        $bom->product->part_name ?? '-',
                        'Raw Material',
                        $bom->product->units->name ?? '-',
                        $bom->qty ?? 0,
                    ];
                    $counter++;
                }
            }

            if ($booms->subParts && count($booms->subParts) > 0) {
                foreach ($booms->subParts as $bom) {
                    $subPartBom = Bom::where('product_id', $bom->product_id)
                        ->where('status', 'Verified')
                        ->with('purchaseParts', 'subParts')
                        ->first();

                    if (
                        $subPartBom && count($subPartBom->subParts) > 0)
                     {
                        $boms[] = [
                            $counter,
                            $bom->product->part_no ?? '-',
                            $bom->product->part_name ?? '-',
                            'Circuit/ Kanban',
                            $bom->product->units->name ?? '-',
                            $bom->qty ?? 0,
                        ];
                        $counter++;

                        $boms = BomController::boomReportTree(
                            $bom->product_id,
                            $boms
                        );
                    }else{
                        $boms[] = [
                            $counter,
                            $bom->product->part_no ?? '-',
                            $bom->product->part_name ?? '-',
                            'Circuit/ Kanban',
                            $bom->product->units->name ?? '-',
                            $bom->qty ?? 0,
                        ];
                        $counter++;
                    }
                }
            }
        }

        return $boms;
    }

    public function bomReportExport(Request $request)
    {
        $boms = [];
    
        $ids = json_decode($request->id);
        if (empty($ids)) {
            return back()->with('custom_errors', 'You didn’t select any product.');
        }
    
        $boms[] = ['BOM REPORT', '', '', '', '', ''];

        $booms = Bom::whereIn('product_id', $ids)
            ->where('status', 'Verified')
            ->with(
                'products.type_of_products',
                'subParts.product.units',
                'purchaseParts.product.units'
            )
            ->first();
    
        $boms[] = ['', '', '', '', '', ''];
        $boms[] = [
            'Part No',
            $booms->products->part_no ?? '-',
            'Type of product',
            $booms->products->type_of_products->type ?? '-',
            'Rev No',
            $booms->rev_no ?? '-',
        ];
        $boms[] = [
            'Part Name',
            $booms->products->part_name ?? '-',
            'Model',
            $booms->products->model ?? '-',
            'Variance',
            $booms->variance ?? '-',
        ];
        $boms[] = ['', '', '', '', '', ''];

        $counter = 1;
        if ($booms && $booms->purchaseParts) {
            if (count($booms->purchaseParts) > 0) {
                $boms[] = ['', '', '', '', '', '']; 
                $boms[] = ['PURCHASE PARTS', '', '', '', '', '']; 
                $boms[] = [
                    '#Sr.',
                    'Part No',
                    'Part Name',
                    'Type',
                    'Unit',
                    'Qty',
                ];
                foreach ($booms->purchaseParts as $bom) {
                    $boms[] = [
                        $counter,
                        $bom->product->part_no ?? '-',
                        $bom->product->part_name ?? '-',
                        'Raw Material',
                        $bom->product->units->name ?? '-',
                        $bom->qty ?? 0,
                    ];
                    $counter++;
                    $boms = $this->boomReportTree($bom->product_id, $boms);
                }
            }
        }

        if ($booms && $booms->subParts) {
            if (count($booms->subParts) > 0) {
                $boms[] = ['', '', '', '', '', '']; 
                $boms[] = ['SUBPARTS', '', '', '', '', ''];
                $boms[] = [
                    '#Sr.',
                    'Part No',
                    'Part Name',
                    'Type',
                    'Unit',
                    'Qty',
                ];
                foreach ($booms->subParts as $bom) {
                    $boms[] = [
                        $counter,
                        $bom->product->part_no ?? '-',
                        $bom->product->part_name ?? '-',
                        'Subparts',
                        $bom->product->units->name ?? '-',
                        $bom->qty ?? 0,
                    ];
                    $counter++;
                    $boms = $this->boomReportTree($bom->product_id, $boms);
                }
            }
        }

        return Excel::download(
            new BomReportExport($boms),
            'BomReport.xlsx'
        );
    }

}
