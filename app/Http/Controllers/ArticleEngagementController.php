<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleReaction;
use App\Models\ArticleAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleEngagementController extends Controller
{
    /**
     * Track article view
     */
    public function trackView(Request $request, Article $article)
    {
        // Increment view count
        $article->increment('views');
        
        // Track detailed analytics
        ArticleAnalytics::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'action_type' => 'view',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'referrer' => $request->header('referer'),
                'timestamp' => now(),
            ]
        ]);

        return response()->json([
            'success' => true,
            'views' => $article->views
        ]);
    }

    /**
     * Toggle like pada artikel
     */
    public function toggleLike(Request $request, Article $article)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to like articles'
            ], 401);
        }

        $userId = Auth::id();
        
        DB::transaction(function () use ($article, $userId, $request) {
            // Cek apakah user sudah punya reaksi sebelumnya
            $existingReaction = $article->reactions()->where('user_id', $userId)->first();
            
            if ($existingReaction) {
                if ($existingReaction->type === 'like') {
                    // Jika sudah like, hapus like
                    $existingReaction->delete();
                    $article->decrement('likes');
                } else {
                    // Jika sudah dislike, ubah ke like
                    $existingReaction->update(['type' => 'like']);
                    $article->decrement('dislikes');
                    $article->increment('likes');
                }
            } else {
                // Jika belum ada reaksi, buat like baru
                ArticleReaction::create([
                    'user_id' => $userId,
                    'article_id' => $article->id,
                    'type' => 'like'
                ]);
                $article->increment('likes');
            }

            // Track analytics
            ArticleAnalytics::create([
                'article_id' => $article->id,
                'user_id' => $userId,
                'action_type' => 'like',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'previous_reaction' => $existingReaction?->type,
                    'timestamp' => now(),
                ]
            ]);
        });

        // Refresh artikel untuk mendapatkan data terbaru
        $article->refresh();

        return response()->json([
            'success' => true,
            'likes' => $article->likes,
            'dislikes' => $article->dislikes,
            'userReaction' => $article->getUserReaction($userId)?->type
        ]);
    }

    /**
     * Toggle dislike pada artikel
     */
    public function toggleDislike(Request $request, Article $article)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to dislike articles'
            ], 401);
        }

        $userId = Auth::id();
        
        DB::transaction(function () use ($article, $userId, $request) {
            // Cek apakah user sudah punya reaksi sebelumnya
            $existingReaction = $article->reactions()->where('user_id', $userId)->first();
            
            if ($existingReaction) {
                if ($existingReaction->type === 'dislike') {
                    // Jika sudah dislike, hapus dislike
                    $existingReaction->delete();
                    $article->decrement('dislikes');
                } else {
                    // Jika sudah like, ubah ke dislike
                    $existingReaction->update(['type' => 'dislike']);
                    $article->decrement('likes');
                    $article->increment('dislikes');
                }
            } else {
                // Jika belum ada reaksi, buat dislike baru
                ArticleReaction::create([
                    'user_id' => $userId,
                    'article_id' => $article->id,
                    'type' => 'dislike'
                ]);
                $article->increment('dislikes');
            }

            // Track analytics
            ArticleAnalytics::create([
                'article_id' => $article->id,
                'user_id' => $userId,
                'action_type' => 'dislike',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'previous_reaction' => $existingReaction?->type,
                    'timestamp' => now(),
                ]
            ]);
        });

        // Refresh artikel untuk mendapatkan data terbaru
        $article->refresh();

        return response()->json([
            'success' => true,
            'likes' => $article->likes,
            'dislikes' => $article->dislikes,
            'userReaction' => $article->getUserReaction($userId)?->type
        ]);
    }

    /**
     * Track social media share with platform
     */
    public function trackShare(Request $request, Article $article)
    {
        $platform = $request->input('platform', 'unknown');
        
        // Increment share count
        $article->increment('shares');
        
        // Track detailed analytics
        ArticleAnalytics::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'action_type' => 'share',
            'platform' => $platform,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'share_url' => $request->input('url'),
                'timestamp' => now(),
            ]
        ]);

        return response()->json([
            'success' => true,
            'shares' => $article->shares,
            'message' => 'Article shared successfully!'
        ]);
    }

    /**
     * Track article print
     */
    public function trackPrint(Request $request, Article $article)
    {
        ArticleAnalytics::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'action_type' => 'print',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'timestamp' => now(),
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Print action tracked successfully!'
        ]);
    }

    /**
     * Track copy link action
     */
    public function trackCopyLink(Request $request, Article $article)
    {
        ArticleAnalytics::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'action_type' => 'copy_link',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'copied_url' => $request->input('url'),
                'timestamp' => now(),
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Link copied successfully!'
        ]);
    }

    /**
     * Increment share count (legacy function)
     */
    public function incrementShare(Request $request, Article $article)
    {
        return $this->trackShare($request, $article);
    }

    /**
     * Get engagement stats for artikel
     */
    public function getEngagementStats(Article $article)
    {
        $userReaction = null;
        if (Auth::check()) {
            $userReaction = $article->getUserReaction(Auth::id())?->type;
        }

        return response()->json([
            'likes' => $article->likes,
            'dislikes' => $article->dislikes,
            'shares' => $article->shares,
            'views' => $article->views,
            'userReaction' => $userReaction
        ]);
    }

    /**
     * Get detailed analytics for an article
     */
    public function getDetailedAnalytics(Article $article)
    {
        $analytics = [
            'total_views' => $article->analytics()->views()->count(),
            'total_shares' => $article->analytics()->shares()->count(),
            'total_prints' => $article->analytics()->prints()->count(),
            'total_bookmarks' => $article->analytics()->bookmarks()->count(),
            'total_copy_links' => $article->analytics()->copyLinks()->count(),
            'share_platforms' => $article->analytics()->shares()
                ->selectRaw('platform, COUNT(*) as count')
                ->groupBy('platform')
                ->pluck('count', 'platform'),
            'daily_views' => $article->analytics()->views()
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date'),
        ];

        return response()->json($analytics);
    }
}
