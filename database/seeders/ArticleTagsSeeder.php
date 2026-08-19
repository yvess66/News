<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleTagsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $articles = Article::all();
        
        // Predefined tags by category
        $tagsByCategory = [
            'olahraga' => [
                ['sepak bola', 'liga', 'pertandingan', 'gol', 'pemain'],
                ['basket', 'NBA', 'turnamen', 'skor', 'tim'],
                ['tenis', 'grand slam', 'wimbledon', 'petenis', 'racket'],
                ['atletik', 'lari', 'marathon', 'olimpiade', 'juara'],
                ['renang', 'kolam', 'gaya', 'medali', 'rekor']
            ],
            'teknologi' => [
                ['AI', 'machine learning', 'artificial intelligence', 'robot', 'automation'],
                ['smartphone', 'android', 'iOS', 'mobile', 'aplikasi'],
                ['internet', 'website', 'online', 'digital', 'cyber'],
                ['komputer', 'laptop', 'hardware', 'software', 'programming'],
                ['blockchain', 'cryptocurrency', 'bitcoin', 'digital currency', 'crypto']
            ],
            'ekonomi' => [
                ['saham', 'investasi', 'pasar modal', 'bursa', 'portofolio'],
                ['rupiah', 'nilai tukar', 'inflasi', 'bank indonesia', 'monetary'],
                ['bisnis', 'startup', 'entrepreneur', 'UMKM', 'perdagangan'],
                ['ekspor', 'impor', 'perdagangan luar negeri', 'komoditas', 'global'],
                ['pajak', 'fiskal', 'anggaran', 'pemerintah', 'kebijakan']
            ],
            'politik' => [
                ['pemilu', 'partai', 'calon', 'suara', 'demokrasi'],
                ['DPR', 'parlemen', 'legislatif', 'undang-undang', 'regulasi'],
                ['presiden', 'menteri', 'kabinet', 'pemerintahan', 'eksekutif'],
                ['daerah', 'gubernur', 'bupati', 'walikota', 'otonomi'],
                ['korupsi', 'KPK', 'antikorupsi', 'hukum', 'pengadilan']
            ],
            'kesehatan' => [
                ['covid', 'pandemi', 'vaksin', 'virus', 'kesehatan masyarakat'],
                ['rumah sakit', 'dokter', 'perawat', 'medis', 'pelayanan'],
                ['obat', 'farmasi', 'pengobatan', 'terapi', 'medical'],
                ['gizi', 'nutrisi', 'diet', 'makanan sehat', 'vitamin'],
                ['mental health', 'psikologi', 'stres', 'depresi', 'wellbeing']
            ]
        ];
        
        foreach ($articles as $article) {
            $categoryName = strtolower($article->category->name ?? 'umum');
            
            // Get appropriate tags for the category
            $categoryTags = $tagsByCategory[$categoryName] ?? [
                ['berita', 'informasi', 'update', 'terkini', 'aktual'],
                ['indonesia', 'nasional', 'lokal', 'daerah', 'regional'],
                ['sosial', 'masyarakat', 'komunitas', 'public', 'citizen']
            ];
            
            // Randomly select a tag set
            $selectedTags = $categoryTags[array_rand($categoryTags)];
            
            // Add 2-3 additional random tags
            $additionalTags = [];
            $allTags = array_merge(...array_values($tagsByCategory));
            for ($i = 0; $i < rand(1, 2); $i++) {
                $randomTagSet = $allTags[array_rand($allTags)];
                $additionalTags[] = $randomTagSet[array_rand($randomTagSet)];
            }
            
            $finalTags = array_unique(array_merge($selectedTags, $additionalTags));
            
            // Generate keywords from title and tags
            $keywords = $this->generateKeywords($article->title, $finalTags);
            
            $article->update([
                'tags' => array_slice($finalTags, 0, 5), // Limit to 5 tags
                'keywords' => $keywords
            ]);
        }
        
        $this->command->info('✅ Article tags and keywords seeded successfully!');
    }
    
    private function generateKeywords(string $title, array $tags): string
    {
        // Extract keywords from title
        $titleWords = $this->extractWords($title);
        
        // Combine with tags
        $allKeywords = array_merge($titleWords, $tags);
        
        // Remove duplicates and join
        return implode(', ', array_unique($allKeywords));
    }
    
    private function extractWords(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $words = explode(' ', $text);
        
        // Remove stop words and short words
        $stopWords = ['dan', 'atau', 'yang', 'ini', 'itu', 'di', 'ke', 'dari', 'untuk', 'pada', 'dalam', 'dengan', 'adalah', 'akan', 'telah', 'sudah', 'masih', 'belum', 'yang', 'tidak', 'juga', 'dapat', 'bisa', 'ada', 'nya'];
        
        return array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });
    }
}
