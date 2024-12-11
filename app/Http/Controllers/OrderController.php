<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\SstPercentage;
use App\Models\PurchasePlanning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Order::select(
                'orders.id',
                'orders.order_no',
                'orders.po_no',
                'orders.date',
                'orders.customer_id',
                'orders.status',

            )
            ->with(['customers']);

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                        $btn = '<div class="d-flex">
                        <a class="btn btn-success btn-sm mx-2" href="' .
                        route('order.view', $row->id) .
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
                                                <form method="POST" action="' . route('order.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

                        if ($row->status == 'in-progress'){
                            $btn .= '<a class="btn btn-info btn-sm mx-2" href="' .
                        route('order.edit', $row->id) .
                        '"><i class="bi bi-pencil"></i></a></div>';
                        }
                        return $btn;


                    })

                ->rawColumns(['action']);

                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('order_no') && !is_null($request->order_no)) {
                        $query->where('order_no', 'like', "%{$request->order_no}%");
                    }
                    if ($request->has('po_no') && !is_null($request->po_no)) {
                        $query->where('po_no', 'like', "%{$request->po_no}%");
                    }
                    if ($request->has('date') && !is_null($request->date)) {
                        $query->where('date', 'like', "%{$request->date}%");
                    }
                    if ($request->has('customer') && !is_null($request->customer)) {
                        $query->whereHas('customers', function($q) use ($request) {
                            $q->where('customers.name', 'like', "%{$request->customer}%");
                        });
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
        if (
            Auth::user()->hasPermissionTo('Order List') ||
            Auth::user()->hasPermissionTo('Order Create') ||
            Auth::user()->hasPermissionTo('Order Edit') ||
            Auth::user()->hasPermissionTo('Order View') ||
            Auth::user()->hasPermissionTo('Order Delete')
        ){
            return view('erp.bd.order.index');
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create(){
        if (!Auth::user()->hasPermissionTo('Order Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $sst_percentage = SstPercentage::find(1);
        $customers = Customer::all();
        $products = Product::all();
        return view('erp.bd.order.create',compact('customers','products','sst_percentage'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Order Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'customer_id' => [
                'required'
            ],
            'order_no' => [
                'required'
            ],
            'po_no' => [
                'required',
                Rule::unique('orders', 'po_no')->whereNull('deleted_at')
            ],
            'status' => [
                'required'
            ],
            'products' => [
                'required'
            ],
            'order_month' => [
                'required',
                Rule::unique('orders')->where(function ($query) use ($request) {
                    return $query->where('customer_id', $request->customer_id);
                }),
            ],
        ],[
            'order_month' => 'This order month has already been taken by selected Customer.'
        ]);

        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->order_no = $request->order_no;
        $order->po_no = $request->po_no;
        $order->order_month = $request->order_month;
        $order->status = $request->status;
        $order->created_by = Auth::user()->id;
        $order->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y');
        if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('order-attachments', $filename);
            $order->attachment =  $filename;
        }
        $order->save();

        foreach($request->products as $product){
            $order_detail = new OrderDetail();
            $order_detail->order_id = $order->id;
            $order_detail->product_id = $product['product_id'];
            $order_detail->price = $product['price'] ?? 0;
            $order_detail->sst_percentage = $product['sst_percentage'] ?? 0;
            $order_detail->sst_value = $product['sst_value'] ?? 0;
            $order_detail->firm_qty = $product['firm_qty'] ?? 0;
            $order_detail->n1_qty = $product['n1_qty'] ?? 0;
            $order_detail->n2_qty = $product['n2_qty'] ?? 0;
            $order_detail->n3_qty = $product['n3_qty'] ?? 0;
            $order_detail->save();
        }
        NotificationController::Notification('Order', 'Create', '' . route('order.view', $order->id) . '');
        return redirect()->route('order.index')->with('custom_success', 'Order Created Successfully.');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Order Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_planning = PurchasePlanning::where('order_id', $id)->first();
        $is_purchase_planning = 0;
        if($purchase_planning){
            $is_purchase_planning = 1;
        }
        $customers = Customer::all();
        $products = Product::all();
        $order = Order::find($id);
        $order_details = OrderDetail::where('order_id', $id)->get();
        return view('erp.bd.order.edit',compact('customers','products','order','order_details','is_purchase_planning'));
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Order Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_planning = PurchasePlanning::where('order_id', $id)->first();
        if(!$purchase_planning){
            $validated = $request->validate([
                'customer_id' => [
                    'required'
                ],
                'order_no' => [
                    'required'
                ],
                'po_no' => [
                    'required',
                    Rule::unique('orders', 'po_no')->whereNull('deleted_at')->ignore($id)
                ],
                'status' => [
                    'required'
                ],
                'products' => [
                    'required'
                ],
                'order_month' => [
                'required',
                Rule::unique('orders')->where(function ($query) use ($request) {
                    return $query->where('customer_id', $request->customer_id);
                })->ignore($id),
            ],
        ],[
            'order_month' => 'This order month has already been taken by selected Customer.'
        ]);
        }else{
            $validated = $request->validate([
                'status' => [
                    'required'
                ]
            ]);
        }

        $order = Order::find($id);
        if(!$purchase_planning){
            $order->customer_id = $request->customer_id;
            $order->order_no = $request->order_no;
            $order->po_no = $request->po_no;
            $order->order_month = $request->order_month;
            $order->created_by = Auth::user()->id;
            $order->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y');
            if($request->file('attachment')){
                $file = $request->file('attachment');
                $filename = date('YmdHis').$file->getClientOriginalName();
                $file->move('order-attachments', $filename);
                $order->attachment =  $filename;
            }
        }
        $order->status = $request->status;
        $order->save();

        if(!$purchase_planning){
            OrderDetail::where('order_id', $id)->delete();

            foreach($request->products as $product){
                $order_detail = new OrderDetail();
                $order_detail->order_id = $order->id;
                $order_detail->product_id = $product['product_id'];
                $order_detail->price = $product['price'] ?? 0;
                $order_detail->sst_percentage = $product['sst_percentage'] ?? 0;
                $order_detail->sst_value = $product['sst_value'] ?? 0;
                $order_detail->firm_qty = $product['firm_qty'] ?? 0;
                $order_detail->n1_qty = $product['n1_qty'] ?? 0;
                $order_detail->n2_qty = $product['n2_qty'] ?? 0;
                $order_detail->n3_qty = $product['n3_qty'] ?? 0;
                $order_detail->save();
            }
        }
        return redirect()->route('order.index')->with('custom_success', 'Order Updated Successfully.');
    }



    public function view($id){
        if (!Auth::user()->hasPermissionTo('Order View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $customers = Customer::all();
        $products = Product::all();
        $order = Order::find($id);
        $order_details = OrderDetail::where('order_id', $id)->get();
        return view('erp.bd.order.view',compact('customers','products','order','order_details'));
    }

    public function destroy(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Order Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_planning = PurchasePlanning::where('order_id', $id)->first();
        if($purchase_planning){
            return back()->with('custom_errors', 'Can`t Delete, This Order Used In Purchase Planning!');
        }
        $order = Order::find($id);
        OrderDetail::where('order_id', $id)->delete();
        $order->delete();
        return redirect()->route('order.index')->with('custom_success', 'Order Deleted Successfully.');
    }

}
