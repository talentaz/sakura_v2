@extends('front.layouts.index')

@section('title', 'Customer Login')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/customer-auth.css') }}" />
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Log In</h2>
        </div>

        <form id="customerLoginForm" class="auth-form">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicle_id }}">

            <div class="form-group">
                <label for="email" class="form-label">
                    Email <span class="required">*</span>
                </label>
                <input id="email" name="email" type="email" required
                       class="form-input"
                       placeholder="Email">
                <div class="error-message" id="email-error"></div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    Password <span class="required">*</span>
                </label>
                <div class="password-wrapper">
                    <input id="password" name="password" type="password" required
                           class="form-input"
                           placeholder="Password">
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="error-message" id="password-error"></div>
            </div>

            <div class="remember-wrapper">
                <div class="remember-group">
                    <input id="remember" name="remember" type="checkbox" class="remember-input">
                    <label for="remember" class="remember-label">
                        Remember Me
                    </label>
                </div>

                <a href="{{ route('front.customer.forgot-password') }}" class="forgot-link">
                    Forgot password?
                </a>
            </div>

            <button type="submit" id="loginBtn" class="btn-primary">
                <span class="btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span id="loginBtnText" class="btn-text">Login</span>
                <span id="loginSpinner" class="btn-spinner">
                    <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Logging in...
                </span>
            </button>

            <div class="auth-footer">
                <p>Don't Have an Account?</p>
                <a href="{{ route('front.customer.signup') }}" class="btn-secondary">
                    Sign Up Now
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast">
    <div class="toast-content">
        <span id="toast-message"></span>
        <button onclick="hideToast()" class="toast-close">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    
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

    // Remove existing type classes
    toast.classList.remove('success');

    if (type === 'success') {
        toast.classList.add('success');
    }

    toast.classList.add('show');

    setTimeout(() => {
        hideToast();
    }, 5000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.classList.remove('show');
}

// Form submission
document.getElementById('customerLoginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const loginSpinner = document.getElementById('loginSpinner');
    
    // Show loading state
    loginBtn.disabled = true;
    loginBtnText.classList.add('hide');
    loginSpinner.classList.add('show');

    // Clear previous errors
    document.getElementById('email-error').classList.remove('show');
    document.getElementById('password-error').classList.remove('show');
    
    const formData = new FormData(this);
    
    fetch('{{ route("front.customer.login.post") }}', {
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
                if (data.redirect === 'notify') {
                    window.location.href = '{{ route("front.stock") }}';
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '{{ route("front.customer.dashboard") }}';
                }
            }, 1500);
        } else {
            showToast(data.message);
            
            if (data.errors) {
                if (data.errors.email) {
                    document.getElementById('email-error').textContent = data.errors.email[0];
                    document.getElementById('email-error').classList.add('show');
                }
                if (data.errors.password) {
                    document.getElementById('password-error').textContent = data.errors.password[0];
                    document.getElementById('password-error').classList.add('show');
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.');
    })
    .finally(() => {
        // Reset loading state
        loginBtn.disabled = false;
        loginBtnText.classList.remove('hide');
        loginSpinner.classList.remove('show');
    });
});
</script>
@endsection
