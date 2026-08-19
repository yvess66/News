<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\ArticleAnalytics;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(10); // menampilkan 10 berita per halaman
        return view('dashboard', compact('articles'));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function analytics()
    {
        // Get monthly article counts for the past 12 months
        $monthlyArticles = Article::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $item->month);
                return [
                    'month' => $date->format('M Y'),
                    'month_short' => $date->format('M'),
                    'year' => $date->format('Y'),
                    'count' => $item->count,
                    'raw_month' => $item->month
                ];
            });

        // Fill missing months with 0 counts
        $allMonths = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $existing = $monthlyArticles->firstWhere('raw_month', $monthKey);
            
            if ($existing) {
                $allMonths->push($existing);
            } else {
                $allMonths->push([
                    'month' => $date->format('M Y'),
                    'month_short' => $date->format('M'),
                    'year' => $date->format('Y'),
                    'count' => 0,
                    'raw_month' => $monthKey
                ]);
            }
        }
        $monthlyArticles = $allMonths;

        // Get category distribution
        $categoryStats = Article::selectRaw('categories.name, COUNT(*) as count')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();

        // Get user engagement (comments per article)
        $articleEngagement = Article::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();

        // Get active users (daily)
        $dailyActiveUsers = \App\Models\UserActivity::where('last_activity', '>=', now()->subDay())
            ->distinct('user_id')
            ->count('user_id');

        // Get active users (weekly)
        $weeklyActiveUsers = \App\Models\UserActivity::where('last_activity', '>=', now()->subWeek())
            ->distinct('user_id')
            ->count('user_id');

        // Get total engagement stats (including new analytics data)
        $totalEngagement = [
            'comments' => \App\Models\Comment::count(),
            'likes' => Article::sum('likes') ?? 0,
            'shares' => Article::sum('shares') ?? 0,
        ];

        // Try to get analytics data, fallback to 0 if table doesn't exist or has issues
        try {
            $totalEngagement['views'] = \App\Models\ArticleAnalytics::where('action_type', 'view')->count();
            $totalEngagement['prints'] = \App\Models\ArticleAnalytics::where('action_type', 'print')->count();
            $totalEngagement['bookmarks'] = \App\Models\ArticleAnalytics::where('action_type', 'bookmark')->count();
            $totalEngagement['copy_links'] = \App\Models\ArticleAnalytics::where('action_type', 'copy_link')->count();
        } catch (\Exception $e) {
            $totalEngagement['views'] = 0;
            $totalEngagement['prints'] = 0;
            $totalEngagement['bookmarks'] = 0;
            $totalEngagement['copy_links'] = 0;
        }

        // Get top viewed articles (from analytics data with fallback)
        try {
            $topViewedArticles = Article::leftJoin('article_analytics', 'articles.id', '=', 'article_analytics.article_id')
                ->selectRaw('articles.*, COUNT(CASE WHEN article_analytics.action_type = "view" THEN 1 END) as analytics_views')
                ->groupBy('articles.id')
                ->orderBy('analytics_views', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            // Fallback to original views column if analytics table has issues
            $topViewedArticles = Article::orderBy('views', 'desc')
                ->take(5)
                ->get();
        }

        // Get social media platform stats (with fallback)
        try {
            $socialPlatformStats = \App\Models\ArticleAnalytics::where('action_type', 'share')
                ->selectRaw('platform, COUNT(*) as count')
                ->whereNotNull('platform')
                ->groupBy('platform')
                ->orderBy('count', 'desc')
                ->get();
        } catch (\Exception $e) {
            $socialPlatformStats = collect([]);
        }

        // Get recent engagement activities (last 7 days, with fallback)
        try {
            $recentEngagement = \App\Models\ArticleAnalytics::with('article')
                ->where('created_at', '>=', now()->subWeek())
                ->selectRaw('action_type, COUNT(*) as count')
                ->groupBy('action_type')
                ->get();
        } catch (\Exception $e) {
            $recentEngagement = collect([]);
        }

        // Get daily analytics for the past 7 days (with fallback)
        try {
            $dailyAnalytics = \App\Models\ArticleAnalytics::selectRaw('DATE(created_at) as date, action_type, COUNT(*) as count')
                ->where('created_at', '>=', now()->subWeek())
                ->groupBy('date', 'action_type')
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy('date');
        } catch (\Exception $e) {
            $dailyAnalytics = collect([]);
        }

        return view('admin.analytics', compact(
            'monthlyArticles', 
            'categoryStats', 
            'articleEngagement',
            'dailyActiveUsers',
            'weeklyActiveUsers',
            'totalEngagement',
            'topViewedArticles',
            'socialPlatformStats',
            'recentEngagement',
            'dailyAnalytics'
        ));
    }
    
    /**
     * Show monthly article details
     */
    public function monthlyArticleDetails($year, $month)
    {
        if ($month === 'all') {
            // Show all months overview
            $articles = Article::with(['user', 'category'])
                ->selectRaw('articles.*, DATE_FORMAT(created_at, "%Y-%m") as month_year')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            $title = 'Semua Artikel - Tren Bulanan';
            $subtitle = 'Overview artikel per bulan dalam 12 bulan terakhir';
        } else {
            // Show specific month articles
            $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->startOfMonth();
            $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->endOfMonth();
            
            $articles = Article::with(['user', 'category'])
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            $monthName = $startOfMonth->format('F Y');
            $title = "Artikel Bulan {$monthName}";
            $subtitle = "Total: " . $articles->total() . " artikel diterbitkan";
        }

        // Get monthly stats
        $monthlyStats = Article::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.analytics.monthly-details', compact('articles', 'title', 'subtitle', 'monthlyStats', 'year', 'month'));
    }

    /**
     * Show category article details
     */
    public function categoryArticleDetails($categoryName)
    {
        $category = \App\Models\Category::where('name', $categoryName)->first();
        
        if (!$category) {
            abort(404, 'Kategori tidak ditemukan');
        }

        $articles = Article::with(['user', 'category'])
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $title = "Artikel Kategori: {$categoryName}";
        $subtitle = "Total: " . $articles->total() . " artikel dalam kategori ini";

        // Get category stats
        $categoryStats = Article::selectRaw('categories.name, COUNT(*) as count')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.analytics.category-details', compact('articles', 'title', 'subtitle', 'categoryStats', 'category'));
    }

    /**
     * Show top viewed articles details
     */
    public function topViewedDetails()
    {
        try {
            $articles = Article::with(['user', 'category'])
                ->leftJoin('article_analytics', 'articles.id', '=', 'article_analytics.article_id')
                ->selectRaw('articles.*, COUNT(CASE WHEN article_analytics.action_type = "view" THEN 1 END) as analytics_views')
                ->groupBy('articles.id')
                ->orderBy('analytics_views', 'desc')
                ->paginate(20);
        } catch (\Exception $e) {
            // Fallback to original views column
            $articles = Article::with(['user', 'category'])
                ->orderBy('views', 'desc')
                ->paginate(20);
        }

        $title = 'Artikel Paling Banyak Dilihat';
        $subtitle = 'Artikel dengan jumlah views tertinggi';

        return view('admin.analytics.top-viewed-details', compact('articles', 'title', 'subtitle'));
    }

    /**
     * Show engagement details
     */
    public function engagementDetails()
    {
        $articles = Article::with(['user', 'category'])
            ->withCount(['comments'])
            ->orderBy('comments_count', 'desc')
            ->orderBy('likes', 'desc')
            ->orderBy('shares', 'desc')
            ->paginate(20);

        $title = 'Artikel dengan Engagement Tertinggi';
        $subtitle = 'Artikel dengan jumlah komentar, likes, dan shares terbanyak';

        // Get engagement stats
        $engagementStats = [
            'total_comments' => \App\Models\Comment::count(),
            'total_likes' => Article::sum('likes') ?? 0,
            'total_shares' => Article::sum('shares') ?? 0,
        ];

        return view('admin.analytics.engagement-details', compact('articles', 'title', 'subtitle', 'engagementStats'));
    }

    // API Methods for Modal Data
    public function apiMonthlyArticles($year, $month)
    {
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
        $startDate = "$year-$monthPadded-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $articles = Article::with(['user', 'category'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'author' => $article->user->name ?? 'Admin',
                    'category' => $article->category->name ?? 'Uncategorized',
                    'created_at' => $article->created_at->format('d M Y'),
                    'views' => $article->views ?? 0,
                    'likes' => $article->likes ?? 0,
                    'shares' => $article->shares ?? 0,
                    'comments_count' => $article->comments_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $articles,
            'month_name' => \Carbon\Carbon::createFromFormat('Y-m', "$year-$monthPadded")->format('F Y'),
            'total' => $articles->count()
        ]);
    }

    public function apiCategoryArticles($category)
    {
        $categoryModel = \App\Models\Category::where('name', $category)->first();
        
        if (!$categoryModel) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $articles = Article::with(['user'])
            ->where('category_id', $categoryModel->id)
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'author' => $article->user->name ?? 'Admin',
                    'created_at' => $article->created_at->format('d M Y'),
                    'views' => $article->views ?? 0,
                    'likes' => $article->likes ?? 0,
                    'shares' => $article->shares ?? 0,
                    'comments_count' => $article->comments_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $articles,
            'category' => $category,
            'total' => $articles->count()
        ]);
    }

    public function apiTopViewed()
    {
        $articles = Article::with(['user', 'category'])
            ->withCount('comments')
            ->orderBy('views', 'desc')
            ->take(20)
            ->get()
            ->map(function ($article, $index) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'rank' => $index + 1,
                    'author' => $article->user->name ?? 'Admin',
                    'category' => $article->category->name ?? 'Uncategorized',
                    'created_at' => $article->created_at->format('d M Y'),
                    'views' => $article->views ?? 0,
                    'likes' => $article->likes ?? 0,
                    'shares' => $article->shares ?? 0,
                    'comments_count' => $article->comments_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $articles,
            'total' => $articles->count()
        ]);
    }

    public function apiEngagement()
    {
        $articles = Article::with(['user', 'category'])
            ->withCount('comments')
            ->get()
            ->map(function ($article) {
                $totalEngagement = ($article->views ?? 0) + 
                                 ($article->likes ?? 0) + 
                                 ($article->shares ?? 0) + 
                                 $article->comments_count;
                return array_merge($article->toArray(), [
                    'total_engagement' => $totalEngagement
                ]);
            })
            ->sortByDesc('total_engagement')
            ->take(20)
            ->values()
            ->map(function ($article, $index) {
                return [
                    'id' => $article['id'],
                    'title' => $article['title'],
                    'slug' => $article['slug'],
                    'rank' => $index + 1,
                    'author' => $article['user']['name'] ?? 'Admin',
                    'category' => $article['category']['name'] ?? 'Uncategorized',
                    'created_at' => \Carbon\Carbon::parse($article['created_at'])->format('d M Y'),
                    'views' => $article['views'] ?? 0,
                    'likes' => $article['likes'] ?? 0,
                    'shares' => $article['shares'] ?? 0,
                    'comments_count' => $article['comments_count'],
                    'total_engagement' => $article['total_engagement'],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $articles,
            'total' => $articles->count()
        ]);
    }
}

