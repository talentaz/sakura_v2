@extends('front.layouts.customer-dashboard')

@section('title', 'Submitted Inquiries')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('front.customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Submitted Inquiries</li>
@endsection

@section('dashboard-content')
<div class="content-section">
    <h5>Submitted Inquiries</h5>
    <div class="table-container">
        @if($inquiries->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Stock No</th>
                            <th>Submission Date</th>
                            <th>Vehicle Name</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inquiry)
                        <tr>
                            <td>
                                <div class="action-buttons">
                                    <a class="action-btn action-btn-delete" target="_blank" href="{{ route('customer.inquiry.generate-pdf', $inquiry->id) }}" title="Quotation PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @if($inquiry->invoice)
                                    <a class="action-btn action-btn-download" target="_blank" href="{{ route('customer.invoice.generate-pdf', $inquiry->invoice->id) }}" title="View Invoice">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                            <td><strong>{{ $inquiry->stock_no ?? 'N/A' }}</strong></td>
                            <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="vehicle-name">{{ $inquiry->vehicle_name }}</div>
                            </td>
                            <td><strong>${{ number_format($inquiry->safe_total_price, 0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($inquiries->hasPages())
                <div class="pagination-wrapper">
                    {{ $inquiries->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No Inquiries Found</h5>
                <p>You haven't submitted any vehicle inquiries yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
  
});
</script>
@endsection


