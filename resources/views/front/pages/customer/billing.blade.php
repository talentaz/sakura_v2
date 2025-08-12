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
        <h5 class="billing-title">
            Billing History for Invoice SM-{{ $inquiry->id }} 
            <i class="fas fa-check-circle text-success ms-2"></i>
        </h5>
    </div>
    
    <div class="billing-content">
        <!-- Billing History Table -->
        <div class="table-responsive mb-4">
            <table class="table table-borderless billing-table">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 20%;">Created Date</th>
                        <th style="width: 55%;">Description</th>
                        <th style="width: 20%; text-align: right;">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($billingHistory as $index => $billing)
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td>{{ $billing->created_at->format('M d, Y') }}</td>
                        <td>{{ $billing->description ?? 'N/A' }}</td>
                        <td style="text-align: right;">${{ number_format($billing->paid_amount, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Payment Summary -->
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <div class="payment-summary">
                    <div class="summary-row">
                        <span>FOB Price</span>
                        <span>${{ number_format($breakdown['fob_price'], 0) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Freight Fee</span>
                        <span>${{ number_format($breakdown['freight_fee'], 0) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Insurance Fee</span>
                        <span>${{ number_format($breakdown['insurance_fee'], 0) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Inspection Fee</span>
                        <span>${{ number_format($breakdown['inspection_fee'], 0) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount</span>
                        <span>({{ $breakdown['discount'] > 0 ? '$' . number_format($breakdown['discount'], 0) : '$0' }})</span>
                    </div>
                    <hr>
                    <div class="summary-row total-row">
                        <strong>Total Payable</strong>
                        <strong>${{ number_format($breakdown['total_payable'], 0) }}</strong>
                    </div>
                    <div class="summary-row paid-row">
                        <strong>Total Paid</strong>
                        <strong class="text-success">${{ number_format($breakdown['total_paid'], 0) }}</strong>
                    </div>
                    <div class="summary-row due-row">
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
@endsection

