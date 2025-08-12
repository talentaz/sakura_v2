@extends('admin.layouts.master')
@section('title') Invoice #{{ $invoice->id }} @endsection
@section('css')
    <link href="{{ URL::asset('/assets/admin/pages/inquiry/style.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Invoice Management @endslot
        @slot('title') Invoice #{{ $invoice->id }} @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.inquiry.generatePDF', $invoice->inquiry_id) }}" class="btn btn-danger btn-sm" target="_blank">
                        <i class="mdi mdi-file-pdf-outline"></i> Quotation PDF
                    </a>
                    <a href="{{ route('admin.invoice.generatePDF', $invoice->id) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="mdi mdi-receipt"></i> View Invoice
                    </a>
                </div>
                <a href="{{ route('admin.invoice.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to List
                </a>
            </div>

            <div class="row">
                <!-- Customer Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">Customer Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Invoice Number</div>
                                <div class="col-lg-7">{{ $invoice->invoice_number ?? 'SM-' . ($invoice->inquiry->vehicle->stock_no ?? $invoice->id) }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Sales Agent</div>
                                <div class="col-lg-7">
                                    {{ $invoice->inquiry->salesAgent ?? $invoice->inquiry->salesAgent->name ?? 'N/A'}}
                                    <br><small class="text-muted">({{ $invoice->salesAgent ?? $invoice->salesAgent->email ?? 'N/A'}})</small>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Issued Date</div>
                                <div class="col-lg-7">{{ $invoice->created_at ? $invoice->created_at->format('M d, Y') : 'Aug 06, 2025' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Due Date</div>
                                <div class="col-lg-7">{{ $invoice->created_at ? $invoice->created_at->addDays(3)->format('M d, Y') : 'Aug 09, 2025' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Customer ID</div>
                                <div class="col-lg-7">#{{ $invoice->inquiry->customer_id ?? $invoice->inquiry->customer_id }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Customer Name</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->customer->name ?? $invoice->inquiry->inqu_name ?? 'Martin Mulenga' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Email Address</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->customer->email ?? $invoice->inquiry->inqu_email ?? 'martin.mulenga5@gmail.com' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Mobile Number</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->inqu_mobile ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Address</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->inqu_address ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">City</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->inqu_city ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Country</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->inquiryCountry->country }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">Vehicle Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Stock No.</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->vehicle->stock_no}}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Name</div>
                                <div class="col-lg-7">{{ $invoice->inquiry->vehicle_name}}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <strong>Vehicle Image</strong>
                                    <div class="vehicle-image-container mt-2">
                                        @if($invoice->vehicleImages)
                                            <img src="{{ asset('uploads/vehicle/thumb/' . $invoice->vehicleImages->image) }}"
                                                 alt="Vehicle Image" class="img-fluid" style="max-width: 150px; border-radius: 5px;">
                                        @else
                                            <div class="border d-flex align-items-center justify-content-center" 
                                                 style="height: 100px; background-color: #f8f9fa;">
                                                <span class="text-muted">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Status</div>
                                <div class="col-lg-7">
                                    <form action="{{ route('admin.inquiry.update', $invoice->inquiry->id) }}" method="POST" id="vehicle-status-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_type" value="vehicle_status">
                                        <div class="mb-2">
                                            <select class="form-control" name="vehicle_status" id="vehicle-status-select">
                                                @foreach($vehicle_status as $status)
                                                    <option value="{{ $status }}" {{ $invoice->inquiry->vehicle_status === $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm" id="vehicle-status-btn" style="{{ $invoice->inquiry->vehicle_status === 'Reserved' ? 'display: none;' : '' }}">
                                            <i class="mdi mdi-check"></i> Update
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row mb-2" id="expiry-date-section" style="{{ $invoice->inquiry->vehicle_status === 'Reserved' ? '' : 'display: none;' }}">
                                <div class="col-lg-5 font-weight-bold">Reserved Expiry Date <span class="text-danger">*</span></div>
                                <div class="col-lg-7">
                                    <form action="{{ route('admin.inquiry.update', $invoice->inquiry->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_type" value="reserved_expiry_date">
                                        <div class="mb-2">
                                            <input type="datetime-local" class="form-control" name="reserved_expiry_date"
                                                   value="{{ $invoice->inquiry->reserved_expiry_date ? $invoice->inquiry->reserved_expiry_date->format('Y-m-d\TH:i') : '' }}" 
                                                   placeholder="Reserved Expiry Date" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-check"></i> Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">Price Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="price-notes">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>FOB Price</span>
                                    <span>${{ number_format($invoice->inquiry->fob_price ?? 0, 0) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Freight Fee</span>
                                    <span>${{ number_format($invoice->inquiry->freight_fee ?? 0, 0) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Insurance Fee</span>
                                    <span>${{ number_format($invoice->inquiry->insurance ?? 0, 0) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Inspection Fee</span>
                                    <span>${{ number_format($invoice->inquiry->inspection ?? 0, 0) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount</span>
                                    <span>({{ number_format($invoice->inquiry->discount ?? 0, 0) }})</span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mb-2 fw-bold">
                                    <span>Total Payable</span>
                                    <span>${{ number_format($invoice->inquiry->total_price ?? 0, 0) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2 text-success fw-bold">
                                    <span>Total Paid</span>
                                    <span>${{ number_format($invoice->total_paid ?? 0, 0) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between text-danger fw-bold">
                                    <span>Amount Due</span>
                                    <span>${{ number_format($invoice->amount_due ?? 0, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Management Section -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Billing Management</h4>
                                @if(!$invoice->verified_at && !$invoice->verified_by)
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm {{ $invoice->is_fully_paid ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#createBillingModal">
                                        <i class="mdi mdi-plus"></i> Create New Billing
                                    </button>
                                    <form action="{{ route('admin.invoice.billing.verify', $invoice->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm {{ $invoice->is_fully_paid ? '' : 'disabled' }}"
                                                onclick="return confirm('Are you sure you want to verify all billing records?')">
                                            <i class="mdi mdi-check"></i> Verify Billing
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label for="billing-entries-select">Show</label>
                                    <select id="billing-entries-select" class="form-select d-inline-block w-auto">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>entries</span>
                                </div>
                                <div>
                                    <label for="billing-search">Search:</label>
                                    <input type="text" id="billing-search" class="form-control d-inline-block w-auto" style="width: 200px;">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            @if(!$invoice->verified_at)
                                            <th>ACTIONS</th>
                                            @endif
                                            <th>PAID AMOUNT</th>
                                            <th>DESCRIPTION</th>
                                            <th>CREATED BY</th>
                                            <th>CREATED AT</th>
                                            <th>VERIFIED BY</th>
                                            <th>VERIFIED AT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoice->billingHistory as $billing)
                                        <tr>
                                            <td>{{ $billing->id }}</td>
                                            @if(!$invoice->verified_at)
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning edit-billing-btn"
                                                            data-id="{{ $billing->id }}"
                                                            data-paid-amount="{{ $billing->paid_amount }}"
                                                            data-description="{{ $billing->description }}"
                                                            title="Edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger delete-billing-btn"
                                                            data-id="{{ $billing->id }}" title="Delete">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            @endif
                                            <td>${{ number_format($billing->paid_amount) }}</td>
                                            <td>{{ $billing->description ?? 'N/A' }}</td>
                                            <td>{{ $billing->creator->name ?? 'N/A' }}</td>
                                            <td>{{ $billing->created_at ? $billing->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                            <td>{{ $billing->verifier->name ?? 'N/A' }}</td>
                                            <td>{{ $billing->verified_at ? $billing->verified_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No billing records found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="text-muted">Showing 0 to 0 of 0 entries</span>
                                </div>
                                <div>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item disabled">
                                                <span class="page-link">Previous</span>
                                            </li>
                                            <li class="page-item disabled">
                                                <span class="page-link">Next</span>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Billing Modal -->
    <div class="modal fade" id="createBillingModal" tabindex="-1" aria-labelledby="createBillingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.invoice.billing.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createBillingModalLabel">Create New Billing</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="paid_amount" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="paid_amount" name="paid_amount"
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Enter billing description..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Billing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Billing Modal -->
    <div class="modal fade" id="editBillingModal" tabindex="-1" aria-labelledby="editBillingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBillingModalLabel">Edit Billing Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editBillingForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_paid_amount" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_paid_amount" name="paid_amount" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Billing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Billing Modal -->
    <div class="modal fade" id="deleteBillingModal" tabindex="-1" aria-labelledby="deleteBillingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteBillingModalLabel">Delete Billing Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this billing record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteBillingForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
$(document).ready(function() {
    let editBillingId = null;
    let deleteBillingId = null;

    // Edit billing modal
    $(document).on('click', '.edit-billing-btn', function() {
        editBillingId = $(this).data('id');
        const paidAmount = $(this).data('paid-amount');
        const description = $(this).data('description');
        
        $('#edit_paid_amount').val(paidAmount);
        $('#edit_description').val(description);
        $('#editBillingForm').attr('action', `/admin/invoice/billing/${editBillingId}`);
        
        $('#editBillingModal').modal('show');
    });

    // Delete billing modal
    $(document).on('click', '.delete-billing-btn', function() {
        deleteBillingId = $(this).data('id');
        $('#deleteBillingForm').attr('action', `/admin/invoice/billing/${deleteBillingId}`);
        $('#deleteBillingModal').modal('show');
    });

    // Show toastr messages
    @if(session('success'))
        toastr["success"]("{{ session('success') }}");
    @endif
    
    @if(session('error'))
        toastr["error"]("{{ session('error') }}");
    @endif

    // Show validation errors
    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr["error"]("{{ $error }}");
        @endforeach
    @endif

    $('#vehicle-status-select').on('change', function() {
        const expirySection = $('#expiry-date-section');
        const statusBtn = $('#vehicle-status-btn');
        if ($(this).val() === 'Reserved') {
            expirySection.show();
            statusBtn.hide();
        } else {
            expirySection.hide();
            statusBtn.show();
        }
    });
});
</script>
@endsection









