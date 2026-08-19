<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="article-id" content="{{ $article->id }}"/>
  <title>{{ $article->title }} - Portal Berita</title>
  <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
  <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>
<body>
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
  @endphp
  <main>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4">
            <!-- Newspaper style grid layout for article detail -->
            <div class="newspaper-layout">
                <!-- Newspaper Header / Masthead -->
                <div class="newspaper-masthead">
                    <div class="newspaper-date">{{ $dateToShow }}</div>
                    <h1 class="newspaper-title">Portal Berita</h1>
                    <div class="newspaper-edition">Digital Edition</div>
                    <div class="newspaper-tagline">"Delivering Truth, Inspiring Minds"</div>
                </div>
                
                <!-- Newspaper Grid Container -->
                <div class="newspaper-grid-container">
                    <!-- Main Content Area (tanpa sidebar kiri) -->
                    
                    <!-- Main Content Area -->
                    <div class="newspaper-main-content">
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
                                            <a href="{{ route('articles.byCategory', $article->category->id) }}" class="hover:text-blue-600">{{ $article->category->name }}</a>
                                            <span class="mx-2">/</span>
                                        @endif
                                        <span class="text-gray-700 truncate">{{ $article->title }}</span>
                                    </div>
                                    
                                    <!-- Category Badge - Prominent at top -->
                                    @if ($article->category)
                                        <div class="mb-2">
                                            <a href="{{ route('articles.byCategory', $article->category->id) }}" class="category-badge-prominent">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                        <!-- Author & Date with Reading Time -->
                                        <div class="flex flex-col w-full">
                                            <div class="flex items-center">
                                                @if ($article->user)
                                                    <div class="author-avatar-unified">
                                                        {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $article->user->name }}</p>
                                                        <div class="flex items-center text-xs text-gray-500">
                                                            <span>{{ $dateToShow }}</span>
                                                            <span class="mx-2">•</span>
                                                            @php
                                                                // Calculate reading time (approx. 225 words per minute)
                                                                $wordCount = str_word_count(strip_tags($article->content));
                                                                $readingTime = max(1, ceil($wordCount / 225));
                                                            @endphp
                                                            <span>{{ $readingTime }} min read</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-xs text-gray-500">
                                                        <span>{{ $dateToShow }}</span>
                                                        <span class="mx-2">•</span>
                                                        @php
                                                            // Calculate reading time (approx. 225 words per minute)
                                                            $wordCount = str_word_count(strip_tags($article->content));
                                                            $readingTime = max(1, ceil($wordCount / 225));
                                                        @endphp
                                                        <span>{{ $readingTime }} min read</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Share Icons - Updated with WSJ-style -->
                                            <div class="share-container">
                                                <div class="social-share-label">Share this article:</div>
                                                <div class="social-share-icons">
                                                    <!-- Facebook -->
                                                    <a href="javascript:void(0)" onclick="shareToSocial('facebook')" class="social-share-icon facebook" title="Share on Facebook">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 320 512">
                                                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- X (Twitter) -->
                                                    <a href="javascript:void(0)" onclick="shareToSocial('twitter')" class="social-share-icon twitter" title="Share on X">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 512 512">
                                                            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- LinkedIn -->
                                                    <a href="javascript:void(0)" onclick="shareToSocial('linkedin')" class="social-share-icon linkedin" title="Share on LinkedIn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512">
                                                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/>
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- WhatsApp -->
                                                    <a href="javascript:void(0)" onclick="shareToSocial('whatsapp')" class="social-share-icon whatsapp" title="Share on WhatsApp">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512">
                                                            <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- Email -->
                                                    <a href="javascript:void(0)" onclick="shareToSocial('email')" class="social-share-icon email" title="Share via Email">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 512 512">
                                                            <path d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/>
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- Copy Link -->
                                                    <a href="javascript:void(0)" onclick="copyArticleLink()" class="social-share-icon copy-link" title="Copy Link">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 640 512">
                                                            <path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Featured Image - Modern, optimized size for article view -->
                                    @if($article->featured_image)
                                        <figure class="featured-image-unified modern-image-container">
                                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                                 alt="{{ $article->title }}" 
                                                 class="featured-img modern-featured-img">
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
                                    
                                    <!-- Related Topics -->
                                    <div class="related-topics-section">
                                        <h4 class="related-topics-title">Related Topics</h4>
                                        <div class="related-topics-tags">
                                            @if($article->category)
                                                <a href="{{ route('articles.byCategory', $article->category->id) }}" class="related-topic-tag">
                                                    {{ $article->category->name }}
                                                </a>
                                            @endif
                                            
                                            @php
                                                // Generate some relevant tags based on the article title and content
                                                $content = strip_tags($article->content . ' ' . $article->title);
                                                $commonWords = ['dan', 'atau', 'yang', 'dengan', 'ini', 'itu', 'di', 'ke', 'dari', 'pada', 'untuk', 'adalah', 'dalam', 'tidak', 'akan'];
                                                $words = str_word_count(strtolower($content), 1, 'àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ0123456789');
                                                $wordCount = array_count_values($words);
                                                
                                                // Filter out common words and sort by frequency
                                                $filteredWords = array_filter($wordCount, function($word) use ($commonWords) {
                                                    return !in_array($word, $commonWords) && strlen($word) > 3;
                                                }, ARRAY_FILTER_USE_KEY);
                                                
                                                arsort($filteredWords);
                                                
                                                // Take top keywords (up to 5)
                                                $keywords = array_slice(array_keys($filteredWords), 0, 5);
                                            @endphp
                                            
                                            @foreach($keywords as $keyword)
                                                <a href="{{ route('home', ['search' => $keyword]) }}" class="related-topic-tag">
                                                    {{ ucfirst($keyword) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Article Engagement Section -->
                                    <div class="article-engagement-section" id="engagementSection">
                                        <div class="engagement-stats">
                                            <div class="stats-summary">
                                                <span class="likes-count" id="likesCount">{{ $article->likes }}</span> likes •
                                                <span class="dislikes-count" id="dislikesCount">{{ $article->dislikes }}</span> dislikes
                                            </div>
                                        </div>
                                        
                                        <div class="engagement-actions">
                                            @auth
                                                <button id="likeBtn" class="engagement-btn like-btn {{ $article->isLikedByUser(Auth::id()) ? 'active' : '' }}" 
                                                        onclick="toggleLike()" title="Like this article">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="like-icon">
                                                        <path d="M7.493 18.75c-.425 0-.82-.236-.975-.632A7.48 7.48 0 016 15.375c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558-.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23h-.777zM2.331 10.977a11.969 11.969 0 00-.831 4.398 12 12 0 00.52 3.507c.26.85 1.084 1.368 1.973 1.368H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 01-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227z"/>
                                                    </svg>
                                                    <span class="btn-text">Like</span>
                                                    <span class="count" id="likeBtnCount">{{ $article->likes }}</span>
                                                </button>
                                                
                                                <button id="dislikeBtn" class="engagement-btn dislike-btn {{ $article->isDislikedByUser(Auth::id()) ? 'active' : '' }}" 
                                                        onclick="toggleDislike()" title="Dislike this article">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="dislike-icon">
                                                        <path d="M15.73 5.25h1.035A7.465 7.465 0 0118 9.375a7.465 7.465 0 01-1.235 4.125h-.148c-.806 0-1.534.446-2.031 1.08a9.04 9.04 0 01-2.861 2.4c-.723.384-1.35.956-1.653 1.715a4.498 4.498 0 00-.322 1.672V21a.75.75 0 01-.75.75 2.25 2.25 0 01-2.25-2.25c0-1.152.26-2.243.723-3.218C7.74 15.724 7.366 15 6.748 15H3.622c-1.026 0-1.945-.694-2.054-1.715A12.134 12.134 0 011.5 12c0-2.848.992-5.464 2.649-7.521.388-.482.987-.729 1.605-.729H9.77a4.5 4.5 0 011.423.23l3.114 1.04a4.5 4.5 0 001.423.23zM21.669 14.023c.536-1.362.831-2.845.831-4.398 0-1.22-.182-2.398-.52-3.507-.26-.85-1.084-1.368-1.973-1.368H19.1c-.445 0-.72.498-.523.898.591 1.2.924 2.55.924 3.977a8.958 8.958 0 01-1.302 4.666c-.245.403.028.959.5.959h1.053c.832 0 1.612-.453 1.918-1.227z"/>
                                                    </svg>
                                                    <span class="btn-text">Dislike</span>
                                                    <span class="count" id="dislikeBtnCount">{{ $article->dislikes }}</span>
                                                </button>
                                                

                                                
                                                <!-- Print button (visual only) -->
                                                <button class="engagement-btn print-btn" onclick="trackPrint()" title="Print article">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                        <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path>
                                                        <rect x="6" y="14" width="12" height="8"></rect>
                                                    </svg>
                                                    <span class="btn-text">Print</span>
                                                </button>
                                            @else
                                                <button class="engagement-btn like-btn" onclick="showLoginPrompt()" title="Login to like">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="like-icon">
                                                        <path d="M7.493 18.75c-.425 0-.82-.236-.975-.632A7.48 7.48 0 016 15.375c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558-.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23h-.777zM2.331 10.977a11.969 11.969 0 00-.831 4.398 12 12 0 00.52 3.507c.26.85 1.084 1.368 1.973 1.368H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 01-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227z"/>
                                                    </svg>
                                                    <span class="btn-text">Like</span>
                                                    <span class="count">{{ $article->likes }}</span>
                                                </button>
                                                
                                                <button class="engagement-btn dislike-btn" onclick="showLoginPrompt()" title="Login to dislike">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="dislike-icon">
                                                        <path d="M15.73 5.25h1.035A7.465 7.465 0 0118 9.375a7.465 7.465 0 01-1.235 4.125h-.148c-.806 0-1.534.446-2.031 1.08a9.04 9.04 0 01-2.861 2.4c-.723.384-1.35.956-1.653 1.715a4.498 4.498 0 00-.322 1.672V21a.75.75 0 01-.75.75 2.25 2.25 0 01-2.25-2.25c0-1.152.26-2.243.723-3.218C7.74 15.724 7.366 15 6.748 15H3.622c-1.026 0-1.945-.694-2.054-1.715A12.134 12.134 0 011.5 12c0-2.848.992-5.464 2.649-7.521.388-.482.987-.729 1.605-.729H9.77a4.5 4.5 0 011.423.23l3.114 1.04a4.5 4.5 0 001.423.23zM21.669 14.023c.536-1.362.831-2.845.831-4.398 0-1.22-.182-2.398-.52-3.507-.26-.85-1.084-1.368-1.973-1.368H19.1c-.445 0-.72.498-.523.898.591 1.2.924 2.55.924 3.977a8.958 8.958 0 01-1.302 4.666c-.245.403.028.959.5.959h1.053c.832 0 1.612-.453 1.918-1.227z"/>
                                                    </svg>
                                                    <span class="btn-text">Dislike</span>
                                                    <span class="count">{{ $article->dislikes }}</span>
                                                </button>
                                                
                                                <!-- Print button (functional) -->
                                                <button class="engagement-btn print-btn" onclick="trackPrint()" title="Print article">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                        <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path>
                                                        <rect x="6" y="14" width="12" height="8"></rect>
                                                    </svg>
                                                    <span class="btn-text">Print</span>
                                                </button>
                                            @endauth
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="article-actions-unified">
                                        <!-- Tidak ada tombol action -->
                                    </div>
                                </div>
                            </div>                        </div>

                        <!-- Comments Section Card - Separate card below article -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-4 sm:p-6">
                                <!-- Comments Header -->
                                <div class="comment-header">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                                                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                            </svg>
                                            Comments
                                            @if($article->comments->count() > 0)
                                                <span class="ml-2 text-sm font-normal text-gray-500">({{ $article->comments->count() }})</span>
                                            @endif
                                        </h3>
                                        <div class="text-sm text-gray-500">
                                            Join the conversation
                                        </div>
                                    </div>
                                    <div class="comment-guidelines mb-4 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                        <strong>Community Guidelines:</strong> Keep comments respectful and relevant to the article. Off-topic or inappropriate comments may be removed.
                                    </div>
                                </div>

                                <!-- Existing Comments -->
                                <div class="comments-list">
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
                                            
                                            <div class="comment-actions-bar">
                                                <!-- Comment reaction options (just visual, not functional) -->
                                                <div class="comment-reactions">
                                                    <button class="comment-reaction-btn" title="Like comment">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2 2 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a10 10 0 0 0-.443.05 9.4 9.4 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a9 9 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.2 2.2 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.9.9 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/>
                                                        </svg>
                                                    </button>
                                                    <button class="comment-reaction-btn" title="Dislike comment">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M8.864 15.674c-.956.24-1.843-.484-1.908-1.42-.072-1.05-.23-2.015-.428-2.59-.125-.36-.479-1.012-1.04-1.638-.557-.624-1.282-1.179-2.131-1.41C2.685 8.432 2 7.85 2 7V3c0-.845.682-1.464 1.448-1.546 1.07-.113 1.564-.415 2.068-.723l.048-.029c.272-.166.578-.349.97-.484C6.931.08 7.395 0 8 0h3.5c.937 0 1.599.478 1.934 1.064.164.287.254.607.254.913 0 .152-.023.312-.077.464.201.262.38.577.488.9.11.33.172.762.004 1.15.069.13.12.268.159.403.077.27.113.567.113.856s-.036.586-.113.856c-.035.12-.08.244-.138.363.394.571.418 1.2.234 1.733-.206.592-.682 1.1-1.2 1.272-.847.283-1.803.276-2.516.211a10 10 0 0 1-.443-.05 9.36 9.36 0 0 1-.062 4.51c-.138.508-.55.848-1.012.964zM11.5 1.5H8c-.51 0-.863.068-1.14.163-.281.097-.506.229-.776.393l-.04.025c-.555.338-1.198.73-2.49.866-.333.035-.554.29-.554.55V7c0 .255.226.543.62.65 1.095.3 1.977.997 2.614 1.709.635.71 1.064 1.475 1.238 1.977.243.7.407 1.768.482 2.85.025.362.36.595.667.518l.262-.065c.16-.04.258-.144.288-.255a8.34 8.34 0 0 0-.145-4.726.5.5 0 0 1 .595-.643h.003l.014.004.058.013a9 9 0 0 0 1.036.157c.663.06 1.457.054 2.11-.163.175-.059.45-.301.57-.651.107-.308.087-.67-.266-1.021l-.354-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581s-.027-.414-.075-.581c-.05-.174-.111-.273-.154-.315l-.353-.354.353-.354c.047-.047.109-.176.005-.488a2.2 2.2 0 0 0-.505-.804l-.353-.354.353-.354c.006-.005.041-.05.041-.17a.9.9 0 0 0-.121-.415C12.4 1.272 12.063 1 11.5 1z"/>
                                                        </svg>
                                                    </button>
                                                    <button class="comment-reaction-btn" title="Reply to comment">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5"/>
                                                        </svg>
                                                        Reply
                                                    </button>
                                                </div>
                                            
                                                <!-- Delete Button for authorized users -->
                                                @auth
                                                    @if(Auth::id() == $comment->user_id || Auth::user()->role == 'admin')
                                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="comment-actions">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="delete-comment-btn" 
                                                                    onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    @empty
                                        <div class="comment-form-section">
                                            <div class="comment-form-header">
                                                <h4>No Comments Yet</h4>
                                            </div>
                                            <div class="comment-form-description">
                                                <p>Be the first to share your thoughts on this article.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Comment Form -->
                                @auth
                                    <div class="comment-form-section">
                                        <div class="comment-form-header">
                                            <h4>Leave a Comment</h4>
                                        </div>
                                        <div class="comment-form-description">
                                            <p>Share your thoughts or insights about this article.</p>
                                        </div>
                                        
                                        <form action="{{ route('comments.store', $article->id) }}" method="POST" class="comment-form">
                                            @csrf
                                            <div class="form-input-group">
                                                <div class="comment-user-info mb-2">
                                                    <div class="inline-flex items-center">
                                                        <div class="comment-avatar-small">
                                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                        </div>
                                                        <span class="ml-2 text-sm font-medium">Commenting as {{ Auth::user()->name }}</span>
                                                    </div>
                                                </div>
                                                <textarea name="content" rows="3" 
                                                        class="comment-textarea" 
                                                        placeholder="Add your comment... (minimum 10 characters)"
                                                        required></textarea>
                                            </div>
                                            
                                            <div class="form-actions">
                                                <div class="form-guidelines">
                                                    <small>By posting, you agree to our community guidelines. Keep it respectful and on topic.</small>
                                                </div>
                                                <button type="submit" class="submit-comment-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                    </svg>
                                                    Post Comment
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="login-prompt">
                                        <div class="login-prompt-content">
                                            <h4>Login Required</h4>
                                            <p>You must be logged in to comment on this article.</p>
                                            <a href="{{ route('login') }}" class="login-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                </svg>
                                                Login Now
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Sidebar with Related Articles -->
                    <div class="newspaper-sidebar-right">
                        <div class="newspaper-related-title">Berita Terkait</div>
                        <div class="newspaper-related-articles">
                            @if($article->category)
                                @php
                                    // Mengambil artikel terkait dengan kategori yang sama
                                    $sidebarArticles = \App\Models\Article::where('category_id', $article->category_id)
                                        ->where('id', '!=', $article->id) // Kecuali artikel yang sedang dilihat
                                        ->latest('published_at') // Urut berdasarkan tanggal publikasi terbaru
                                        ->take(5) // Batasi hingga 5 artikel untuk sidebar
                                        ->get();
                                @endphp
                                
                                @foreach($sidebarArticles as $relatedArticle)
                                    <div class="newspaper-related-article">
                                        @if($relatedArticle->featured_image)
                                            <div class="newspaper-related-article-image">
                                                <img src="{{ asset('storage/' . $relatedArticle->featured_image) }}" 
                                                     alt="{{ $relatedArticle->title }}" 
                                                     loading="lazy">
                                            </div>
                                        @endif
                                        <a href="{{ route('articles.show', $relatedArticle->slug) }}">
                                            <h4 class="newspaper-related-article-title">{{ $relatedArticle->title }}</h4>
                                        </a>
                                        <div class="newspaper-related-article-meta">
                                            @if($relatedArticle->published_at)
                                                {{ \Carbon\Carbon::parse($relatedArticle->published_at)->diffForHumans() }}
                                            @else
                                                {{ \Carbon\Carbon::parse($relatedArticle->created_at)->diffForHumans() }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @php
                                    // Tampilkan artikel populer jika tidak ada kategori
                                    $sidebarArticles = \App\Models\Article::orderBy('published_at', 'desc')
                                        ->where('id', '!=', $article->id)
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @foreach($sidebarArticles as $popularArticle)
                                    <div class="newspaper-related-article">
                                        @if($popularArticle->featured_image)
                                            <div class="newspaper-related-article-image">
                                                <img src="{{ asset('storage/' . $popularArticle->featured_image) }}" 
                                                     alt="{{ $popularArticle->title }}" 
                                                     loading="lazy">
                                            </div>
                                        @endif
                                        <a href="{{ route('articles.show', $popularArticle->slug) }}">
                                            <h4 class="newspaper-related-article-title">{{ $popularArticle->title }}</h4>
                                        </a>
                                        <div class="newspaper-related-article-meta">
                                            @if($popularArticle->published_at)
                                                {{ \Carbon\Carbon::parse($popularArticle->published_at)->diffForHumans() }}
                                            @else
                                                {{ \Carbon\Carbon::parse($popularArticle->created_at)->diffForHumans() }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            
                            <div class="newspaper-see-more-link">
                                @if($article->category)
                                    <a href="{{ route('articles.byCategory', $article->category_id) }}">Lihat Semua Berita {{ $article->category->name }} →</a>
                                @else
                                    <a href="{{ route('home') }}">Lihat Semua Berita Terbaru →</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- AI-powered Recommendations Section (menggantikan Artikel Lainnya) -->
                @include('components.recommendations')
            </div>
        </div>
    </div>
  </main>

  <footer>
    <p>&copy; {{ date('Y') }} Zehandra Gibran. All rights reserved.</p>
  </footer>
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="{{ asset('css/show.css') }}">
  <link rel="stylesheet" href="{{ asset('css/view.css') }}">
  <link rel="stylesheet" href="{{ asset('css/engagement.css') }}">
  <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
  
  <!-- Custom styles for modern image display and newspaper layout -->
  <style>
    /* WSJ-inspired styles */
    .newspaper-title {
      font-family: 'Times New Roman', Times, serif;
      font-weight: bold;
      letter-spacing: -1px;
    }
    
    .newspaper-masthead {
      text-align: center;
      border-bottom: 3px solid #000;
      padding: 20px 0 15px 0;
      margin-bottom: 30px;
      background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }
    
    .newspaper-date {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 5px;
    }
    
    .newspaper-edition {
      font-size: 0.8rem;
      color: #888;
      font-style: italic;
      margin-top: 5px;
    }
    
    .newspaper-tagline {
      font-size: 0.7rem;
      color: #999;
      font-style: italic;
      margin-top: 3px;
    }
    
    /* Enhanced share buttons */
    .social-share-label {
      font-size: 0.8rem;
      color: #666;
      margin-bottom: 8px;
      font-weight: 500;
    }
    
    .social-share-icons {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }
    
    .social-share-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      transition: all 0.3s ease;
      text-decoration: none;
      border: 1px solid #e1e5e9;
    }
    
    .social-share-icon:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .social-share-icon.facebook { background-color: #1877f2; color: white; }
    .social-share-icon.twitter { background-color: #000000; color: white; }
    .social-share-icon.linkedin { background-color: #0077b5; color: white; }
    .social-share-icon.whatsapp { background-color: #25d366; color: white; }
    .social-share-icon.email { background-color: #ea4335; color: white; }
    .social-share-icon.copy-link { background-color: #6c757d; color: white; }
    
    /* Article content styling */
    .article-content-unified {
      font-family: Georgia, serif;
      line-height: 1.7;
      font-size: 1.1rem;
      color: #333;
    }
    
    .article-content-unified p {
      margin-bottom: 1.2rem;
    }
    
    .article-content-unified h2, 
    .article-content-unified h3 {
      font-family: 'Times New Roman', Times, serif;
      margin-top: 1.8rem;
      margin-bottom: 1rem;
      font-weight: bold;
    }
    
    /* Related Topics section */
    .related-topics-section {
      margin: 30px 0;
      padding: 20px 0;
      border-top: 1px solid #e1e5e9;
      border-bottom: 1px solid #e1e5e9;
    }
    
    .related-topics-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 15px;
    }
    
    .related-topics-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }
    
    .related-topic-tag {
      display: inline-block;
      padding: 6px 12px;
      background-color: #f8f9fa;
      color: #495057;
      text-decoration: none;
      border-radius: 4px;
      font-size: 0.85rem;
      font-weight: 500;
      border: 1px solid #dee2e6;
      transition: all 0.3s ease;
    }
    
    .related-topic-tag:hover {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }
    
    /* Enhanced engagement buttons */
    .engagement-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 15px;
    }
    
    .engagement-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border: 1px solid #dee2e6;
      background-color: #fff;
      color: #495057;
      border-radius: 6px;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .engagement-btn:hover {
      background-color: #f8f9fa;
      border-color: #adb5bd;
    }
    
    .engagement-btn.active {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }
    

    
    .engagement-btn.print-btn:hover {
      background-color: #6c757d;
      border-color: #6c757d;
      color: white;
    }
    
    /* Enhanced comment styling */
    .comment-guidelines {
      border-left: 4px solid #007bff;
    }
    
    .comment-actions-bar {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid #f1f3f4;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .comment-reactions {
      display: flex;
      gap: 15px;
    }
    
    .comment-reaction-btn {
      display: flex;
      align-items: center;
      gap: 4px;
      background: none;
      border: none;
      color: #6c757d;
      font-size: 0.8rem;
      cursor: pointer;
      padding: 4px 8px;
      border-radius: 4px;
      transition: all 0.2s ease;
    }
    
    .comment-reaction-btn:hover {
      background-color: #f8f9fa;
      color: #495057;
    }
    
    .comment-avatar-small {
      width: 32px;
      height: 32px;
      background-color: #007bff;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 0.9rem;
    }
    
    /* Reading time styling */
    .article-metadata-unified .text-xs {
      font-size: 0.75rem;
    }
    
    /* Print media styles */
    @media print {
      .engagement-actions,
      .social-share-icons,
      .comment-form-section,
      .newspaper-sidebar-right {
        display: none !important;
      }
      
      .newspaper-title {
        font-size: 2rem;
      }
      
      .article-content-unified {
        font-size: 12pt;
        line-height: 1.6;
      }
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
      .social-share-icons {
        justify-content: center;
      }
      
      .engagement-actions {
        justify-content: center;
      }
      
      .related-topics-tags {
        justify-content: center;
      }
      
      .comment-reactions {
        flex-wrap: wrap;
        gap: 10px;
      }
    }
  </style>


  <!-- Notification Dropdown JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Track page view when the page loads
        trackPageView();
        
        // Existing notification functionality
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

    // Page View Tracking - Automatically called on page load
    function trackPageView() {
        fetch(`/articles/{{ $article->id }}/track-view`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .catch(error => {
            console.error('View tracking error:', error);
        });
    }

    // Track share with platform information
    function trackShareWithPlatform(platform) {
        fetch(`/articles/{{ $article->id }}/track-share`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                platform: platform
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Share berhasil!', 'success');
            }
        })
        .catch(error => {
            console.error('Share tracking error:', error);
        });
    }

    // Track print action
    function trackPrint() {
        fetch(`/articles/{{ $article->id }}/track-print`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Proceed with printing
                window.print();
                showToast('Printing...', 'info');
            }
        })
        .catch(error => {
            console.error('Print tracking error:', error);
            // Still allow printing even if tracking fails
            window.print();
        });
    }



    // Track copy link action
    function trackCopyLink() {
        fetch(`/articles/{{ $article->id }}/track-copy-link`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .catch(error => {
            console.error('Copy link tracking error:', error);
        });
    }

    // Engagement Functions
    function incrementShare() {
        fetch(`/articles/{{ $article->id }}/share`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success, but we don't need to update any counts since we don't show them
                showToast('Share berhasil!', 'success');
            } else {
                showToast('Gagal melakukan share', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }
    
    // Social Media Share Functions
    document.addEventListener('DOMContentLoaded', function() {
        // Nothing special to do here for the new circular share buttons
        // Each button will directly call shareToSocial function
    });
    
    // Function to share to social media platforms
    function shareToSocial(platform) {
        const articleUrl = encodeURIComponent(window.location.href);
        const articleTitle = encodeURIComponent(document.querySelector('.article-headline-unified').textContent.trim());
        let shareUrl = '';
        
        switch(platform) {
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${articleUrl}`;
                break;
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?text=${articleTitle}&url=${articleUrl}`;
                break;
            case 'whatsapp':
                shareUrl = `https://api.whatsapp.com/send?text=${articleTitle} ${articleUrl}`;
                break;
            case 'telegram':
                shareUrl = `https://t.me/share/url?url=${articleUrl}&text=${articleTitle}`;
                break;
            case 'linkedin':
                shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${articleUrl}`;
                break;
            case 'email':
                shareUrl = `mailto:?subject=${articleTitle}&body=I thought you might be interested in this article: ${articleUrl}`;
                break;
            case 'instagram':
                // Open Instagram DM directly
                const instagramAppUrl = `instagram://direct?text=${articleTitle} ${window.location.href}`;
                const instagramWebUrl = `https://www.instagram.com/direct/inbox/`;
                
                // Try to open the Instagram app first
                window.location.href = instagramAppUrl;
                
                // Set a timeout to redirect to web version if app doesn't open
                setTimeout(() => {
                    // Check if we're still on the same page, which means app didn't open
                    if (document.hasFocus()) {
                        window.open(instagramWebUrl, '_blank');
                        showToast('Opening Instagram web to send DM', 'info');
                    }
                }, 500);
                break;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=450');
        }
        
        // Track share with platform information
        trackShareWithPlatform(platform);
    }
    
    // Function to copy article link to clipboard
    function copyArticleLink() {
        const articleUrl = window.location.href;
        
        navigator.clipboard.writeText(articleUrl).then(() => {
            showToast('Link berhasil disalin!', 'success');
        }).catch(() => {
            // Fallback if Clipboard API fails
            const textarea = document.createElement('textarea');
            textarea.value = articleUrl;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showToast('Link berhasil disalin!', 'success');
        });
        
        // Track copy link action
        trackCopyLink();
    }
    function toggleLike() {
        const btn = document.getElementById('likeBtn');
        const dislikeBtn = document.getElementById('dislikeBtn');
        
        btn.classList.add('loading');
        
        fetch(`/articles/{{ $article->id }}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateEngagementUI(data);
                showToast('Like berhasil!', 'success');
            } else {
                showToast(data.message || 'Gagal memberikan like', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        })
        .finally(() => {
            btn.classList.remove('loading');
        });
    }

    function toggleDislike() {
        const btn = document.getElementById('dislikeBtn');
        const likeBtn = document.getElementById('likeBtn');
        
        btn.classList.add('loading');
        
        fetch(`/articles/{{ $article->id }}/dislike`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateEngagementUI(data);
                showToast('Dislike berhasil!', 'success');
            } else {
                showToast(data.message || 'Gagal memberikan dislike', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        })
        .finally(() => {
            btn.classList.remove('loading');
        });
    }



    function updateEngagementUI(data) {
        // Update counts
        document.getElementById('likesCount').textContent = data.likes;
        document.getElementById('dislikesCount').textContent = data.dislikes;
        document.getElementById('likeBtnCount').textContent = data.likes;
        document.getElementById('dislikeBtnCount').textContent = data.dislikes;
        
        // Update button states
        const likeBtn = document.getElementById('likeBtn');
        const dislikeBtn = document.getElementById('dislikeBtn');
        
        likeBtn.classList.remove('active');
        dislikeBtn.classList.remove('active');
        
        if (data.userReaction === 'like') {
            likeBtn.classList.add('active');
        } else if (data.userReaction === 'dislike') {
            dislikeBtn.classList.add('active');
        }
    }

    function showLoginPrompt() {
        if (confirm('You must be logged in to like or dislike articles. Login now?')) {
            window.location.href = '{{ route("login") }}';
        }
    }

    function showToast(message, type = 'success') {
        // Remove existing toast if any
        const existingToast = document.querySelector('.engagement-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create new toast
        const toast = document.createElement('div');
        toast.className = `engagement-toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Stub for legacy shareWithCount function
    function shareWithCount() {
        console.log("Share functionality has been removed");
        return false;
    }
  </script>
</body>
</html>