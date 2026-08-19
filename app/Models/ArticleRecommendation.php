<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'recommended_article_id',
        'similarity_score',
        'recommendation_type',
        'factors',
    ];

    protected $casts = [
        'factors' => 'array',
        'similarity_score' => 'float',
    ];

    /**
     * Get the article that owns the recommendation.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the recommended article.
     */
    public function recommendedArticle(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'recommended_article_id');
    }
}
