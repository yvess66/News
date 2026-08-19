<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserActivity;
use App\Models\User;
use App\Models\Article;
use Carbon\Carbon;

class UserActivitySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $articles = Article::all();
        
        foreach ($articles as $article) {
            // Random number of views for each article (50-500)
            $article->views = rand(50, 500);
            
            // Random number of likes (0-100)
            $article->likes = rand(0, 100);
            
            // Random number of shares (0-50)
            $article->shares = rand(0, 50);
            
            $article->save();

            // Create user activities for this article
            $numActivities = rand(3, 10); // Each article gets 3-10 activities
            for ($i = 0; $i < $numActivities; $i++) {
                $activityType = rand(0, 2) === 0 ? 'comment' : (rand(0, 1) === 0 ? 'like' : 'share');
                $activityDate = Carbon::instance($article->created_at)
                    ->addDays(rand(0, 7))
                    ->addHours(rand(0, 23));

                UserActivity::create([
                    'user_id' => $users->random()->id,
                    'activity_type' => $activityType,
                    'last_activity' => $activityDate,
                    'created_at' => $activityDate,
                    'updated_at' => $activityDate
                ]);
            }
        }

        // Add some login activities
        foreach ($users as $user) {
            for ($i = 0; $i < rand(3, 7); $i++) {
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'login',
                    'last_activity' => Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23)),
                    'created_at' => Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23))
                ]);
            }
        }
    }
}
