@extends('admin.layouts.master')
@section('title') Inquiries @endsection
@section('css')
    <link href="{{ URL::asset('/assets/admin/pages/inquiry/style.css') }}" rel="stylesheet" type="text/css" />
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
        @slot('li_1') Inquiry Management @endslot
        @slot('title') Inquiries @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Inquiry List</h4>
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ACTIONS</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>VEHICLE NAME</th>
                                    <th>STOCK NUMBER</th>
                                    <th>VEHICLE STATUS</th>
                                    <th>TOTAL</th>
                                    <th>DATE SUBMITTED</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inquiries as $inquiry)
                                <tr>
                                    <td>{{ $inquiry->id ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.inquiry.edit', $inquiry->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.inquiry.generatePDF', $inquiry->id) }}" class="btn btn-sm btn-danger" target="_blank" title="View Quotation PDF">
                                                <i class="mdi mdi-file-pdf-outline"></i>
                                            </a>
                                            @if($inquiry->invoice)
                                                <a href="{{ route('admin.invoice.generatePDF', $inquiry->invoice->id) }}" class="btn btn-sm btn-info" target="_blank" title="View Invoice">
                                                    <i class="mdi mdi-receipt"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $inquiry->inqu_name ?? 'N/A' }}</td>
                                    <td>{{ $inquiry->vehicle_name ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($inquiry->stock_no) && $inquiry->stock_no)
                                            <a href="{{ $inquiry->site_url ?? '#' }}" target="_blank" class="text-primary">
                                                {{ $inquiry->stock_no }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ getStatusBadgeClass($inquiry->vehicle_status ?? 'Open') }} status-badge"
                                              data-id="{{ $inquiry->id ?? '' }}"
                                              data-status="{{ $inquiry->vehicle_status ?? 'Open' }}"
                                              style="cursor: pointer;">
                                            {{ $inquiry->vehicle_status ?? 'Open' }}
                                        </span>
                                    </td>
                                    <td>${{ $inquiry->total_price ?? '0' }}</td>
                                    <td>{{ isset($inquiry->created_at) ? $inquiry->created_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No inquiries found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($inquiries->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted">
                                    Showing {{ $inquiries->firstItem() }} to {{ $inquiries->lastItem() }} of {{ $inquiries->total() }} entries
                                </p>
                            </div>
                            <div>
                                {{ $inquiries->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Change Vehicle Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        <input type="hidden" id="inquiry-id" name="inquiry_id">
                        <div class="mb-3">
                            <label for="vehicle-status" class="form-label">Vehicle Status</label>
                            <select class="form-select" id="vehicle-status" name="status" required>
                                @php
                                    $user = auth()->user();
                                    $userRole = $user->role->slug ?? '';

                                    // Define allowed statuses for sales agents
                                    $salesAgentStatuses = ['Open', 'Reserved', 'Prepare for Shipment', 'Inactive'];

                                    // All available statuses
                                    $allStatuses = ['Open', 'Reserved', 'Prepare for Shipment', 'Ready to Ship', 'Shipped', 'Completed', 'Inactive'];

                                    // Choose which statuses to show based on role
                                    $allowedStatuses = ($userRole === 'sales_agent') ? $salesAgentStatuses : $allStatuses;
                                @endphp

                                @foreach($allowedStatuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="expiry-date-section" style="display: none;">
                            <label for="reserved-expiry-date" class="form-label">Reserved Expiry Date</label>
                            <input type="date" class="form-control" id="reserved-expiry-date" name="reserved_expiry_date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-status">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    function changePerPage() {
        const perPage = document.getElementById('entries-select').value;
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset to first page when changing per_page
        window.location.href = url.toString();
    }

    $(document).ready(function() {
        // Handle status badge clicks
        $(document).on('click', '.status-badge', function(e) {
            e.preventDefault();
            const inquiryId = $(this).data('id');
            const currentStatus = $(this).data('status');

            // Set the inquiry ID in the modal
            $('#inquiry-id').val(inquiryId);

            // Set the current status as selected
            $('#vehicle-status').val(currentStatus);

            // Show/hide expiry date section based on status
            if (currentStatus === 'Reserved') {
                $('#expiry-date-section').show();
            } else {
                $('#expiry-date-section').hide();
            }

            // Show the modal
            $('#statusModal').modal('show');
        });

        // Handle status dropdown change
        $('#vehicle-status').on('change', function() {
            const selectedStatus = $(this).val();
            if (selectedStatus === 'Reserved') {
                $('#expiry-date-section').show();
            } else {
                $('#expiry-date-section').hide();
                $('#reserved-expiry-date').val('');
            }
        });

        // Handle save status button
        $('#save-status').on('click', function() {
            const inquiryId = $('#inquiry-id').val();
            const status = $('#vehicle-status').val();
            const expiryDate = $('#reserved-expiry-date').val();

            // Validate required fields
            if (!status) {
                toastr.error('Please select a status');
                return;
            }

            if (status === 'Reserved' && !expiryDate) {
                toastr.error('Please select an expiry date for Reserved status');
                return;
            }

            // Send AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("admin.inquiry.updateStatus") }}',
                method: 'POST',
                data: {
                    id: inquiryId,
                    status: status,
                    reserved_expiry_date: expiryDate
                },
                success: function(response) {
                    if (response.result) {
                        toastr.success(response.message || 'Status updated successfully');
                        $('#statusModal').modal('hide');
                        location.reload(); // Reload to show updated status
                    } else {
                        toastr.error(response.message || 'Failed to update status');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Error updating status';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                }
            });
        });
    });
</script>
@endsection