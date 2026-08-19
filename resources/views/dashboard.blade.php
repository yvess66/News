<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portal Berita Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/category-colors.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    @if(Auth::user()->role !== 'admin')
        <div style="text-align: center; padding: 50px;">
            <h1>Access Denied</h1>
            <p>You don't have permission to access this page.</p>
            <a href="{{ route('home') }}" class="btn">Go to Home</a>
        </div>
    @else
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
            </div>
            <div class="navbar-right">
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
                            <a href="{{ route('dashboard') }}" class="nav-link active">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('articles.index') }}" class="nav-link">
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
        <main class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <span>/</span>
                    <span>Dashboard</span>
                </nav>
            </div>

            <!-- Statistics Cards -->
            @if(Auth::user()->role === 'admin')
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $articles->count() }}</div>
                    <div class="stat-label">Jumlah Berita</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                    <div class="stat-label">Komentar</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\Category::count() }}</div>
                    <div class="stat-label">Users</div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\Comment::count() }}</div>
                    <div class="stat-label">Admin</div>
                </div>
            </div>
            @endif

            <!-- Data Tables Section -->
            <div class="table-section">
                <div class="table-header">
                    <h3 class="table-title">Data Berita</h3>
                    <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                        Tambah Berita
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Berita</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $index => $article)
                            <tr>
                                <td>{{ ($articles->currentPage() - 1) * $articles->perPage() + $index + 1 }}</td>
                                <td>{{ Str::limit($article->title, 30) }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $article->category->name ?? 'Tidak Berkategori' }}
                                    </span>
                                </td>
                                <td>{{ $article->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('articles.edit', $article->slug) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data berita</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links -->
                <div class="pagination-container">
                    {{ $articles->links() }}
                </div>
            </div>

            @if(Auth::user()->role === 'admin')

            @endif
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
            const cards = document.querySelectorAll('.stat-card, .table-section');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * 100);
            });
        });
    </script>
    @endif
</body>
</html>
