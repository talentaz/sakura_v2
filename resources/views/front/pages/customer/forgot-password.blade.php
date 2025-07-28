@extends('front.layouts.index')

@section('title', 'Forgot Password')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/customer-auth.css') }}" />
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Forgot Password</h2>
            <p class="auth-subtitle">
                If you have lost your password or wish to reset it,
                please enter your email below and we will email you a link to proceed.
            </p>
        </div>

        <form id="forgotPasswordForm" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">
                    Email <span class="required">*</span>
                </label>
                <input id="email" name="email" type="email" required
                       class="form-input"
                       placeholder="Email">
                <div class="error-message" id="email-error"></div>
            </div>

            <button type="submit" id="requestBtn" class="btn-primary">
                <span class="btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </span>
                <span id="requestBtnText" class="btn-text">Request</span>
                <span id="requestSpinner" class="btn-spinner">
                    <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
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
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const requestBtn = document.getElementById('requestBtn');
    const requestBtnText = document.getElementById('requestBtnText');
    const requestSpinner = document.getElementById('requestSpinner');
    
    // Show loading state
    requestBtn.disabled = true;
    requestBtnText.classList.add('hide');
    requestSpinner.classList.add('show');

    // Clear previous errors
    document.getElementById('email-error').classList.remove('show');
    
    const formData = new FormData(this);
    
    fetch('{{ route("front.customer.forgot-password.post") }}', {
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
            
            // Clear the form
            document.getElementById('email').value = '';
            
            setTimeout(() => {
                window.location.href = '{{ route("front.customer.login") }}';
            }, 3000);
        } else {
            showToast(data.message);
            
            if (data.errors && data.errors.email) {
                document.getElementById('email-error').textContent = data.errors.email[0];
                document.getElementById('email-error').classList.add('show');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.');
    })
    .finally(() => {
        // Reset loading state
        requestBtn.disabled = false;
        requestBtnText.classList.remove('hide');
        requestSpinner.classList.remove('show');
    });
});
</script>
@endsection
