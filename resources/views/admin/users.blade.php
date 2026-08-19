<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - Portal Berita Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-tabs.css') }}">
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
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
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
                </div>                <nav class="sidebar-nav">
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">
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
                            <a href="{{ route('admin.users') }}" class="nav-link active">
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
                <h1 class="page-title">Kelola Admin & User</h1>                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <span>/</span>
                    <span>Users</span>
                </nav>
            </div>            <!-- Statistics Cards - Admin -->
            <div id="adminStats" class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                    <div class="stat-label">Total Admin</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\User::where('role', 'admin')->whereDate('created_at', today())->count() }}</div>
                    <div class="stat-label">Admin Baru</div>
                </div>
            </div>

            <!-- Statistics Cards - User -->
            <div id="userStats" class="stats-grid" style="display:none;">
                <div class="stat-card green">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                    <div class="stat-label">Total User</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\User::where('role', 'user')->whereDate('created_at', today())->count() }}</div>
                    <div class="stat-label">User Baru</div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                    <div class="stat-label">Active Users</div>
                </div>
            </div><!-- Tab Navigation -->
            <div class="tab-navigation">
                <button id="adminTab" class="tab-button active" onclick="showTab('admin')">Data Admin</button>
                <button id="userTab" class="tab-button" onclick="showTab('user')">Data User</button>
            </div>

            <!-- Admin Table -->
            <div id="adminTable" class="table-section">
                <div class="table-header">
                    <h3 class="table-title">Data Admin</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $adminCount = 0; @endphp
                            @forelse($users->where('role', 'admin') as $user)
                                @php $adminCount++; @endphp
                                <tr>
                                    <td>{{ $adminCount }}</td>
                                    <td>
                                        <div class="user-info-table">
                                            <div class="user-name">{{ $user->name }}</div>
                                            @if($user->id === Auth::id())
                                                <span class="badge badge-info">You</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            @if($user->id !== Auth::id())
                                                <button class="btn btn-sm btn-warning" 
                                                        onclick="changeRole({{ $user->id }}, '{{ $user->role }}')"
                                                        title="Ubah Role">
                                                    <i class="fas fa-user-cog"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteUser({{ $user->id }})"
                                                        title="Hapus User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <div class="empty-state">
                                            <i class="fas fa-user-shield"></i>
                                            <h4>Tidak Ada Admin</h4>
                                            <p>Belum ada admin yang terdaftar dalam sistem.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- User Table -->
            <div id="userTable" class="table-section" style="display:none;">
                <div class="table-header">
                    <h3 class="table-title">Data User</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $userCount = 0; @endphp
                            @forelse($users->where('role', 'user') as $user)
                                @php $userCount++; @endphp
                                <tr>
                                    <td>{{ $userCount }}</td>
                                    <td>
                                        <div class="user-info-table">
                                            <div class="user-name">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge badge-primary">
                                                <i class="fas fa-user-shield"></i>
                                                Admin
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="fas fa-user"></i>
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="changeRole({{ $user->id }}, '{{ $user->role }}')"
                                                    title="Ubah Role">
                                                <i class="fas fa-user-cog"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="deleteUser({{ $user->id }})"
                                                    title="Hapus User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <div class="empty-state">
                                            <i class="fas fa-users"></i>
                                            <h4>Tidak Ada User</h4>
                                            <p>Belum ada user yang terdaftar dalam sistem.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
        window.addEventListener('resize', checkScreenSize);        // Tab switching function
        function showTab(tab) {
            // Update tab buttons
            document.getElementById('adminTab').classList.remove('active');
            document.getElementById('userTab').classList.remove('active');
            document.getElementById(tab + 'Tab').classList.add('active');
            
            // Update tables visibility
            document.getElementById('adminTable').style.display = 'none';
            document.getElementById('userTable').style.display = 'none';
            document.getElementById(tab + 'Table').style.display = 'block';
            
            // Update stats visibility
            document.getElementById('adminStats').style.display = 'none';
            document.getElementById('userStats').style.display = 'none';
            document.getElementById(tab + 'Stats').style.display = 'grid';
            
            // Update page title
            if (tab === 'admin') {
                document.querySelector('.page-title').textContent = 'Kelola Admin';
                document.querySelector('.breadcrumb span:last-child').textContent = 'Users';
            } else {
                document.querySelector('.page-title').textContent = 'Kelola User';
                document.querySelector('.breadcrumb span:last-child').textContent = 'Users';
            }
        }

        // Change user role
        function changeRole(userId, currentRole) {
            const newRole = currentRole === 'admin' ? 'user' : 'admin';
            if (confirm(`Ubah role user menjadi ${newRole}?`)) {
                // Here you would implement the role change functionality
                alert('Fitur ubah role akan diimplementasikan selanjutnya');
            }
        }

        // Delete user
        function deleteUser(userId) {
            if (confirm('Yakin ingin menghapus user ini?')) {
                // Here you would implement the delete functionality
                alert('Fitur hapus user akan diimplementasikan selanjutnya');
            }
        }

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
</body>
</html>
