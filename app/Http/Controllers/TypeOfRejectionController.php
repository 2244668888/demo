<?php

namespace App\Http\Controllers;
use App\Models\TypeOfRejection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TypeOfRejectionController extends Controller
{
    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = TypeOfRejection::select(
                'type_of_rejections.id',
                'type_of_rejections.type',

            );



// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('type_of_rejection.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('type_of_rejection.view', $row->id) .
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
                                                <form method="POST" action="' . route('type_of_rejection.destroy', $row->id) . '">
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
        if (
            Auth::user()->hasPermissionTo('Type Of Rejection List') ||
            Auth::user()->hasPermissionTo('Type Of Rejection Create') ||
            Auth::user()->hasPermissionTo('Type Of Rejection Edit') ||
            Auth::user()->hasPermissionTo('Type Of Rejection View') ||
            Auth::user()->hasPermissionTo('Type Of Rejection Delete')
        ) {
            $type_of_rejections = TypeOfRejection::all();
            return view('database.type_of_rejection.index', compact('type_of_rejections'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Type Of Rejection Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        return view('database.type_of_rejection.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Type Of Rejection Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'type' => [
                'required',
                Rule::unique('type_of_rejections', 'type')->whereNull('deleted_at')
            ],
        ]);

        $type_of_rejections = new TypeOfRejection();
        $type_of_rejections->type = $request->type;
        $type_of_rejections->save();

        return redirect()->route('type_of_rejection.index')->with('custom_success', 'Type Of Rejection Created Successfully.');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Type Of Rejection Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $type_of_rejection = TypeOfRejection::find($id);
        return view('database.type_of_rejection.edit', compact('type_of_rejection'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Type Of Rejection Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'type' => [
                'required',
                Rule::unique('type_of_rejections', 'type')->whereNull('deleted_at')->ignore($id)
            ],
        ]);

        $type_of_rejections = TypeOfRejection::find($id);
        $type_of_rejections->type = $request->type;
        $type_of_rejections->save();

        return redirect()->route('type_of_rejection.index')->with('custom_success', 'Type Of Rejection Updated Successfully.');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Type Of Rejection View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $type_of_rejection = TypeOfRejection::find($id);
        return view('database.type_of_rejection.view', compact('type_of_rejection'));
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Type Of Rejection Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $type_of_rejections = TypeOfRejection::find($id);
        $type_of_rejections->delete();
        return redirect()->route('type_of_rejection.index')->with('custom_success', 'Type Of Rejection Deleted Successfully.');
    }
}
