@extends('front.layouts.customer-dashboard')

@section('title', 'My Profile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('front.customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">My Profile</li>
@endsection

@section('dashboard-content')
<div class="content-section" style="padding: 30px;">
    <h5 style="margin-bottom: 30px; color: #495057; font-weight: 600;">My Profile</h5>

    <form id="profileForm">
        @csrf

        <!-- Personal Information -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label for="name" style="display: block; margin-bottom: 8px; color: #495057; font-weight: 500;">
                    Full Name <span style="color: #dc3545;">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ $customer->name }}" required
                       style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 4px; font-size: 14px;">
                <div id="name-error" style="color: #dc3545; font-size: 12px; margin-top: 4px;"></div>
            </div>

            <div>
                <label for="email" style="display: block; margin-bottom: 8px; color: #495057; font-weight: 500;">
                    Email <span style="color: #dc3545;">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ $customer->email }}" required
                       style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 4px; font-size: 14px;">
                <div id="email-error" style="color: #dc3545; font-size: 12px; margin-top: 4px;"></div>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <div style="width: 50%;">
                <label for="country" style="display: block; margin-bottom: 8px; color: #495057; font-weight: 500;">
                    Country <span style="color: #dc3545;">*</span>
                </label>
                <select id="country" name="country"
                        style="width: 100%; padding: 12px; border: 1px solid #dee2e6; border-radius: 4px; font-size: 14px;">
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country }}"
                                {{ (old('country') == $country->country) ? 'selected' : '' }}>
                            {{ $country->country }}
                        </option>
                    @endforeach
                </select>
                <div id="country-error" style="color: #dc3545; font-size: 12px; margin-top: 4px;"></div>
            </div>
        </div>

        <hr style="margin: 30px 0; border-color: #dee2e6;">

        <!-- Password Change Section -->
        <h6 style="margin-bottom: 20px; color: #495057; font-weight: 600;">Change Password</h6>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label for="current_password" style="display: block; margin-bottom: 8px; color: #495057; font-weight: 500;">
                    Current Password
                </label>
                <div style="position: relative;">
                    <input type="password" id="current_password" name="current_password"
                           style="width: 100%; padding: 12px 40px 12px 12px; border: 1px solid #dee2e6; border-radius: 4px; font-size: 14px;">
                    <button type="button" onclick="togglePassword('current_password')"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6c757d; cursor: pointer;">
                        <i class="fas fa-eye" id="current_password-icon"></i>
                    </button>
                </div>
                <div id="current_password-error" style="color: #dc3545; font-size: 12px; margin-top: 4px;"></div>
            </div>

            <div>
                <label for="new_password" style="display: block; margin-bottom: 8px; color: #495057; font-weight: 500;">
                    New Password
                </label>
                <div style="position: relative;">
                    <input type="password" id="new_password" name="new_password"
                           style="width: 100%; padding: 12px 40px 12px 12px; border: 1px solid #dee2e6; border-radius: 4px; font-size: 14px;">
                    <button type="button" onclick="togglePassword('new_password')"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6c757d; cursor: pointer;">
                        <i class="fas fa-eye" id="new_password-icon"></i>
                    </button>
                </div>
                <div id="new_password-error" style="color: #dc3545; font-size: 12px; margin-top: 4px;"></div>
            </div>

            <div>
                <label for="new_password_confirmation" style="display: block; margin-bottom: 8px; color: #495057; font-weight: 500;">
                    Confirm Password
                </label>
                <div style="position: relative;">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                           style="width: 100%; padding: 12px 40px 12px 12px; border: 1px solid #dee2e6; border-radius: 4px; font-size: 14px;">
                    <button type="button" onclick="togglePassword('new_password_confirmation')"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6c757d; cursor: pointer;">
                        <i class="fas fa-eye" id="new_password_confirmation-icon"></i>
                    </button>
                </div>
                <div id="new_password_confirmation-error" style="color: #dc3545; font-size: 12px; margin-top: 4px;"></div>
            </div>
        </div>

        <div style="background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 12px; border-radius: 4px; margin-bottom: 30px;">
            <small>Your password needs to be at least 8 characters, include both lower and upper case characters, and at least one number or symbol.</small>
        </div>

        <div style="text-align: right;">
            <button type="submit" class="view-all-btn" style="padding: 12px 24px;">
                <i class="fas fa-save" style="margin-right: 8px;"></i>Save Changes
            </button>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

$(document).ready(function() {
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("front.customer.profile.update") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showToast(response.message, 'success');
                    
                    // Clear password fields
                    $('#current_password, #new_password, #new_password_confirmation').val('');
                } else {
                    showToast(response.message || 'An error occurred', 'error');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    
                    // Display validation errors
                    Object.keys(errors).forEach(function(field) {
                        $('#' + field).addClass('is-invalid');
                        $('#' + field + '-error').text(errors[field][0]);
                    });
                    
                    showToast(xhr.responseJSON.message || 'Please fix the errors below', 'error');
                } else {
                    showToast('An error occurred. Please try again.', 'error');
                }
            },
            complete: function() {
                // Restore button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});

function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = $(`
        <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.alert('close');
    }, 5000);
}
</script>
@endsection
