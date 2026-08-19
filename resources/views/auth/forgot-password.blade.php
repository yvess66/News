<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Reset Password</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Forgot Password CSS -->
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <!-- Header -->
            <div class="forgot-header">
                <h1 class="forgot-title">Reset Password</h1>
                <p class="forgot-subtitle">Lupa kata sandi? Tidak masalah</p>
            </div>

            <!-- Back to Login Link -->
            <a href="{{ route('login') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Login
            </a>

            <!-- Description -->
            <div class="description-box">
                <div class="description-content">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        Masukkan email, hint password, dan password baru untuk reset password Anda.
                    </div>
                </div>
            </div>

            <!-- Password Hint Display -->
            <div id="hintDisplay" class="hint-display" style="display: none;">
                <div class="hint-content">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <div>
                        <strong>Hint Password Anda:</strong>
                        <p id="hintText"></p>
                        <small>Ingat sesuatu? <a href="{{ route('login') }}" class="hint-login-link">Coba login sekarang</a></small>
                    </div>
                </div>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.reset.direct') }}" id="forgotForm">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan alamat email Anda">
                    @error('email')
                        <div class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Hint -->
                <div class="form-group">
                    <label for="hint" class="form-label">Hint Password</label>
                    <input id="hint" class="form-input" type="text" name="hint" value="{{ old('hint') }}" required placeholder="Masukkan hint password Anda">
                    @error('hint')
                        <div class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input id="password" class="form-input" type="password" name="password" required placeholder="Masukkan password baru">
                    @error('password')
                        <div class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required placeholder="Konfirmasi password baru">
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    
                    <button type="submit" class="submit-button" id="submitBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Reset Password
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="back-to-login">
                <a href="{{ route('login') }}" class="login-link">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Ingat password? Masuk sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript for Enhanced UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forgotForm = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');
            // const hintBtn = document.getElementById('hintBtn'); // Commented out since button was removed
            const emailInput = document.getElementById('email');
            const hintInput = document.getElementById('hint');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const hintDisplay = document.getElementById('hintDisplay');
            const hintText = document.getElementById('hintText');

            // Success popup function
            function showSuccessPopup(message) {
                // Create popup overlay
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                `;

                // Create popup content
                const popup = document.createElement('div');
                popup.style.cssText = `
                    background: white;
                    border-radius: 16px;
                    padding: 32px;
                    max-width: 400px;
                    width: 90%;
                    text-align: center;
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
                    position: relative;
                    animation: popupScale 0.3s ease-out;
                `;

                popup.innerHTML = `
                    <div style="
                        width: 64px;
                        height: 64px;
                        background: linear-gradient(135deg, #10b981, #059669);
                        border-radius: 50%;
                        margin: 0 auto 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        <svg width="32" height="32" fill="white" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 style="
                        font-size: 24px;
                        font-weight: 700;
                        color: #1f2937;
                        margin-bottom: 12px;
                    ">Berhasil!</h3>
                    <p style="
                        color: #6b7280;
                        margin-bottom: 24px;
                        font-size: 16px;
                    ">${message}</p>
                    <button id="popupOkBtn" style="
                        background: linear-gradient(135deg, #3b82f6, #2563eb);
                        color: white;
                        border: none;
                        padding: 12px 24px;
                        border-radius: 8px;
                        font-weight: 600;
                        cursor: pointer;
                        font-size: 16px;
                        transition: all 0.2s;
                    ">OK</button>
                `;

                // Add animation styles
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes popupScale {
                        0% { transform: scale(0.8); opacity: 0; }
                        100% { transform: scale(1); opacity: 1; }
                    }
                `;
                document.head.appendChild(style);

                overlay.appendChild(popup);
                document.body.appendChild(overlay);

                // Handle close
                document.getElementById('popupOkBtn').addEventListener('click', function() {
                    document.body.removeChild(overlay);
                    // Reset form instead of redirecting
                    forgotForm.reset();
                    // Hide hint display
                    hintDisplay.style.display = 'none';
                });

                // Close on overlay click
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        document.body.removeChild(overlay);
                        // Reset form instead of redirecting
                        forgotForm.reset();
                        // Hide hint display
                        hintDisplay.style.display = 'none';
                    }
                });
            }

            // Hint button functionality (disabled since button was removed)
            /*
            hintBtn.addEventListener('click', function() {
                const email = emailInput.value.trim();
                
                if (!email) {
                    emailInput.classList.add('error');
                    emailInput.focus();
                    return;
                }

                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    emailInput.classList.add('error');
                    emailInput.focus();
                    return;
                }

                // Show loading state
                hintBtn.disabled = true;
                hintBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Mencari...
                `;

                // Make AJAX request to get hint
                fetch('/password/hint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.hint) {
                        hintText.textContent = data.hint;
                        hintDisplay.style.display = 'block';
                        hintDisplay.scrollIntoView({ behavior: 'smooth' });
                        hintInput.value = data.hint;
                    } else {
                        alert(data.message || 'Email tidak ditemukan atau tidak memiliki hint.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                })
                .finally(() => {
                    // Reset button state
                    hintBtn.disabled = false;
                    hintBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        Lihat Hint Password
                    `;
                });
            });
            */

            // Form submission with AJAX
            forgotForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!emailInput.value.trim()) {
                    emailInput.classList.add('error');
                    emailInput.focus();
                    return;
                }
                
                if (!hintInput.value.trim()) {
                    hintInput.classList.add('error');
                    hintInput.focus();
                    return;
                }
                
                if (!passwordInput.value) {
                    passwordInput.classList.add('error');
                    passwordInput.focus();
                    return;
                }
                
                if (passwordInput.value !== passwordConfirmInput.value) {
                    passwordConfirmInput.classList.add('error');
                    passwordConfirmInput.focus();
                    alert('Konfirmasi password tidak sesuai.');
                    return;
                }

                // Show loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Mereset...
                `;

                // Make AJAX request to reset password
                fetch('/password/reset-direct', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: emailInput.value,
                        hint: hintInput.value,
                        password: passwordInput.value,
                        password_confirmation: passwordConfirmInput.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessPopup(data.message);
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat mereset password.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Reset Password
                    `;
                });
            });

            // Input validation styling
            function validateInput(input) {
                if (input.value.trim() === '') {
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            }

            [emailInput, hintInput, passwordInput, passwordConfirmInput].forEach(input => {
                input.addEventListener('blur', function() {
                    validateInput(this);
                });

                input.addEventListener('input', function() {
                    this.classList.remove('error');
                });
            });

            // Password confirmation validation
            passwordConfirmInput.addEventListener('input', function() {
                if (passwordInput.value && this.value && passwordInput.value !== this.value) {
                    this.style.borderColor = 'var(--error)';
                } else {
                    this.style.borderColor = '';
                }
            });

            // Email validation
            emailInput.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.style.borderColor = 'var(--error)';
                } else {
                    this.style.borderColor = '';
                }
                // Hide hint display when email changes
                hintDisplay.style.display = 'none';
            });
        });
    </script>
</body>
</html>
