<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    //

    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Customer::select(
                'customers.id',
                'customers.name',
                'customers.address',
                'customers.phone',
                );


// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('customer.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('customer.view', $row->id) .
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
                                                <form method="POST" action="' . route('customer.destroy', $row->id) . '">
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
                ->rawColumns(['action']);
                // dd($request->search['value']);




                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('name') && !is_null($request->name)) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }
                    if ($request->has('address') && !is_null($request->address)) {
                        $query->where('address', 'like', "%{$request->address}%");
                    }

                    if ($request->has('phone') && !is_null($request->phone)) {
                        $query->where('phone', 'like', "%{$request->phone}%");
                    }


                });
            }

               return $datatable->make(true);
        }



    }
    public function index()
    {
      if (
        Auth::user()->hasPermissionTo('Customer List') ||
        Auth::user()->hasPermissionTo('Customer Create') ||
        Auth::user()->hasPermissionTo('Customer Edit') ||
        Auth::user()->hasPermissionTo('Customer Delete')
    ) {
      $customers = Customer::all();
      return view('database.customer.index', compact('customers'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Customer Create')) {
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
      return view('database.customer.create');
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Customer Create')) {
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('customers', 'name')->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique('customers', 'code')->whereNull('deleted_at')
            ],
            'address' => [
                'required'
            ],
            'phone' => [
                'required'
            ],
            'pic_name' => [
                'required'
            ],
            'pic_department' => [
                'required'
            ],
            'pic_phone_work' => [
                'required'
            ],
            'pic_email' => [
                'required',
                Rule::unique('customers', 'pic_email')->whereNull('deleted_at')
            ],
            'payment_term' => [
                'required',
            ],
          ]);

      $customer= new Customer();
      $customer->name = $request->name;
      $customer->code= $request->code;
      $customer->address= $request->address;
      $customer->phone = $request->phone;
      $customer->pic_name = $request->pic_name;
      $customer->pic_department = $request->pic_department;
      $customer->pic_phone_work = $request->pic_phone_work;
      $customer->pic_phone_mobile = $request->pic_phone_mobile;
      $customer->pic_fax = $request->pic_fax;
      $customer->pic_email = $request->pic_email;
      $customer->payment_term = $request->payment_term;
      $customer->save();
      return redirect()->route('customer.index')->with('custom_success', 'Customer Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Customer Edit')) {
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
      $customer = Customer::find($id);
      return view('database.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Customer Edit')) {
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
    $validated = $request->validate([
        'name' => [
            'required',
            Rule::unique('customers', 'name')->whereNull('deleted_at')->ignore($id)
        ],
        'code' => [
            'required',
            Rule::unique('customers', 'code')->whereNull('deleted_at')->ignore($id)
        ],
        'address' => [
            'required'
        ],
        'phone' => [
            'required'
        ],
        'pic_name' => [
            'required'
        ],
        'pic_department' => [
            'required'
        ],
        'pic_phone_work' => [
            'required'
        ],
        'pic_email' => [
            'required',
            Rule::unique('customers', 'pic_email')->whereNull('deleted_at')->ignore($id)
        ],
        'payment_term' => [
            'required',
        ],
      ]);

      $customer = Customer::find($id);
      $customer->name = $request->name;
      $customer->code= $request->code;
      $customer->address= $request->address;
      $customer->phone = $request->phone;
      $customer->pic_name = $request->pic_name;
      $customer->pic_department = $request->pic_department;
      $customer->pic_phone_work = $request->pic_phone_work;
      $customer->pic_phone_mobile = $request->pic_phone_mobile;
      $customer->pic_fax = $request->pic_fax;
      $customer->pic_email = $request->pic_email;
      $customer->payment_term = $request->payment_term;
      $customer->save();
      return redirect()->route('customer.index')->with('custom_success', 'Customer Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Customer View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $customer = Customer::find($id);
      return view('database.customer.view', compact('customer'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Customer Delete')) {
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

      $customer = Customer::find($id);
      $customer->delete();
      return redirect()->route('customer.index')->with('custom_success', 'Customer Deleted Successfully.');
    }
}
