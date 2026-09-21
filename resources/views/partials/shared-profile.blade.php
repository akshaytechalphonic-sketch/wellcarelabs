@extends('layouts.app')

@section('title', 'Profile - Wellcare Labs')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<style>
    :root {
        --primary: #0f9d80;
        --primary-light: #e6f7f4;
        --primary-lighter: #f0fbf9;
        --primary-dark: #0d8a71;
        --secondary: #4285f4;
        --accent: #34a853;
        --accent-light: #eaf7ed;
        --warning: #fbbc05;
        --warning-light: #fef7e0;
        --danger: #ea4335;
        --danger-light: #fdeaea;
        --dark: #2d3748;
        --dark-light: #4a5568;
        --light: #f8fafc;
        --light-gray: #edf2f7;
        --border: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
        --shadow: 0 4px 12px rgba(0,0,0,0.05);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.1);
        --radius-sm: 6px;
        --radius: 10px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .profile-wrapper {
        min-height: calc(100vh - 80px);
        background: linear-gradient(135deg, #f8fafc 0%, #f0f9ff 100%);
        padding: 20px;
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header Section */
    .profile-header {
        margin-bottom: 30px;
        padding: 30px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
    }

    .greeting {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        text-decoration: none;
        transition: var(--transition);
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-4px);
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Main Layout */
    .profile-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    @media (min-width: 992px) {
        .profile-layout {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Card Styles */
    .profile-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        transition: var(--transition);
        overflow: hidden;
    }

    .profile-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
    }

    .card-title i {
        color: var(--primary);
    }

    .card-body {
        padding: 25px;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .form-label i {
        color: var(--primary);
        width: 20px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-size: 1rem;
        transition: var(--transition);
        background: var(--light);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(15, 157, 128, 0.1);
        background: white;
    }

    .form-control:disabled {
        background: var(--light-gray);
        cursor: not-allowed;
    }

    .form-control.is-invalid {
        border-color: var(--danger);
        background-color: rgba(234, 67, 53, 0.02);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(234, 67, 53, 0.1);
    }

    .input-with-icon {
        position: relative;
    }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dark-light);
        cursor: pointer;
        transition: var(--transition);
    }

    .input-icon:hover {
        color: var(--primary);
    }

    /* Error Messages */
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 8px;
        font-size: 0.85rem;
        color: var(--danger);
        display: flex;
        align-items: center;
        gap: 5px;
        animation: fadeIn 0.3s ease;
    }

    .invalid-feedback i {
        font-size: 0.8rem;
    }

    /* Button Styles */
    .btn {
        padding: 12px 24px;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .btn-primary:hover:not(:disabled) {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 157, 128, 0.3);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: white;
        color: var(--dark);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--light);
        border-color: var(--dark-light);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #d3382c 100%);
        color: white;
    }

    .btn-danger:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(234, 67, 53, 0.3);
    }

    .btn-full {
        width: 100%;
    }

    /* Info Display */
    .info-item {
        background: var(--light);
        padding: 15px;
        border-radius: var(--radius);
        margin-bottom: 15px;
        border-left: 4px solid var(--primary);
    }

    .info-label {
        font-size: 0.85rem;
        color: var(--dark-light);
        margin-bottom: 5px;
        font-weight: 500;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
    }

    .info-value.highlight {
        color: var(--primary);
    }

    /* Hospital Manager Additional Info */
    .hospital-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .hospital-info-item {
        background: var(--light);
        padding: 15px;
        border-radius: var(--radius);
        border-left: 3px solid var(--secondary);
    }

    /* Toast Notification */
    .toast {
        position: fixed;
        top: 30px;
        right: 30px;
        background: white;
        padding: 20px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 15px;
        max-width: 400px;
        z-index: 1100;
        animation: slideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        transform-origin: top right;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%) scale(0.8);
            opacity: 0;
        }
        to {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }

    .toast-success {
        border-left: 5px solid var(--accent);
    }

    .toast-error {
        border-left: 5px solid var(--danger);
    }

    .toast-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.2rem;
    }

    .toast-success .toast-icon {
        background: var(--accent-light);
        color: var(--accent);
    }

    .toast-error .toast-icon {
        background: var(--danger-light);
        color: var(--danger);
    }

    .toast-content h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .toast-content p {
        font-size: 0.9rem;
        color: var(--dark-light);
    }

    /* Password Strength Meter */
    .password-strength {
        margin-top: 8px;
        height: 4px;
        background: var(--light-gray);
        border-radius: 2px;
        overflow: hidden;
    }

    .strength-bar {
        height: 100%;
        width: 0;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .strength-weak { background: var(--danger); }
    .strength-fair { background: var(--warning); }
    .strength-good { background: #29b6f6; }
    .strength-strong { background: var(--accent); }

    .strength-text {
        font-size: 0.8rem;
        color: var(--dark-light);
        margin-top: 4px;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-wrapper {
            padding: 15px;
        }
        
        .profile-header {
            padding: 20px;
        }
        
        .header-content {
            flex-direction: column;
            gap: 15px;
        }
        
        .greeting {
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .toast {
            left: 15px;
            right: 15px;
            max-width: none;
        }
    }
</style>

<div class="profile-wrapper">
    <div class="profile-container">
        {{-- Toast Notifications --}}
        @if(session('success'))
        <div class="toast toast-success" id="successToast">
            <div class="toast-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="toast-content">
                <h4>Success!</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast toast-error" id="errorToast">
            <div class="toast-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="toast-content">
                <h4>Error!</h4>
                <p>{{ session('error') }}</p>
            </div>
        </div>
        @endif

        {{-- Header --}}
        <div class="profile-header">
            <div class="header-content">
                <div>
                    <h1 class="greeting">Welcome, {{ $user->name }}!</h1>
                    <p style="margin-bottom: 15px; opacity: 0.9;">Manage your profile and account settings</p>
                    <div class="role-badge">
                        <i class="fas {{ $roleIcon }}"></i>
                        <span>{{ $roleName }}</span>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ $backRoute }}" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="profile-layout">
            {{-- Left Column: Account Details --}}
            <div>
                {{-- Profile Information Card --}}
                <div class="profile-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-circle"></i>
                            Profile Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value highlight">{{ $user->name }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Account Status</div>
                            <div class="info-value" style="color: var(--accent);">
                                <i class="fas fa-check-circle"></i>
                                Active & Verified
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Role</div>
                            <div class="info-value">
                                <i class="fas {{ $roleIcon }}"></i>
                                {{ $roleName }}
                            </div>
                        </div>

                        {{-- Hospital Manager Specific Information --}}
                        @if($isHospitalManager && $hospital)
                            <div class="hospital-info-grid">
                                <div class="hospital-info-item">
                                    <div class="info-label">Hospital</div>
                                    <div class="info-value">{{ $hospital->name ?? 'N/A' }}</div>
                                </div>
                                
                                @if($hospital->branch)
                                <div class="hospital-info-item">
                                    <div class="info-label">Branch</div>
                                    <div class="info-value">{{ $hospital->branch }}</div>
                                </div>
                                @endif
                                
                                @if($hospital->city)
                                <div class="hospital-info-item">
                                    <div class="info-label">Location</div>
                                    <div class="info-value">{{ $hospital->city }}</div>
                                </div>
                                @endif
                                
                                @if($hospital->contact_person)
                                <div class="hospital-info-item">
                                    <div class="info-label">Contact Person</div>
                                    <div class="info-value">{{ $hospital->contact_person }}</div>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Reset Password via Email Card --}}
                <div class="profile-card" style="margin-top: 24px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-envelope"></i>
                            Reset Password via Email
                        </h3>
                    </div>
                    <div class="card-body">
                        <p style="margin-bottom: 20px; color: var(--dark-light);">
                            We'll send you a secure link to reset your password via email.
                        </p>
                        <form id="emailResetForm" action="{{ $sendResetLinkRoute }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Your Email
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    value="{{ $user->email }}" 
                                    disabled
                                >
                            </div>
                            <button type="submit" class="btn btn-secondary btn-full">
                                <i class="fas fa-paper-plane"></i>
                                Send Reset Link
                            </button>
                        </form>
                        <p style="margin-top: 15px; font-size: 0.85rem; color: var(--dark-light);">
                            <i class="fas fa-info-circle"></i>
                            Check your email for the password reset link
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right Column: Password Management --}}
            <div>
                {{-- Change Password Card --}}
                <div class="profile-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-key"></i>
                            Change Password
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="changePasswordForm" action="{{ $updatePasswordRoute }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Current Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-with-icon">
                                    <input 
                                        type="password" 
                                        name="current_password" 
                                        class="form-control @error('current_password') is-invalid @enderror" 
                                        placeholder="Enter current password"
                                        value="{{ old('current_password') }}"
                                    >
                                    <i class="input-icon fas fa-eye" onclick="togglePassword(this)"></i>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-with-icon">
                                    <input 
                                        type="password" 
                                        name="new_password" 
                                        id="newPassword"
                                        class="form-control @error('new_password') is-invalid @enderror" 
                                        placeholder="Enter new password"
                                        value="{{ old('new_password') }}"
                                        oninput="checkPasswordStrength(this.value)"
                                    >
                                    <i class="input-icon fas fa-eye" onclick="togglePassword(this)"></i>
                                </div>
                                <div class="password-strength">
                                    <div id="strengthBar" class="strength-bar"></div>
                                </div>
                                <div id="strengthText" class="strength-text"></div>
                                @error('new_password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Confirm New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-with-icon">
                                    <input 
                                        type="password" 
                                        name="new_password_confirmation" 
                                        class="form-control" 
                                        placeholder="Confirm new password"
                                    >
                                    <i class="input-icon fas fa-eye" onclick="togglePassword(this)"></i>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-full">
                                <i class="fas fa-save"></i>
                                Update Password
                            </button>
                        </form>

                        <div style="margin-top: 20px; padding: 15px; background: var(--light); border-radius: var(--radius);">
                            <h4 style="font-size: 0.9rem; margin-bottom: 10px; color: var(--dark);">
                                <i class="fas fa-shield-alt"></i>
                                Password Requirements
                            </h4>
                            <ul style="font-size: 0.85rem; color: var(--dark-light); padding-left: 20px;">
                                <li>Minimum 8 characters</li>
                                <li>At least one uppercase letter</li>
                                <li>At least one number</li>
                                <li>At least one special character</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="profile-card" style="margin-top: 24px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; gap: 15px;">
                            <a href="{{ $dashboardRoute }}" class="btn btn-secondary">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                            
                            @if($isHospitalManager)
                            <a href="{{ route('hospital.appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-calendar-check"></i>
                                View Appointments
                            </a>
                            @endif
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-full">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide toasts
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%) scale(0.8)';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        });

        // Form submission handling
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    submitBtn.disabled = true;
                    
                    // Re-enable button after 5 seconds in case of error
                    setTimeout(() => {
                        if (submitBtn.disabled) {
                            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                            submitBtn.disabled = false;
                        }
                    }, 5000);
                }
            });
        });

        // Add focus effects to form inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Check password strength on page load if there's a value
        const newPasswordInput = document.getElementById('newPassword');
        if (newPasswordInput && newPasswordInput.value) {
            checkPasswordStrength(newPasswordInput.value);
        }
    });

    // Toggle password visibility
    function togglePassword(icon) {
        const input = icon.parentElement.querySelector('input');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Check password strength
    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        if (!strengthBar || !strengthText) return;
        
        let strength = 0;
        let text = '';
        
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        switch(strength) {
            case 0:
                strengthBar.style.width = '0%';
                strengthBar.className = 'strength-bar';
                text = '';
                break;
            case 1:
                strengthBar.style.width = '25%';
                strengthBar.className = 'strength-bar strength-weak';
                text = 'Weak';
                break;
            case 2:
                strengthBar.style.width = '50%';
                strengthBar.className = 'strength-bar strength-fair';
                text = 'Fair';
                break;
            case 3:
                strengthBar.style.width = '75%';
                strengthBar.className = 'strength-bar strength-good';
                text = 'Good';
                break;
            case 4:
                strengthBar.style.width = '100%';
                strengthBar.className = 'strength-bar strength-strong';
                text = 'Strong';
                break;
        }
        
        strengthText.textContent = text;
    }
</script>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show error toast for form errors
        const errors = @json($errors->all());
        if (errors.length > 0) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'toast toast-error';
            errorDiv.innerHTML = `
                <div class="toast-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="toast-content">
                    <h4>Please fix the errors below:</h4>
                    <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                </div>
            `;
            document.querySelector('.profile-wrapper').prepend(errorDiv);
            
            setTimeout(() => {
                errorDiv.style.opacity = '0';
                errorDiv.style.transform = 'translateX(100%) scale(0.8)';
                setTimeout(() => errorDiv.remove(), 300);
            }, 8000);
        }
    });
</script>
@endif

@endsection