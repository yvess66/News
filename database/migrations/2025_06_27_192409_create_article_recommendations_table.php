<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('recommended_article_id')->constrained('articles')->onDelete('cascade');
            $table->decimal('similarity_score', 8, 4)->default(0); // Score 0-100
            $table->string('recommendation_type')->default('content_based'); // content_based, collaborative, hybrid
            $table->json('factors')->nullable(); // Store factors that influenced recommendation
            $table->timestamps();
            
            // Prevent duplicate recommendations
            $table->unique(['article_id', 'recommended_article_id']);
            
            // Index for performance
            $table->index(['article_id', 'similarity_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_recommendations');
    }
};
