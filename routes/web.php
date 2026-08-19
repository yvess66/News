<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticleEngagementController;
use App\Models\Article;

// ROUTE HALAMAN UTAMA
Route::get('/', [ArticleController::class, 'welcome'])->name('home'); // Menggunakan welcome() dari ArticleController

// DASHBOARD (khusus admin)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// ADMIN ROUTES (khusus admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users'); // Manage users/admins
    Route::get('/admin/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics'); // Analytics dashboard
});

// ADMIN (jika ada dashboard khusus admin)
Route::get('/admin', function () {
    return view('admin.dashboard'); // sesuaikan nama view kamu
})->middleware(['auth', 'admin']);

// FILTER ARTIKEL BERDASARKAN KATEGORI
Route::get('/kategori/{id}', [ArticleController::class, 'filterByCategory'])->name('articles.byCategory');

// Route untuk menandai notifikasi sebagai telah dibaca
Route::post('/mark-notifications-as-read', [ArticleController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');

// CRUD ARTICLE (khusus user login)
Route::middleware(['auth'])->group(function () {
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index'); // List all articles
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit'); // Menggunakan slug untuk edit
    Route::put('/articles/{slug}', [ArticleController::class, 'update'])->name('articles.update'); // Menggunakan slug untuk update
    Route::delete('/articles/{slug}', [ArticleController::class, 'destroy'])->name('articles.destroy'); // Menggunakan slug untuk delete
    
    // Comment routes
    Route::post('/articles/{articleId}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Article Engagement Routes (Like, Dislike, Share)
    Route::post('/articles/{article}/like', [ArticleEngagementController::class, 'toggleLike'])->name('articles.like');
    Route::post('/articles/{article}/dislike', [ArticleEngagementController::class, 'toggleDislike'])->name('articles.dislike');
});

// Article Engagement Routes (accessible to all users)
Route::post('/articles/{article}/share', [ArticleEngagementController::class, 'incrementShare'])->name('articles.share');
Route::get('/articles/{article}/engagement', [ArticleEngagementController::class, 'getEngagementStats'])->name('articles.engagement');

// Article Analytics Routes (accessible to all users for tracking)
Route::post('/articles/{article}/track-view', [ArticleEngagementController::class, 'trackView'])->name('articles.track.view');
Route::post('/articles/{article}/track-share', [ArticleEngagementController::class, 'trackShare'])->name('articles.track.share');
Route::post('/articles/{article}/track-print', [ArticleEngagementController::class, 'trackPrint'])->name('articles.track.print');
Route::post('/articles/{article}/track-copy-link', [ArticleEngagementController::class, 'trackCopyLink'])->name('articles.track.copy-link');

// Admin Analytics Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/analytics/{article}/detailed', [ArticleEngagementController::class, 'getDetailedAnalytics'])->name('admin.analytics.detailed');
    // Monthly articles detail routes
    Route::get('/admin/analytics/monthly/{year}/{month}', [DashboardController::class, 'monthlyArticleDetails'])->name('admin.analytics.monthly');
    Route::get('/admin/analytics/category/{category}', [DashboardController::class, 'categoryArticleDetails'])->name('admin.analytics.category');
    Route::get('/admin/analytics/top-viewed', [DashboardController::class, 'topViewedDetails'])->name('admin.analytics.top-viewed');
    Route::get('/admin/analytics/engagement', [DashboardController::class, 'engagementDetails'])->name('admin.analytics.engagement');
    
    // API endpoints for modal data
    Route::get('/admin/analytics/api/monthly/{year}/{month}', [DashboardController::class, 'apiMonthlyArticles'])->name('admin.analytics.api.monthly');
    Route::get('/admin/analytics/api/category/{category}', [DashboardController::class, 'apiCategoryArticles'])->name('admin.analytics.api.category');
    Route::get('/admin/analytics/api/top-viewed', [DashboardController::class, 'apiTopViewed'])->name('admin.analytics.api.top-viewed');
    Route::get('/admin/analytics/api/engagement', [DashboardController::class, 'apiEngagement'])->name('admin.analytics.api.engagement');
});

// SHOW DETAIL ARTICLE (menggunakan slug sebagai pengganti ID)
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show'); // Menggunakan slug untuk detail artikel

// UPLOAD GAMBAR via Trix Editor
Route::post('/upload-image', [ArticleController::class, 'uploadImage'])->name('articles.uploadImage');

// TEST HALAMAN TRIX
Route::get('/trix-test', function () {
    return view('trix-test');
});


// PROFILE (hanya untuk user login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ROUTE AUTH
require __DIR__.'/auth.php';

// API Routes for recommendations
Route::get('/api/recommendations', [ArticleController::class, 'getRecommendations'])->name('api.recommendations');
