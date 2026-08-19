<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita Baru | News Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern-article-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ckeditor-overrides.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="description" content="Tambah artikel berita baru dengan editor yang modern dan professional">
</head>
<body>
    <div class="article-form-container">
        <!-- Background decoration -->
        <div class="decoration-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="form-card">
            <!-- Header Section -->
            <div class="form-header">
                <div class="header-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 9H15" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 13H15" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 17H11" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <defs>
                            <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#3b82f6"/>
                                <stop offset="100%" style="stop-color:#10b981"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <h1 class="form-title">Tambah Berita Baru</h1>
                <p class="form-subtitle">Buat dan publikasikan artikel berita yang menarik dan informatif</p>
            </div>

            @if ($errors->any())
                <div class="error-alert">
                    <div class="error-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <span>Mohon perbaiki kesalahan berikut:</span>
                    </div>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif            <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
                @csrf

                <!-- Judul -->
                <div class="form-group">
                    <label for="title" class="form-label">
                        <span class="label-icon">📝</span>
                        Judul Berita
                        <span class="required-asterisk">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            class="form-input" 
                            placeholder="Masukkan judul berita yang menarik dan deskriptif..."
                            value="{{ old('title') }}" 
                            required
                            maxlength="255"
                        >
                        <div class="input-border"></div>
                    </div>
                    <div class="character-counter" id="titleCounter">0/255 karakter</div>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label for="category_id" class="form-label">
                        <span class="label-icon">🏷️</span>
                        Kategori
                        <span class="required-asterisk">*</span>
                    </label>
                    <div class="select-wrapper">
                        <select name="category_id" id="category_id" class="form-select" required>                            <option value="" disabled selected>Pilih kategori berita</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="select-arrow">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Gambar Unggulan -->
                <div class="form-group">
                    <label for="featured_image" class="form-label">
                        <span class="label-icon">🖼️</span>
                        Gambar Unggulan
                        <span class="optional-text">(opsional)</span>
                    </label>
                    <div class="file-upload-wrapper">
                        <input 
                            type="file" 
                            name="featured_image" 
                            id="featured_image" 
                            class="file-upload-input" 
                            accept="image/*"
                            onchange="handleFileSelect(this)"
                        >
                        <label for="featured_image" class="file-upload-label" id="fileLabel">
                            <div class="upload-content">
                                <div class="upload-icon">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                                        <path d="M14.5 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V7.5L14.5 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 13L12 17L8 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 17V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="upload-text">
                                    <h4>Klik untuk upload gambar</h4>
                                    <p>atau seret & lepas file di sini</p>
                                    <small>PNG, JPG, GIF hingga 2MB</small>
                                </div>
                            </div>                        </label>
                    </div>
                </div>

                <!-- Konten -->
                <div class="form-group">
                    <label for="content" class="form-label">
                        <span class="label-icon">📄</span>
                        Konten Berita
                        <span class="required-asterisk">*</span>
                    </label>
                    <div class="editor-wrapper">
                        <textarea 
                            name="content" 
                            id="content" 
                            placeholder="Tulis konten berita yang informatif dan menarik di sini..."
                        >{{ old('content') }}</textarea>
                    </div>
                    <div class="editor-help">
                        <p>💡 Tips: Gunakan toolbar untuk memformat teks, menambahkan gambar, dan membuat konten yang menarik</p>
                    </div>
                </div>

                <!-- Button Group -->
                <div class="button-group">
                    <a href="{{ route('dashboard') }}" class="cancel-button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="submit-button" id="submitBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M22 11.08V12C21.9988 14.1564 21.3005 16.2547 20.0093 17.9818C18.7182 19.7088 16.9033 20.9725 14.8354 21.5839C12.7674 22.1953 10.5573 22.1219 8.53447 21.3746C6.51168 20.6273 4.78465 19.2461 3.61096 17.4371C2.43727 15.628 1.87979 13.4905 2.02168 11.3363C2.16356 9.18203 2.99721 7.13214 4.39828 5.49883C5.79935 3.86553 7.69279 2.72636 9.79619 2.24899C11.8996 1.77162 14.1003 1.98046 16.07 2.84L18 1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 4L12 14.01L9 11.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Publikasikan Berita</span>
                    </button>
                </div>
            </form>
        </div>
    </div>    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        // Initialize CKEditor
        let editor;
        ClassicEditor
            .create(document.querySelector('#content'), {
                ckfinder: {
                    uploadUrl: '{{ route("articles.uploadImage") }}?_token={{ csrf_token() }}'
                },
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ],
                placeholder: 'Tulis konten berita yang informatif dan menarik di sini...',
                // Preserve our custom styling
                ui: {
                    poweredBy: {
                        position: 'inside',
                    }
                }
            })            .then(ck => {
                editor = ck;
            })
            .catch(error => {
                console.error(error);
            });        // File upload handler with preview
        function handleFileSelect(input) {
            const fileLabel = document.getElementById('fileLabel');
            const file = input.files[0];
            
            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    fileLabel.innerHTML = `
                        <div class="upload-content">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <img src="${e.target.result}" alt="Preview" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #10b981;">
                                <div>
                                    <h4 style="color: #065f46; margin-bottom: 0.5rem;">✅ File berhasil dipilih</h4>
                                    <p style="color: #059669;"><strong>${file.name}</strong></p>
                                    <small style="color: #047857;">Ukuran: ${(file.size / 1024 / 1024).toFixed(2)} MB</small><br>
                                    <small style="color: #047857;">Klik untuk mengubah gambar</small>
                                </div>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);                
                fileLabel.classList.add('file-selected');
            }
        }

        // Real-time character counting with color coding
        const titleInput = document.getElementById('title');
        titleInput.addEventListener('input', function() {
            const maxLength = 255;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;
            
            const counter = document.getElementById('titleCounter');
            counter.textContent = `${currentLength}/${maxLength} karakter`;
            
            // Color coding
            if (remaining < 20) {
                counter.className = 'character-counter danger';
            } else if (remaining < 50) {
                counter.className = 'character-counter warning';            } else {
                counter.className = 'character-counter';
            }
        });        // Enhanced form submission with loading animation
        document.getElementById('articleForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const title = document.getElementById('title').value.trim();
            const category = document.getElementById('category_id').value;
            const content = editor ? editor.getData().trim() : '';
            
            // Validation with better UX
            if (!title) {
                e.preventDefault();
                showError('Judul berita harus diisi!', 'title');
                return;
            }
            
            if (title.length < 5) {
                e.preventDefault();
                showError('Judul berita minimal 5 karakter!', 'title');
                return;
            }
            
            if (!category) {
                e.preventDefault();
                showError('Kategori berita harus dipilih!', 'category_id');
                return;
            }
            
            if (!content || content.length < 50) {
                e.preventDefault();
                showError('Konten berita minimal 50 karakter!');
                return;
            }
            
            // Show loading state with animation
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Mempublikasikan...</span>
            `;
            submitBtn.disabled = true;
            
            // Add a small delay for better UX
            setTimeout(() => {
                // Form will submit naturally
            }, 500);
        });

        // Error display function
        function showError(message, fieldId = null) {
            // Create or update error alert
            let errorAlert = document.querySelector('.error-alert');
            if (!errorAlert) {
                errorAlert = document.createElement('div');
                errorAlert.className = 'error-alert';
                document.querySelector('form').insertBefore(errorAlert, document.querySelector('.form-group'));
            }
            
            errorAlert.innerHTML = `
                <div class="error-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span>Mohon perbaiki kesalahan berikut:</span>
                </div>
                <ul class="error-list">
                    <li>${message}</li>
                </ul>
            `;
            
            // Focus on problematic field
            if (fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.focus();
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            // Auto-remove error after 5 seconds
            setTimeout(() => {
                if (errorAlert.parentNode) {
                    errorAlert.style.opacity = '0';
                    errorAlert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        if (errorAlert.parentNode) {
                            errorAlert.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Auto-save draft functionality
        let saveTimeout;
        function autoSave() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                const formData = {
                    title: document.getElementById('title').value,
                    category_id: document.getElementById('category_id').value,
                    content: editor ? editor.getData() : '',
                    timestamp: new Date().toISOString()
                };
                localStorage.setItem('article_draft', JSON.stringify(formData));
                
                // Show save indicator
                showSaveIndicator();
            }, 2000);
        }

        function showSaveIndicator() {
            let indicator = document.querySelector('.save-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.className = 'save-indicator';
                indicator.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #10b981;
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 8px;
                    font-size: 0.875rem;
                    font-weight: 500;
                    z-index: 1000;
                    opacity: 0;
                    transform: translateY(-20px);
                    transition: all 0.3s ease;
                `;
                document.body.appendChild(indicator);
            }
            
            indicator.textContent = '💾 Draft tersimpan otomatis';
            indicator.style.opacity = '1';
            indicator.style.transform = 'translateY(0)';
            
            setTimeout(() => {
                indicator.style.opacity = '0';
                indicator.style.transform = 'translateY(-20px)';
            }, 2000);
        }

        // Load draft on page load
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('article_draft');
            if (draft) {
                try {
                    const data = JSON.parse(draft);
                    const draftAge = new Date() - new Date(data.timestamp);
                    
                    // Only load draft if less than 24 hours old
                    if (draftAge < 24 * 60 * 60 * 1000) {
                        if (data.title && !document.getElementById('title').value) {
                            document.getElementById('title').value = data.title;
                        }
                        if (data.category_id && !document.getElementById('category_id').value) {
                            document.getElementById('category_id').value = data.category_id;
                        }
                        
                        // Show draft loaded message
                        setTimeout(() => {
                            if (data.title || data.category_id || data.content) {
                                const draftAlert = document.createElement('div');
                                draftAlert.style.cssText = `
                                    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
                                    border: 1px solid #93c5fd;
                                    color: #1e40af;
                                    padding: 1rem;
                                    border-radius: 12px;
                                    margin-bottom: 1rem;
                                    display: flex;
                                    align-items: center;
                                    gap: 0.5rem;
                                `;
                                draftAlert.innerHTML = `
                                    <span>📝</span>
                                    <span>Draft tersimpan telah dimuat. Lanjutkan pengeditan Anda.</span>
                                    <button onclick="this.parentNode.remove()" style="margin-left: auto; background: none; border: none; color: #1e40af; cursor: pointer; font-size: 1.2rem;">×</button>
                                `;
                                document.querySelector('.form-card').insertBefore(draftAlert, document.querySelector('form'));
                            }
                        }, 1000);
                    }                } catch (e) {
                    console.error('Error loading draft:', e);
                }
            }
        });

        // Add auto-save listeners
        titleInput.addEventListener('input', autoSave);
        document.getElementById('category_id').addEventListener('change', autoSave);

        // Clear draft on successful submission
        window.addEventListener('beforeunload', function(e) {
            const form = document.getElementById('articleForm');
            if (form.checkValidity() && !form.classList.contains('submitting')) {
                // Don't clear draft if form is being submitted
                return;
            }
        });

        // Add form validation indicators
        function addValidationFeedback() {
            const inputs = document.querySelectorAll('.form-input, .form-select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.checkValidity() && this.value.trim()) {
                        this.style.borderColor = '#10b981';
                        this.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
                        this.style.backgroundColor = 'var(--input-bg)';
                        this.style.color = '#222222'; // Adding explicit black text color
                    } else if (this.value.trim()) {
                        this.style.borderColor = '#ef4444';
                        this.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                        this.style.backgroundColor = 'var(--input-bg)';
                        this.style.color = '#222222'; // Adding explicit black text color
                    } else {
                        this.style.borderColor = '#e2e8f0';
                        this.style.boxShadow = 'none';
                        this.style.backgroundColor = 'var(--input-bg)';
                        this.style.color = '#222222'; // Adding explicit black text color
                    }
                });
                
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#3b82f6';
                    this.style.boxShadow = '0 0 0 4px rgba(59, 130, 246, 0.1)';
                    this.style.backgroundColor = 'var(--input-bg)';
                    this.style.color = '#222222'; // Adding explicit black text color
                    this.classList.add('input-default-bg');
                });
            });
        }

        // Initialize validation feedback
        addValidationFeedback();

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+S to save draft
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                autoSave();
                showSaveIndicator();
            }
            
            // Ctrl+Enter to submit
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('articleForm').dispatchEvent(new Event('submit'));
            }
        });

        // Add tooltips for keyboard shortcuts
        document.addEventListener('DOMContentLoaded', function() {
            const shortcutInfo = document.createElement('div');
            shortcutInfo.style.cssText = `
                position: fixed;
                bottom: 20px;
                left: 20px;
                background: rgba(15, 23, 42, 0.9);
                color: white;
                padding: 0.75rem 1rem;
                border-radius: 8px;
                font-size: 0.75rem;
                z-index: 1000;
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s ease;
            `;
            shortcutInfo.innerHTML = `
                💡 Keyboard shortcuts:<br>
                <strong>Ctrl+S</strong> - Simpan draft<br>
                <strong>Ctrl+Enter</strong> - Submit form
            `;
            document.body.appendChild(shortcutInfo);
            
            // Show shortcuts after 3 seconds
            setTimeout(() => {
                shortcutInfo.style.opacity = '1';
                shortcutInfo.style.transform = 'translateY(0)';
            }, 3000);
            
            // Hide after 8 seconds
            setTimeout(() => {
                shortcutInfo.style.opacity = '0';
                shortcutInfo.style.transform = 'translateY(20px)';
            }, 8000);
        });
    </script>
</body>
</html>
