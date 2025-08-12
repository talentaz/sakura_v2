@extends('admin.layouts.master')

@section('title')
    Invoice List
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/admin/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@php
    // Helper function to get status badge class
    function getStatusBadgeClass($status) {
        switch($status) {
            case 'Reserved': return 'bg-warning';
            case 'Ready to Ship': return 'bg-primary';
            case 'Open': return 'bg-success';
            case 'Inactive': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }
@endphp

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            Invoice List
        @endslot
    @endcomponent

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Invoice List</h4>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <label for="entries-select">Show</label>
                                <select id="entries-select" class="form-select d-inline-block w-auto" onchange="changePerPage()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span>entries</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Actions</th>
                                    <th>Customer Name</th>
                                    <th>Vehicle Name</th>
                                    <th>Stock Number</th>
                                    <th>Vehicle Status</th>
                                    <th>Total</th>
                                    <th>Payment Details</th>
                                    <th>Sales Agent</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->inquiry->stock_no ?? 'SM-' . $invoice->inquiry->stock_no }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.invoice.edit', $invoice->id) }}" class="btn btn-sm btn-primary" title="View Invoice">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.invoice.generatePDF', $invoice->id) }}" class="btn btn-sm btn-success" title="Download PDF" target="_blank">
                                                <i class="mdi mdi-file-pdf-outline"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $invoice->inquiry->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->inquiry->vehicle_name ?? 'N/A' }}</td>
                                    <td>#{{ $invoice->inquiry->stock_no ?? 'N/A'}}</td>
                                    
                                    <td>
                                        <span class="badge {{ getStatusBadgeClass($invoice->inquiry->vehicle_status ?? 'Open') }}">
                                            {{ $invoice->inquiry->status ?? 'Open' }}
                                        </span>
                                        @if($invoice->inquiry->reserved_expiry_date)
                                        <p><strong>Reserved Expiry Date:</strong> <span>{{ $invoice->inquiry->reserved_expiry_date->format('M d, Y') }}</span></p>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($invoice->inquiry->total_price))
                                            ${{ number_format($invoice->inquiry->total_price) }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <strong>Payment Status:</strong>
                                            <span class="badge {{ $invoice->payment_status == 'Paid' ? 'bg-success' : ($invoice->payment_status == 'Partially Paid' ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $invoice->payment_status }}
                                            </span>
                                        </div>
                                        <div class="mb-1">
                                            <strong>Total Paid:</strong> ${{ number_format($invoice->total_paid, 2) }}
                                        </div>
                                        <div class="mb-1">
                                            <strong>Amount Due:</strong> ${{ number_format($invoice->amount_due, 2) }}
                                        </div>
                                        <div class="mb-1">
                                            <strong>Billing Verified:</strong>
                                            <span class="badge {{ $invoice->verified_at ? 'bg-success' : 'bg-danger' }}">
                                                {{ $invoice->verified_at ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                        @if($invoice->verified_by)
                                        <div class="mb-1">
                                            <strong>Verified By:</strong> {{ $invoice->verifier->name ?? 'N/A' }}
                                        </div>
                                        @endif
                                        @if($invoice->verified_at)
                                        <div>
                                            <strong>Verified At:</strong> {{ $invoice->verified_at->format('M d, Y') }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ $invoice->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->created_at ? $invoice->created_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No invoices found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($invoices->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted">
                                    Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} entries
                                </p>
                            </div>
                            <div>
                                {{ $invoices->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this invoice? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    
    // Handle delete button click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            deleteId = e.target.closest('.delete-btn').getAttribute('data-id');
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    });

    function changePerPage() {
        const perPage = document.getElementById('entries-select').value;
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset to first page when changing per_page
        window.location.href = url.toString();
    }
</script>
@endsection




