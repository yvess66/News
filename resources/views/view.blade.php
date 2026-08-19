<x-app-layout>
    <!-- Header dengan styling sama seperti welcome.blade.php -->
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="{{ url('/') }}">Portal Berita</a>
            </div>
            <nav class="nav-links">
                <form action="{{ route('home') }}" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}">
                    <div class="search-button-wrapper">
                        <button type="submit" class="search-button">
                            <img src="{{ asset('storage/uploads/search.png') }}" alt="Search" class="search-icon">
                        </button>
                    </div>
                </form>
                
                <!-- Notification Icon -->
                <div class="notification-wrapper">
                    <button id="notificationButton" type="button" class="notification-button">
                        <img src="{{ asset('storage/uploads/notification.png') }}" alt="Notifications" class="notification-icon">
                        @if(isset($newArticles) && count($newArticles) > 0)
                            <span class="notification-badge">
                                {{ count($newArticles) }}
                            </span>
                        @endif
                    </button>
                    
                    <div id="notificationDropdown" class="notification-dropdown">
                        <div class="header">
                            <h3>Berita Terbaru</h3>
                        </div>
                        <div>
                            @if(isset($newArticles))
                                @forelse($newArticles as $newArticle)
                                    <a href="{{ route('articles.show', $newArticle->slug) }}" class="notification-item">
                                        <div class="notification-content">
                                            <div class="notification-image">
                                                <img src="{{ $newArticle->featured_image ? asset('storage/' . $newArticle->featured_image) : 'https://via.placeholder.com/50x50' }}" alt="{{ $newArticle->title }}">
                                            </div>
                                            <div class="notification-text">
                                                <p class="notification-title">{{ Str::limit($newArticle->title, 40) }}</p>
                                                <p class="notification-time">{{ $newArticle->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="notification-empty">
                                        <p>Tidak ada berita baru</p>
                                    </div>
                                @endforelse
                            @else
                                <div class="notification-empty">
                                    <p>Tidak ada berita baru</p>
                                </div>
                            @endif
                        </div>
                        @if(isset($newArticles) && count($newArticles) > 0)
                            <div class="notification-footer">
                                <a href="{{ route('home') }}">Lihat Semua</a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <ul>
                    @guest
                        <li><a href="{{ route('login') }}" class="btn">Login</a></li>
                        <li><a href="{{ route('register') }}" class="btn">Register</a></li>
                    @else
                        <li><span>Halo, {{ Auth::user()->name }}</span></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn">Logout</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </nav>
        </div>
    </header>

    <div class="category-nav">
        <div class="category-nav-container">
            <ul>
                @if(isset($categories))
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('articles.byCategory', $category->id) }}" 
                               class="{{ $article->category && $article->category->id == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    @php
        // Definisikan variabel $dateToShow di awal agar tersedia untuk semua bagian template
        if ($article->published_at) {
            $dateToShow = $article->published_at instanceof \DateTime 
                ? $article->published_at->format('d M Y') 
                : date('d M Y', strtotime($article->published_at));
        } else {
            $dateToShow = $article->created_at instanceof \DateTime 
                ? $article->created_at->format('d M Y') 
                : date('d M Y', strtotime($article->created_at));
        }
    @endphp    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Grid layout container - Force side by side layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Column (2/3 width - 66.67%) -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Main Article Card -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 sm:p-8">
                                <!-- Unified Article Container -->
                                <div class="article-unified-container">
                        <!-- Breadcrumb Navigation -->
                        <div class="breadcrumb mb-4 flex items-center text-sm text-gray-500">
                            <a href="{{ url('/') }}" class="hover:text-blue-600">Beranda</a>
                            <span class="mx-2">/</span>
                            @if($article->category)
                                <a href="{{ url('/category/' . $article->category->id) }}" class="hover:text-blue-600">{{ $article->category->name }}</a>
                                <span class="mx-2">/</span>
                            @endif
                            <span class="text-gray-700 truncate">{{ $article->title }}</span>
                        </div>
                        
                        <!-- Category Badge - Prominent at top -->
                        @if ($article->category)
                            <div class="mb-4">
                                <a href="{{ url('/category/' . $article->category->id) }}" class="category-badge-prominent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ $article->category->name }}
                                </a>
                            </div>
                        @endif
                        
                        <!-- Article Title -->
                        <h1 class="article-headline-unified">{{ $article->title }}</h1>
                        
                        <!-- Publication Info -->
                        <div class="article-metadata-unified">
                            <!-- Author & Date -->
                            <div class="flex items-center">
                                @if ($article->user)
                                    <div class="author-avatar-unified">
                                        {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $article->user->name }}</p>
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $dateToShow }}
                                        </p>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $dateToShow }}
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Quick Share Buttons -->
                            <div class="quick-share-buttons">
                                <button onclick="shareToFacebook()" class="quick-share-btn" title="Share to Facebook">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </button>
                                <button onclick="shareToX()" class="quick-share-btn" title="Share to X (Twitter)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </button>
                                <button onclick="shareToWhatsApp()" class="quick-share-btn" title="Share to WhatsApp">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.488"/>
                                    </svg>
                                </button>
                                <button onclick="toggleShareDropdown()" class="quick-share-btn" title="More sharing options">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                  <!-- Featured Image - Larger, optimized for article view -->                        @if($article->featured_image)
                            <figure class="featured-image-unified">
                                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                     alt="{{ $article->title }}" 
                                     class="featured-img">
                            </figure>
                        @elseif($article->summary)
                            <div class="article-summary-unified">
                                {{ $article->summary }}
                            </div>
                        @endif
                        
                        <!-- Article Content -->
                        <div class="article-content-unified">
                            {!! $article->content !!}
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="article-actions-unified">
                            <div class="flex flex-wrap justify-center gap-3">
                                <a href="{{ url('/') }}" class="unified-action-btn bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali
                                </a>
                                
                                <button onclick="window.print()" class="unified-action-btn bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Cetak
                                </button>
                                
                                <button onclick="toggleShareDropdown()" class="unified-action-btn bg-blue-500 text-white hover:bg-blue-600" id="shareButton">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>                                    Bagikan
                                </button>
                            </div>
                        </div>                        </div>
                    </div>
                </div>

                        <!-- Comments Section Card - Separate card below article -->                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-4 sm:p-6">
                                <!-- Comments Header -->
                                <div class="comment-header">
                                    <div class="flex items-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Komentar
                                            @if($article->comments->count() > 0)
                                                <span class="text-sm font-normal text-gray-500">({{ $article->comments->count() }})</span>
                                            @endif
                                        </h3>
                                    </div>
                                </div>

                                <!-- Existing Comments -->                                <div class="comments-list">
                                    @forelse($article->comments as $comment)
                                        <div class="comment-item">
                                            <div class="comment-author-info">
                                                <div class="comment-avatar">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                                <div class="comment-meta">
                                                    <h5 class="comment-author-name">{{ $comment->user->name }}</h5>
                                                    <span class="comment-date">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="comment-content">
                                                <p>{{ $comment->content }}</p>
                                            </div>
                                            
                                            <!-- Delete Button for authorized users -->
                                            @auth
                                                @if(Auth::id() == $comment->user_id || Auth::user()->role == 'admin')
                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="comment-actions">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-comment-btn" 
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>                                    @empty
                                        <div class="no-comments">
                                            <h4>Belum Ada Komentar</h4>
                                            <p>Jadilah yang pertama memberikan komentar pada artikel ini.</p>
                                        </div>
                                    @endforelse
                                </div>

                        <!-- Comment Form -->
                        @auth                            <div class="comment-form-section">
                                <div class="comment-form-header">
                                    <h4>Tinggalkan Komentar</h4>
                                </div>
                                <div class="comment-form-description">
                                    <p>Berikan pendapat atau tanggapan Anda tentang artikel ini.</p>
                                </div>
                                
                                <form action="{{ route('comments.store', $article->id) }}" method="POST" class="comment-form">
                                    @csrf
                                    <div class="form-user-info">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <span class="user-name">{{ Auth::user()->name }}</span>
                                    </div>
                                    
                                    <div class="form-input-group">
                                        <textarea name="content" rows="4" 
                                                class="comment-textarea" 
                                                placeholder="Tulis komentar Anda di sini... (minimal 10 karakter)"
                                                required></textarea>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <div class="form-guidelines">
                                            <small>Pastikan komentar sesuai dengan topik dan bersifat konstruktif.</small>
                                        </div>
                                        <button type="submit" class="submit-comment-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Kirim Komentar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else                            <div class="login-prompt">
                                <div class="login-prompt-content">
                                    <h4>Login Diperlukan</h4>
                                    <p>Anda harus login untuk dapat memberikan komentar pada artikel ini.</p>
                                    <a href="{{ route('login') }}" class="login-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                        Login Sekarang
                                    </a>
                                </div>
                            </div>@endauth
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Column (1/3 width - 33.33%) -->
                <div class="lg:col-span-1">
                    <!-- Advertisement Banner -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
                        <p class="text-xs text-gray-500 mb-2 text-center">ADVERTISEMENT</p>
                        <div class="bg-gray-100 h-32 rounded flex items-center justify-center">
                            <span class="text-gray-400 text-sm">Ad Space 300x250</span>
                        </div>
                    </div>
                    
                    <!-- Berita Terkait Sidebar - Like CNN Indonesia -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">
                                Berita Terkait
                            </h3>
                        </div>
                        
                        @if($article->category)
                            @php
                                // Mengambil artikel terkait dengan kategori yang sama
                                $relatedArticles = \App\Models\Article::where('category_id', $article->category_id)
                                    ->where('id', '!=', $article->id) // Kecuali artikel yang sedang dilihat
                                    ->latest('published_at') // Urut berdasarkan tanggal publikasi terbaru
                                    ->take(5) // Batasi hingga 5 artikel seperti pada gambar referensi
                                    ->get();
                                
                                // Hitung total artikel dalam kategori ini untuk link "Lihat Semua"
                                $totalInCategory = \App\Models\Article::where('category_id', $article->category_id)->count();
                            @endphp
                            
                            @if($relatedArticles->count() > 0)
                                <div class="p-4">
                                    @foreach($relatedArticles as $index => $relatedArticle)
                                        <a href="{{ url('/articles/' . $relatedArticle->slug) }}" class="block hover:bg-gray-50 p-3 rounded-lg transition-colors duration-200 {{ $index > 0 ? 'border-t border-gray-100 mt-3 pt-3' : '' }}">
                                            <div class="flex space-x-3">
                                                <div class="flex-shrink-0">                                                    @if($relatedArticle->featured_image)
                                                        <img src="{{ asset('storage/' . $relatedArticle->featured_image) }}" 
                                                             alt="{{ $relatedArticle->title }}" 
                                                             class="w-20 h-14 object-cover rounded-md"
                                                             loading="lazy">
                                                    @else
                                                        <div class="w-20 h-14 bg-gray-200 rounded-md">
                                                        </div>
                                                    @endif
                                                </div>                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-base font-semibold text-gray-900 line-clamp-2 hover:text-blue-600 leading-tight mb-2">
                                                        {{ $relatedArticle->title }}
                                                    </h4>
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <span class="text-blue-600 font-medium">{{ $article->category->name }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span>
                                                            @if($relatedArticle->published_at)
                                                                {{ \Carbon\Carbon::parse($relatedArticle->published_at)->diffForHumans() }}
                                                            @else
                                                                {{ \Carbon\Carbon::parse($relatedArticle->created_at)->diffForHumans() }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                
                                @if($relatedArticles->count() < $totalInCategory)
                                <div class="px-4 pb-4">
                                    <a href="{{ url('/kategori/' . $article->category_id) }}" 
                                       class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium py-2 border border-blue-200 rounded-lg hover:border-blue-300 transition-colors">
                                        Lihat Semua Berita {{ $article->category->name }}
                                    </a>
                                </div>
                                @endif
                            @endif
                        @else
                            <!-- Tampilkan artikel populer jika tidak ada kategori -->
                            @php
                                $popularArticles = \App\Models\Article::orderBy('published_at', 'desc')
                                    ->where('id', '!=', $article->id)
                                    ->take(5)
                                    ->get();
                            @endphp
                            
                            @if($popularArticles->count() > 0)
                                <div class="p-4">
                                    @foreach($popularArticles as $index => $popularArticle)
                                        <a href="{{ url('/articles/' . $popularArticle->slug) }}" class="block hover:bg-gray-50 p-3 rounded-lg transition-colors duration-200 {{ $index > 0 ? 'border-t border-gray-100 mt-3 pt-3' : '' }}">
                                            <div class="flex space-x-3">
                                                <div class="flex-shrink-0">                                                    @if($popularArticle->featured_image)
                                                        <img src="{{ asset('storage/' . $popularArticle->featured_image) }}" 
                                                             alt="{{ $popularArticle->title }}"                                                             class="w-20 h-14 object-cover rounded-md"
                                                             loading="lazy">
                                                    @else
                                                        <div class="w-20 h-14 bg-gray-200 rounded-md">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-base font-semibold text-gray-900 line-clamp-2 hover:text-blue-600 leading-tight mb-2">
                                                        {{ $popularArticle->title }}
                                                    </h4>
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        @if($popularArticle->category)
                                                        <span class="text-blue-600 font-medium">{{ $popularArticle->category->name }}</span>
                                                        <span class="mx-2">•</span>
                                                        @endif
                                                        <span>
                                                            @if($popularArticle->published_at)
                                                                {{ \Carbon\Carbon::parse($popularArticle->published_at)->diffForHumans() }}
                                                            @else
                                                                {{ \Carbon\Carbon::parse($popularArticle->created_at)->diffForHumans() }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>                            @endif
                        @endif
                    </div>
                </div>
            </div>        </div>
    </div>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/view.css') }}">    <!-- Modern CSS Styles -->
    <style>        /* Override category active state untuk view.blade.php */
        .category-nav li a.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        /* Content spacing untuk fixed header - sama seperti welcome.css */
        .py-6 {
            margin-top: 150px; /* Space for both header (80px) and category nav (70px) */
        }/* Override welcome.css untuk view.blade.php dengan positioning yang sama */
        header {
            background: var(--header-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            padding: 15px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: var(--shadow-light);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo a {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: var(--primary-color); /* Fallback for browsers that don't support background-clip */
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .category-nav {
            background: var(--white-95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            padding: 15px 0;
            margin-top: 80px;
            position: sticky;
            top: 80px;
            z-index: 999;
        }

        .category-nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .category-nav ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin: 0;
            padding: 0;
            justify-content: center;
        }

        .category-nav li a {
            font-weight: 500;
            color: var(--text-medium);
            padding: 8px 0;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
            font-size: 14px;
        }

        .category-nav li a:hover,
        .category-nav li a.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
        }/* Sticky navbar fix - agar kategori navbar tidak terpisah */
        header {
            position: fixed !important;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: var(--header-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: none; /* Remove border from header */
            box-shadow: none; /* Remove shadow from header */
        }        /* Ensure proper spacing and alignment for header content */
        .header-container {
            max-width: none !important; /* Remove max-width constraint */
            margin: 0 !important; /* Remove centering margin */
            padding-left: 0.5rem !important; /* Minimal left padding */
            padding-right: 0.5rem !important; /* Minimal right padding */
        }

        .category-nav {
            position: fixed !important;
            top: 80px !important; /* Height of main header */
            width: 100%;
            z-index: 999;
            background: var(--white-95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            margin-top: 0 !important; /* Remove margin */
            box-shadow: var(--shadow-light); /* Add shadow to category nav instead */
            padding: 15px 0;
        }

        /* Ensure category nav container has same alignment as header */
        .category-nav-container {
            max-width: none !important; /* Remove max-width constraint */
            margin: 0 !important; /* Remove centering margin */
            padding: 0 0.5rem !important; /* Match header padding */
        }

        /* Category nav items alignment */
        .category-nav ul {
            justify-content: flex-start !important; /* Align categories to the left */
            margin-left: 0 !important;
            padding-left: 0 !important;
        }

        /* Ensure seamless connection between header and category nav */
        header + .category-nav {
            margin-top: 0 !important;
            border-top: none;
        }

        /* Add a subtle connecting line */
        header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-light);
        }        /* Notification dropdown styling sama dengan welcome.css */
        .notification-dropdown {
            display: none !important; /* Hidden by default */
        }

        .notification-dropdown.show {
            display: block !important;
        }

        /* Search form focus effects sama seperti welcome.blade.php */
        .search-form {
            transition: var(--transition);
        }

        .search-form:focus-within {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }/* Responsive improvement untuk mobile - sama seperti welcome.css */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                width: 100%;
            }

            .nav-links form {
                width: 100%;
            }
            
            .search-form input {
                width: 100%;
            }
            
            .category-nav-container {
                padding: 0 1rem;
            }
            
            .category-nav ul {
                overflow-x: auto;
                flex-wrap: nowrap;
                justify-content: flex-start;
                gap: 1rem;
                padding-bottom: 10px;
            }
            
            .category-nav li a {
                white-space: nowrap;
            }

            /* Mobile notification dropdown */
            .notification-dropdown {
                width: 300px;
                right: -50px;
            }
        }        /* Additional responsive fixes untuk consistency dengan welcome.css */
        @media (max-width: 480px) {
            .notification-dropdown {
                width: 280px;
                right: -100px;
            }
        }/* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Ensure no gap between header and category nav */
        header + .category-nav {
            margin-top: 0 !important;
        }        /* Override any potential conflicting styles from welcome.css */
        body .category-nav {
            position: fixed !important;
            margin-top: 0 !important;
        }        /* Remove any duplicate or overlapping navbar elements */
        .duplicate-navbar,
        .overlapping-nav,
        .redundant-header {
            display: none !important;
        }        /* Hide Laravel's default navigation that's loaded from app.blade.php */
        nav[x-data] {
            display: none !important;
        }

        /* Hide any Tailwind-based navigation */
        .bg-white.border-b.border-gray-100 {
            display: none !important;
        }

        /* More specific selectors to hide Laravel navigation */
        nav.bg-white.border-b.border-gray-100,
        .max-w-7xl .flex.justify-between.h-16 {
            display: none !important;
        }

        /* Hide dropdown elements from Laravel nav */
        div[x-data="{ open: false }"],
        .hidden.sm\\:flex.sm\\:items-center {
            display: none !important;
        }

        /* Ensure main header is the only visible header */
        header:not(:first-of-type) {
            display: none !important;
        }

        /* Clean up any potential z-index conflicts */
        header {
            z-index: 1000 !important;
        }

        .category-nav {
            z-index: 999 !important;
        }        /* Prevent any background elements from overlapping logo */
        .logo {
            position: relative;
            z-index: 1001 !important;
            background: transparent;
        }

        .logo a {
            position: relative;
            z-index: 1002 !important;
            text-decoration: none;
            display: block;
        }

        /* Ensure header background doesn't interfere */
        header::before {
            display: none !important;
        }

        /* Clear any pseudo-elements that might overlap */
        .header-container::before,
        .header-container::after {
            display: none !important;
        }        /* Force clean background for header area */
        header {
            background: var(--header-bg) !important;
            border: none !important;
            outline: none !important;
        }

        /* Override welcome.css header-container styles completely */
        header .header-container {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 0.5rem !important;
            width: 100% !important;
        }

        /* Force logo to extreme left */
        .logo {
            position: relative !important;
            left: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            transform: none !important;
        }

        /* Ensure no margin/padding interference */
        body {
            margin: 0 !important;
            padding: 0 !important;
            padding-top: 0 !important;
        }

        /* Clear any conflicting Tailwind/Laravel styles */
        .min-h-screen.bg-gray-100 {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        /* Ensure content starts below both navbars */
        main, .py-6 {
            padding-top: 0 !important;
        }

        /* Fix z-index layering */
        header {
            z-index: 1001 !important;
        }
        
        .category-nav {
            z-index: 1000 !important;
        }

        /* Remove any transitions that might cause separation */
        header, .category-nav {
            transition: none !important;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
          /* Professional Typography */
        .article-headline-unified {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .article-content-unified {
            font-size: 1.125rem;
            line-height: 1.7;
            color: #374151;
        }
        
        .article-content-unified p {
            margin-bottom: 1.25rem;
        }
        
        .article-content-unified h1,
        .article-content-unified h2,
        .article-content-unified h3 {
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        
        .article-content-unified h1 {
            font-size: 1.75rem;
        }
        
        .article-content-unified h2 {
            font-size: 1.5rem;
        }
        
        .article-content-unified h3 {
            font-size: 1.25rem;
        }
        
        /* Sidebar consistency */
        .related-article-item {
            padding: 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease;
        }
        
        .related-article-item:hover {
            background-color: #f9fafb;
        }
        
        .related-article-image {
            width: 5rem;
            height: 3.5rem;
            object-fit: cover;
            border-radius: 0.375rem;
        }
        
        .related-article-placeholder {
            width: 5rem;
            height: 3.5rem;
            background-color: #e5e7eb;
            border-radius: 0.375rem;
        }

        /* Additional navbar styles */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth transitions for better UX */
        .transition-colors {
            transition: color 0.2s ease-in-out;
        }

        /* Notification dropdown animation */
        #notificationDropdown {
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
            transform-origin: top right;
        }

        #notificationDropdown.hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        /* Mobile responsive improvements */
        @media (max-width: 640px) {
            .max-w-7xl {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
          /* Force proper grid layout - Ensure 2:1 ratio (66.67% : 33.33%) */
        .grid {
            display: grid;
        }
        
        /* Desktop Layout */
        @media (min-width: 1024px) {
            .lg\:grid-cols-3 {
                grid-template-columns: 2fr 1fr; /* 66.67% : 33.33% ratio for perfect 2:1 */
                gap: 1.5rem;
            }
            
            .lg\:col-span-2 {
                grid-column: 1;
            }
            
            .lg\:col-span-1 {
                grid-column: 2;
            }
        }
        
        /* Mobile Layout */
        @media (max-width: 1023px) {
            .lg\:grid-cols-3 {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .lg\:col-span-2,
            .lg\:col-span-1 {
                grid-column: 1;
            }
            
            .article-headline-unified {
                font-size: 1.75rem;
            }
            
            .article-content-unified {
                font-size: 1rem;
            }
        }
        
        /* Ensure cards have white background and shadow */
        .bg-white {
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>

    <!-- JavaScript for Social Sharing -->
    <script>
        // Article data for sharing
        const articleData = {
            title: '{{ addslashes($article->title) }}',
            summary: '{{ addslashes(Str::limit(strip_tags($article->summary ?? $article->content), 150)) }}',
            url: '{{ url()->current() }}',
            image: '{{ $article->featured_image ? asset("storage/" . $article->featured_image) : "" }}'
        };

        // Toggle share dropdown
        function toggleShareDropdown() {
            const dropdown = document.getElementById('shareDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('shareDropdown');
            const shareButton = document.getElementById('shareButton');
            
            if (!dropdown.contains(event.target) && !shareButton.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Social media sharing functions
        function shareToFacebook() {
            const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(articleData.url)}&quote=${encodeURIComponent(articleData.title + ' - ' + articleData.summary)}`;
            openShareWindow(url);
        }

        function shareToX() {
            const text = `${articleData.title}\n\n${articleData.summary}`;
            const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(articleData.url)}`;
            openShareWindow(url);
        }

        function shareToWhatsApp() {
            const text = `*${articleData.title}*\n\n${articleData.summary}\n\n${articleData.url}`;
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            openShareWindow(url);
        }

        function shareToLinkedIn() {
            const url = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(articleData.url)}`;
            openShareWindow(url);
        }

        function shareToTelegram() {
            const text = `${articleData.title}\n\n${articleData.summary}`;
            const url = `https://t.me/share/url?url=${encodeURIComponent(articleData.url)}&text=${encodeURIComponent(text)}`;
            openShareWindow(url);
        }

        function copyArticleLink() {
            navigator.clipboard.writeText(articleData.url).then(function() {
                showNotification('Link artikel berhasil disalin ke clipboard!', 'success');
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = articleData.url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Link artikel berhasil disalin!', 'success');
            });
        }

        function openShareWindow(url) {
            window.open(url, 'share', 'width=600,height=400,resizable=yes,scrollbars=yes');
        }        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-blue-500'} text-white transition-all duration-300`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);        }

        // Notification dropdown functionality yang sama dengan welcome.blade.php
        document.addEventListener('DOMContentLoaded', function() {
            const notificationButton = document.getElementById('notificationButton');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationBadge = document.querySelector('.notification-badge');
            
            if (notificationButton && notificationDropdown) {
                // Cek apakah notifikasi sudah dibaca sebelumnya
                const notificationsReadTime = localStorage.getItem('notificationsReadTime');
                const currentNotifications = '{{ isset($newArticles) && count($newArticles) > 0 ? $newArticles->max("created_at")->timestamp : 0 }}';
                
                // Jika notifikasi sudah dibaca dan tidak ada notifikasi baru sejak waktu tersebut
                if (notificationsReadTime && parseInt(notificationsReadTime) >= parseInt(currentNotifications)) {
                    // Sembunyikan badge notifikasi
                    if (notificationBadge) {
                        notificationBadge.style.display = 'none';
                    }
                }
                
                // Toggle dropdown when notification button is clicked
                notificationButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';
                    
                    // Jika dropdown terbuka, tandai notifikasi sebagai telah dibaca
                    if (notificationDropdown.style.display === 'block' && notificationBadge) {
                        // Sembunyikan badge notifikasi
                        notificationBadge.style.display = 'none';
                        
                        // Simpan waktu sekarang ke localStorage untuk menandai notifikasi sudah dibaca
                        const currentTime = Math.floor(Date.now() / 1000); // Waktu saat ini dalam detik (Unix timestamp)
                        localStorage.setItem('notificationsReadTime', currentTime > currentNotifications ? currentTime : currentNotifications);
                        
                        // Kirim request ke server untuk menandai notifikasi telah dibaca (jika route tersedia)
                        @if(Route::has('notifications.markAsRead'))
                        fetch('{{ route("notifications.markAsRead") }}', { 
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Notifikasi telah dibaca', data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                        @endif
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!notificationDropdown.contains(event.target) && event.target !== notificationButton) {
                        notificationDropdown.style.display = 'none';
                    }
                });
                
                // Close dropdown when pressing Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        notificationDropdown.style.display = 'none';
                    }
                });
            }

            // Search form enhancement (sama seperti welcome.blade.php)
            const searchInput = document.querySelector('.search-form input');
            const searchForm = document.querySelector('.search-form');
            
            if (searchInput && searchForm) {
                // Add enter key support
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });
                
                // Add focus/blur effects
                searchInput.addEventListener('focus', function() {
                    this.parentElement.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
                });
                
                searchInput.addEventListener('blur', function() {
                    this.parentElement.style.boxShadow = 'var(--shadow-light)';
                });
            }
        });
    </script>
</x-app-layout>