@extends('front.layouts.index')

@section('title', '@yield("title")')

@section('css')
<style>
/* Customer Dashboard Styles - Override any conflicting styles */
.dashboard-banner {
    background: url('{{ URL::asset("/assets/_frontend/images/banner.png") }}') center/cover !important;
    min-height: 300px !important;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

.dashboard-banner::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    background: rgba(0, 0, 0, 0.3) !important;
    z-index: 1;
}

.dashboard-banner-content {
    position: relative !important;
    z-index: 2 !important;
    text-align: center !important;
    color: white !important;
}

.dashboard-banner-content h1 {
    color: white !important;
    font-size: 2.5rem !important;
    font-weight: 700 !important;
    margin: 0 !important;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.dashboard-main {
    background: #f5f5f5 !important;
    min-height: 600px;
    padding: 40px 0;
    font-family: var(--font-primary, 'Poppins', sans-serif);
}

.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.dashboard-content {
    display: flex !important;
    gap: 30px;
    flex-wrap: wrap;
}

.dashboard-sidebar {
    width: 280px;
    background: #e9ecef !important;
    border-radius: 8px;
    padding: 0;
    height: fit-content;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.sidebar-nav {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.sidebar-nav li {
    border-bottom: 1px solid #dee2e6;
    margin: 0 !important;
}

.sidebar-nav li:last-child {
    border-bottom: none;
}

.sidebar-nav a,
.sidebar-nav button {
    display: flex !important;
    align-items: center;
    padding: 15px 20px !important;
    color: #495057 !important;
    text-decoration: none !important;
    background: none !important;
    border: none !important;
    width: 100% !important;
    text-align: left !important;
    font-size: 14px !important;
    transition: background-color 0.3s;
    cursor: pointer;
}

.sidebar-nav a:hover,
.sidebar-nav button:hover {
    background: #dee2e6 !important;
    color: #495057 !important;
    text-decoration: none !important;
}

.sidebar-nav a.active {
    background: #6c757d !important;
    color: white !important;
}

.sidebar-nav i {
    width: 20px;
    margin-right: 10px;
    font-size: 16px;
}

.dashboard-main-content {
    flex: 1;
    min-width: 0; /* Prevents flex item from overflowing */
}

.breadcrumb-section {
    background: white !important;
    padding: 15px 20px;
    border-radius: 0px;
    margin-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb {
    margin: 0 !important;
    padding: 0 !important;
    background: none !important;
    font-size: 14px;
    display: flex;
    list-style: none;
    align-items: center;
}

.breadcrumb li {
    margin: 0;
    color: #6c757d;
}

.breadcrumb li:not(:last-child)::after {
    content: " > ";
    margin: 0 8px;
    color: #6c757d;
}

.breadcrumb a {
    color: #007bff !important;
    text-decoration: none !important;
    font-size: 14px;
}

.breadcrumb a:hover {
    text-decoration: underline !important;
}

.breadcrumb .active {
    color: #dc3545 !important;
    font-weight: 500;
}

.content-section {
    background: white !important;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.stats-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
    gap: 20px !important;
    margin-bottom: 30px !important;
}

.stats-card {
    background: #e9ecef !important;
    padding: 20px !important;
    border-radius: 8px !important;
    position: relative;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stats-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 15px !important;
}

.stats-title {
    font-size: 14px !important;
    color: #6c757d !important;
    margin: 0 !important;
}

.view-all-btn {
    background: #007bff !important;
    color: white !important;
    border: none !important;
    padding: 5px 12px !important;
    border-radius: 4px !important;
    font-size: 12px !important;
    text-decoration: none !important;
    display: inline-block !important;
    cursor: pointer;
}

.view-all-btn:hover {
    background: #0056b3 !important;
    color: white !important;
    text-decoration: none !important;
}

.stats-number {
    font-size: 48px !important;
    font-weight: bold !important;
    color: #495057 !important;
    text-align: center !important;
    margin: 10px 0 !important;
}

.stats-subtitle {
    font-size: 12px !important;
    color: #6c757d !important;
    text-align: center !important;
}

.account-info {
    background: #e9ecef;
    padding: 20px;
    border-radius: 8px;
}

.account-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.account-title {
    font-size: 16px;
    color: #495057;
    margin: 0;
}

.edit-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 12px;
    text-decoration: none;
}

.edit-btn:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
}

.account-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.account-item {
    display: flex;
    align-items: center;
}

.account-item i {
    width: 20px;
    margin-right: 10px;
    color: #6c757d;
}

.account-item-content {
    flex: 1;
}

.account-item-label {
    font-size: 12px;
    color: #6c757d;
    margin: 0;
}

.account-item-value {
    font-size: 14px;
    color: #495057;
    margin: 2px 0 0 0;
}

/* Table Styles */
.table-container {
    overflow-x: auto;
    padding: 0;
}

.data-table {
    width: 100% !important;
    border-collapse: collapse !important;
    font-size: 14px;
    margin: 0 !important;
}

.data-table th {
    background: #f8f9fa !important;
    padding: 12px !important;
    text-align: left !important;
    border-bottom: 2px solid #dee2e6 !important;
    font-weight: 600 !important;
    color: #495057 !important;
    border: none !important;
}

.data-table td {
    padding: 12px !important;
    border-bottom: 1px solid #dee2e6 !important;
    border-left: none !important;
    border-right: none !important;
    border-top: none !important;
    vertical-align: middle;
}

.data-table tr:hover {
    background: #f8f9fa !important;
}

.action-buttons {
    display: flex !important;
    gap: 5px;
    align-items: center;
}

.action-btn {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    border: 1px solid !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 12px !important;
    cursor: pointer !important;
    transition: all 0.3s !important;
    text-decoration: none !important;
    background: none !important;
}

.action-btn-delete {
    background: #ffebee !important;
    border-color: #f8bbd9 !important;
    color: #c62828 !important;
}

.action-btn-download {
    background: #e3f2fd !important;
    border-color: #90caf9 !important;
    color: #1976d2 !important;
}

.action-btn-view {
    background: #e8f5e8 !important;
    border-color: #a5d6a7 !important;
    color: #388e3c !important;
}

.action-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .dashboard-content {
        flex-direction: column;
    }

    .dashboard-sidebar {
        width: 100%;
        margin-bottom: 20px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .account-details {
        grid-template-columns: 1fr;
    }

    .data-table {
        font-size: 12px;
    }

    .data-table th,
    .data-table td {
        padding: 8px;
    }
}
</style>
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
