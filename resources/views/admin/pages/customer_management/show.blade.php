@extends('admin.layouts.master')
@section('title') Customer Details @endsection
@section('css')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Customer Management @endslot
        @slot('title') Customer Details @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title">Customer Details</h4>
                            <p class="card-title-desc">Complete information for customer: {{ $customer->email }}</p>
                        </div>
                        <div>
                            @if($customer->deleted_at)
                                <span class="badge bg-danger fs-6">DELETED</span>
                            @else
                                <span class="badge bg-{{ $customer->status == 'Active' ? 'success' : ($customer->status == 'Inactive' ? 'secondary' : 'warning') }} fs-6">
                                    {{ $customer->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" width="40%">Customer ID:</td>
                                        <td>{{ $customer->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Name:</td>
                                        <td>{{ $customer->name ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Country ID:</td>
                                        <td>{{ $customer->country_id ?? 'Not assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>
                                            <span class="badge bg-{{ $customer->status == 'Active' ? 'success' : ($customer->status == 'Inactive' ? 'secondary' : 'warning') }}">
                                                {{ $customer->status }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" width="40%">Created At:</td>
                                        <td>{{ $customer->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Updated At:</td>
                                        <td>{{ $customer->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    @if($customer->deleted_at)
                                    <tr>
                                        <td class="fw-bold">Deleted At:</td>
                                        <td class="text-danger">{{ $customer->deleted_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="fw-bold">Account Age:</td>
                                        <td>{{ $customer->created_at->diffForHumans() }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Last Updated:</td>
                                        <td>{{ $customer->updated_at->diffForHumans() }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Actions</h5>
                            <div class="btn-group" role="group">
                                @if($customer->deleted_at)
                                    <form method="POST" action="{{ route('admin.customer_management.restore', $customer->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to restore this customer?')">
                                            <i class="mdi mdi-restore"></i> Restore Customer
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.customer_management.force_delete', $customer->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this customer? This action cannot be undone!')">
                                            <i class="mdi mdi-delete-forever"></i> Delete Forever
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.customer_management.edit', $customer->id) }}" class="btn btn-primary">
                                        <i class="mdi mdi-pencil"></i> Edit Customer
                                    </a>
                                    <form method="POST" action="{{ route('admin.customer_management.destroy', $customer->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                                            <i class="mdi mdi-delete"></i> Delete Customer
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.customer_management.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Quick Stats</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-primary">{{ $customer->id }}</h3>
                                <p class="text-muted mb-0">Customer ID</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-{{ $customer->status == 'Active' ? 'success' : 'warning' }}">
                                    {{ $customer->status }}
                                </h3>
                                <p class="text-muted mb-0">Status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Account Information</h4>
                    <div class="alert alert-info">
                        <h6>Customer Account Details:</h6>
                        <ul class="mb-0">
                            <li><strong>Email:</strong> Primary login credential</li>
                            <li><strong>Status:</strong> Controls account access</li>
                            <li><strong>Country ID:</strong> For future country relationship</li>
                            @if($customer->deleted_at)
                            <li class="text-danger"><strong>Deleted:</strong> Account is soft deleted</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            @if($customer->deleted_at)
            <div class="card border-danger">
                <div class="card-body">
                    <h4 class="card-title text-danger">Deleted Account</h4>
                    <div class="alert alert-danger">
                        <h6>Account Status:</h6>
                        <ul class="mb-0">
                            <li>This customer account has been deleted</li>
                            <li>Deleted on: {{ $customer->deleted_at->format('Y-m-d H:i:s') }}</li>
                            <li>You can restore or permanently delete this account</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Related Actions</h4>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.customer_management.index') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-format-list-bulleted"></i> View All Customers
                        </a>
                        <a href="{{ route('admin.customer_management.create') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-plus"></i> Add New Customer
                        </a>
                        @if(!$customer->deleted_at)
                        <a href="{{ route('admin.customer_management.edit', $customer->id) }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-pencil"></i> Edit This Customer
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            @if(session('success'))
                toastr.success('{{ session('success') }}');
            @endif
        });
    </script>
@endsection
