<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Inquiry;
use App\Models\Invoice;
use App\Models\Port;
use App\Models\BillingHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display vehicle sales report
     */
    public function vehicleSalesReport(Request $request)
    {
        // Get filter options - get unique maker types from vehicle table
        $makerTypes = DB::table('vehicle')
            ->select('make_type')
            ->whereNotNull('make_type')
            ->where('make_type', '!=', '')
            ->distinct()
            ->orderBy('make_type')
            ->get();

        // Build query for vehicle sales data
        $query = DB::table('inquiry')
            ->join('vehicle', 'inquiry.vehicle_id', '=', 'vehicle.id')
            ->leftJoin('invoices', 'inquiry.id', '=', 'invoices.inquiry_id')
            ->leftJoin('ports', 'inquiry.inqu_country', '=', 'ports.id')
            ->where('inquiry.vehicle_status', 'Shipped') // Only completed sales
            ->select(
                'vehicle.make_type as maker_name',
                'vehicle.model_type as model_name',
                'ports.country as shipment_country',
                DB::raw('COUNT(DISTINCT inquiry.id) as number_of_vehicles'),
                DB::raw('MIN(inquiry.updated_at) as min_completed_date'),
                DB::raw('MAX(inquiry.updated_at) as max_completed_date')
            )
            ->groupBy('vehicle.make_type', 'vehicle.model_type', 'ports.country');

        // Apply filters
        if ($request->filled('maker_type')) {
            $query->where('vehicle.make_type', $request->maker_type);
        }

        if ($request->filled('model_type')) {
            $query->where('vehicle.model_type', 'LIKE', '%' . $request->model_type . '%');
        }

        if ($request->filled('shipment_country')) {
            $query->where('ports.country', $request->shipment_country);
        }

        if ($request->filled('date_from')) {
            $query->where('inquiry.updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('inquiry.updated_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Get results with pagination
        $perPage = $request->get('per_page', 10);
        $salesData = $query->orderBy('vehicle.make_type')
            ->orderBy('vehicle.model_type')
            ->paginate($perPage);

        // Get unique countries for filter from ports table
        $countries = DB::table('ports')
            ->select('country')
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('id')
            ->pluck('country');

        return view('admin.pages.reports.vehicle-sales', [
            'salesData' => $salesData,
            'makerTypes' => $makerTypes,
            'countries' => $countries,
            'filters' => $request->all(),
            'perPage' => $perPage
        ]);
    }

    /**
     * Get models by maker type (AJAX)
     */
    public function getModelsByMaker(Request $request)
    {
        $makerType = $request->get('maker_id');

        $models = Vehicle::where('make_type', $makerType)
            ->select('model_type')
            ->distinct()
            ->whereNotNull('model_type')
            ->where('model_type', '!=', '')
            ->orderBy('model_type')
            ->pluck('model_type');

        return response()->json($models);
    }

    /**
     * Export vehicle sales report to Excel
     */
    public function exportVehicleSalesReport(Request $request)
    {
        // Build the same query as the report
        $query = DB::table('inquiry')
            ->join('vehicle', 'inquiry.vehicle_id', '=', 'vehicle.id')
            ->leftJoin('invoices', 'inquiry.id', '=', 'invoices.inquiry_id')
            ->leftJoin('ports', 'inquiry.inqu_country', '=', 'ports.id')
            ->where('inquiry.vehicle_status', 'Shipped')
            ->select(
                'vehicle.make_type as maker_name',
                'vehicle.model_type as model_name',
                'ports.country as shipment_country',
                DB::raw('COUNT(DISTINCT inquiry.id) as number_of_vehicles'),
                DB::raw('MIN(inquiry.updated_at) as min_completed_date'),
                DB::raw('MAX(inquiry.updated_at) as max_completed_date')
            )
            ->groupBy('vehicle.make_type', 'vehicle.model_type', 'ports.country');

        // Apply same filters
        if ($request->filled('maker_type')) {
            $query->where('vehicle.make_type', $request->maker_type);
        }

        if ($request->filled('model_type')) {
            $query->where('vehicle.model_type', 'LIKE', '%' . $request->model_type . '%');
        }

        if ($request->filled('shipment_country')) {
            $query->where('ports.country', $request->shipment_country);
        }

        if ($request->filled('date_from')) {
            $query->where('inquiry.updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('inquiry.updated_at', '<=', $request->date_to . ' 23:59:59');
        }

        $salesData = $query->orderBy('vehicle.make_type')
            ->orderBy('vehicle.model_type')
            ->get();

        // Create CSV content
        $csvContent = "Make,Model,Shipment Country,Number of Vehicles,Completed Date\n";
        
        foreach ($salesData as $row) {
            $completedDate = '';
            if ($row->min_completed_date && $row->max_completed_date) {
                $minDate = Carbon::parse($row->min_completed_date)->format('Y-m-d');
                $maxDate = Carbon::parse($row->max_completed_date)->format('Y-m-d');
                $completedDate = $minDate === $maxDate ? $minDate : $minDate . ' - ' . $maxDate;
            }
            
            $csvContent .= sprintf(
                "%s,%s,%s,%d,%s\n",
                $row->maker_name ?? 'N/A',
                $row->model_name ?? 'N/A',
                $row->shipment_country ?? 'N/A',
                $row->number_of_vehicles,
                $completedDate
            );
        }

        $filename = 'vehicle_sales_report_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Display customer purchase report
     */
    public function customerPurchaseReport(Request $request)
    {
        // Build query for customer purchase data
        $query = DB::table('inquiry')
            ->join('vehicle', 'inquiry.vehicle_id', '=', 'vehicle.id')
            ->leftJoin('invoices', 'inquiry.id', '=', 'invoices.inquiry_id')
            ->where('inquiry.vehicle_status', 'Shipped') // Only completed purchases
            ->select(
                'inquiry.inqu_name as user_name',
                'inquiry.inqu_email as user_email',
                DB::raw('COUNT(DISTINCT inquiry.id) as number_of_car_purchased'),
                DB::raw('MIN(inquiry.updated_at) as min_completed_date'),
                DB::raw('MAX(inquiry.updated_at) as max_completed_date')
            )
            ->groupBy('inquiry.inqu_name', 'inquiry.inqu_email');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('inquiry.inqu_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('inquiry.inqu_email', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->where('inquiry.updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('inquiry.updated_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Get results with pagination
        $perPage = $request->get('per_page', 10);
        $customerData = $query->orderBy('inquiry.inqu_name')
            ->paginate($perPage);

        return view('admin.pages.reports.customer-purchase', [
            'customerData' => $customerData,
            'filters' => $request->all(),
            'perPage' => $perPage
        ]);
    }

    /**
     * Export customer purchase report to Excel
     */
    public function exportCustomerPurchaseReport(Request $request)
    {
        // Build the same query as the report
        $query = DB::table('inquiry')
            ->join('vehicle', 'inquiry.vehicle_id', '=', 'vehicle.id')
            ->leftJoin('invoices', 'inquiry.id', '=', 'invoices.inquiry_id')
            ->where('inquiry.vehicle_status', 'Shipped')
            ->select(
                'inquiry.inqu_name as user_name',
                'inquiry.inqu_email as user_email',
                DB::raw('COUNT(DISTINCT inquiry.id) as number_of_car_purchased'),
                DB::raw('MIN(inquiry.updated_at) as min_completed_date'),
                DB::raw('MAX(inquiry.updated_at) as max_completed_date')
            )
            ->groupBy('inquiry.inqu_name', 'inquiry.inqu_email');

        // Apply same filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('inquiry.inqu_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('inquiry.inqu_email', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->where('inquiry.updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('inquiry.updated_at', '<=', $request->date_to . ' 23:59:59');
        }

        $customerData = $query->orderBy('inquiry.inqu_name')->get();

        // Create CSV content
        $csvContent = "ID,User Name,User Email,Number of Car Purchased,Completed Date\n";

        $id = 1;
        foreach ($customerData as $row) {
            $completedDate = '';
            if ($row->min_completed_date && $row->max_completed_date) {
                $minDate = Carbon::parse($row->min_completed_date)->format('Y-m-d');
                $maxDate = Carbon::parse($row->max_completed_date)->format('Y-m-d');
                $completedDate = $minDate === $maxDate ? $minDate : $minDate . ' - ' . $maxDate;
            }

            $csvContent .= sprintf(
                "%d,%s,%s,%d,%s\n",
                $id++,
                $row->user_name ?? 'N/A',
                $row->user_email ?? 'N/A',
                $row->number_of_car_purchased,
                $completedDate
            );
        }

        $filename = 'customer_purchase_report_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Display agent performance report
     */
    public function agentPerformanceReport(Request $request)
    {
        // Get all users who can be agents (sales_manager, sales_agent roles)
        $agents = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->whereIn('roles.slug', ['sales_manager', 'sales_agent'])
            ->select('users.id', 'users.name', 'users.email')
            ->orderBy('users.name')
            ->get();

        // Build query for agent performance data
        $query = DB::table('inquiry')
            ->join('vehicle', 'inquiry.vehicle_id', '=', 'vehicle.id')
            ->join('invoices', 'inquiry.id', '=', 'invoices.inquiry_id')
            ->leftJoin('billing_history', 'invoices.id', '=', 'billing_history.invoice_id')
            ->join('users', 'inquiry.sales_agent', '=', 'users.id')
            ->where('inquiry.vehicle_status', 'Shipped') // Only completed sales
            ->select(
                'users.name as agent_name',
                'users.email as agent_email',
                DB::raw('COUNT(DISTINCT inquiry.id) as number_of_cars_sold'),
                DB::raw('COALESCE(SUM(CAST(billing_history.paid_amount AS DECIMAL(10,2))), 0) as total_sales_amount'),
                DB::raw('MIN(billing_history.created_at) as min_invoice_date'),
                DB::raw('MAX(billing_history.created_at) as max_invoice_date')
            )
            ->groupBy('users.id', 'users.name', 'users.email');

        // Apply filters
        if ($request->filled('agent_id')) {
            $query->where('users.id', $request->agent_id);
        }

        if ($request->filled('date_from')) {
            $query->where('billing_history.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('billing_history.created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Get results with pagination
        $perPage = $request->get('per_page', 10);
        $agentData = $query->orderBy('users.name')
            ->paginate($perPage);

        return view('admin.pages.reports.agent-performance', [
            'agentData' => $agentData,
            'agents' => $agents,
            'filters' => $request->all(),
            'perPage' => $perPage
        ]);
    }

    /**
     * Export agent performance report to Excel
     */
    public function exportAgentPerformanceReport(Request $request)
    {
        // Build the same query as the report
        $query = DB::table('inquiry')
            ->join('vehicle', 'inquiry.vehicle_id', '=', 'vehicle.id')
            ->join('invoices', 'inquiry.id', '=', 'invoices.inquiry_id')
            ->leftJoin('billing_history', 'invoices.id', '=', 'billing_history.invoice_id')
            ->join('users', 'inquiry.sales_agent', '=', 'users.id')
            ->where('inquiry.vehicle_status', 'Shipped')
            ->select(
                'users.name as agent_name',
                'users.email as agent_email',
                DB::raw('COUNT(DISTINCT inquiry.id) as number_of_cars_sold'),
                DB::raw('COALESCE(SUM(CAST(billing_history.paid_amount AS DECIMAL(10,2))), 0) as total_sales_amount'),
                DB::raw('MIN(billing_history.created_at) as min_invoice_date'),
                DB::raw('MAX(billing_history.created_at) as max_invoice_date')
            )
            ->groupBy('users.id', 'users.name', 'users.email');

        // Apply same filters
        if ($request->filled('agent_id')) {
            $query->where('users.id', $request->agent_id);
        }

        if ($request->filled('date_from')) {
            $query->where('billing_history.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('billing_history.created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $agentData = $query->orderBy('users.name')->get();

        // Create CSV content
        $csvContent = "ID,Invoice Date,Agent Name,Agent Email,Number of Cars Sold,Total Sales Amount\n";

        $id = 1;
        foreach ($agentData as $row) {
            $invoiceDate = '';
            if ($row->min_invoice_date && $row->max_invoice_date) {
                $minDate = Carbon::parse($row->min_invoice_date)->format('Y-m-d');
                $maxDate = Carbon::parse($row->max_invoice_date)->format('Y-m-d');
                $invoiceDate = $minDate === $maxDate ? $minDate : $minDate . ' - ' . $maxDate;
            }

            $csvContent .= sprintf(
                "%d,%s,%s,%s,%d,$%s\n",
                $id++,
                $invoiceDate,
                $row->agent_name ?? 'N/A',
                $row->agent_email ?? 'N/A',
                $row->number_of_cars_sold,
                number_format($row->total_sales_amount ?? 0, 2)
            );
        }

        $filename = 'agent_performance_report_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
