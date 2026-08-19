{{-- filepath: c:\laragon\www\berita\resources\views\welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Berita</title>
  <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
  <style>
    .mini-article-likes {
      color: #666;
      font-size: 0.85em;
      margin-left: 5px    <!-- API News Section -->
    <div class="api-news-section">
      <div class="section-header">
        <span class="section-label">Wall Street Journal News</span>
        <div class="section-border"></div>
        <div class="refresh-container">
          <span id="lastRefreshTime" class="last-refresh-time">Terakhir diperbarui: Baru saja</span>
          <button id="refreshNewsBtn" class="refresh-button">
            <img src="{{ asset('storage/uploads/refresh.png') }}" alt="Refresh" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjEgNCAxIDEwIDcgMTAiPjwvcG9seWxpbmU+PHBvbHlsaW5lIHBvaW50cz0iMjMgMjAgMjMgMTQgMTcgMTQiPjwvcG9seWxpbmU+PHBhdGggZD0iTTIwLjQ5IDlBOSA5IDAgMCAwIDUuNjQgNS42NEwxIDEwbTIyIDRsLTQuNjQgNC4zNkE5IDkgMCAwIDEgMy41MSAxNSI+PC9wYXRoPjwvc3ZnPg=='">
            <span>Refresh</span>
          </button>
        </div>
      </div>}
    
    .top-story .category-tag {
      color: white;
    }
    
    /* API News Section Styles */
    .api-news-section {
      margin-top: 40px;
      padding: 20px 0;
      border-top: 1px solid #eaeaea;
    }
    
    .api-news-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    
    .api-news-card {
      border: 1px solid #eaeaea;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background-color: #fff;
    }
    
    .api-news-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .api-news-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    
    .api-news-content {
      padding: 15px;
    }
    
    .api-news-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
    }
    
    .api-news-description {
      font-size: 14px;
      color: #666;
      margin-bottom: 12px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    
    .api-news-source {
      font-size: 12px;
      color: #888;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .api-news-date {
      font-style: italic;
    }
    
    .loading-indicator {
      grid-column: 1 / -1;
      text-align: center;
      padding: 30px;
      color: #666;
    }
    
    .error-message {
      grid-column: 1 / -1;
      text-align: center;
      padding: 30px;
      color: #d9534f;
    }
    
    /* Refresh Button and Last Refresh Time Styles */
    .refresh-container {
      display: flex;
      align-items: center;
      margin-left: auto;
    }
    
    .last-refresh-time {
      font-size: 12px;
      color: #888;
      margin-right: 10px;
    }
    
    .refresh-button {
      display: flex;
      align-items: center;
      background-color: #f8f9fa;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 5px 10px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    
    .refresh-button:hover {
      background-color: #e9ecef;
    }
    
    .refresh-button img {
      width: 16px;
      height: 16px;
      margin-right: 5px;
    }
    
    .refresh-button.spinning img {
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .section-header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }
    
    /* Enhanced sidebar articles styling */
    .sidebar-article {
      margin-bottom: 15px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background: white;
    }
    
    .sidebar-article:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    .sidebar-article .article-image {
      position: relative;
      height: 120px;
      overflow: hidden;
    }
    
    .sidebar-article .article-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .sidebar-article:hover .article-image img {
      transform: scale(1.05);
    }
    
    .sidebar-article .article-category {
      position: absolute;
      top: 8px;
      left: 8px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
    }
    
    .sidebar-article-content {
      padding: 12px;
    }
    
    .sidebar-article-content h5 {
      font-size: 14px;
      font-weight: 600;
      line-height: 1.3;
      margin-bottom: 8px;
      color: #333;
    }
    
    .sidebar-article .article-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 11px;
      color: #666;
      flex-wrap: wrap;
      gap: 4px;
    }
    
    .sidebar-article .author {
      font-weight: 500;
    }
    
    .sidebar-article .article-time {
      font-style: italic;
    }
  </style>
</head>
<body>
  <!-- Flash Messages -->
  @if(session('error'))
    <div class="alert alert-error" id="errorAlert">
      <div class="alert-content">
        <span class="alert-icon">⚠️</span>
        <span class="alert-message">{{ session('error') }}</span>
        <button class="alert-close" onclick="closeAlert('errorAlert')">&times;</button>
      </div>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success" id="successAlert">
      <div class="alert-content">
        <span class="alert-icon">✅</span>
        <span class="alert-message">{{ session('success') }}</span>
        <button class="alert-close" onclick="closeAlert('successAlert')">&times;</button>
      </div>
    </div>
  @endif

  <header>
    <div class="header-container">
      <div class="logo">
        {{-- <img src="{{ asset('storage/uploads/logo.png') }}" alt="Logo" class="logo-image"> --}}
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
        
        <!-- Notification Icon - Diposisikan sangat dekat dengan search -->
        <div class="notification-wrapper">
          <button id="notificationButton" type="button" class="notification-button">
            <img src="{{ asset('storage/uploads/notification.png') }}" alt="Notifications" class="notification-icon">
            @if(count($newArticles) > 0)
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
            </div>
            @if(count($newArticles) > 0)
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
        @foreach($categories as $category)
          <li>
            <a href="{{ route('articles.byCategory', $category->id) }}">
              {{ $category->name }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <main>
    <div class="hero">
      <h1>Selamat datang di Portal Berita!</h1>
      <p>Temukan berita terkini dari berbagai kategori dengan tampilan elegan dan profesional</p>

      @if(Auth::check())
        <p><strong>Selamat datang, {{ Auth::user()->name }}!</strong></p>
      @endif

      @isset($category)
        {{-- <p>Menampilkan berita untuk kategori: <strong>{{ $category->name }}</strong></p> --}}
      @endisset
    </div>

    @if(request('search'))
      <div class="search-results-info">
        <p>Hasil pencarian untuk: <strong>{{ request('search') }}</strong> ({{ $articles->count() + (isset($featuredArticle) ? 1 : 0) }} artikel)</p>
      </div>
    @elseif(isset($category))
      <div class="category-results-info" style="display: none;">
        <p>Menampilkan artikel kategori: <strong>{{ $category->name }}</strong> ({{ $articles->count() + (isset($featuredArticle) ? 1 : 0) }} artikel)</p>
      </div>
    @endif

    <div class="news-grid newspaper-layout">
      @if(isset($featuredArticle) && $featuredArticle)
      <!-- Featured Article (Top Story) -->
      <div class="top-story">
        <div class="top-story-header">
          <span class="section-label">Berita Utama</span>
          <div class="top-story-border"></div>
        </div>
        <a href="{{ route('articles.show', $featuredArticle->slug) }}">
          <div class="top-story-content">
            <div class="featured-image">
              <img src="{{ $featuredArticle->featured_image ? asset('storage/' . $featuredArticle->featured_image) : 'https://via.placeholder.com/1200x600' }}" alt="{{ $featuredArticle->title }}" onerror="this.src='https://via.placeholder.com/1200x600'">
            </div>
            <h2 class="headline">{{ $featuredArticle->title }}</h2>
            <p class="featured-summary">{{ Str::limit(strip_tags($featuredArticle->summary), 200) }}</p>
            
            <div class="article-byline">
              @if($featuredArticle->user)
                <span class="author">
                  Oleh: <strong>{{ $featuredArticle->user->name }}</strong>
                </span>
              @endif
              
              @if($featuredArticle->category)
                <span class="divider">|</span>
                <a href="{{ route('articles.byCategory', $featuredArticle->category->id) }}" class="category-tag">
                  {{ $featuredArticle->category->name }}
                </a>
              @endif
              
              <span class="divider">|</span>
              <span class="publish-time">{{ $featuredArticle->created_at->diffForHumans() }}</span>
            </div>
          </div>
        </a>
      </div>
      @endif

      <!-- News Sections -->
      <div class="newspaper-sections">
        <!-- Left Column (Main Articles) -->
        <div class="main-column">
          <div class="section-header">
            <span class="section-label">Berita Terkini</span>
            <div class="section-border"></div>
          </div>
          
          <!-- Main Articles -->
          @if($articles->count() > 0)
            <div class="main-article">
              @php $mainArticle = $articles->shift(); @endphp
              @if(isset($mainArticle))
                <a href="{{ route('articles.show', $mainArticle->slug) }}">
                  <div class="main-article-content">
                    <div class="article-image">
                      <img src="{{ $mainArticle->featured_image ? asset('storage/' . $mainArticle->featured_image) : 'https://via.placeholder.com/600x400' }}" alt="{{ $mainArticle->title }}" onerror="this.src='https://via.placeholder.com/600x400'">
                      
                      @if($mainArticle->category)
                        <span class="category-tag">{{ $mainArticle->category->name }}</span>
                      @endif
                    </div>
                    
                    <h3 class="article-headline">{{ $mainArticle->title }}</h3>
                    <p class="article-summary">{{ Str::limit(strip_tags($mainArticle->summary), 150) }}</p>
                    
                    <div class="article-meta">
                      @if($mainArticle->user)
                        <span class="author">{{ $mainArticle->user->name }}</span>
                      @endif
                      <span class="publish-time">{{ $mainArticle->created_at->diffForHumans() }}</span>
                    </div>
                  </div>
                </a>
              @endif
            </div>
            
            <!-- Secondary Articles (Recently Uploaded) -->
            <div class="secondary-articles">
              @php
                // Get recently uploaded articles (excluding the main article)
                $recentArticles = $articles->filter(function($item) use ($mainArticle) {
                    return isset($mainArticle) ? $item->id != $mainArticle->id : true;
                })->sortByDesc('created_at');
              @endphp
              
              @foreach($recentArticles as $article)
                <div class="secondary-article">
                  <a href="{{ route('articles.show', $article->slug) }}">
                    <div class="secondary-article-content">
                      <div class="article-image">
                        <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://via.placeholder.com/300x200' }}" alt="{{ $article->title }}" onerror="this.src='https://via.placeholder.com/300x200'">
                        
                        @if($article->category)
                          <span class="category-tag">{{ $article->category->name }}</span>
                        @endif
                      </div>
                      
                      <h4>{{ $article->title }}</h4>
                      <p class="article-excerpt">{{ Str::limit(strip_tags($article->summary), 80) }}</p>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          @endif
        </div>
        
        <!-- Right Column (Sidebar) -->
        <div class="sidebar-column">
          <div class="section-header">
            <span class="section-label">Berita Lainnya</span>
            <div class="section-border"></div>
          </div>
          
          <!-- Sidebar Articles List - Articles with fewest views -->
          <div class="sidebar-articles">
            @php
              // Get 8 articles with the fewest views (excluding featured article if exists)
              $leastViewedArticles = $articles->filter(function($article) use ($featuredArticle) {
                  return !$featuredArticle || $article->id !== $featuredArticle->id;
              })->sortBy('views')->take(8)->values();
            @endphp
            
            @foreach($leastViewedArticles as $index => $article)
              <div class="sidebar-article">
                <a href="{{ route('articles.show', $article->slug) }}">
                  <div class="article-image">
                    <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://via.placeholder.com/300x200' }}" 
                         alt="{{ $article->title }}" 
                         onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                    @if($article->category)
                      <span class="category-tag">{{ $article->category->name }}</span>
                    @endif
                  </div>
                  <div class="sidebar-article-content">
                    <h5>{{ Str::limit($article->title, 50) }}</h5>
                    <div class="article-meta">
                      @if($article->user)
                        <span class="author">{{ $article->user->name }}</span>
                      @endif
                      <span class="article-time">{{ $article->created_at->diffForHumans() }}</span>
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
          
          <div class="mini-list-section">
            <div class="section-header">
              <span class="section-label">Jangan Lewatkan</span>
              <div class="section-border"></div>
            </div>
            
            @php
              // Get articles with the most views (excluding featured article if exists)
              $mostViewedArticles = $articles->filter(function($article) use ($featuredArticle) {
                  return (!$featuredArticle || $article->id !== $featuredArticle->id) && $article->views > 0;
              })->sortByDesc('views')->take(8)->values();
            @endphp
            
            <ul class="mini-article-list">
              @forelse($mostViewedArticles as $article)
                <li>
                  <a href="{{ route('articles.show', $article->slug) }}">
                    <span class="mini-article-title">{{ Str::limit($article->title, 60) }}</span>
                  </a>
                </li>
              @empty
                <li>Tidak ada artikel yang telah dilihat</li>
              @endforelse
            </ul>
          </div>
        </div>
      </div>
      
      @if($articles->isEmpty() && !isset($featuredArticle))
        <p class="no-articles">Tidak ada artikel yang ditemukan.</p>
      @endif
    </div>
    
    <div class="pagination-container">
      <!-- No pagination needed as all articles are shown -->
    </div>
    
    <!-- API News Section -->
    <div class="api-news-section">
      <div class="section-header">
        <span class="section-label">BERITA INTERNASIONAL</span>
        <div class="section-border"></div>
      </div>
      
      <div class="api-news-grid" id="apiNewsGrid">
        <!-- News from API will be loaded here -->
        <div class="loading-indicator">
          <p>Memuat berita...</p>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <p>&copy; {{ date('Y') }} Zehandra Gibran. All rights reserved.</p>
  </footer>

  <!-- Notification Dropdown JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const notificationButton = document.getElementById('notificationButton');
      const notificationDropdown = document.getElementById('notificationDropdown');
      const notificationBadge = document.querySelector('.notification-badge');
      
      // Cek apakah notifikasi sudah dibaca sebelumnya
      const notificationsReadTime = localStorage.getItem('notificationsReadTime');
      const currentNotifications = '{{ count($newArticles) > 0 ? $newArticles->max("created_at")->timestamp : 0 }}';
      
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
          
          // Kirim request ke server untuk menandai notifikasi telah dibaca
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
        }
      });
      
      // Close dropdown when clicking outside
      document.addEventListener('click', function(event) {
        if (!notificationDropdown.contains(event.target) && event.target !== notificationButton) {
          notificationDropdown.style.display = 'none';
        }
      });
    });

    // Flash Messages Functions
    function closeAlert(alertId) {
      const alert = document.getElementById(alertId);
      if (alert) {
        alert.classList.add('fade-out');
        setTimeout(() => {
          alert.remove();
        }, 300);
      }
    }

    // Auto close alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          if (alert.parentNode) {
            alert.classList.add('fade-out');
            setTimeout(() => {
              alert.remove();
            }, 300);
          }
        }, 5000);
      });
    });
    
    // API News Fetching
    document.addEventListener('DOMContentLoaded', function() {
      // API configuration
      const apiKey = '1c67a7ce2e9943f49947e78a1c7d0b21'; // NewsAPI key
      const apiUrl = `https://newsapi.org/v2/everything?q=apple&from=2025-06-26&to=2025-06-26&sortBy=popularity&apiKey=1c67a7ce2e9943f49947e78a1c7d0b21`;
      const apiNewsGrid = document.getElementById('apiNewsGrid');
      
      // Remove loading indicator
      const loadingIndicator = document.querySelector('.loading-indicator');
      if (loadingIndicator) {
        loadingIndicator.remove();
      }

      // Function to fetch news
      function fetchNews() {
        // Clear existing news before fetching new ones
        while (apiNewsGrid.firstChild) {
          apiNewsGrid.removeChild(apiNewsGrid.firstChild);
        }
        
        // Show loading indicator when refreshing
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'loading-indicator';
        loadingDiv.innerHTML = '<p>Memuat berita...</p>';
        apiNewsGrid.appendChild(loadingDiv);

        fetch(apiUrl)
          .then(response => {
            if (!response.ok) {
              throw new Error('Gagal memuat berita dari API. Status: ' + response.status);
            }
            return response.json();
          })
          .then(data => {
            console.log('API response:', data); // For debugging
            
            // Remove loading indicator
            const loadingIndicator = apiNewsGrid.querySelector('.loading-indicator');
            if (loadingIndicator) {
              loadingIndicator.remove();
            }
            
            if (data.articles && data.articles.length > 0) {
              // Display more news articles (12 instead of 6)
              data.articles.slice(0, 12).forEach(article => {
                const newsCard = createNewsCard(article);
                apiNewsGrid.appendChild(newsCard);
              });
              
              // Update the last refresh time
              updateLastRefreshTime();
            } else {
              showErrorMessage('Tidak ada berita yang ditemukan dari API.');
            }
          })
          .catch(error => {
            console.error('Error fetching news:', error);
            showErrorMessage('Gagal memuat berita: ' + error.message);
          });
      }
      
      // Create a news card element
      function createNewsCard(article) {
        const card = document.createElement('div');
        card.className = 'api-news-card';
        
        // Format date
        const publishedDate = new Date(article.publishedAt || new Date());
        const formattedDate = publishedDate.toLocaleDateString('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        });
        
        // Use placeholder image if no image is available
        const imageUrl = article.urlToImage || 'https://via.placeholder.com/300x180?text=No+Image';
        
        card.innerHTML = `
          <a href="${article.url}" target="_blank" rel="noopener noreferrer">
            <img src="${imageUrl}" alt="${article.title}" class="api-news-image" onerror="this.src='https://via.placeholder.com/300x180?text=Error+Loading+Image'">
            <div class="api-news-content">
              <h3 class="api-news-title">${article.title}</h3>
              <p class="api-news-description">${article.description || 'Tidak ada deskripsi tersedia.'}</p>
              <div class="api-news-source">
                <span>${article.source.name || 'Wall Street Journal'}</span>
                <span class="api-news-date">${formattedDate}</span>
              </div>
            </div>
          </a>
        `;
        
        return card;
      }
      
      function showErrorMessage(message) {
        const loadingIndicator = apiNewsGrid.querySelector('.loading-indicator');
        if (loadingIndicator) {
          loadingIndicator.remove();
        }
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `
          <p>${message}</p>
          <p>Silakan coba lagi nanti atau hubungi administrator.</p>
        `;
        
        apiNewsGrid.appendChild(errorDiv);
      }
      
      // Update the last refresh time
      function updateLastRefreshTime() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        
        const lastRefreshTime = document.getElementById('lastRefreshTime');
        if (lastRefreshTime) {
          lastRefreshTime.textContent = `Terakhir diperbarui: ${timeString}`;
        }
      }
      
      // Initial fetch
      fetchNews();
      
      // Set up refresh button
      const refreshButton = document.getElementById('refreshNewsBtn');
      if (refreshButton) {
        refreshButton.addEventListener('click', function() {
          // Add spinning animation
          this.classList.add('spinning');
          
          // Fetch new news
          fetchNews();
          
          // Remove spinning animation after a delay
          setTimeout(() => {
            this.classList.remove('spinning');
          }, 1000);
        });
      }
      
      // Auto-refresh news every 5 minutes (300000 ms)
      setInterval(fetchNews, 300000);
    });
  </script>
</body>
</html>