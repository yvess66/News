<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Portal Berita Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .details-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .details-header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .details-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .details-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            padding: 2rem;
            background: rgba(245, 158, 11, 0.05);
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #f59e0b;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2rem;
            color: #f59e0b;
            margin-bottom: 0.75rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .articles-grid {
            padding: 2rem;
        }

        .articles-grid h3 {
            margin: 0 0 1.5rem 0;
            color: #1f2937;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .article-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #f59e0b;
            position: relative;
        }

        .article-card:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .rank-number {
            position: absolute;
            top: -10px;
            right: 1rem;
            background: #f59e0b;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .rank-number.top-3 {
            background: linear-gradient(135deg, #ffd700, #ffb700);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .article-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
            line-height: 1.4;
            padding-right: 2rem;
        }

        .article-info {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .article-date {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .article-author {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .article-category {
            background: #f59e0b;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .article-stats {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .stat-badge {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.85rem;
            color: #6b7280;
            padding: 0.25rem 0.75rem;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 12px;
        }

        .views-highlight {
            background: rgba(245, 158, 11, 0.2);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #92400e;
            font-weight: 600;
        }

        .pagination-wrapper {
            padding: 2rem;
            text-align: center;
        }

        .pagination {
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
        }

        .pagination .current {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
        }

        @media (max-width: 768px) {
            .stats-overview {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                padding: 1.5rem;
            }

            .articles-grid {
                padding: 1.5rem;
            }

            .article-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .article-info {
                gap: 0.75rem;
            }

            .article-stats {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .article-title {
                padding-right: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="details-container">
        <div class="details-header">
            <button class="close-btn" onclick="window.close()">
                <i class="fas fa-times"></i>
            </button>
            <h1>{{ $title }}</h1>
            <p>{{ $subtitle }}</p>
        </div>

        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="stat-value">{{ $articles->total() }}</div>
                <div class="stat-label">Total Artikel</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-value">{{ number_format($articles->sum('views')) }}</div>
                <div class="stat-label">Total Views</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">{{ $articles->count() > 0 ? number_format($articles->sum('views') / $articles->count()) : '0' }}</div>
                <div class="stat-label">Rata-rata Views</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-value">{{ number_format($articles->max('views') ?? 0) }}</div>
                <div class="stat-label">Views Tertinggi</div>
            </div>
        </div>

        <div class="articles-grid">
            <h3>
                <i class="fas fa-list"></i>
                Ranking Artikel
                @if($articles->total() > 0)
                    ({{ $articles->firstItem() }}-{{ $articles->lastItem() }} dari {{ $articles->total() }})
                @endif
            </h3>

            @forelse($articles as $index => $article)
            <div class="article-card">
                <div class="rank-number {{ $index < 3 ? 'top-3' : '' }}">
                    #{{ ($articles->currentPage() - 1) * $articles->perPage() + $index + 1 }}
                </div>
                <div class="article-meta">
                    <div style="flex: 1;">
                        <h4 class="article-title">{{ $article->title }}</h4>
                        <div class="article-info">
                            <span class="article-date">
                                <i class="fas fa-calendar"></i>
                                {{ $article->created_at->format('d M Y') }}
                            </span>
                            <span class="article-author">
                                <i class="fas fa-user"></i>
                                {{ $article->user->name }}
                            </span>
                            <span class="article-category">
                                {{ $article->category->name }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="article-stats">
                    <span class="stat-badge views-highlight">
                        <i class="fas fa-eye"></i>
                        {{ number_format($article->analytics_views ?? $article->views) }} views
                    </span>
                    <span class="stat-badge">
                        <i class="fas fa-comments"></i>
                        {{ $article->comments->count() }} komentar
                    </span>
                    <span class="stat-badge">
                        <i class="fas fa-heart"></i>
                        {{ number_format($article->likes) }} likes
                    </span>
                    <span class="stat-badge">
                        <i class="fas fa-share-alt"></i>
                        {{ number_format($article->shares) }} shares
                    </span>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 3rem; color: #9ca3af;">
                <i class="fas fa-newspaper" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Tidak ada artikel yang ditemukan.</p>
            </div>
            @endforelse

            @if($articles->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($articles->onFirstPage())
                        <span><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $articles->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                        @if ($page == $articles->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Close window with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.close();
            }
        });
    </script>
</body>
</html>
