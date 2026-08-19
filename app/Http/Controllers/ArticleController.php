<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // Tampilkan daftar semua artikel
    public function index(Request $request)
    {
        $query = Article::with(['category', 'user']);
        
        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $articles = $query->latest()->paginate(10);
        
        // Maintain filter parameters in pagination links
        $articles->appends($request->query());
        
        return view('articles.index', compact('articles'));
    }

    // Tampilkan form tambah berita
    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    // Simpan berita baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $title = $request->input('title');
        $rawContent = $request->input('content');
        $cleanContent = preg_replace('/<\/?p>/', '', $rawContent);

        // Simpan gambar unggulan jika ada
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('uploads', 'public');
        }

        Article::create([
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => Str::limit(strip_tags($cleanContent), 150),
            'content' => $cleanContent,
            'category_id' => $request->input('category_id'),
            'user_id' => Auth::id(),
            'featured_image' => $imagePath,
            'published_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Berita berhasil ditambahkan.');
    }

    // Upload gambar dari Trix Editor
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Pastikan folder uploads ada
            $uploadPath = storage_path('app/public/uploads');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename, 'public');

            return response()->json([
                'uploaded' => 1,
                'fileName' => $filename,
                'url' => asset('storage/'.$path),
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'Gagal upload.']
        ]);
    }

    
    // Tampilkan form untuk edit artikel
    public function edit($slug)
    {
        // Coba cari berdasarkan slug terlebih dahulu
        $article = Article::where('slug', $slug)->first();
        
        // Jika tidak ditemukan dengan slug, coba cari dengan ID (jika parameter adalah angka)
        if (!$article && is_numeric($slug)) {
            $article = Article::find($slug);
        }
        
        // Jika artikel tidak ditemukan, arahkan kembali dengan pesan error
        if (!$article) {
            return redirect()->route('dashboard')->with('error', 'Artikel tidak ditemukan!');
        }
        
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    // Update artikel
    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        // Coba cari berdasarkan slug terlebih dahulu
        $article = Article::where('slug', $slug)->first();
        
        // Jika tidak ditemukan dengan slug, coba cari dengan ID (jika parameter adalah angka)
        if (!$article && is_numeric($slug)) {
            $article = Article::find($slug);
        }
        
        // Jika artikel tidak ditemukan, arahkan kembali dengan pesan error
        if (!$article) {
            return redirect()->route('dashboard')->with('error', 'Artikel tidak ditemukan!');
        }
        
        // Menghapus tag <p> dari konten yang diupdate
        $cleanContent = preg_replace('/<\/?p>/', '', $request->content);
    
        // Memperbarui artikel dengan konten yang sudah dibersihkan
        $article->title = $request->title;
        $article->slug = Str::slug($request->title);
        $article->summary = Str::limit(strip_tags($cleanContent), 150);
        $article->content = $cleanContent;
        $article->category_id = $request->category_id;
    
        // Handle gambar baru jika ada
        if ($request->hasFile('featured_image')) {
            // Hapus gambar lama jika ada
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
    
            // Simpan gambar baru
            $article->featured_image = $request->file('featured_image')->store('uploads', 'public');
        }
    
        // Simpan artikel yang telah diperbarui
        $article->save();
    
        // Mengarahkan kembali ke dashboard
        return redirect()->route('dashboard')->with('success', 'Berita berhasil diperbarui');
    }

    public function show($slug)
    {
        // Coba cari berdasarkan slug terlebih dahulu
        $article = Article::where('slug', $slug)->first();
        
        // Jika tidak ditemukan dengan slug, coba cari dengan ID (jika parameter adalah angka)
        if (!$article && is_numeric($slug)) {
            $article = Article::find($slug);
        }

        // Jika artikel masih tidak ditemukan, arahkan kembali
        if (!$article) {
            return redirect()->route('home')->with('error', 'Artikel tidak ditemukan!');
        }

        // Pastikan kategori tersedia untuk mencegah error "Attempt to read property 'name' on null"
        if (!$article->category) {
            // Buat kategori dummy untuk mencegah error
            $dummyCategory = new Category();
            $dummyCategory->id = 0;
            $dummyCategory->name = 'Tidak Berkategori';
            $article->setRelation('category', $dummyCategory);
        }

        // Load comments dengan eager loading untuk user
        $article->load(['comments' => function($query) {
            $query->with('user')->latest();
        }, 'reactions']);

        // Get categories for navigation (same as welcome page)
        $categories = Category::all();
        
        // Get latest articles for notifications (same logic as welcome page)
        $newArticles = Article::where('created_at', '>=', now()->subHours(24))
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        // Kirim artikel ke articles.show dengan categories dan newArticles
        return view('articles.show', compact('article', 'categories', 'newArticles'));
    }

    public function destroy($slug)
    {
        // Coba cari berdasarkan slug terlebih dahulu
        $article = Article::where('slug', $slug)->first();
        
        // Jika tidak ditemukan dengan slug, coba cari dengan ID (jika parameter adalah angka)
        if (!$article && is_numeric($slug)) {
            $article = Article::find($slug);
        }
        
        // Jika artikel tidak ditemukan, arahkan kembali dengan pesan error
        if (!$article) {
            return redirect()->route('dashboard')->with('error', 'Artikel tidak ditemukan!');
        }

        // Hapus gambar unggulan jika ada
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        // Hapus artikel dari database
        $article->delete();

        return redirect()->route('dashboard')->with('success', 'Berita berhasil dihapus.');
    }

    public function welcome(Request $request)
    {
        $query = Article::query();
        $search = $request->input('search', ''); // Define $search variable with default empty string

        // Filter pencarian jika ada
        if ($request->has('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Get featured article (artikel teratas/terbaru)
        $featuredArticle = $query->orderBy('published_at', 'desc')->first();
        
        // Get ALL remaining articles except featured for full display
        if ($featuredArticle) {
            $articles = Article::when($request->has('search'), function($q) use ($search) {
                                $q->where(function($sq) use ($search) {
                                    $sq->where('title', 'like', '%' . $search . '%')
                                      ->orWhere('content', 'like', '%' . $search . '%');
                                });
                            })
                            ->where('id', '!=', $featuredArticle->id)
                            ->orderBy('published_at', 'desc')
                            ->get(); // Remove take() to get all articles
        } else {
            $articles = $query->orderBy('published_at', 'desc')
                        ->get(); // Remove take() to get all articles
            $featuredArticle = null;
        }
        
        $categories = \App\Models\Category::all(); 

        // Ambil berita terbaru untuk notifikasi (artikel yang diupload dalam 24 jam terakhir)
        // Periksa cookie atau session untuk mengetahui apakah user sudah membaca notifikasi
        $lastRead = $request->cookie('last_notification_read');
        $hasUnreadNotifications = false;
        
        // Jika tidak ada cookie last_read, anggap semua notifikasi baru belum dibaca
        if (!$lastRead) {
            $newArticles = Article::where('created_at', '>=', now()->subHours(24))
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
            $hasUnreadNotifications = $newArticles->count() > 0;
        } else {
            // Jika ada cookie, hanya ambil artikel yang lebih baru dari timestamp terakhir dibaca
            $newArticles = Article::where('created_at', '>=', now()->subHours(24))
                            ->where('created_at', '>', $lastRead)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
            $hasUnreadNotifications = $newArticles->count() > 0;
        }

        return view('welcome', compact('articles', 'categories', 'newArticles', 'hasUnreadNotifications', 'featuredArticle'));
    }

    // Fungsi untuk menandai notifikasi sudah dibaca
    public function markNotificationsAsRead(Request $request)
    {
        $response = response()->json(['success' => true]);
        
        // Set cookie untuk menyimpan waktu terakhir notifikasi dibaca
        return $response->cookie('last_notification_read', now()->toDateTimeString(), 60*24*7); // 1 minggu
    }

    public function filterByCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Get featured article for this category (artikel teratas/terbaru)
        $featuredArticle = Article::where('category_id', $id)
                            ->orderBy('published_at', 'desc')
                            ->first();
        
        // Get ALL remaining articles except featured
        if ($featuredArticle) {
            $articles = Article::where('category_id', $id)
                        ->where('id', '!=', $featuredArticle->id)
                        ->orderBy('published_at', 'desc')
                        ->get(); // Remove take() to get all articles
        } else {
            $articles = Article::where('category_id', $id)
                        ->orderBy('published_at', 'desc')
                        ->get(); // Remove take() to get all articles
            $featuredArticle = null;
        }
        
        $categories = Category::all();

        // Ambil berita terbaru untuk notifikasi (artikel yang diupload dalam 24 jam terakhir)
        $newArticles = Article::where('created_at', '>=', now()->subHours(24))
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('welcome', compact('articles', 'categories', 'category', 'newArticles', 'featuredArticle'));
    }
    
    /**
     * Get recommendations for an article
     */
    public function getRecommendations(Request $request)
    {
        $articleId = $request->input('article_id');
        $limit = $request->input('limit', 5);
        
        if (!$articleId) {
            return response()->json(['error' => 'Article ID is required'], 400);
        }
        
        $article = Article::find($articleId);
        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }
        
        $recommendationService = app(RecommendationService::class);
        $recommendations = $recommendationService->getRecommendedArticles($article, $limit);
        
        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'summary' => Str::limit(strip_tags($article->summary), 100),
                    'featured_image' => $article->featured_image ? asset('storage/' . $article->featured_image) : null,
                    'category' => $article->category->name ?? null,
                    'created_at' => $article->created_at->diffForHumans(),
                    'url' => route('articles.show', $article->slug)
                ];
            })
        ]);
    }
}
