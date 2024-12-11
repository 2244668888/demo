<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Yajra\DataTables\Facades\DataTables;

class AccountCategoryController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax()) {

            $query = AccountCategory::select(
                'account_categories.id',
                'account_categories.name',
                'account_categories.type',

            );

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('type', function($row){
                    return ucfirst($row->type);
                })

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('account_categories.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('account_categories.view', $row->id) .
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
                                                <form method="POST" action="' . route('account_categories.destroy', $row->id) . '">
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
                if (!empty($request->search['value']) || $request->has('name') || $request->has('type')) {
                    $datatable = $datatable->filter(function ($query) use ($request) {
                        if (!empty($request->search['value'])) { 
                                                       $globalSearch = $request->search['value'];
                            $query->where(function ($q) use ($globalSearch) {
                                $q->where('name', 'like', "%{$globalSearch}%")
                                  ->orWhere('type', 'like', "%{$globalSearch}%");
                        });
                    }
                    if ($request->has('name') && !is_null($request->name)) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }
                    if ($request->has('type') && !is_null($request->type)) {
                        $query->where('type', 'like', "%{$request->type}%");
                    }

                });
            }

               return $datatable->make(true);
        }
    }

    public function index()
    {
        if(
            Auth::user()->hasPermissionTo('Account Category List') ||
            Auth::user()->hasPermissionTo('Account Category Create') ||
            Auth::user()->hasPermissionTo('Account Category Edit') ||
            Auth::user()->hasPermissionTo('Account Category View') ||
            Auth::user()->hasPermissionTo('Account Category Delete')
        ){
            $categories = AccountCategory::all();
            return view('accounting.accounts.categories.index', compact('categories'));
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Account Category Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        return view('accounting.accounts.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('account_categories', 'name')
            ],
            'type' => 'required',
          ]);

      $accountCategories= new AccountCategory();
      $accountCategories->name = $request->name;
      $accountCategories->type = $request->type;
      $accountCategories->save();
      return redirect()->route('account_categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
      {
        if (!Auth::user()->hasPermissionTo('Account Category Edit')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $accountCategories = AccountCategory::find($id);
        return view('accounting.accounts.categories.edit', compact('accountCategories'));
      }

      public function update(Request $request, $id)
      {

          $validated = $request->validate([
              'name' => [
                  'required',
                  Rule::unique('account_categories', 'name')->ignore($id)
              ],
            ]);
            $accountCategories = AccountCategory::find($id);
            $accountCategories->name = $request->name;
            $accountCategories->type = $request->type;
            $accountCategories->save();
        return redirect()->route('account_categories.index')->with('custom_success', 'Category Updated Successfully.');
      }

      public function view($id)
      {
        if (!Auth::user()->hasPermissionTo('Account Category View')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $accountCategories = AccountCategory::find($id);
        return view('accounting.accounts.categories.view', compact('accountCategories'));
      }

      public function destroy($id)
      {
        if (!Auth::user()->hasPermissionTo('Account Category Delete')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $accountCategories = AccountCategory::find($id);
        $accountCategories->delete();
        return redirect()->route('account_categories.index')->with('custom_success', 'Category Deleted Successfully.');
      }
}

