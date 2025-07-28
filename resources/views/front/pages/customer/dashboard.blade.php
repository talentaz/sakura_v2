@extends('front.layouts.customer-dashboard')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('dashboard-content')
<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stats-card">
        <div class="stats-header">
            <h6 class="stats-title">Submitted Inquiries</h6>
            <a href="{{ route('front.customer.inquiries') }}" class="view-all-btn">View All</a>
        </div>
        <div class="stats-number">{{ $submittedInquiriesCount }}</div>
        <div class="stats-subtitle">Latest since {{ $latestInquiryDays }} days ago</div>
    </div>

    <div class="stats-card">
        <div class="stats-header">
            <h6 class="stats-title">Total Purchased</h6>
            <a href="{{ route('front.customer.purchases') }}" class="view-all-btn">View All</a>
        </div>
        <div class="stats-number">{{ $totalPurchasedCount }}</div>
        <div class="stats-subtitle">Latest since {{ $latestPurchaseDays }} days ago</div>
    </div>

    <div class="stats-card">
        <div class="stats-header">
            <h6 class="stats-title">Account Status</h6>
        </div>
        <div class="stats-number" style="color: #3c9a03;">{{ $customer->status }}</div>
        <div class="stats-subtitle">Active since {{ $customer->created_at->format('M Y') }}</div>
    </div>
</div>

<!-- Account Information -->
<div class="account-info">
    <div class="account-header">
        <h5 class="account-title">Account Information</h5>
        <a href="{{ route('front.customer.profile') }}" class="edit-btn">Edit</a>
    </div>

    <div class="account-details">
        <div class="account-item">
            <i class="fas fa-user"></i>
            <div class="account-item-content">
                <p class="account-item-label">Full Name</p>
                <p class="account-item-value">{{ $customer->name }}</p>
            </div>
        </div>

        <div class="account-item">
            <i class="fas fa-envelope"></i>
            <div class="account-item-content">
                <p class="account-item-label">Email</p>
                <p class="account-item-value">{{ $customer->email }}</p>
            </div>
        </div>

        <div class="account-item">
            <i class="fas fa-globe"></i>
            <div class="account-item-content">
                <p class="account-item-label">Country</p>
                <p class="account-item-value">{{ $customerCountry ?? 'Not specified' }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
