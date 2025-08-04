@extends('front.layouts.customer-dashboard')

@section('title', 'Purchase History')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('front.customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Purchase History</li>
@endsection

@section('dashboard-content')
<div class="content-section">
    @if($purchases->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>Invoice Number</th>
                        <th>Invoice Date</th>
                        <th>Vehicle Name</th>
                        <th>Vehicle Status</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn action-btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="{{ route('front.customer.billing', $purchase->id) }}"
                                   class="action-btn action-btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                        <td>SM-{{ $purchase->id }}</td>
                        <td class="date-display">{{ $purchase->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="vehicle-info">
                                <div class="vehicle-name">{{ $purchase->vehicle_name }}</div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusText = $purchase->vehicle_status ?? 'Open';
                                $statusClass = 'status-open';

                                switch($statusText) {
                                    case 'Payment Received':
                                        $statusClass = 'status-payment-received';
                                        break;
                                    case 'Shipping':
                                        $statusClass = 'status-shipped';
                                        break;
                                    case 'Document':
                                        $statusClass = 'status-open';
                                        break;
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="price-display">${{ number_format($purchase->safe_total_price, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($purchases->hasPages())
            <div class="pagination-wrapper">
                {{ $purchases->links() }}
            </div>
        @endif
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-shopping-cart" style="font-size: 48px; color: #6c757d; margin-bottom: 20px;"></i>
                <h6 style="color: #6c757d; margin-bottom: 10px;">No Purchases Found</h6>
                <p style="color: #6c757d; margin-bottom: 20px;">You haven't made any purchases yet.</p>
                <a href="{{ route('front.stock') }}" class="view-all-btn">
                    <i class="fas fa-search" style="margin-right: 8px;"></i>Browse Vehicles
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Handle delete button clicks
    $('.action-btn-delete').click(function() {
        if (confirm('Are you sure you want to delete this purchase record?')) {
            // Add delete functionality here
            console.log('Delete purchase');
        }
    });
});
</script>
@endsection
