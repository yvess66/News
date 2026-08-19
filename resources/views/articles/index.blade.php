<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Portal Berita Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Modern Admin Dashboard Layout -->
    <div class="admin-container">
        <!-- Top Navigation Bar -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="navbar-logo">
                    <i class="fas fa-newspaper"></i>
                    Administrator
                </a>
            </div>            <div class="navbar-right">
                <div class="user-info">
                    <div class="user-avatar">hb</div>
                    <div class="user-details">
                        <h4>{{ Auth::user()->name }}</h4>
                        <span>Administrator</span>
                    </div>
                </div>
                <div class="logout-btn">
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <div class="user-profile">
                    <div class="profile-avatar">hb</div>
                    <div class="profile-greeting">Selamat Datang,</div>
                    <div class="profile-name">{{ Auth::user()->name }}</div>
                    <div class="profile-status">
                        <div class="status-dot"></div>
                        Online
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('articles.index') }}" class="nav-link active">
                                <i class="fas fa-newspaper"></i>
                                <span>Berita</span>
                            </a>
                        </li>
                        
                        @if(Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('admin.users') }}" class="nav-link">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.analytics') }}" class="nav-link">
                                <i class="fas fa-chart-line"></i>
                                <span>Analytics</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Kelola Berita</h1>
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <span>/</span>
                    <span>Berita</span>
                </nav>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <a href="{{ route('articles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Tambah Berita Baru
                </a>
            </div>            <!-- Articles Table -->
            <div class="table-section">
                <div class="table-header">
                    <h3 class="table-title">Daftar Berita</h3>
                    <div class="table-filters">
                        <form method="GET" action="{{ route('articles.index') }}" class="filter-form">
                            <select class="form-select" name="category_id" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @if(request('category_id'))
                                <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times"></i>
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Judul Berita</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $index => $article)
                            <tr>
                                <td>{{ $articles->firstItem() + $index }}</td>
                                <td>
                                    @if($article->featured_image)
                                        <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                             alt="Featured Image" 
                                             class="article-thumbnail">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="article-title">
                                        {{ Str::limit($article->title, 40) }}
                                    </div>
                                    <div class="article-summary">
                                        {{ Str::limit($article->summary, 60) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $article->category->name ?? 'Tidak Berkategori' }}
                                    </span>
                                </td>
                                <td>{{ $article->user->name ?? 'Unknown' }}</td>
                                <td>{{ $article->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge badge-success">Published</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('articles.show', $article->slug) }}" 
                                           class="btn btn-sm btn-success" 
                                           title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('articles.edit', $article->slug) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('articles.destroy', $article->slug) }}" 
                                              method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>                                <td colspan="8" class="text-center text-muted">
                                    <div class="empty-state">
                                        <i class="fas fa-newspaper"></i>
                                        <h4>Belum Ada Berita</h4>
                                        <p>Mulai tambahkan berita pertama Anda.</p>
                                        <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i>
                                            Tambah Berita
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                <div class="pagination-wrapper">
                    {{ $articles->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- JavaScript for sidebar toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        // Auto-collapse sidebar on mobile
        function checkScreenSize() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        }

        // Check screen size on load and resize
        window.addEventListener('load', checkScreenSize);
        window.addEventListener('resize', checkScreenSize);

        // Add fade-in animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.table-section');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * 100);
            });
        });
    </script>
</body>
</html>
