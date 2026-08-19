<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern-article-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ckeditor-overrides.css') }}">
</head>
<body>
    <div class="article-form-container">
        <div class="form-card">
            <div class="form-header">
                <h1 class="form-title">Edit Berita</h1>
                <p class="form-subtitle">Perbarui dan publikasikan ulang artikel berita</p>
            </div>

            @if ($errors->any())
                <div class="error-alert">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" id="articleForm">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div class="form-group">
                    <label for="title" class="form-label">Judul Berita</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        class="form-input" 
                        placeholder="Masukkan judul berita yang menarik..."
                        value="{{ old('title', $article->title) }}" 
                        required
                        maxlength="255"
                    >
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="" disabled>Pilih kategori berita</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $article->category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gambar Unggulan -->
                <div class="form-group">
                    <label for="featured_image" class="form-label">Gambar Unggulan</label>
                    @if($article->featured_image)
                        <div style="margin-bottom: 1rem;">
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="Current Image" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem;">Gambar saat ini</p>
                        </div>
                    @endif
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
                            <div>
                                <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <div>
                                    <strong>Klik untuk {{ $article->featured_image ? 'mengganti' : 'upload' }} gambar</strong><br>
                                    <span>atau drag & drop file di sini</span><br>
                                    <small>PNG, JPG, GIF hingga 2MB</small>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Konten -->
                <div class="form-group">
                    <label for="content" class="form-label">Konten Berita</label>
                    <textarea 
                        name="content" 
                        id="content" 
                        placeholder="Tulis konten berita di sini..."
                    >{{ old('content', $article->content) }}</textarea>
                </div>

                <!-- Button Group -->
                <div class="button-group">
                    <a href="{{ route('dashboard') }}" class="cancel-button">
                        Batal
                    </a>
                    <button type="submit" class="submit-button" id="submitBtn">
                        <span>Perbarui Berita</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        // Initialize CKEditor
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
                // Preserve our custom styling
                ui: {
                    poweredBy: {
                        position: 'inside',
                    }
                }
            })
            .catch(error => {
                console.error(error);
            });

        // File upload handler
        function handleFileSelect(input) {
            const fileLabel = document.getElementById('fileLabel');
            const fileName = input.files[0]?.name;
            
            if (fileName) {
                fileLabel.innerHTML = `
                    <div>
                        <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <strong>File dipilih: ${fileName}</strong><br>
                            <span>Klik untuk mengubah file</span>
                        </div>
                    </div>
                `;
                fileLabel.style.borderColor = '#10b981';
                fileLabel.style.backgroundColor = '#f0fdf4';
                fileLabel.style.color = '#059669';
            }
        }

        // Form submission handler
        document.getElementById('articleForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const title = document.getElementById('title').value.trim();
            const category = document.getElementById('category_id').value;
            
            // Basic validation
            if (!title) {
                e.preventDefault();
                alert('Judul berita harus diisi!');
                document.getElementById('title').focus();
                return;
            }
            
            if (!category) {
                e.preventDefault();
                alert('Kategori berita harus dipilih!');
                document.getElementById('category_id').focus();
                return;
            }
            
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<span>Memperbarui...</span>';
            submitBtn.disabled = true;
        });

        // Real-time character count for title
        const titleInput = document.getElementById('title');
        titleInput.addEventListener('input', function() {
            const maxLength = 255;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;
            
            // Create or update character counter
            let counter = document.querySelector('.title-counter');
            if (!counter) {
                counter = document.createElement('div');
                counter.className = 'title-counter';
                counter.style.cssText = `
                    font-size: 0.875rem;
                    color: #6b7280;
                    margin-top: 0.5rem;
                    text-align: right;
                `;
                titleInput.parentNode.appendChild(counter);
            }
            
            counter.textContent = `${currentLength}/${maxLength} karakter`;
            
            if (remaining < 20) {
                counter.style.color = '#ef4444';
            } else if (remaining < 50) {
                counter.style.color = '#f59e0b';
            } else {
                counter.style.color = '#6b7280';
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
    </script>
</body>
</html>
