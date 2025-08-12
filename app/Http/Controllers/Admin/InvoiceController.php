<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\BillingHistory;
use App\Models\Inquiry;
use App\Models\User;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default to 10 entries
        
        $invoices = Invoice::with(['inquiry', 'inquiry.customer'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        // dd(Invoice::with(['inquiry', 'inquiry.customer', 'inquiry.vehicle'])->get())    ;
        return view('admin.pages.invoice.index', [
            'invoices' => $invoices,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create()
    {
        $inquiries = Inquiry::whereDoesntHave('invoice')->get();
        $users = User::all();

        return view('admin.pages.invoice.create', [
            'inquiries' => $inquiries,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request)
    {
        $request->validate([
            'inquiry_id' => 'required|exists:inquiry,id',
        ]);

        $invoice = Invoice::create([
            'inquiry_id' => $request->inquiry_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.invoice.edit', $invoice->id)
            ->with('success', 'Invoice created successfully. You can now add billing records.');
    }

    /**
     * Display the specified invoice
     */
    public function show($id)
    {
        $invoice = Invoice::with(['inquiry.vehicle', 'inquiry.customer', 'inquiry.user', 'creator'])->findOrFail($id);

        return view('admin.pages.invoice.show', [
            'invoice' => $invoice,
        ]);
    }
    /**
     * Show the form for editing the specified invoice
     */
    public function edit($id)
    {
        $invoice = Invoice::with([
            'inquiry.vehicle.vehicleImages',
            'inquiry.inquiryCountry',
            'inquiry.customer',
            'inquiry.user',
            'inquiry.salesAgent',
            'billingHistory.creator',
            'billingHistory.verifier',
            'creator',
            'verifier'
        ])->findOrFail($id);
        
        $users = User::all();
        $vehicle_status = config('config.vehicle_status');
        
        return view('admin.pages.invoice.edit', [
            'invoice' => $invoice,
            'users' => $users,
            'vehicle_status' => $vehicle_status,
        ]);
    }

    /**
     * Update the specified invoice
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'verified_by' => 'nullable|exists:users,id',
        ]);

        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'verified_by' => $request->verified_by,
            'verified_at' => $request->verified_by ? now() : null,
        ]);

        return redirect()->route('admin.invoice.edit', $id)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('admin.invoice.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Show the form for editing invoice by inquiry ID (for billing management)
     */
    public function editByInquiry($inquiry_id)
    {
        $inquiry = Inquiry::with([
            'vehicle' => function($query) {
                $query->with(['vehicleImages' => function($imageQuery) {
                    $imageQuery->orderByRaw('CONVERT(image, SIGNED) ASC')->limit(1);
                }]);
            },
            'user',
            'salesAgent',
            'inquiryCountry',
            'invoice.billingHistory.creator',
            'invoice.billingHistory.verifier'
        ])->findOrFail($inquiry_id);

        $invoice = $inquiry->invoice;
        if (!$invoice) {
            return redirect()->route('admin.inquiry.edit', $inquiry_id)
                ->with('error', 'No invoice found for this inquiry. Please create an invoice first.');
        }

        $users = User::with('role')
            ->where('id', '!=', auth()->id())
            ->orderBy('id', 'DESC')
            ->get();

        $vehicle_status = config('config.vehicle_status');

        return view('admin.pages.invoice.edit', [
            'invoice' => $invoice,
            'inquiry' => $inquiry,
            'users' => $users,
            'vehicle_status' => $vehicle_status,
        ]);
    }

    /**
     * Store a new billing record
     */
    public function storeBilling(Request $request)
    {
        
        $request->validate([
            'invoice_id' => 'required',
            'paid_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $invoice = Invoice::with('inquiry')->findOrFail($request->invoice_id);

        $totalPaid = $invoice->getTotalPaidAttribute();
        // Check if new payment would exceed total price
        $newTotalPaid = $totalPaid + $request->paid_amount;
        
        if ($newTotalPaid > $invoice->inquiry->total_price) {
            return redirect()->back()->with('error', 'Payment amount exceeds total invoice amount. Remaining balance: $' . number_format($newTotalPaid - $invoice->inquiry->total_price));
        }

        BillingHistory::create([
            'invoice_id' => $request->invoice_id,
            'paid_amount' => $request->paid_amount,
            'description' => $request->description,
            'created_by' => auth()->id(),
            'verified_by' => $request->verified_by,
            'verified_at' => $request->verified_by ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Billing record created successfully.');
    }

    /**
     * Update a billing record
     */
    public function updateBilling(Request $request, $id)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'verified_by' => 'nullable|exists:users,id',
        ]);

        $billingHistory = BillingHistory::findOrFail($id);
        $invoice = Invoice::with('inquiry')->findOrFail($billingHistory->invoice_id);

        // Calculate total paid excluding current record being updated
        $totalPaidExcludingCurrent = $invoice->billingHistory()->where('id', '!=', $id)->sum('paid_amount');
        $newTotalPaid = $totalPaidExcludingCurrent + $request->paid_amount;
        
        if ($newTotalPaid > $invoice->inquiry->total_price) {
            return redirect()->back()->with('error', 'Payment amount exceeds total invoice amount. Remaining balance: $' . number_format($newTotalPaid - $invoice->inquiry->total_price));
        }

        $billingHistory->update([
            'paid_amount' => $request->paid_amount,
            'description' => $request->description,
            'verified_by' => $request->verified_by,
            'verified_at' => $request->verified_by ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Billing record updated successfully.');
    }

    /**
     * Delete a billing record
     */
    public function deleteBilling($id)
    {
        $billingHistory = BillingHistory::findOrFail($id);
        $billingHistory->delete();

        return redirect()->back()->with('success', 'Billing record deleted successfully.');
    }

    /**
     * Verify billing records
     */
    public function verifyBilling($invoice_id)
    {
        Invoice::where('id', $invoice_id)
            ->update([
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All billing records verified successfully.');
    }
    
    /**
     * Generate PDF for the specified invoice
     */
    public function generatePDF($id)
    {
        $invoice = Invoice::with([
            'inquiry.vehicle.vehicleImages',
            'inquiry.customer',
            'inquiry.user',
            'inquiry.salesAgent',
            'billingHistory',
            'billingHistory.creator',
            'billingHistory.verifier',
            'creator',
            'verifier'
        ])->findOrFail($id);
            
        $pdf = \PDF::loadView('admin.pages.invoice.pdf', compact('invoice'));

        $filename = 'Invoice_' . ($invoice->invoice_number ?? 'SM-' . $invoice->id) . '.pdf';

        return $pdf->stream($filename);
    }
}





