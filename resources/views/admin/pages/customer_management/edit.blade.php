@extends('admin.layouts.master')
@section('title') Edit Customer @endsection
@section('css')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Customer Management @endslot
        @slot('title') Edit Customer @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Customer: {{ $customer->email }}</h4>
                    <p class="card-title-desc">Update customer account details</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.customer_management.update', $customer->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $customer->name) }}" placeholder="Enter customer name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $customer->email) }}" placeholder="Enter email address" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Enter new password (leave empty to keep current)">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to keep current password</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Country ID</label>
                                    <input type="number" class="form-control @error('country_id') is-invalid @enderror" 
                                           id="country_id" name="country_id" value="{{ old('country_id', $customer->country_id) }}" placeholder="Enter country ID">
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty if no country is assigned</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="Active" {{ old('status', $customer->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status', $customer->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Suspended" {{ old('status', $customer->status) == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> Update Customer
                                    </button>
                                    <a href="{{ route('admin.customer_management.index') }}" class="btn btn-secondary">
                                        <i class="mdi mdi-arrow-left"></i> Back to List
                                    </a>
                                    <a href="{{ route('admin.customer_management.show', $customer->id) }}" class="btn btn-info">
                                        <i class="mdi mdi-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Details</h4>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $customer->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Current Email:</strong></td>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Current Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $customer->status == 'Active' ? 'success' : ($customer->status == 'Inactive' ? 'secondary' : 'warning') }}">
                                    {{ $customer->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $customer->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Updated:</strong></td>
                            <td>{{ $customer->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Information</h4>
                    <div class="alert alert-info">
                        <h6>Password Update:</h6>
                        <ul class="mb-0">
                            <li>Leave password fields empty to keep current password</li>
                            <li>New password must be at least 6 characters</li>
                            <li>Both password fields must match</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6>Status Changes:</h6>
                        <ul class="mb-0">
                            <li><strong>Active:</strong> Customer can login and use services</li>
                            <li><strong>Inactive:</strong> Customer account is disabled</li>
                            <li><strong>Suspended:</strong> Customer account is temporarily suspended</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Form validation
            $('form').on('submit', function(e) {
                var password = $('#password').val();
                var confirmPassword = $('#password_confirmation').val();
                
                // Only validate if password is being changed
                if (password.length > 0) {
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('Passwords do not match!');
                        return false;
                    }
                    
                    if (password.length < 6) {
                        e.preventDefault();
                        alert('Password must be at least 6 characters long!');
                        return false;
                    }
                }
            });
        });
    </script>
@endsection
