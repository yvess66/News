<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Pastikan 'name' bisa diisi lewat mass assignment

    /**
     * Relasi: Satu kategori memiliki banyak artikel
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
