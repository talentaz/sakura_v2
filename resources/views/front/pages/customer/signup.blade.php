@extends('front.layouts.index')

@section('title', 'Customer Sign Up')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/customer-auth.css') }}" />
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Sign Up</h2>
        </div>

        <form id="customerSignupForm" class="auth-form">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicle_id }}">

            <div class="form-group">
                <label for="name" class="form-label">
                    Full Name <span class="required">*</span>
                </label>
                <input id="name" name="name" type="text" required
                       class="form-input"
                       placeholder="Full Name">
                <div class="error-message" id="name-error"></div>
            </div>

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
                <label for="country" class="form-label">
                    Country <span class="required">*</span>
                </label>
                <select id="country" name="country" required
                        class="form-input form-select">
                    <option value="">Please select</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ $current_country == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
                <div class="error-message" id="country-error"></div>
            </div>

            <div class="form-group half">
                <div class="form-group">
                    <label for="password" class="form-label">
                        Password <span class="required">*</span>
                    </label>
                    <div class="password-wrapper">
                        <input id="password" name="password" type="password" required
                               class="form-input"
                               placeholder="Password">
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
                        Confirm Password <span class="required">*</span>
                    </label>
                    <div class="password-wrapper">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="form-input"
                               placeholder="Confirm Password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <svg id="eye-icon-password_confirmation" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <div class="error-message" id="password_confirmation-error"></div>
                </div>
            </div>

            <div class="info-box">
                Your password needs to be at least 8 characters, include both lower and upper case characters,
                and at least one number or symbol.
            </div>

            <div class="checkbox-wrapper">
                <input id="terms" name="terms" type="checkbox" required class="checkbox-input">
                <label for="terms" class="checkbox-label">
                    I agree to the
                    <a href="#">Privacy Policy</a>
                    and
                    <a href="#">Terms & Conditions</a>.
                </label>
            </div>
            <div class="error-message" id="terms-error"></div>

            <button type="submit" id="signupBtn" class="btn-primary">
                <span class="btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                    </svg>
                </span>
                <span id="signupBtnText" class="btn-text">Register</span>
                <span id="signupSpinner" class="btn-spinner">
                    <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Registering...
                </span>
            </button>

            <div class="auth-footer">
                <p>Already Have an Account?</p>
                <a href="{{ route('front.customer.login') }}" class="btn-secondary">
                    Login Now
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

// Clear all error messages
function clearErrors() {
    const errorFields = ['name', 'email', 'country', 'password', 'password_confirmation', 'terms'];
    errorFields.forEach(field => {
        document.getElementById(field + '-error').classList.remove('show');
    });
}

// Form submission
document.getElementById('customerSignupForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const signupBtn = document.getElementById('signupBtn');
    const signupBtnText = document.getElementById('signupBtnText');
    const signupSpinner = document.getElementById('signupSpinner');

    // Show loading state
    signupBtn.disabled = true;
    signupBtnText.classList.add('hide');
    signupSpinner.classList.add('show');

    // Clear previous errors
    clearErrors();

    const formData = new FormData(this);

    fetch('{{ route("front.customer.signup.post") }}', {
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
                Object.keys(data.errors).forEach(field => {
                    const errorElement = document.getElementById(field + '-error');
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.classList.add('show');
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
        signupBtn.disabled = false;
        signupBtnText.classList.remove('hide');
        signupSpinner.classList.remove('show');
    });
});

// Password strength validation
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const isLongEnough = password.length >= 8;

    // You can add visual feedback for password strength here
});
</script>
@endsection
