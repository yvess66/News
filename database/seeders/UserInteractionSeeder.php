<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use App\Models\ArticleReaction;
use Illuminate\Database\Seeder;

class UserInteractionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $articles = Article::all();
        $users = User::all();
        
        // Generate random likes/dislikes for collaborative filtering
        foreach ($articles as $article) {
            // Randomly select 30-70% of users to interact with this article
            $interactionRate = rand(30, 70) / 100;
            $usersToInteract = $users->random(ceil($users->count() * $interactionRate));
            
            foreach ($usersToInteract as $user) {
                // 80% chance to like, 20% chance to dislike
                $reactionType = rand(1, 100) <= 80 ? 'like' : 'dislike';
                
                // Check if reaction already exists
                $existingReaction = ArticleReaction::where('article_id', $article->id)
                                                 ->where('user_id', $user->id)
                                                 ->first();
                
                if (!$existingReaction) {
                    ArticleReaction::create([
                        'article_id' => $article->id,
                        'user_id' => $user->id,
                        'type' => $reactionType
                    ]);
                    
                    // Update article counters
                    if ($reactionType === 'like') {
                        $article->increment('likes');
                    } else {
                        $article->increment('dislikes');
                    }
                }
            }
            
            // Generate random views (100-1000 views per article)
            $randomViews = rand(100, 1000);
            $article->update(['views' => $randomViews]);
        }
        
        $this->command->info('✅ User interactions seeded successfully!');
    }
}
