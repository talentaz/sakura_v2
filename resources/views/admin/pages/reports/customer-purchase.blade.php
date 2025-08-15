@extends('admin.layouts.master')

@section('title') Customer Purchase Report @endsection

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
        .completed-date {
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Reports @endslot
        @slot('title') Customer Purchase Report @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="bx bx-filter-alt me-2"></i>Filter
                </div>
                
                <form method="GET" action="{{ route('admin.reports.customer_purchase') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by name or email">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Completed Date</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="From Date">
                                <span class="input-group-text">to</span>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" placeholder="To Date">
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
                                <a href="{{ route('admin.reports.customer_purchase.export', request()->all()) }}" class="btn btn-success">
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
                                <th>USER NAME</th>
                                <th>USER EMAIL</th>
                                <th>NUMBER OF CAR PURCHASED</th>
                                <th>COMPLETED DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerData as $index => $row)
                                <tr>
                                    <td>{{ $customerData->firstItem() + $index }}</td>
                                    <td>{{ $row->user_name ?? 'N/A' }}</td>
                                    <td>{{ $row->user_email ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $row->number_of_car_purchased }}</span>
                                    </td>
                                    <td>
                                        @if($row->min_completed_date && $row->max_completed_date)
                                            @php
                                                $minDate = \Carbon\Carbon::parse($row->min_completed_date)->format('Y-m-d');
                                                $maxDate = \Carbon\Carbon::parse($row->max_completed_date)->format('Y-m-d');
                                            @endphp
                                            <span class="completed-date">
                                                {{ $minDate === $maxDate ? $minDate : $minDate . ' - ' . $maxDate }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-search-alt-2 font-size-24 mb-2 d-block"></i>
                                            No customer purchase data found for the selected criteria
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($customerData->hasPages())
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-0">
                                    Showing {{ $customerData->firstItem() }} to {{ $customerData->lastItem() }} of {{ $customerData->total() }} results
                                </p>
                            </div>
                            <div class="col-md-6">
                                {{ $customerData->appends(request()->query())->links() }}
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
            window.location.href = '{{ route("admin.reports.customer_purchase") }}';
        }
    </script>
@endsection
