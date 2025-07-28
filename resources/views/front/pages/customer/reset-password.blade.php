@extends('front.layouts.index')

@section('title', 'Reset Password')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/customer-auth.css') }}" />
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Reset Password</h2>
            <p class="auth-subtitle">
                Please enter your new password below.
            </p>
        </div>

        <form id="resetPasswordForm" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label for="email_display" class="form-label">
                    Email
                </label>
                <input id="email_display" type="email" value="{{ $email }}" disabled
                       class="form-input">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    New Password <span class="required">*</span>
                </label>
                <div class="password-wrapper">
                    <input id="password" name="password" type="password" required
                           class="form-input"
                           placeholder="New Password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg id="eye-icon-password" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="error-message" id="password-error"></div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    Confirm New Password <span class="required">*</span>
                </label>
                <div class="password-wrapper">
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="form-input"
                           placeholder="Confirm New Password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <svg id="eye-icon-password_confirmation" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="error-message" id="password_confirmation-error"></div>
            </div>

            <div class="info-box">
                Your password needs to be at least 8 characters, include both lower and upper case characters,
                and at least one number or symbol.
            </div>

            <button type="submit" id="resetBtn" class="btn-primary">
                <span class="btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span id="resetBtnText" class="btn-text">Reset Password</span>
                <span id="resetSpinner" class="btn-spinner">
                    <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Resetting...
                </span>
            </button>

            <div class="auth-footer">
                <p>Remember your password?</p>
                <a href="{{ route('front.customer.login') }}" class="btn-secondary">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="flex items-center">
        <span id="toast-message"></span>
        <button onclick="hideToast()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const eyeIcon = document.getElementById('eye-icon-' + fieldId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

// Toast functions
function showToast(message, type = 'error') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    
    if (type === 'success') {
        toast.className = toast.className.replace('bg-red-500', 'bg-green-500');
    } else {
        toast.className = toast.className.replace('bg-green-500', 'bg-red-500');
    }
    
    toast.classList.remove('translate-x-full');
    
    setTimeout(() => {
        hideToast();
    }, 5000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.classList.add('translate-x-full');
}

// Clear all error messages
function clearErrors() {
    const errorFields = ['password', 'password_confirmation'];
    errorFields.forEach(field => {
        document.getElementById(field + '-error').classList.add('hidden');
    });
}

// Form submission
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const resetBtn = document.getElementById('resetBtn');
    const resetBtnText = document.getElementById('resetBtnText');
    const resetSpinner = document.getElementById('resetSpinner');
    
    // Show loading state
    resetBtn.disabled = true;
    resetBtnText.classList.add('hidden');
    resetSpinner.classList.remove('hidden');
    
    // Clear previous errors
    clearErrors();
    
    const formData = new FormData(this);
    
    fetch('{{ route("front.customer.reset-password.post") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '{{ route("front.customer.login") }}';
                }
            }, 2000);
        } else {
            showToast(data.message);
            
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errorElement = document.getElementById(field + '-error');
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.classList.remove('hidden');
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.');
    })
    .finally(() => {
        // Reset loading state
        resetBtn.disabled = false;
        resetBtnText.classList.remove('hidden');
        resetSpinner.classList.add('hidden');
    });
});
</script>

<style>
/* Custom styles for better visual appeal */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Animation for form elements */
.form-input:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Smooth transitions */
* {
    transition: all 0.2s ease-in-out;
}
</style>
@endsection
