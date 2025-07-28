<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::with('role')->where('role_id', 2)->orderBy('id', 'DESC')->get();
        
        return view('admin.pages.user.index', [
            'data'  => $data,
        ]);
    }

    public function create()
    {
        $country = config('config.country');
        $roles = Role::all();
        return view('admin.pages.user.create', [
            'country' => $country,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role_id' => 'required|integer|exists:roles,id',
            ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->address = $request->address;
        $user->role_id = $request->role_id;
        $user->status = 1; // Active by default
        $user->save();

        // Handle avatar upload if provided
        if ($request->hasFile('file')) {
            $avatar = $request->file('file');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/uploads/avatar/' . $user->id);

            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0755, true);
            }

            $avatar->move($avatarPath, $avatarName);
            $user->avatar = $avatarName;
            $user->save(); // Save again to update avatar
        }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $country = config('config.country');
        $roles = Role::all();
        return view('admin.pages.user.edit', [
            'user' => $user,
            'country' => $country,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role_id' => 'required|integer|exists:roles,id',
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:6|confirmed';
            }

            $request->validate($rules);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->city = $request->city;
            $user->address = $request->address;
            $user->role_id = $request->role_id;

            // Update password only if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Handle avatar upload if provided
            if ($request->hasFile('file')) {
                $avatar = $request->file('file');
                $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
                $avatarPath = public_path('/uploads/avatar/' . $user->id);

                if (!file_exists($avatarPath)) {
                    mkdir($avatarPath, 0755, true);
                }

                $avatar->move($avatarPath, $avatarName);
                $user->avatar = $avatarName;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user.'
            ], 500);
        }
    }

    public function change_password($id){
        $data = User::findOrFail($id);
        return view('admin.pages.user.changePassword', [
            'data'  => $data,
        ]);
    }
    
    public function updatePassword(Request $request){
        $messages = [
            'cpass.required' => 'Current password is required',
            'npass.required' => 'New password is required',
            'cfpass.required' => 'Confirm password is required',
        ];

        $request->validate([
            'cpass' => 'required',
            'npass' => 'required',
            'cfpass' => 'required',
        ], $messages);

        $user = User::findOrFail($request->user_id);
        if ($request->cpass) {
            if (Hash::check($request->cpass, $user->password)) {
                if ($request->npass == $request->cfpass) {
                    $input['password'] = Hash::make($request->npass);
                } else {
                    return back()->with('error', __('Confirm password does not match.'));
                }
            } else {
                return back()->with('error', __('Current password Does not match.'));
            }
        }
        $user->update($input);

        Session::flash('success', 'Password update for ' . $user->name);
        return back();
    }
    public function delete(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();

        return response()->json('success');
    }
}
