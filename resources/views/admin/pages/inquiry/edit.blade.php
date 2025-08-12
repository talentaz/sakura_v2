@extends('admin.layouts.master')
@section('title') Inquiry #{{ $inquiry->id }} @endsection
@section('css')
    <link href="{{ URL::asset('/assets/admin/pages/inquiry/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .form-control:disabled {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
            opacity: 1;
        }

        .input-group-text {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .vehicle-image-container .border {
            border: 2px dashed #dee2e6 !important;
        }

        .price-notes {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }

        .card-header.bg-light {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6;
        }

        .update-form {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ffeaa7;
            margin-bottom: 10px;
        }

        .btn-update {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }

        .btn-update:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Inquiry Management @endslot
        @slot('title') Inquiry #{{ $inquiry->id }} @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.inquiry.generatePDF', $inquiry->id) }}" class="btn btn-danger btn-sm" target="_blank">
                        <i class="mdi mdi-file-pdf-outline"></i> Quotation PDF
                    </a>
                    @if($inquiry->invoice)
                        <a href="{{ route('admin.inquiry.generateInvoice', $inquiry->id) }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="mdi mdi-receipt"></i> View Invoice
                        </a>
                        <a href="{{ route('admin.invoice.edit', $inquiry->id) }}" class="btn btn-info btn-sm">
                            <i class="mdi mdi-file-document-edit"></i> Manage Billing
                        </a>
                    @else
                        <form action="{{ route('admin.inquiry.createInvoice', $inquiry->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to create an invoice for this inquiry?')">
                                <i class="mdi mdi-receipt"></i> Proceed to Invoice
                            </button>
                        </form>
                    @endif
                </div>
                <a href="{{ route('admin.inquiry.index') }}" class="btn btn-secondary">
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
                                <div class="col-lg-5 font-weight-bold">Inquiry ID</div>
                                <div class="col-lg-7">#{{ $inquiry->id }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Sales Agent</div>
                                <div class="col-lg-7">
                                    <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_type" value="sales_agent">
                                        <div class="mb-2">
                                            <select class="form-select" name="sales_agent">
                                                <option value="">Select Agent</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $inquiry->sales_agent == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-check"></i> Update
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Created At</div>
                                <div class="col-lg-7">{{ $inquiry->created_at->format('M d, Y H:i') }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Customer ID</div>
                                <div class="col-lg-7">{{ $inquiry->customer_id ?: $inquiry->id }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Customer Name</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_name }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Email Address</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_email }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Mobile Number</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_mobile }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Address</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_address ?: '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">City</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_city ?: '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Country</div>
                                <div class="col-lg-7">{{ $inquiry->inquiryCountry->country ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Message</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_comment ?: '-' }}</div>
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
                                <div class="col-lg-7">#{{ $inquiry->stock_no ?? $inquiry->vehicle->stock_no ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Name</div>
                                <div class="col-lg-7">{{ $inquiry->vehicle_name ?? $inquiry->vehicle->name ?? 'N/A' }} <a href="{{ $inquiry->site_url }}" target = "_blank" class="text-primary"><i class="mdi mdi-open-in-new"></i></a></div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Image</div>
                                <div class="col-lg-7">
                                    <div class="vehicle-image-container">
                                        @if($inquiry->vehicle && $inquiry->vehicle->vehicleImages->count() > 0)
                                            <img src="{{ asset('uploads/vehicle/thumb/' . $inquiry->vehicle->vehicleImages[0]->image) }}" class="img-fluid rounded border" style="max-width: 150px; max-height: 100px; object-fit: cover;" alt="Vehicle Image">
                                        @else
                                            <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="width: 150px; height: 100px;">
                                                <div class="text-center text-muted">
                                                    <i class="mdi mdi-car mdi-24px"></i>
                                                    <p class="mb-0 small">No Image</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Status</div>
                                <div class="col-lg-7">
                                    <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST" id="vehicle-status-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_type" value="vehicle_status">
                                        <div class="mb-2">
                                            <select class="form-control" name="vehicle_status" id="vehicle-status-select">
                                                @foreach($vehicle_status as $status)
                                                    <option value="{{ $status }}" {{ $inquiry->vehicle_status === $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm" id="vehicle-status-btn" style="{{ $inquiry->vehicle_status === 'Reserved' ? 'display: none;' : '' }}">
                                            <i class="mdi mdi-check"></i> Update
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row mb-2" id="expiry-date-section" style="{{ $inquiry->vehicle_status === 'Reserved' ? '' : 'display: none;' }}">
                                <div class="col-lg-5 font-weight-bold">Reserved Expiry Date <span class="text-danger">*</span></div>
                                <div class="col-lg-7">
                                    <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_type" value="reserved_expiry_date">
                                        <div class="mb-2">
                                            <input type="datetime-local" class="form-control" name="reserved_expiry_date"
                                                   value="{{ $inquiry->reserved_expiry_date ? $inquiry->reserved_expiry_date->format('Y-m-d\TH:i') : '' }}" 
                                                   placeholder="Reserved Expiry Date" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-check"></i> Update
                                        </button>
                                    </form>
                                    @if($inquiry->reserved_expiry_date)
                                        <small class="text-muted">Current: {{ $inquiry->reserved_expiry_date->format('M d, Y H:i') }}</small>
                                    @endif
                                </div>
                            </div>

                            <h6 class="text-primary mb-3 mt-4">Shipping Details</h6>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Final Country</div>
                                <div class="col-lg-7">{{ $inquiry->inquiryCountry->country ?? $inquiry->final_country ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Port Name</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_port ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Type of Purchase</div>
                                <div class="col-lg-7">{{ $inquiry->type_of_purchase ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Insurance</div>
                                <div class="col-lg-7">
                                    <span class="badge {{ $inquiry->insurance ? 'bg-success' : 'bg-danger' }}">
                                        {{ $inquiry->insurance ?: 'No' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Inspection</div>
                                <div class="col-lg-7">
                                    <span class="badge {{ $inquiry->inspection  ? 'bg-success' : 'bg-danger' }}">
                                        {{ $inquiry->inspection ?: 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Price Breakdown</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted">FOB Price</label>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control text-end" name="fob_price" value="{{ $inquiry->fob_price ?? 0 }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted">Freight Fee</label>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control text-end" name="freight_fee" value="{{ $inquiry->freight_fee ?? 0 }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted">Insurance Fee</label>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control text-end" name="insurance" value="{{ $inquiry->insurance ?? 0 }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted">Inspection Fee</label>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control text-end" name="inspection" value="{{ $inquiry->inspection ?? 0 }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted">- Discount</label>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control text-end" name="discount" value="{{ $inquiry->discount ?? 0 }}" onchange="calculateTotal()">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="update_type" value="total_discount">
                                <input type="hidden" name="total_price" id="hidden-total" value="{{ ($inquiry->fob_price ?? 0) + ($inquiry->freight_fee ?? 0) + ($inquiry->insurance ?? 0) + ($inquiry->inspection ?? 0) - ($inquiry->discount ?? 0) }}">
                                <input type="hidden" name="discount" id="hidden-discount" value="{{ $inquiry->discount ?? 0 }}">
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Total</label>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="h5 text-primary fw-bold" id="total-display">
                                            ${{ number_format(($inquiry->fob_price ?? 0) + ($inquiry->freight_fee ?? 0) + ($inquiry->insurance ?? 0) + ($inquiry->inspection ?? 0) - ($inquiry->discount ?? 0), 0) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Update
                                    </button>
                                </div>
                            </form>

                             <div class="mt-4 text-muted small price-notes">
                                <p class="mb-1">** FOB Fee as of the date of requested quotation: Jul 20, 2025</p>
                                <p class="mb-1">** Freight Fee = Port Fee + Vehicle Length (m) x Vehicle Width (m) x Vehicle Height (m)</p>
                                <p class="mb-1">** Total = FOB Price + Freight Fee + Insurance Fee (if applicable) + Inspection Fee (if applicable) - Discount</p>
                                <p class="mb-0">** The total is rounded up if the decimal is 0.5 or higher, and rounded down if it's less than 0.5 to eliminate cents.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
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
    
    // Calculate total when discount changes
    $('input[name="discount"]').on('input change', function() {
        calculateTotal();
    });
});

function calculateTotal() {
    const fobPrice = parseFloat($('input[name="fob_price"]').val()) || 0;
    const freightFee = parseFloat($('input[name="freight_fee"]').val()) || 0;
    const insurance = parseFloat($('input[name="insurance"]').val()) || 0;
    const inspection = parseFloat($('input[name="inspection"]').val()) || 0;
    const discount = parseFloat($('input[name="discount"]').val()) || 0;
    
    const total = fobPrice + freightFee + insurance + inspection - discount;
    
    $('#total-display').text('$' + Math.round(total).toLocaleString());
    
    // Update hidden fields for form submission
    $('#hidden-total').val(Math.round(total));
    $('#hidden-discount').val(discount);
}

</script>
@endsection













