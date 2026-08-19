<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'article_id',
        'type'
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Article
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
