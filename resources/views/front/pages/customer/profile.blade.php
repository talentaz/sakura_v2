@extends('front.layouts.customer-dashboard')

@section('title', 'My Profile')

@section('css')
@parent
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/customer-profile.css') }}?v={{ time() }}">
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('front.customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">My Profile</li>
@endsection

@section('dashboard-content')
<div class="profile-content">
    <h5 class="profile-title">My Profile</h5>

    <form id="profileForm">
        @csrf

        <!-- Personal Information -->
        <div class="profile-grid">
            <div class="form-group">
                <label for="name" class="form-label">
                    Full Name <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ $customer->name }}" required class="form-control">
                <span id="name-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    Email <span class="required">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ $customer->email }}" required class="form-control">
                <span id="email-error" class="error-message"></span>
            </div>
        </div>

        <div class="profile-grid-single">
            <div class="form-group">
                <label for="country" class="form-label">
                    Country <span class="required">*</span>
                </label>
                <select id="country" name="country" class="form-control">
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country }}"
                                {{ (old('country', $customer->country_id ? $customer->country->country ?? '' : '') == $country->country) ? 'selected' : '' }}>
                            {{ $country->country }}
                        </option>
                    @endforeach
                </select>
                <span id="country-error" class="error-message"></span>
            </div>
        </div>

        <hr class="section-divider">

        <!-- Password Change Section -->
        <h6 class="password-section-title">Change Password</h6>
        <div class="password-grid">
            <div class="form-group">
                <label for="current_password" class="form-label">
                    Current Password
                </label>
                <div class="password-field">
                    <input type="password" id="current_password" name="current_password" class="form-control">
                    <button type="button" onclick="togglePassword('current_password')" class="password-toggle">
                        <i class="fas fa-eye" id="current_password-icon"></i>
                    </button>
                </div>
                <span id="current_password-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="new_password" class="form-label">
                    New Password
                </label>
                <div class="password-field">
                    <input type="password" id="new_password" name="new_password" class="form-control">
                    <button type="button" onclick="togglePassword('new_password')" class="password-toggle">
                        <i class="fas fa-eye" id="new_password-icon"></i>
                    </button>
                </div>
                <span id="new_password-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation" class="form-label">
                    Confirm Password
                </label>
                <div class="password-field">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                    <button type="button" onclick="togglePassword('new_password_confirmation')" class="password-toggle">
                        <i class="fas fa-eye" id="new_password_confirmation-icon"></i>
                    </button>
                </div>
                <span id="new_password_confirmation-error" class="error-message"></span>
            </div>
        </div>

        <div class="password-info">
            <small>Your password needs to be at least 8 characters, include both lower and upper case characters, and at least one number or symbol.</small>
        </div>

        <div class="submit-section">
            <button type="submit" class="view-all-btn">
                <i class="fas fa-save"></i>Save Changes
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

        // Clear previous errors and states
        clearFormErrors();

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i>Saving...').prop('disabled', true);

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

                    // Mark form fields as valid
                    $('.form-control').addClass('is-valid');
                } else {
                    showToast(response.message || 'An error occurred', 'error');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const response = xhr.responseJSON;

                    // Handle validation errors
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(field) {
                            const fieldElement = $('#' + field);
                            const errorElement = $('#' + field + '-error');

                            fieldElement.addClass('is-invalid');
                            errorElement.text(response.errors[field][0]);
                        });
                    }

                    // Show specific error message
                    const errorMessage = response.message || 'Please fix the errors below';
                    showToast(errorMessage, 'error');

                    // Special handling for password errors
                    if (response.message === 'Current password is incorrect') {
                        $('#current_password').addClass('is-invalid').focus();
                        $('#current_password-error').text('Current password is incorrect');
                    }
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

    // Clear errors when user starts typing
    $('.form-control').on('input', function() {
        $(this).removeClass('is-invalid is-valid');
        $(this).siblings('.error-message').text('');
    });
});

function clearFormErrors() {
    $('.form-control').removeClass('is-invalid is-valid');
    $('.error-message').text('');
}

function showToast(message, type = 'info') {
    // Remove existing toasts
    $('.toast-notification').remove();

    // Create new toast
    const toastClass = type === 'success' ? 'toast-success' : 'toast-error';
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

    const toast = $(`
        <div class="toast-notification ${toastClass}">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="${icon}"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="toast-close" onclick="closeToast(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `);

    $('body').append(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        closeToast(toast.find('.toast-close')[0]);
    }, 5000);
}

function closeToast(button) {
    const toast = $(button).closest('.toast-notification');
    toast.addClass('fade-out');
    setTimeout(() => {
        toast.remove();
    }, 300);
}
</script>
@endsection
