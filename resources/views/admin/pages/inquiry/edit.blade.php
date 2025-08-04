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
                    <a href="{{ route('admin.inquiry.generateInvoice', $inquiry->id) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="mdi mdi-receipt"></i> View Invoice
                    </a>
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
                                    <div class="update-form">
                                        <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_type" value="sales_agent">
                                            <div class="d-flex align-items-center">
                                                <select class="form-select form-select-sm me-2" name="sales_agent" style="flex: 1;">
                                                    <option value="">Select Agent</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ $inquiry->sales_agent == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }} ({{ $user->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-update">
                                                    <i class="mdi mdi-check"></i> Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- @if($inquiry->sales_agent)
                                        <small class="text-muted">Current: {{ $inquiry->sales_agent }}</small>
                                    @endif -->
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Date Submitted</div>
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
                                <div class="col-lg-7">{{ $inquiry->inqu_country }}</div>
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
                                <div class="col-lg-7">#3652</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Name</div>
                                <div class="col-lg-7">Mitsubishi Rosa <a href="#" class="text-primary"><i class="mdi mdi-open-in-new"></i></a></div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Image</div>
                                <div class="col-lg-7">
                                    <div class="vehicle-image-container">
                                        @if($inquiry->vehicle_image)
                                            <img src="{{ asset('storage/' . $inquiry->vehicle_image) }}" class="img-fluid rounded border" style="max-width: 150px; max-height: 100px; object-fit: cover;" alt="Vehicle Image">
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
                                    <div class="update-form">
                                        <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_type" value="vehicle_status">
                                            <div class="d-flex align-items-center">
                                                <select class="form-select form-select-sm me-2" name="vehicle_status" id="vehicle-status-select" style="flex: 1;">
                                                    <option value="Reserved" {{ $inquiry->vehicle_status === 'Reserved' ? 'selected' : '' }}>Reserved</option>
                                                    <option value="Ready to Ship" {{ $inquiry->vehicle_status === 'Ready to Ship' ? 'selected' : '' }}>Ready to Ship</option>
                                                    <option value="Open" {{ $inquiry->vehicle_status === 'Open' ? 'selected' : '' }}>Open</option>
                                                    <option value="Inactive" {{ $inquiry->vehicle_status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                <button type="submit" class="btn btn-update">
                                                    <i class="mdi mdi-check"></i> Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <span class="badge {{ $inquiry->vehicle_status === 'Reserved' ? 'bg-warning' : ($inquiry->vehicle_status === 'Ready to Ship' ? 'bg-success' : ($inquiry->vehicle_status === 'Open' ? 'bg-primary' : 'bg-secondary')) }}">
                                        {{ $inquiry->vehicle_status ?: 'Reserved' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-2" id="expiry-date-section" style="{{ $inquiry->vehicle_status === 'Reserved' ? '' : 'display: none;' }}">
                                <div class="col-lg-5 font-weight-bold">Reserved Expiry Date <span class="text-danger">*</span></div>
                                <div class="col-lg-7">
                                    <div class="update-form">
                                        <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_type" value="reserved_expiry_date">
                                            <div class="d-flex align-items-center">
                                                <input type="datetime-local" class="form-control form-control-sm me-2" name="reserved_expiry_date"
                                                       value="{{ $inquiry->reserved_expiry_date ? $inquiry->reserved_expiry_date->format('Y-m-d\TH:i') : '' }}" style="flex: 1;">
                                                <button type="submit" class="btn btn-update">
                                                    <i class="mdi mdi-check"></i> Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    @if($inquiry->reserved_expiry_date)
                                        <small class="text-muted">Current: {{ $inquiry->reserved_expiry_date->format('M d, Y H:i') }}</small>
                                    @else
                                        <small class="text-muted">Jul 23, 2025 12:00 PM</small>
                                    @endif
                                </div>
                            </div>

                            <h6 class="text-primary mb-3 mt-4">Shipping Details</h6>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Final Country</div>
                                <div class="col-lg-7">Zambia</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Port Name</div>
                                <div class="col-lg-7">Dar es Salaam</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Type of Purchase</div>
                                <div class="col-lg-7">C&F</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Insurance</div>
                                <div class="col-lg-7">
                                    <span class="badge {{ $inquiry->insurance === 'Yes' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $inquiry->insurance ?: 'No' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Inspection</div>
                                <div class="col-lg-7">
                                    <span class="badge {{ $inquiry->inspection === 'Yes' ? 'bg-success' : 'bg-danger' }}">
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
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">Price Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">FOB Price</div>
                                <div class="col-lg-7">$7,197</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Freight Fee</div>
                                <div class="col-lg-7">$4,733</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Insurance Fee</div>
                                <div class="col-lg-7">$0</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Inspection Fee</div>
                                <div class="col-lg-7">$0</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">- Discount</div>
                                <div class="col-lg-7">$230</div>
                            </div>

                            <hr>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold h5">Total</div>
                                <div class="col-lg-7 h4 text-primary font-weight-bold">$11,700</div>
                            </div>

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
    // Handle vehicle status change to show/hide expiry date section
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelects = document.querySelectorAll('#vehicle-status-select');
        statusSelects.forEach(function(select) {
            select.addEventListener('change', function() {
                const expirySection = document.getElementById('expiry-date-section');
                if (this.value === 'Reserved') {
                    expirySection.style.display = 'block';
                } else {
                    expirySection.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection

