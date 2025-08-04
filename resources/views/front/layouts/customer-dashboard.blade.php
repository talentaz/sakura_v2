@extends('front.layouts.index')

@section('title', '@yield("title")')

@section('css')
<!-- Dashboard Core Styles -->
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/customer-dashboard.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/customer-tables.css') }}?v={{ time() }}">
<style>
/* Page-specific banner background override */
.dashboard-banner {
    background: url('{{ URL::asset("/assets/_frontend/images/banner.png") }}') center/cover !important;
}
</style>
@yield('css')
@endsection

@section('content')
<!-- Dashboard Banner -->
<section class="dashboard-banner">
    <div class="dashboard-banner-content">
        <h1>Customer Dashboard</h1>
    </div>
</section>

<!-- Dashboard Main Content -->
<section class="dashboard-main">
    <div class="dashboard-container">
        <div class="dashboard-content">
            <!-- Sidebar -->
            <aside class="dashboard-sidebar">
                <ul class="sidebar-nav">
                    <li>
                        <a href="{{ route('front.customer.dashboard') }}" 
                           class="{{ request()->routeIs('front.customer.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.customer.inquiries') }}" 
                           class="{{ request()->routeIs('front.customer.inquiries') ? 'active' : '' }}">
                            <i class="fas fa-question-circle"></i>
                            Submitted Inquiries
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.customer.purchases') }}" 
                           class="{{ request()->routeIs('front.customer.purchases') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            Purchase History
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.customer.profile') }}" 
                           class="{{ request()->routeIs('front.customer.profile') ? 'active' : '' }}">
                            <i class="fas fa-user"></i>
                            My Profile
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('front.customer.logout') }}">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="dashboard-main-content">
                <!-- Breadcrumb -->
                <div class="breadcrumb-section">
                    <nav>
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>

                <!-- Page Content -->
                @yield('dashboard-content')
            </main>
        </div>
    </div>
</section>
@endsection
