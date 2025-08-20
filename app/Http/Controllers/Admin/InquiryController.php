<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Invoice;
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
        $perPage = $request->get('per_page', 10); // Default to 10 entries
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        // Build query with role-based filtering
        $query = Inquiry::with(['vehicle', 'user', 'invoice']);

        // Sales agents can only see their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }
        // Sales managers and admins can see all inquiries
        // No additional filtering needed for admin and sales_manager roles

        $inquiries = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('admin.pages.inquiry.index', [
            'inquiries' => $inquiries,
            'perPage' => $perPage,
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
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        $query = Inquiry::with(['vehicle', 'user']);

        // Sales agents can only view their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($id);

        return view('admin.pages.inquiry.show', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Show the form for editing the specified inquiry
     */
    public function edit($id)
    {
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        $query = Inquiry::with([
            'vehicle' => function($query) {
                $query->with(['vehicleImages' => function($imageQuery) {
                    $imageQuery->orderByRaw('CONVERT(image, SIGNED) ASC')->limit(1);
                }]);
            },
            'user',
            'salesAgent',
            'inquiryCountry',
            'invoice'
        ]);

        // Sales agents can only edit their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($id);
        
        $users = User::with('role')
            ->where('id', '!=', auth()->id())
            ->orderBy('id', 'DESC')
            ->get();
        
        $vehicle_status = config('config.vehicle_status');
        
        return view('admin.pages.inquiry.edit', [
            'inquiry' => $inquiry,
            'users' => $users,
            'vehicle_status' => $vehicle_status,
        ]);
    }

    /**
     * Update the specified inquiry
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        $query = Inquiry::query();

        // Sales agents can only update their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($id);
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
                // Define allowed statuses for sales agents
                $salesAgentStatuses = ['Open', 'Reserved', 'Prepare for Shipment', 'Inactive'];

                // Validate based on user role
                if ($userRole === 'sales_agent') {
                    $request->validate([
                        'vehicle_status' => 'required|string|in:' . implode(',', $salesAgentStatuses),
                    ]);
                } else {
                    $request->validate([
                        'vehicle_status' => 'required|string',
                    ]);
                }

                $inquiry->vehicle_status = $request->vehicle_status;

                // Clear expiry date if status is not Reserved
                if ($request->vehicle_status !== 'Reserved') {
                    $inquiry->reserved_expiry_date = null;
                }

                $message = 'Vehicle status updated successfully!';
                break;

            case 'reserved_expiry_date':
                // dd(2);
                $request->validate([
                    'reserved_expiry_date' => 'required|date',
                ]);
                
                // When updating expiry date, ensure status is Reserved
                $inquiry->vehicle_status = 'Reserved';
                $inquiry->reserved_expiry_date = $request->reserved_expiry_date;
                $message = 'Vehicle status set to Reserved and expiry date updated successfully!';
                break;

            case 'total_discount':
                $validationRules = [
                    'total_price' => 'required|numeric|min:0',
                    'discount' => 'nullable|numeric|min:0',
                ];

                // Add vehicle status validation if provided
                if ($request->has('vehicle_status')) {
                    if ($userRole === 'sales_agent') {
                        $salesAgentStatuses = ['Open', 'Reserved', 'Prepare for Shipment', 'Inactive'];
                        $validationRules['vehicle_status'] = 'required|string|in:' . implode(',', $salesAgentStatuses);
                    } else {
                        $validationRules['vehicle_status'] = 'required|string';
                    }
                }

                $request->validate($validationRules);

                $inquiry->total_price = $request->total_price;

                // Only update discount if user is not a sales agent (discount is read-only for sales agents)
                if ($userRole !== 'sales_agent') {
                    $inquiry->discount = $request->discount;
                }

                // Update vehicle status if provided
                if ($request->has('vehicle_status')) {
                    $inquiry->vehicle_status = $request->vehicle_status;
                }

                $message = 'Information updated successfully!';
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
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        $query = Inquiry::query();

        // Sales agents can only delete their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($id);
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
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        $query = Inquiry::with(['vehicle', 'user']);

        // Sales agents can only view details of their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($id);

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
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        // Define allowed statuses for sales agents
        $salesAgentStatuses = ['Open', 'Reserved', 'Prepare for Shipment', 'Inactive'];

        // Validate based on user role
        if ($userRole === 'sales_agent') {
            $request->validate([
                'id' => 'required|exists:inquiry,id',
                'status' => 'required|string|in:' . implode(',', $salesAgentStatuses),
                'reserved_expiry_date' => 'nullable|date',
            ]);
        } else {
            $request->validate([
                'id' => 'required|exists:inquiry,id',
                'status' => 'required|string',
                'reserved_expiry_date' => 'nullable|date',
            ]);
        }

        $query = Inquiry::query();

        // Sales agents can only update status of their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($request->id);
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

        return $pdf->stream('quotation-' . $inquiry->id . '.pdf');
    }

    /**
     * Generate invoice
     */
    public function generateInvoice($id)
    {
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        $query = Inquiry::with(['vehicle', 'user', 'invoice', 'salesAgent', 'inquiryCountry']);

        // Sales agents can only view invoice generation for their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($id);

        return view('admin.pages.inquiry.invoice', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Create invoice for inquiry
     */
    public function createInvoice($id)
    {
        $user = auth()->user();
        $userRole = $user->role->slug ?? '';

        $query = Inquiry::query();

        // Sales agents can only create invoices for their assigned inquiries
        if ($userRole === 'sales_agent') {
            $query->where('sales_agent', $user->id);
        }

        $inquiry = $query->findOrFail($id);

        // Check if invoice already exists
        if ($inquiry->invoice) {
            return redirect()->back()->with('error', 'Invoice already exists for this inquiry.');
        }

        // Create invoice with inquiry data
        $invoice = Invoice::create([
            'inquiry_id' => $inquiry->id,
            'paid_amount' => $inquiry->total_price ?? 0,
            // 'description' => 'Invoice for ' . $inquiry->vehicle_name,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Invoice created successfully.');
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




