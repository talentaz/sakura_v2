<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;
use PDF;

class InquiryController extends Controller
{
    /**
     * Display a listing of inquiries
     */
    public function index(Request $request)
    {
        $inquiries = Inquiry::with(['vehicle', 'user'])
            ->orderBy('id', 'desc')
            ->get();
       
        return view('admin.pages.inquiry.index', [
            'inquiries' => $inquiries,
        ]);
    }

    /**
     * Show the form for creating a new inquiry
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $users = User::all();

        return view('admin.pages.inquiry.create', [
            'vehicles' => $vehicles,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created inquiry
     */
    public function store(Request $request)
    {
        $request->validate([
            'inqu_name' => 'required|string|max:255',
            'inqu_email' => 'required|email|max:255',
            'inqu_mobile' => 'required|string|max:255',
            'inqu_country' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicle,id',
        ]);

        Inquiry::create($request->all());

        return redirect()->route('admin.inquiry.index')
            ->with('success', 'Inquiry created successfully.');
    }

    /**
     * Display the specified inquiry
     */
    public function show($id)
    {
        $inquiry = Inquiry::with(['vehicle', 'user'])->findOrFail($id);

        return view('admin.pages.inquiry.show', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Show the form for editing the specified inquiry
     */
    public function edit($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('admin.pages.inquiry.edit', compact('inquiry'));
    }

    /**
     * Update the specified inquiry
     */
    public function update(Request $request, $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $updateType = $request->input('update_type');

        switch ($updateType) {
            case 'sales_agent':
                $request->validate([
                    'sales_agent' => 'nullable|string|max:255',
                ]);
                $inquiry->sales_agent = $request->sales_agent;
                $message = 'Sales agent updated successfully!';
                break;

            case 'vehicle_status':
                $request->validate([
                    'vehicle_status' => 'required|in:Reserved,Ready to Ship,Open,Inactive',
                ]);
                $inquiry->vehicle_status = $request->vehicle_status;

                // Clear expiry date if status is not Reserved
                if ($request->vehicle_status !== 'Reserved') {
                    $inquiry->reserved_expiry_date = null;
                }

                $message = 'Vehicle status updated successfully!';
                break;

            case 'reserved_expiry_date':
                $request->validate([
                    'reserved_expiry_date' => 'nullable|date',
                ]);
                $inquiry->reserved_expiry_date = $request->reserved_expiry_date;
                $message = 'Reserved expiry date updated successfully!';
                break;

            default:
                return redirect()->back()->with('error', 'Invalid update type.');
        }

        $inquiry->save();

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified inquiry
     */
    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->route('admin.inquiry.index')
            ->with('success', 'Inquiry deleted successfully.');
    }

    /**
     * Get inquiry details for AJAX requests
     */
    public function detail(Request $request)
    {
        $id = $request->id;
        $inquiry = Inquiry::with(['vehicle', 'user'])->findOrFail($id);

        return response()->json([
            'result' => true,
            'data' => $inquiry
        ]);
    }

    /**
     * Update inquiry status
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:inquiry,id',
            'status' => 'required|string',
            'reserved_expiry_date' => 'nullable|date',
        ]);

        $inquiry = Inquiry::findOrFail($request->id);
        $inquiry->vehicle_status = $request->status;

        if ($request->reserved_expiry_date) {
            $inquiry->reserved_expiry_date = Carbon::parse($request->reserved_expiry_date);
        }

        $inquiry->save();

        return response()->json([
            'result' => true,
            'message' => 'Status updated successfully',
            'data' => $inquiry
        ]);
    }

    /**
     * Generate PDF quotation
     */
    public function generatePDF($id)
    {
        $inquiry = Inquiry::with(['vehicle', 'user'])->findOrFail($id);

        $pdf = PDF::loadView('admin.pages.inquiry.pdf', compact('inquiry'));

        return $pdf->download('quotation-' . $inquiry->id . '.pdf');
    }

    /**
     * Generate invoice
     */
    public function generateInvoice($id)
    {
        $inquiry = Inquiry::with(['vehicle', 'user'])->findOrFail($id);

        return view('admin.pages.inquiry.invoice', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Delete inquiry via AJAX
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return response()->json([
            'result' => true,
            'message' => 'Inquiry deleted successfully'
        ]);
    }

}
