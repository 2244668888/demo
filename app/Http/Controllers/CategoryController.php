<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    //

    public function Data(Request $request)
    {
        if ($request->ajax()) {

            $query = Category::select(
                'categories.id',
                'categories.name',

            );

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('category.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('category.view', $row->id) .
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
                                                <form method="POST" action="' . route('category.destroy', $row->id) . '">
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


                });
            }

               return $datatable->make(true);
        }
    }

    public function index(){

      if (
        Auth::user()->hasPermissionTo('Category List') ||
        Auth::user()->hasPermissionTo('Category Create') ||
        Auth::user()->hasPermissionTo('Category Edit') ||
        Auth::user()->hasPermissionTo('Category View') ||
        Auth::user()->hasPermissionTo('Category Delete')
    ){
        $categories = Category::all();
        return view('database.category.index', compact('categories'));
      }
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
      public function create()
      {
        if (!Auth::user()->hasPermissionTo('Category Create')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
        return view('database.category.create');
      }

      public function store(Request $request)
      {
        if (!Auth::user()->hasPermissionTo('Category Create')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
          $validated = $request->validate([
              'name' => [
                  'required',
                  Rule::unique('categories', 'name')->whereNull('deleted_at')
              ],
            ]);

        $category= new Category();
        $category->name = $request->name;
        $category->save();
        return redirect()->route('category.index')->with('custom_success', 'Category Created Successfully.');
      }

      public function edit($id)
      {
        if (!Auth::user()->hasPermissionTo('Category Edit')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
        $category = Category::find($id);
        return view('database.category.edit', compact('category'));
      }

      public function update(Request $request, $id)
      {
        if (!Auth::user()->hasPermissionTo('Category Edit')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }

          $validated = $request->validate([
              'name' => [
                  'required',
                  Rule::unique('categories', 'name')->whereNull('deleted_at')->ignore($id)
              ],
            ]);
        $category = Category::find($id);
        $category->name= $request->name;
        $category->save();
        return redirect()->route('category.index')->with('custom_success', 'Category Updated Successfully.');
      }

      public function view($id)
      {
        if (!Auth::user()->hasPermissionTo('Category View')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
        $category = category::find($id);
        return view('database.category.view', compact('category'));
      }

      public function destroy($id)
      {
        if (!Auth::user()->hasPermissionTo('Category Delete')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }

        $categorys = category::find($id);
        $categorys->delete();
        return redirect()->route('category.index')->with('custom_success', 'Category Deleted Successfully.');
      }
}
