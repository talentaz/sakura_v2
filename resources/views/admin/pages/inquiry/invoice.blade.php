@extends('admin.layouts.master')
@section('title') Generate Invoice - Inquiry #{{ $inquiry->id }} @endsection
@section('css')
    <link href="{{ URL::asset('/assets/admin/pages/inquiry/style.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Inquiry Management @endslot
        @slot('title') Generate Invoice - Inquiry #{{ $inquiry->id }} @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.inquiry.generatePDF', $inquiry->id) }}" class="btn btn-danger btn-sm" target="_blank">
                        <i class="mdi mdi-file-pdf-outline"></i> View Quotation PDF
                    </a>
                </div>
                <a href="{{ route('admin.inquiry.edit', $inquiry->id) }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Inquiry
                </a>
            </div>

            @if($inquiry->invoice)
                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    An invoice already exists for this inquiry. 
                    <a href="{{ route('admin.invoice.edit', $inquiry->invoice->id) }}" class="btn btn-sm btn-primary ms-2">
                        <i class="mdi mdi-receipt"></i> View Invoice
                    </a>
                </div>
            @endif

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
                                <div class="col-lg-5 font-weight-bold">Customer Name</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_name ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Email</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_email ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Phone</div>
                                <div class="col-lg-7">{{ $inquiry->inqu_phone ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Country</div>
                                <div class="col-lg-7">{{ $inquiry->inquiryCountry->name ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Sales Agent</div>
                                <div class="col-lg-7">
                                    {{ $inquiry->salesAgent->name ?? 'Not Assigned' }}
                                    @if($inquiry->salesAgent)
                                        <br><small class="text-muted">({{ $inquiry->salesAgent->email }})</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-success">Vehicle Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Vehicle Name</div>
                                <div class="col-lg-7">{{ $inquiry->vehicle_name ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Stock No</div>
                                <div class="col-lg-7">
                                    @if($inquiry->stock_no)
                                        <a href="{{ $inquiry->site_url ?? '#' }}" target="_blank" class="text-primary">
                                            {{ $inquiry->stock_no }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-5 font-weight-bold">Status</div>
                                <div class="col-lg-7">
                                    <span class="badge bg-primary">{{ $inquiry->vehicle_status ?? 'Open' }}</span>
                                </div>
                            </div>

                            @if($inquiry->vehicle)
                                <div class="row mb-2">
                                    <div class="col-lg-5 font-weight-bold">Year</div>
                                    <div class="col-lg-7">{{ $inquiry->vehicle->year ?? 'N/A' }}</div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-5 font-weight-bold">Mileage</div>
                                    <div class="col-lg-7">{{ $inquiry->vehicle->mileage ?? 'N/A' }} km</div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-5 font-weight-bold">Fuel Type</div>
                                    <div class="col-lg-7">{{ $inquiry->vehicle->fuel ?? 'N/A' }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pricing Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-warning">Pricing Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label text-muted">FOB Price</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="fw-bold">${{ number_format($inquiry->fob_price ?? 0, 0) }}</span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label text-muted">Freight Fee</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="fw-bold">${{ number_format($inquiry->freight_fee ?? 0, 0) }}</span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label text-muted">Insurance</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="fw-bold">${{ number_format($inquiry->insurance ?? 0, 0) }}</span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label text-muted">Inspection</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="fw-bold">${{ number_format($inquiry->inspection ?? 0, 0) }}</span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label text-muted">- Discount</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="fw-bold text-danger">-${{ number_format($inquiry->discount ?? 0, 0) }}</span>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Total</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="h5 text-primary fw-bold">
                                        ${{ number_format(($inquiry->fob_price ?? 0) + ($inquiry->freight_fee ?? 0) + ($inquiry->insurance ?? 0) + ($inquiry->inspection ?? 0) - ($inquiry->discount ?? 0), 0) }}
                                    </span>
                                </div>
                            </div>

                            @if(!$inquiry->invoice)
                                <div class="text-center">
                                    <form action="{{ route('admin.inquiry.createInvoice', $inquiry->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="mdi mdi-receipt"></i> Generate Invoice
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($inquiry->vehicle && $inquiry->vehicle->vehicleImages->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0 text-info">Vehicle Images</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($inquiry->vehicle->vehicleImages->take(6) as $image)
                                        <div class="col-md-2 mb-3">
                                            <img src="{{ $image->image_url }}" class="img-fluid rounded" alt="Vehicle Image">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
<script>
    // Any additional JavaScript if needed
</script>
@endsection
