<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleRecommendation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    /**
     * Generate recommendations for all articles using content-based filtering
     */
    public function generateAllRecommendations(): void
    {
        $articles = Article::with('category')->get();
        
        foreach ($articles as $article) {
            $this->generateRecommendationsForArticle($article);
        }
    }
    
    /**
     * Generate recommendations for a specific article
     */
    public function generateRecommendationsForArticle(Article $article): Collection
    {
        // Clear existing recommendations for this article
        ArticleRecommendation::where('article_id', $article->id)->delete();
        
        $recommendations = collect();
        
        // Content-based recommendations
        $contentBased = $this->getContentBasedRecommendations($article);
        $recommendations = $recommendations->merge($contentBased);
        
        // Collaborative filtering recommendations
        $collaborative = $this->getCollaborativeRecommendations($article);
        $recommendations = $recommendations->merge($collaborative);
        
        // Store recommendations in database
        $this->storeRecommendations($article, $recommendations);
        
        return $recommendations->take(10); // Return top 10
    }
    
    /**
     * Content-based filtering using category, tags, and keywords similarity
     */
    private function getContentBasedRecommendations(Article $article): Collection
    {
        $otherArticles = Article::where('id', '!=', $article->id)
                               ->where('published_at', '<=', now())
                               ->get();
        
        $recommendations = collect();
        
        foreach ($otherArticles as $otherArticle) {
            $score = $this->calculateContentSimilarity($article, $otherArticle);
            
            if ($score > 0.2) { // Minimum threshold
                $recommendations->push([
                    'article' => $otherArticle,
                    'score' => $score,
                    'type' => 'content_based',
                    'factors' => $this->getContentSimilarityFactors($article, $otherArticle)
                ]);
            }
        }
        
        return $recommendations->sortByDesc('score');
    }
    
    /**
     * Collaborative filtering based on user behavior
     */
    private function getCollaborativeRecommendations(Article $article): Collection
    {
        // Find users who liked/viewed this article
        $userIds = DB::table('article_reactions')
                    ->where('article_id', $article->id)
                    ->where('type', 'like')
                    ->pluck('user_id');
        
        if ($userIds->isEmpty()) {
            return collect();
        }
        
        // Find other articles these users also liked
        $likedArticles = DB::table('article_reactions')
                          ->whereIn('user_id', $userIds)
                          ->where('article_id', '!=', $article->id)
                          ->where('type', 'like')
                          ->select('article_id', DB::raw('COUNT(*) as like_count'))
                          ->groupBy('article_id')
                          ->orderBy('like_count', 'desc')
                          ->get();
        
        $recommendations = collect();
        
        foreach ($likedArticles as $liked) {
            $otherArticle = Article::find($liked->article_id);
            if ($otherArticle) {
                $score = $this->calculateCollaborativeScore($liked->like_count, $userIds->count());
                
                $recommendations->push([
                    'article' => $otherArticle,
                    'score' => $score,
                    'type' => 'collaborative',
                    'factors' => [
                        'common_users' => $liked->like_count,
                        'total_users' => $userIds->count()
                    ]
                ]);
            }
        }
        
        return $recommendations->sortByDesc('score');
    }
    
    /**
     * Calculate content similarity between two articles
     */
    private function calculateContentSimilarity(Article $article1, Article $article2): float
    {
        $score = 0.0;
        
        // Category similarity (40% weight)
        if ($article1->category_id === $article2->category_id) {
            $score += 0.4;
        }
        
        // Tags similarity (30% weight)
        if ($article1->tags && $article2->tags) {
            $tags1 = $article1->tags;
            $tags2 = $article2->tags;
            $intersection = array_intersect($tags1, $tags2);
            $union = array_unique(array_merge($tags1, $tags2));
            
            if (count($union) > 0) {
                $jaccard = count($intersection) / count($union);
                $score += $jaccard * 0.3;
            }
        }
        
        // Keywords similarity (20% weight)
        if ($article1->keywords && $article2->keywords) {
            $similarity = $this->calculateTextSimilarity($article1->keywords, $article2->keywords);
            $score += $similarity * 0.2;
        }
        
        // Title similarity (10% weight)
        $titleSimilarity = $this->calculateTextSimilarity($article1->title, $article2->title);
        $score += $titleSimilarity * 0.1;
        
        return min($score, 1.0);
    }
    
    /**
     * Calculate text similarity using cosine similarity
     */
    private function calculateTextSimilarity(string $text1, string $text2): float
    {
        $words1 = $this->getWords($text1);
        $words2 = $this->getWords($text2);
        
        if (empty($words1) || empty($words2)) {
            return 0.0;
        }
        
        $intersection = array_intersect($words1, $words2);
        $union = array_unique(array_merge($words1, $words2));
        
        return count($union) > 0 ? count($intersection) / count($union) : 0.0;
    }
    
    /**
     * Extract words from text for similarity calculation
     */
    private function getWords(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $words = explode(' ', $text);
        
        // Remove stop words and short words
        $stopWords = ['dan', 'atau', 'yang', 'ini', 'itu', 'di', 'ke', 'dari', 'untuk', 'pada', 'dalam', 'dengan', 'adalah', 'akan', 'telah', 'sudah', 'masih', 'belum'];
        
        return array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });
    }
    
    /**
     * Calculate collaborative filtering score
     */
    private function calculateCollaborativeScore(int $commonUsers, int $totalUsers): float
    {
        return $totalUsers > 0 ? ($commonUsers / $totalUsers) * 0.8 : 0.0;
    }
    
    /**
     * Get factors that contributed to content similarity
     */
    private function getContentSimilarityFactors(Article $article1, Article $article2): array
    {
        $factors = [];
        
        if ($article1->category_id === $article2->category_id) {
            $factors[] = 'same_category';
        }
        
        if ($article1->tags && $article2->tags) {
            $intersection = array_intersect($article1->tags, $article2->tags);
            if (!empty($intersection)) {
                $factors[] = 'common_tags: ' . implode(', ', $intersection);
            }
        }
        
        return $factors;
    }
    
    /**
     * Store recommendations in database
     */
    private function storeRecommendations(Article $article, Collection $recommendations): void
    {
        $data = [];
        
        foreach ($recommendations->take(20) as $rec) { // Store top 20
            $data[] = [
                'article_id' => $article->id,
                'recommended_article_id' => $rec['article']->id,
                'similarity_score' => $rec['score'] * 100, // Store as percentage
                'recommendation_type' => $rec['type'],
                'factors' => json_encode($rec['factors']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (!empty($data)) {
            DB::table('article_recommendations')->insert($data);
        }
    }
    
    /**
     * Get recommended articles for display
     */
    public function getRecommendedArticles(Article $article, int $limit = 5): Collection
    {
        $recommendations = ArticleRecommendation::where('article_id', $article->id)
                                               ->with('recommendedArticle.category', 'recommendedArticle.user')
                                               ->orderBy('similarity_score', 'desc')
                                               ->limit($limit)
                                               ->get();
        
        return $recommendations->map(function($rec) {
            return $rec->recommendedArticle;
        })->filter();
    }
    
    /**
     * Update recommendations when article is updated
     */
    public function updateRecommendationsForArticle(Article $article): void
    {
        // Regenerate recommendations for this article
        $this->generateRecommendationsForArticle($article);
        
        // Also update recommendations for articles that might be affected
        $relatedArticles = Article::where('category_id', $article->category_id)
                                 ->where('id', '!=', $article->id)
                                 ->limit(10)
                                 ->get();
        
        foreach ($relatedArticles as $relatedArticle) {
            $this->generateRecommendationsForArticle($relatedArticle);
        }
    }
}
