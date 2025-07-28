@extends('front.layouts.customer-dashboard')

@section('title', 'Submitted Inquiries')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('front.customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Submitted Inquiries</li>
@endsection

@section('dashboard-content')
<div class="content-section">
    <div class="table-container">
        @if($inquiries->count() > 0)
            <table class="data-table">
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
                                <button class="action-btn action-btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="action-btn action-btn-download" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </td>
                        <td>{{ $inquiry->stock_no ?? 'N/A' }}</td>
                        <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                        <td>{{ $inquiry->vehicle_name }}</td>
                        <td>${{ number_format($inquiry->safe_total_price, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if($inquiries->hasPages())
                <div style="padding: 20px; border-top: 1px solid #dee2e6;">
                    {{ $inquiries->links() }}
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-question-circle" style="font-size: 48px; color: #6c757d; margin-bottom: 20px;"></i>
                <h6 style="color: #6c757d; margin-bottom: 10px;">No Inquiries Found</h6>
                <p style="color: #6c757d; margin-bottom: 20px;">You haven't submitted any inquiries yet.</p>
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
        if (confirm('Are you sure you want to delete this inquiry?')) {
            // Add delete functionality here
            console.log('Delete inquiry');
        }
    });

    // Handle download button clicks
    $('.action-btn-download').click(function() {
        // Add download functionality here
        console.log('Download inquiry');
    });
});
</script>
@endsection
