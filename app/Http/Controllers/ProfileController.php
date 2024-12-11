<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Department;
use App\Models\Designation;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('layouts.profile');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'full_name' => [
                'required'
            ],
            'user_name' => [
                'required',
                Rule::unique('Users', 'user_name')->whereNull('deleted_at')->ignore(Auth::user()->id)
            ],
            'email' => [
                'required',
                Rule::unique('Users', 'email')->whereNull('deleted_at')->ignore(Auth::user()->id)
            ]
          ]);

        $user = User::find(Auth::user()->id);
        $user->full_name = $request->full_name;
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return redirect()->route('profile')->with('custom_success', 'Profile Updated Successfully!');
    }

    public function password(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Auth::validate(['email' => Auth::user()->email, 'password' => $value])) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
            ],
            'confirm_password' => 'required|same:new_password',
          ]);

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->confirm_password);
        $user->save();

        return redirect()->route('profile')->with('custom_success', 'Password Changed Successfully!');
    }
}
