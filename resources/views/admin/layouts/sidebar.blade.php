<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @php
                    $userRole = auth()->user()->role->slug ?? null;
                    $roleHelper = new \App\Helpers\RoleHelper();
                @endphp

                @if($roleHelper::canShowMenuItem($userRole, 'dashboard'))
                <li>
                    <a href="{{route('root')}}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboards</span>
                    </a>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'port'))
                <li>
                    <a href="{{route('admin.port.index')}}">
                        <i class="bx bx-calculator"></i>
                        <span>Port List</span>
                    </a>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'rate'))
                <li>
                    <a href="{{route('admin.vehicle.rate')}}">
                        <i class="bx bx-bitcoin"></i>
                        <span>Rate</span>
                    </a>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'vehicle_management'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-car"></i>
                        <span>Vehicle Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.vehicle.index')}}" >Vehicle List</a></li>
                        <li><a href="{{route('admin.vehicle.create')}}" >Add Vehicle</a></li>
                        <li><a href="{{route('admin.vehicle.deletedList')}}" >Deleted Vehicle</a></li>
                    </ul>
                </li>
                @elseif($roleHelper::canShowMenuItem($userRole, 'vehicle_management_limited'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-car"></i>
                        <span>Vehicle Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.vehicle.index')}}" >Vehicle List</a></li>
                    </ul>
                </li>
                @elseif($roleHelper::canShowMenuItem($userRole, 'vehicle_management_view'))
                <li>
                    <a href="{{route('admin.vehicle.index')}}">
                        <i class="bx bxs-car"></i>
                        <span>Vehicle List</span>
                    </a>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'category'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-briefcase-alt-2"></i>
                        <span>Category</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.bodyType.index')}}" >Vehicle Type</a></li>
                        <li><a href="{{route('admin.bodyType.add')}}" >Add Vehicle Type</a></li>
                        <li><a href="{{route('admin.makerType.index')}}" >Maker Type</a></li>
                        <li><a href="{{route('admin.makerType.add')}}" >Add Maker Type</a></li>
                    </ul>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'customer_voice'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span>Customer Voice</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.customer.index')}}" >Review List</a></li>
                        <li><a href="{{route('admin.customer.add')}}" >Add Review</a></li>
                    </ul>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'news'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-detail"></i>
                        <span>News</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.news.index')}}" >News List</a></li>
                        <li><a href="{{route('admin.news.add')}}" >Add News</a></li>
                    </ul>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'video'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-video-recording"></i>
                        <span>Video</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.video.index')}}" >Video List</a></li>
                        <li><a href="{{route('admin.video.add')}}" >Add Video</a></li>
                    </ul>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'page_management'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span>Page Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.page_setting.index')}}">Page Settings</a></li>
                        <li><a href="{{route('admin.page_setting.create')}}">Add Page</a></li>
                    </ul>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'user_management'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.user')}}">Registered User</a></li>
                    </ul>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'customer_management'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-group"></i>
                        <span>Customer Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.customer_management.index')}}">Customer List</a></li>
                        <li><a href="{{route('admin.customer_management.create')}}">Add Customer</a></li>
                    </ul>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'inquiry') || $roleHelper::canShowMenuItem($userRole, 'inquiry_view'))
                <li>
                    <a href="{{route('admin.inquiry.index')}}">
                        <i class="bx bx-envelope"></i>
                        <span>Inquiry List</span>
                    </a>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'invoice') || $roleHelper::canShowMenuItem($userRole, 'invoice_view'))
                <li>
                    <a href="{{route('admin.invoice.index')}}">
                        <i class="bx bx-receipt"></i>
                        <span>Invoice List</span>
                    </a>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'shipping'))
                <li>
                    <a href="{{route('admin.shipping.index')}}">
                        <i class="bx bx-store"></i>
                        <span>Shipping</span>
                    </a>
                </li>
                @endif

                @if($roleHelper::canShowMenuItem($userRole, 'reports'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-bar-chart-alt-2"></i>
                        <span>Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.reports.vehicle_sales')}}">Vehicle Sales Report</a></li>
                        <li><a href="{{route('admin.reports.customer_purchase')}}">Customer Purchase Report</a></li>
                        <li><a href="{{route('admin.reports.agent_performance')}}">Agent Performance Report</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
