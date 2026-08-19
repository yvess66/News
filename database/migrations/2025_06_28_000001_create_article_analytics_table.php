<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('action_type'); // view, share, print, bookmark, copy_link
            $table->string('platform')->nullable(); // facebook, twitter, linkedin, whatsapp, email, etc
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['article_id', 'action_type']);
            $table->index(['user_id', 'action_type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_analytics');
    }
};
