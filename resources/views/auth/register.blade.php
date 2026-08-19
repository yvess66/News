<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Daftar Akun</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Modern Register CSS -->
    <link rel="stylesheet" href="{{ asset('css/modern-register.css') }}">
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <h1 class="register-title">Buat Akun Baru</h1>
                <p class="register-subtitle">Bergabunglah dengan komunitas berita kami</p>
            </div>

            <!-- Back to Home Link -->
            <a href="{{ url('/') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Halaman Utama
            </a>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Nama Lengkap') }}</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input id="name" class="form-input with-icon" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda">
                    </div>
                    @error('name')
                        <div class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input id="email" class="form-input with-icon" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan alamat email Anda">
                    </div>
                    @error('email')
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
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input id="password" class="form-input with-icon" type="password" name="password" required autocomplete="new-password" placeholder="Buat password yang kuat">
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-bars">
                            <div class="strength-bar" id="bar1"></div>
                            <div class="strength-bar" id="bar2"></div>
                            <div class="strength-bar" id="bar3"></div>
                            <div class="strength-bar" id="bar4"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Lemah</div>
                        <ul class="strength-requirements">
                            <li class="strength-requirement" id="lengthReq">
                                <span class="requirement-icon">✗</span>
                                Minimal 8 karakter
                            </li>
                            <li class="strength-requirement" id="uppercaseReq">
                                <span class="requirement-icon">✗</span>
                                Huruf besar (A-Z)
                            </li>
                            <li class="strength-requirement" id="numberReq">
                                <span class="requirement-icon">✗</span>
                                Angka (0-9)
                            </li>
                            <li class="strength-requirement" id="specialReq">
                                <span class="requirement-icon">✗</span>
                                Karakter khusus (!@#$)
                            </li>
                        </ul>
                    </div>
                    
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
                    <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <input id="password_confirmation" class="form-input with-icon" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password Anda">
                    </div>
                    @error('password_confirmation')
                        <div class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Hint -->
                <div class="form-group">
                    <label for="password_hint" class="form-label">{{ __('Hint Password') }}</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m0 0V6a2 2 0 012-2h8a2 2 0 012 2v2" />
                        </svg>
                        <input id="password_hint" class="form-input with-icon" type="text" name="password_hint" value="{{ old('password_hint') }}" required placeholder="Contoh: Warna favorit saya">
                    </div>
                    <div class="hint-description">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Hint ini akan membantu Anda mengingat password saat reset. Contoh: warna favorit, makanan kesukaan, atau tempat lahir.</span>
                    </div>
                    @error('password_hint')
                        <div class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary" id="submitBtn">
                    {{ __('Daftar Akun') }}
                </button>
            </form>

            <!-- Login Link -->
            <div class="form-links">
                <a href="{{ route('login') }}" class="login-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Sudah punya akun? Masuk sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const strengthIndicator = document.getElementById('passwordStrength');
            
            // Password strength checker
            function checkPasswordStrength(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };
                
                let strength = 0;
                Object.values(requirements).forEach(req => req && strength++);
                
                return { strength, requirements };
            }
            
            function updatePasswordStrength(password) {
                if (!password) {
                    strengthIndicator.style.display = 'none';
                    return;
                }
                
                strengthIndicator.style.display = 'block';
                const { strength, requirements } = checkPasswordStrength(password);
                
                // Update bars
                const bars = document.querySelectorAll('.strength-bar');
                bars.forEach((bar, index) => {
                    bar.className = 'strength-bar';
                    if (index < strength) {
                        if (strength <= 1) bar.classList.add('weak');
                        else if (strength <= 2) bar.classList.add('medium');
                        else bar.classList.add('strong');
                    }
                });
                
                // Update text
                const strengthText = document.getElementById('strengthText');
                strengthText.className = 'strength-text';
                if (strength <= 1) {
                    strengthText.textContent = 'Lemah';
                    strengthText.classList.add('weak');
                } else if (strength <= 2) {
                    strengthText.textContent = 'Sedang';
                    strengthText.classList.add('medium');
                } else if (strength <= 3) {
                    strengthText.textContent = 'Kuat';
                    strengthText.classList.add('strong');
                } else {
                    strengthText.textContent = 'Sangat Kuat';
                    strengthText.classList.add('strong');
                }
                
                // Update requirements
                updateRequirement('lengthReq', requirements.length);
                updateRequirement('uppercaseReq', requirements.uppercase);
                updateRequirement('numberReq', requirements.number);
                updateRequirement('specialReq', requirements.special);
            }
            
            function updateRequirement(id, valid) {
                const element = document.getElementById(id);
                const icon = element.querySelector('.requirement-icon');
                
                element.className = 'strength-requirement';
                if (valid) {
                    element.classList.add('valid');
                    icon.textContent = '✓';
                } else {
                    icon.textContent = '✗';
                }
            }
            
            // Password input event
            passwordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value);
            });
            
            // Confirm password validation
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value && this.value !== passwordInput.value) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
            
            // Form submission
            form.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Mendaftar...';
            });
            
            // Input validation
            form.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });
                
                input.addEventListener('input', function() {
                    this.classList.remove('error');
                });
            });
            
            // Email validation
            document.getElementById('email').addEventListener('input', function() {
                if (this.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
        });
    </script>
</body>
</html>
