@extends('front.layouts.customer-dashboard')

@section('title', 'Billing History for Invoice SM-' . $inquiry->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('front.customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('front.customer.purchases') }}">Purchase History</a></li>
    <li class="breadcrumb-item active">Billing History for Invoice SM-{{ $inquiry->id }}</li>
@endsection

@section('dashboard-content')
<div class="content-section">
    <div class="billing-header">
        <h5 class="billing-title">Billing History for Invoice SM-{{ $inquiry->id }}</h5>
        <span class="status-badge status-payment-received">
            <i class="fas fa-check"></i>Paid
        </span>
    </div>

    <div class="billing-content">
        <!-- Payment History Table -->
        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Created Date</th>
                        <th>Description</th>
                        <th>Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($billingHistory as $payment)
                    <tr>
                        <td>{{ $payment['id'] }}</td>
                        <td>{{ $payment['created_date'] }}</td>
                        <td>{{ $payment['description'] }}</td>
                        <td><strong>${{ $payment['paid_amount'] }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Payment Breakdown -->
        <div class="row">
            <div class="col-md-8">
                <!-- Vehicle Information -->
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Vehicle Information</h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Vehicle:</strong> {{ $inquiry->vehicle_name }}
                        </div>
                        <div class="col-sm-6">
                            <strong>Stock No:</strong> {{ $inquiry->stock_no ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Payment Summary -->
                <div class="card border-0" style="background: rgba(102, 126, 234, 0.1);">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">Payment Breakdown</h6>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>FOB Price</span>
                            <span>${{ number_format($breakdown['fob_price'], 0) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Freight Fee</span>
                            <span>${{ number_format($breakdown['freight_fee'], 0) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Insurance Fee</span>
                            <span>${{ number_format($breakdown['insurance_fee'], 0) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Inspection Fee</span>
                            <span>${{ number_format($breakdown['inspection_fee'], 0) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span>({{ $breakdown['discount'] < 0 ? '$' . number_format(abs($breakdown['discount']), 0) : '$0' }})</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total Payable</strong>
                            <strong>${{ number_format($breakdown['total_payable'], 0) }}</strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <strong>Total Paid</strong>
                            <strong>${{ number_format($breakdown['total_paid'], 0) }}</strong>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <strong>Amount Due</strong>
                            <strong class="{{ $breakdown['amount_due'] > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($breakdown['amount_due'], 0) }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
