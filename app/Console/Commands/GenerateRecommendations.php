<?php

namespace App\Console\Commands;

use App\Services\RecommendationService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommendations:generate {--article_id= : Generate recommendations for specific article}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate article recommendations using data mining algorithms';

    private RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        parent::__construct();
        $this->recommendationService = $recommendationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting recommendation generation...');
        
        $articleId = $this->option('article_id');
        
        if ($articleId) {
            $this->generateForArticle($articleId);
        } else {
            $this->generateForAllArticles();
        }
        
        $this->info('✅ Recommendation generation completed!');
    }
    
    private function generateForArticle(int $articleId): void
    {
        $article = \App\Models\Article::find($articleId);
        
        if (!$article) {
            $this->error("Article with ID {$articleId} not found!");
            return;
        }
        
        $this->info("Generating recommendations for: {$article->title}");
        
        $recommendations = $this->recommendationService->generateRecommendationsForArticle($article);
        
        $this->info("Generated {$recommendations->count()} recommendations");
        
        // Display top 5 recommendations
        $this->table(
            ['Title', 'Score', 'Type'],
            $recommendations->take(5)->map(function($rec) {
                return [
                    Str::limit($rec['article']->title, 50),
                    round($rec['score'] * 100, 2) . '%',
                    $rec['type']
                ];
            })->toArray()
        );
    }
    
    private function generateForAllArticles(): void
    {
        $this->info('Generating recommendations for all articles...');
        
        $this->withProgressBar(\App\Models\Article::count(), function() {
            $this->recommendationService->generateAllRecommendations();
        });
        
        $this->newLine();
        $this->info('All recommendations generated successfully!');
    }
}
