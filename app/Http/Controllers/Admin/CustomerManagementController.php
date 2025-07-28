<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerManagementController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Include soft deleted if requested
        if ($request->has('include_deleted') && $request->include_deleted) {
            $query->withTrashed();
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pages.customer_management.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.pages.customer_management.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6|confirmed',
            'country_id' => 'nullable|integer',
            'status' => 'required|string|in:Active,Inactive,Suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country_id' => $request->country_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.customer_management.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        return view('admin.pages.customer_management.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.pages.customer_management.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customer->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'country_id' => 'nullable|integer',
            'status' => 'required|string|in:Active,Inactive,Suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'status' => $request->status,
        ];

        // Only update password if provided
        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
        }

        $customer->update($updateData);

        return redirect()->route('admin.customer_management.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage (soft delete).
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customer_management.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Restore a soft-deleted customer.
     */
    public function restore($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('admin.customer_management.index')
            ->with('success', 'Customer restored successfully.');
    }

    /**
     * Permanently delete a customer.
     */
    public function forceDelete($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        $customer->forceDelete();

        return redirect()->route('admin.customer_management.index')
            ->with('success', 'Customer permanently deleted.');
    }

    /**
     * Change customer status via AJAX.
     */
    public function changeStatus(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $customer->status = $request->status;
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer status updated successfully.'
        ]);
    }
}
