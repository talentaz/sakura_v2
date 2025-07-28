@extends('admin.layouts.master')
@section('title') Customer Management @endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Customer Management Styles -->
    <link href="{{ URL::asset('/assets/admin/pages/customer_management/style.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Customer Management @endslot
        @slot('title') Customer List @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Customer Management</h4>
                            <p class="card-title-desc">Manage customer accounts</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.customer_management.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Add New Customer
                            </a>
                        </div>
                    </div>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('admin.customer_management.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Suspended" {{ request('status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="include_deleted" value="1" {{ request('include_deleted') ? 'checked' : '' }}>
                                    <label class="form-check-label">Include Deleted</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                                <a href="{{ route('admin.customer_management.index') }}" class="btn btn-light">Clear</a>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Country ID</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr class="{{ $customer->deleted_at ? 'table-secondary' : '' }}">
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name ?? 'N/A' }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>
                                        @if($customer->deleted_at)
                                            <span class="badge bg-danger">Deleted</span>
                                        @else
                                            <select class="form-select form-select-sm status-select" data-id="{{ $customer->id }}">
                                                <option value="Active" {{ $customer->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ $customer->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="Suspended" {{ $customer->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                        @endif
                                    </td>
                                    <td>{{ $customer->country_id ?? 'N/A' }}</td>
                                    <td>{{ $customer->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($customer->deleted_at)
                                            <form method="POST" action="{{ route('admin.customer_management.restore', $customer->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to restore this customer?')">
                                                    <i class="mdi mdi-restore"></i> Restore
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.customer_management.force_delete', $customer->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to permanently delete this customer? This action cannot be undone!')">
                                                    <i class="mdi mdi-delete-forever"></i> Delete Forever
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.customer_management.show', $customer->id) }}" class="btn btn-sm btn-info">
                                                <i class="mdi mdi-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.customer_management.edit', $customer->id) }}" class="btn btn-sm btn-primary">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.customer_management.destroy', $customer->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                                                    <i class="mdi mdi-delete"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No customers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable without pagination (using Laravel pagination)
            $('#datatable').DataTable({
                "paging": false,
                "searching": false,
                "info": false
            });

            // Handle status change
            $('.status-select').change(function() {
                var customerId = $(this).data('id');
                var newStatus = $(this).val();
                
                $.ajax({
                    url: '{{ route("admin.customer_management.change_status") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: customerId,
                        status: newStatus
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Error updating status');
                    }
                });
            });
        });
    </script>
@endsection
