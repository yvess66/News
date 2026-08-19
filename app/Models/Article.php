<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'category_id',
        'user_id',
        'featured_image',
        'published_at',
        'views',
        'likes',
        'dislikes',
        'shares',
        'tags',
        'keywords',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke user (penulis artikel)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // Relasi ke reactions (likes/dislikes)
    public function reactions()
    {
        return $this->hasMany(ArticleReaction::class);
    }
    
    // Relasi ke analytics
    public function analytics()
    {
        return $this->hasMany(ArticleAnalytics::class);
    }
    
    // Relasi ke likes saja
    public function likesReactions()
    {
        return $this->hasMany(ArticleReaction::class)->where('type', 'like');
    }
    
    // Relasi ke dislikes saja
    public function dislikesReactions()
    {
        return $this->hasMany(ArticleReaction::class)->where('type', 'dislike');
    }
    
    // Relasi ke recommendations
    public function recommendations()
    {
        return $this->hasMany(ArticleRecommendation::class);
    }
    
    // Relasi ke recommended articles
    public function recommendedArticles()
    {
        return $this->hasMany(ArticleRecommendation::class)
                   ->with('recommendedArticle')
                   ->orderBy('similarity_score', 'desc');
    }
    
    // Relasi ke articles yang merekomendasikan artikel ini
    public function recommendedBy()
    {
        return $this->hasMany(ArticleRecommendation::class, 'recommended_article_id');
    }
    
    // Helper method untuk mendapatkan reaksi user tertentu
    public function getUserReaction($userId)
    {
        return $this->reactions()->where('user_id', $userId)->first();
    }
    
    // Helper method untuk mengecek apakah user sudah like
    public function isLikedByUser($userId)
    {
        return $this->reactions()->where('user_id', $userId)->where('type', 'like')->exists();
    }
    
    // Helper method untuk mengecek apakah user sudah dislike
    public function isDislikedByUser($userId)
    {
        return $this->reactions()->where('user_id', $userId)->where('type', 'dislike')->exists();
    }
}
