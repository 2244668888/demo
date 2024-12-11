<?php

namespace App\Http\Controllers;
use App\Models\CallForAssistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class CallForAssistanceController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Call For Assistance List') ||
            Auth::user()->hasPermissionTo('Call For Assistance Update') ||
            Auth::user()->hasPermissionTo('Call For Assistance View')
        ) {
            $calls = CallForAssistance::all();
            return view('mes.production.call-for-assistance.index', compact('calls'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Call For Assistance Update')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $call = CallForAssistance::find($id);
        $users = User::select('id', 'user_name')->get();
        return view('mes.production.call-for-assistance.edit', compact('call', 'users'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Call For Assistance Update')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validator = null;

        if($request->file('image') != null){
            $validatedData = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg',
                'attended_pic' => 'required'
            ]);
        }else{
            $validatedData = $request->validate([
                'attended_pic' => 'required'
            ]);
        }

        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        $call = CallForAssistance::find($id);
        if($request->file('image')){
            $file = $request->file('image');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('calls', $filename);
            $call->image =  $filename;
        }
        if($call->submitted_datetime == null){
            $call->submitted_datetime = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s A');
            $call->status = 'Submitted';
        }else{
            $call->submitted_datetime = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s A');
        }
        $call->attended_pic =  $request->attended_pic;
        $call->remarks =  $request->remarks;
        $call->save();

        return redirect()->route('call_for_assistance.index')->with('custom_success', 'Call For Assistance Updated Successfully!');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Call For Assistance View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $call = CallForAssistance::find($id);
        return view('mes.production.call-for-assistance.view', compact('call'));
    }
}
