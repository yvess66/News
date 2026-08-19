<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'action_type',
        'platform',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Relationship to Article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for specific action types
    public function scopeViews($query)
    {
        return $query->where('action_type', 'view');
    }

    public function scopeShares($query)
    {
        return $query->where('action_type', 'share');
    }

    public function scopePrints($query)
    {
        return $query->where('action_type', 'print');
    }

    public function scopeBookmarks($query)
    {
        return $query->where('action_type', 'bookmark');
    }

    public function scopeCopyLinks($query)
    {
        return $query->where('action_type', 'copy_link');
    }

    // Scope for specific platforms
    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    // Get analytics by date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
