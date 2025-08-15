@extends('admin.layouts.master')

@section('title') Agent Performance Report @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .filter-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #495057;
        }
        .report-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }
        .btn-group-actions {
            gap: 10px;
        }
        .invoice-date {
            font-size: 0.9em;
            color: #6c757d;
        }
        .sales-amount {
            font-weight: 600;
            color: #28a745;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Reports @endslot
        @slot('title') Agent Performance Report @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="bx bx-filter-alt me-2"></i>Filter
                </div>
                
                <form method="GET" action="{{ route('admin.reports.agent_performance') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agents</label>
                            <select class="form-select" name="agent_id">
                                <option value="">Please Select</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }} ({{ $agent->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Invoice Date Ranges</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="Invoice Date Ranges">
                                <span class="input-group-text">to</span>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" placeholder="Invoice Date Ranges">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="btn-group-actions d-flex">
                                <button type="button" class="btn btn-danger" onclick="resetFilters()">
                                    <i class="bx bx-reset me-1"></i>Reset Filter
                                </button>
                                <button type="submit" class="btn btn-info">
                                    <i class="bx bx-search me-1"></i>Filter Search
                                </button>
                                <a href="{{ route('admin.reports.agent_performance.export', request()->all()) }}" class="btn btn-success">
                                    <i class="bx bx-download me-1"></i>Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Table -->
            <div class="report-table">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>INVOICE DATE</th>
                                <th>AGENT NAME</th>
                                <th>AGENT EMAIL</th>
                                <th>NUMBER OF CARS SOLD</th>
                                <th>TOTAL SALES AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agentData as $index => $row)
                                <tr>
                                    <td>{{ $agentData->firstItem() + $index }}</td>
                                    <td>
                                        @if($row->min_invoice_date && $row->max_invoice_date)
                                            @php
                                                $minDate = \Carbon\Carbon::parse($row->min_invoice_date)->format('Y-m-d');
                                                $maxDate = \Carbon\Carbon::parse($row->max_invoice_date)->format('Y-m-d');
                                            @endphp
                                            <span class="invoice-date">
                                                {{ $minDate === $maxDate ? $minDate : $minDate . ' - ' . $maxDate }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->agent_name ?? 'N/A' }}</td>
                                    <td>{{ $row->agent_email ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $row->number_of_cars_sold }}</span>
                                    </td>
                                    <td>
                                        <span class="sales-amount">${{ number_format($row->total_sales_amount ?? 0, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-search-alt-2 font-size-24 mb-2 d-block"></i>
                                            No agent performance data found for the selected criteria
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($agentData->hasPages())
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-0">
                                    Showing {{ $agentData->firstItem() }} to {{ $agentData->lastItem() }} of {{ $agentData->total() }} results
                                </p>
                            </div>
                            <div class="col-md-6">
                                {{ $agentData->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        function resetFilters() {
            window.location.href = '{{ route("admin.reports.agent_performance") }}';
        }
    </script>
@endsection
