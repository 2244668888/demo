<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{

    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Supplier::select(
                'suppliers.id',
                'suppliers.name',
                'suppliers.address',
                'suppliers.contact',
                'suppliers.group',);


// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('supplier.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('supplier.view', $row->id) .
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
                                                <form method="POST" action="' . route('supplier.destroy', $row->id) . '">
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

                    if ($request->has('contact') && !is_null($request->contact)) {
                        $query->where('contact', 'like', "%{$request->contact}%");
                    }
                    if ($request->has('group') && !is_null($request->group)) {
                        $query->where('group', 'like', "%{$request->group}%");
                    }
                  

                });
            }

               return $datatable->make(true);
        }




    }
    public function index()
    {
      if (
        Auth::user()->hasPermissionTo('Supplier List') ||
        Auth::user()->hasPermissionTo('Supplier Create') ||
        Auth::user()->hasPermissionTo('Supplier Edit') ||
        Auth::user()->hasPermissionTo('Supplier View') ||
        Auth::user()->hasPermissionTo('Supplier Delete')
    ){
      $suppliers = Supplier::all();
      return view('database.supplier.index', compact('suppliers'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Supplier Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      return view('database.supplier.create');
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Supplier Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('suppliers', 'name')->whereNull('deleted_at')
            ],
            'address' => [
                'required'
            ],
            'contact' => [
                'required'
            ],
            'group' => [
                'required'
            ],
            'contact_person_name' => [
                'required'
            ],
            'contact_person_department' => [
                'required'
            ],
            'contact_person_mobile' => [
                'required'
            ],
            'contact_person_email' => [
                'required'
            ],
          ]);

      $supplier= new Supplier();
      $supplier->name = $request->name;
      $supplier->address= $request->address;
      $supplier->contact = $request->contact;
      $supplier->group = $request->group;
      $supplier->contact_person_name = $request->contact_person_name;
      $supplier->contact_person_telephone = $request->contact_person_telephone;
      $supplier->contact_person_department = $request->contact_person_department;
      $supplier->contact_person_mobile = $request->contact_person_mobile;
      $supplier->contact_person_fax = $request->contact_person_fax;
      $supplier->contact_person_email = $request->contact_person_email;
      $supplier->save();
      return redirect()->route('supplier.index')->with('custom_success', 'Supplier Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Supplier Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $supplier = Supplier::find($id);
      return view('database.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Supplier Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    $validated = $request->validate([
        'name' => [
            'required',
            Rule::unique('suppliers', 'name')->whereNull('deleted_at')->ignore($id)
        ],
        'address' => [
            'required'
        ],
        'contact' => [
            'required'
        ],
        'group' => [
            'required'
        ],
        'contact_person_name' => [
            'required'
        ],
        'contact_person_department' => [
            'required'
        ],
        'contact_person_mobile' => [
            'required'
        ],
        'contact_person_email' => [
            'required'
        ],
      ]);

      $supplier = Supplier::find($id);
      $supplier->name = $request->name;
      $supplier->address= $request->address;
      $supplier->contact = $request->contact;
      $supplier->group = $request->group;
      $supplier->contact_person_name = $request->contact_person_name;
      $supplier->contact_person_telephone = $request->contact_person_telephone;
      $supplier->contact_person_department = $request->contact_person_department;
      $supplier->contact_person_mobile = $request->contact_person_mobile;
      $supplier->contact_person_fax = $request->contact_person_fax;
      $supplier->contact_person_email = $request->contact_person_email;
      $supplier->save();
      return redirect()->route('supplier.index')->with('custom_success', 'Supplier Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Supplier View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $supplier = Supplier::find($id);
      return view('database.supplier.view', compact('supplier'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Supplier Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

      $supplier = Supplier::find($id);
      $supplier->delete();
      return redirect()->route('supplier.index')->with('custom_success', 'Supplier Deleted Successfully.');
    }
}
