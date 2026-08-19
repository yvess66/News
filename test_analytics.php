<?php
// Test file untuk analytics
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Models\ArticleAnalytics;

echo "=== Testing Analytics System ===\n";

// Test 1: Check if ArticleAnalytics model works
try {
    $count = ArticleAnalytics::count();
    echo "✓ ArticleAnalytics model works. Total records: $count\n";
} catch (Exception $e) {
    echo "✗ ArticleAnalytics model error: " . $e->getMessage() . "\n";
}

// Test 2: Check if we can create a test record
try {
    $article = Article::first();
    if ($article) {
        $analytics = new ArticleAnalytics();
        $analytics->article_id = $article->id;
        $analytics->action_type = 'view';
        $analytics->ip_address = '127.0.0.1';
        $analytics->user_agent = 'Test Agent';
        $analytics->save();
        
        echo "✓ Successfully created test analytics record for article: " . $article->title . "\n";
        
        // Clean up test record
        $analytics->delete();
        echo "✓ Test record cleaned up\n";
    } else {
        echo "✗ No articles found to test with\n";
    }
} catch (Exception $e) {
    echo "✗ Error creating test record: " . $e->getMessage() . "\n";
}

// Test 3: Check the scopes
try {
    $views = ArticleAnalytics::views()->count();
    $shares = ArticleAnalytics::shares()->count();
    $prints = ArticleAnalytics::prints()->count();
    $bookmarks = ArticleAnalytics::bookmarks()->count();
    $copyLinks = ArticleAnalytics::copyLinks()->count();
    
    echo "✓ Scopes work - Views: $views, Shares: $shares, Prints: $prints, Bookmarks: $bookmarks, Copy Links: $copyLinks\n";
} catch (Exception $e) {
    echo "✗ Scope error: " . $e->getMessage() . "\n";
}

echo "=== Test Complete ===\n";
