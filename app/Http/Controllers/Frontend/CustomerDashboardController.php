<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\Port;
use PDF;

class CustomerDashboardController extends Controller
{
    /**
     * Show submitted inquiries page
     */
    public function inquiries()
    {
        $customer = Auth::guard('customer')->user();
        
        $inquiries = $customer->submittedInquiries()
            ->with(['vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('front.pages.customer.inquiries', compact('customer', 'inquiries'));
    }
    
    /**
     * Show purchase history page
     */
    public function purchases()
    {
        $customer = Auth::guard('customer')->user();
        
        $purchases = $customer->inquiries()
            ->whereIn('vehicle_status', ['Payment Received', 'Shipping', 'Document'])
            ->with(['vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('front.pages.customer.purchases', compact('customer', 'purchases'));
    }
    
    /**
     * Show customer profile page
     */
    public function profile()
    {
        $customer = Auth::guard('customer')->user()->load('country');

        // Get available countries from ports
        $countries = Port::select('country')->distinct()->orderBy('country')->get();

        return view('front.pages.customer.profile', compact('customer', 'countries'));
    }
    
    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'country' => 'nullable|string|max:255',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Check current password if new password is provided
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $customer->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 422);
            }
        }
        
        // Find country_id from ports table
        $country_id = null;
        if ($request->filled('country')) {
            $port = Port::where('country', $request->country)->first();
            $country_id = $port ? $port->id : null;
        }
        
        // Update customer data
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'country_id' => $country_id,
        ];
        
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }
        
        $customer->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }
    
    /**
     * Show billing history for a specific inquiry
     */
    public function billingHistory($inquiryId)
    {
        $customer = Auth::guard('customer')->user();
        
        $inquiry = $customer->inquiries()
            ->with(['vehicle'])
            ->findOrFail($inquiryId);
        
        // Generate mock billing history data
        $billingHistory = [
            [
                'id' => 1,
                'created_date' => $inquiry->created_at->format('M d, Y'),
                'description' => 'Initial Payment',
                'paid_amount' => number_format($inquiry->safe_total_price * 0.6, 0)
            ],
            [
                'id' => 2,
                'created_date' => $inquiry->created_at->addDays(1)->format('M d, Y'),
                'description' => 'Final Payment',
                'paid_amount' => number_format($inquiry->safe_total_price * 0.4, 0)
            ]
        ];
        
        // Calculate breakdown
        $breakdown = [
            'fob_price' => $inquiry->safe_fob_price ?? ($inquiry->safe_total_price * 0.7),
            'freight_fee' => $inquiry->safe_freight_fee ?? ($inquiry->safe_total_price * 0.2),
            'insurance_fee' => $inquiry->safe_insurance_fee ?? ($inquiry->safe_total_price * 0.05),
            'inspection_fee' => $inquiry->safe_inspection_fee ?? ($inquiry->safe_total_price * 0.1),
            'discount' => $inquiry->safe_discount ?? ($inquiry->safe_total_price * -0.05),
            'total_payable' => $inquiry->safe_total_price,
            'total_paid' => $inquiry->safe_total_price,
            'amount_due' => 0
        ];
        
        return view('front.pages.customer.billing', compact('customer', 'inquiry', 'billingHistory', 'breakdown'));
    }

    /**
     * Generate PDF quotation for customer
     */
    public function generatePDF($id)
    {
        $customer = Auth::guard('customer')->user();
        
        // Ensure customer can only access their own inquiries
        $inquiry = $customer->inquiries()->with(['vehicle', 'user'])->findOrFail($id);
        
        $pdf = PDF::loadView('admin.pages.inquiry.pdf', compact('inquiry'));
        
        return $pdf->stream('quotation-' . $inquiry->id . '.pdf');
    }
}


