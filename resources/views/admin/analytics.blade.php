<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Portal Berita Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js with modern styling -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <style>
        /* Modern Analytics Data Styling */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
            overflow-x: auto;
            padding-bottom: 1rem;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .analytics-grid::-webkit-scrollbar {
            height: 8px;
        }

        .analytics-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .analytics-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .analytics-grid::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .analytics-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            backdrop-filter: blur(20px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: visible;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: fit-content;
            display: flex;
            flex-direction: column;
        }

        .analytics-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .analytics-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(30, 64, 175, 0.05));
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            flex-shrink: 0;
        }

        .analytics-header h3 {
            font-size: 1.15rem;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
            font-family: 'Inter', sans-serif;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.3;
            white-space: normal;
        }

        .analytics-header h3 i {
            color: #2563eb;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .analytics-body {
            padding: 2rem;
            flex: 1;
            overflow: visible;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
        }

        .stat-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-item[data-clickable="true"] {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .stat-item[data-clickable="true"]:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        .click-hint {
            position: absolute;
            bottom: 0.5rem;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            color: #2563eb;
            opacity: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            background: rgba(37, 99, 235, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        .stat-item[data-clickable="true"]:hover .click-hint {
            opacity: 1;
            transform: translateX(-50%) translateY(-2px);
        }

        .stat-icon {
            font-size: 2rem;
            color: #2563eb;
            margin-bottom: 0.75rem;
            opacity: 0.8;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Inter', sans-serif;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            word-wrap: break-word;
            hyphens: auto;
            line-height: 1.3;
        }

        .list-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .list-item {
            padding: 1.25rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            min-height: fit-content;
            overflow: visible;
        }

        .list-item:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .rank-badge {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
            margin-top: 0.25rem;
        }

        .item-content {
            flex: 1;
            min-width: 0;
            overflow: visible;
        }

        .item-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
            line-height: 1.4;
            font-family: 'Inter', sans-serif;
            word-wrap: break-word;
            hyphens: auto;
            overflow-wrap: break-word;
            white-space: normal;
            max-width: 100%;
            display: block;
            text-overflow: clip;
        }

        .item-stats {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            overflow-x: auto;
            overflow-y: visible;
            padding-bottom: 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .item-stats::-webkit-scrollbar {
            height: 6px;
        }

        .item-stats::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .item-stats::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .item-stats::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .stat-badge {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            color: #6b7280;
            padding: 0.25rem 0.75rem;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
            min-width: fit-content;
        }

        .stat-badge.views i { color: #06b6d4; }
        .stat-badge.comments i { color: #f59e0b; }
        .stat-badge.likes i { color: #ef4444; }
        .stat-badge.shares i { color: #10b981; }

        .chart-container {
            position: relative;
            height: 320px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .chart-container canvas {
            border-radius: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1rem;
            color: #6b7280;
        }

        /* Tooltip styles for full title display */
        .item-title[title] {
            cursor: help;
            position: relative;
        }

        .item-title[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1.4;
            z-index: 1000;
            white-space: normal;
            word-wrap: break-word;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Ensure proper text overflow handling */
        .list-container {
            overflow: visible;
            position: relative;
        }

        /* Modal styles */
        .analytics-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 2% auto;
            padding: 0;
            border: none;
            border-radius: 16px;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-header .close {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .modal-header .close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
            max-height: calc(90vh - 120px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .modal-article-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .modal-article-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .modal-article-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .modal-article-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            font-size: 1rem;
        }

        .modal-article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .modal-article-stats {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .modal-stat-badge {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            color: #6b7280;
            padding: 0.25rem 0.75rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 500;
        }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f1f5f9;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: #9ca3af;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 5% auto;
                max-height: 85vh;
            }

            .modal-header {
                padding: 1rem 1.5rem;
            }

            .modal-header h2 {
                font-size: 1.25rem;
            }

            .modal-body {
                padding: 1.5rem;
                max-height: calc(85vh - 100px);
            }

            .modal-article-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .modal-article-card {
                padding: 1rem;
            }
        }

        @media (max-width: 1200px) {
            .analytics-grid {
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .analytics-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin-top: 1.5rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 1rem;
            }
            
            .stat-item {
                padding: 1rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }

            .stat-label {
                font-size: 0.75rem;
                letter-spacing: 0.3px;
                word-wrap: break-word;
                hyphens: auto;
                line-height: 1.3;
                text-align: center;
            }

            .analytics-header {
                padding: 1.25rem 1.5rem;
            }

            .analytics-header h3 {
                font-size: 1rem;
                gap: 0.5rem;
                line-height: 1.4;
            }

            .analytics-body {
                padding: 1.5rem;
            }

            .list-item {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .rank-badge {
                align-self: flex-start;
                margin-top: 0;
            }

            .item-content {
                width: 100%;
                min-width: 0;
            }

            .item-title {
                font-size: 0.95rem;
                line-height: 1.4;
                margin-bottom: 0.75rem;
            }

            .item-stats {
                gap: 0.5rem;
                overflow-x: auto;
                padding-bottom: 0.5rem;
                width: 100%;
            }

            .stat-badge {
                font-size: 0.75rem;
                padding: 0.2rem 0.6rem;
                min-width: fit-content;
            }
        }

        @media (max-width: 480px) {
            .analytics-grid {
                gap: 1rem;
                margin-top: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .stat-item {
                padding: 0.75rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .stat-label {
                font-size: 0.7rem;
                word-wrap: break-word;
                hyphens: auto;
                line-height: 1.3;
                text-align: center;
            }

            .analytics-header {
                padding: 1rem 1.25rem;
            }

            .analytics-header h3 {
                font-size: 0.95rem;
                line-height: 1.4;
                gap: 0.5rem;
            }

            .analytics-body {
                padding: 1.25rem;
            }

            .chart-container {
                height: 250px;
            }

            .list-item {
                padding: 0.75rem;
                gap: 0.5rem;
            }

            .item-title {
                font-size: 0.9rem;
                line-height: 1.3;
                margin-bottom: 0.5rem;
            }

            .item-stats {
                gap: 0.4rem;
                overflow-x: auto;
                width: 100%;
                padding-bottom: 0.5rem;
            }

            .stat-badge {
                font-size: 0.7rem;
                padding: 0.15rem 0.5rem;
                min-width: fit-content;
                white-space: nowrap;
            }

            .rank-badge {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }
        }
    </style>
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
                            <a href="{{ route('admin.users') }}" class="nav-link">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.analytics') }}" class="nav-link active">
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
                <h1 class="page-title">Analytics</h1>
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <span>/</span>
                    <span>Analytics</span>
                </nav>
            </div>

            <!-- Analytics Grid -->
            <div class="analytics-grid">
                <!-- Active Users Card -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-users"></i>
                            Pengguna Aktif
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div class="stat-value">{{ number_format($dailyActiveUsers) }}</div>
                                <div class="stat-label">Hari Ini</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-value">{{ number_format($weeklyActiveUsers) }}</div>
                                <div class="stat-label">Minggu Ini</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Engagement Overview -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-chart-bar"></i>
                            Total Engagement
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stat-value">{{ number_format($totalEngagement['views']) }}</div>
                                <div class="stat-label">Views</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="stat-value">{{ number_format($totalEngagement['comments']) }}</div>
                                <div class="stat-label">Komentar</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="stat-value">{{ number_format($totalEngagement['likes']) }}</div>
                                <div class="stat-label">Likes</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                <div class="stat-value">{{ number_format($totalEngagement['shares']) }}</div>
                                <div class="stat-label">Shares</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Actions -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-mouse-pointer"></i>
                            User Actions
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="stat-value">{{ number_format($totalEngagement['prints']) }}</div>
                                <div class="stat-label">Prints</div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div class="stat-value">{{ number_format($totalEngagement['copy_links']) }}</div>
                                <div class="stat-label">Copy Links</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Articles Summary -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-calendar-alt"></i>
                            Ringkasan Artikel Bulanan
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="stats-grid">
                            <div class="stat-item monthly-stat-item" data-clickable="true" data-month="{{ now()->format('m') }}" data-year="{{ now()->format('Y') }}" title="Klik untuk melihat artikel bulan ini">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="stat-value">{{ number_format($monthlyArticles->last()['count'] ?? 0) }}</div>
                                <div class="stat-label">Bulan Ini</div>
                                <div class="click-hint">
                                    <i class="fas fa-mouse-pointer"></i>
                                    Klik untuk detail
                                </div>
                            </div>
                            <div class="stat-item" data-clickable="true" onclick="showMonthlyArticles('all', 'average')" title="Klik untuk melihat tren bulanan">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-value">{{ number_format($monthlyArticles->avg('count')) }}</div>
                                <div class="stat-label">Rata-rata/Bulan</div>
                                <div class="click-hint">
                                    <i class="fas fa-mouse-pointer"></i>
                                    Klik untuk detail
                                </div>
                            </div>
                            <div class="stat-item" data-clickable="true" onclick="showMonthlyArticles('peak', '{{ $monthlyArticles->where('count', $monthlyArticles->max('count'))->first()['year'] ?? date('Y') }}')" title="Klik untuk melihat bulan terbaik">
                                <div class="stat-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-value">{{ number_format($monthlyArticles->max('count')) }}</div>
                                <div class="stat-label">Terbanyak</div>
                                <div class="click-hint">
                                    <i class="fas fa-mouse-pointer"></i>
                                    Klik untuk detail
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Articles Chart -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-chart-line"></i>
                            Tren Artikel 12 Bulan Terakhir
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="chart-container" style="height: 380px;">
                            <canvas id="monthlyArticlesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Category Distribution -->
                <div class="analytics-card" title="Klik pada bagian chart untuk melihat detail kategori">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-pie-chart"></i>
                            Distribusi Kategori
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="chart-container" style="cursor: pointer;">
                            <canvas id="categoryDistributionChart"></canvas>
                        </div>
                        @if($categoryStats->count() > 0)
                        <div style="text-align: center; margin-top: 1rem; color: #6b7280; font-size: 0.8rem;">
                            <i class="fas fa-mouse-pointer"></i>
                            Klik pada bagian chart untuk detail
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Most Viewed Articles -->
                <div class="analytics-card" data-clickable="true" onclick="showTopViewedDetails()" title="Klik untuk melihat semua artikel dengan views tertinggi" style="cursor: pointer;">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-trophy"></i>
                            Artikel Terbanyak Dilihat
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="list-container">
                            @forelse($topViewedArticles as $index => $article)
                            <div class="list-item">
                                <div class="rank-badge">#{{ $index + 1 }}</div>
                                <div class="item-content">
                                    <div class="item-title" title="{{ $article->title }}">{{ $article->title }}</div>
                                    <div class="item-stats">
                                        <span class="stat-badge views">
                                            <i class="fas fa-eye"></i>
                                            {{ number_format($article->analytics_views ?? $article->views ?? 0) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-chart-line"></i>
                                <p>Belum ada data artikel</p>
                            </div>
                            @endforelse
                            
                            @if($topViewedArticles->count() > 0)
                            <div class="click-hint" style="text-align: center; margin-top: 1rem; color: #2563eb; font-size: 0.8rem;">
                                <i class="fas fa-mouse-pointer"></i>
                                Klik card untuk melihat semua artikel
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Social Media Platform Stats -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fab fa-share-alt"></i>
                            Social Media Shares
                        </h3>
                    </div>
                    <div class="analytics-body">
                        @if($socialPlatformStats->count() > 0)
                            <div class="chart-container">
                                <canvas id="socialPlatformChart"></canvas>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-share-alt"></i>
                                <p>Belum ada data social media shares</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Top Engaged Articles -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-fire"></i>
                            Artikel dengan Engagement Tertinggi
                        </h3>
                    </div>
                    <div class="analytics-body">
                        <div class="list-container">
                            @forelse($articleEngagement as $index => $article)
                            <div class="list-item">
                                <div class="rank-badge">#{{ $index + 1 }}</div>
                                <div class="item-content">
                                    <div class="item-title" title="{{ $article->title }}">{{ $article->title }}</div>
                                    <div class="item-stats">
                                        <span class="stat-badge comments">
                                            <i class="fas fa-comments"></i>
                                            {{ number_format($article->comments_count) }}
                                        </span>
                                        <span class="stat-badge likes">
                                            <i class="fas fa-heart"></i>
                                            {{ number_format($article->likes) }}
                                        </span>
                                        <span class="stat-badge shares">
                                            <i class="fas fa-share-alt"></i>
                                            {{ number_format($article->shares) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-fire"></i>
                                <p>Belum ada data engagement</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Engagement Activities -->
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>
                            <i class="fas fa-clock"></i>
                            Aktivitas Minggu Ini
                        </h3>
                    </div>
                    <div class="analytics-body">
                        @forelse($recentEngagement as $activity)
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    @switch($activity->action_type)
                                        @case('view')
                                            <i class="fas fa-eye"></i>
                                            @break
                                        @case('share')
                                            <i class="fas fa-share-alt"></i>
                                            @break
                                        @case('print')
                                            <i class="fas fa-print"></i>
                                            @break
                                        @case('copy_link')
                                            <i class="fas fa-link"></i>
                                            @break
                                        @default
                                            <i class="fas fa-chart-line"></i>
                                    @endswitch
                                </div>
                                <div class="stat-value">{{ number_format($activity->count) }}</div>
                                <div class="stat-label">{{ ucfirst($activity->action_type) }}s</div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-clock"></i>
                            <p>Belum ada data aktivitas minggu ini</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Analytics Detail Modal -->
    <div id="analyticsModal" class="analytics-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">
                    <i class="fas fa-chart-line"></i>
                    Detail Analytics
                </h2>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="loading-spinner">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Charts -->
    <script>
        // Modern Chart.js Configuration
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#6b7280';
        
        // Monthly Articles Chart
        const monthlyData = @json($monthlyArticles);
        const monthlyCtx = document.getElementById('monthlyArticlesChart').getContext('2d');
        
        // Create gradient for the chart
        const gradient = monthlyCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.3)');
        gradient.addColorStop(0.5, 'rgba(37, 99, 235, 0.1)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.05)');

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month_short),
                datasets: [{
                    label: 'Jumlah Artikel',
                    data: monthlyData.map(item => item.count),
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    pointHoverBackgroundColor: '#1d4ed8',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    shadowColor: 'rgba(37, 99, 235, 0.3)',
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowOffsetY: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2563eb',
                        borderWidth: 2,
                        cornerRadius: 8,
                        displayColors: false,
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return monthlyData[index].month;
                            },
                            label: function(context) {
                                return `Artikel: ${context.parsed.y} buah`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            },
                            stepSize: 1,
                            callback: function(value) {
                                return Math.floor(value);
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#1d4ed8'
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Category Distribution Chart
        const categoryData = @json($categoryStats);
        const categoryCtx = document.getElementById('categoryDistributionChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.name),
                datasets: [{
                    data: categoryData.map(item => item.count),
                    backgroundColor: [
                        '#2563eb',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#06b6d4'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const categoryName = categoryData[index].name;
                        showCategoryArticles(categoryName);
                    }
                }
            }
        });

        // Social Media Platform Chart
        @if($socialPlatformStats->count() > 0)
        const socialData = @json($socialPlatformStats);
        const socialCtx = document.getElementById('socialPlatformChart').getContext('2d');
        new Chart(socialCtx, {
            type: 'bar',
            data: {
                labels: socialData.map(item => item.platform ? item.platform.charAt(0).toUpperCase() + item.platform.slice(1) : 'Direct'),
                datasets: [{
                    label: 'Shares',
                    data: socialData.map(item => item.count),
                    backgroundColor: [
                        '#1877f2', // Facebook
                        '#1da1f2', // Twitter
                        '#25d366', // WhatsApp
                        '#0077b5', // LinkedIn
                        '#e1306c', // Instagram
                        '#ea4335'  // Email
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
        @endif

        // Sidebar Toggle Function
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const topNavbar = document.querySelector('.top-navbar');
            
            sidebar.classList.toggle('open');
            
            if (window.innerWidth <= 1024) {
                if (sidebar.classList.contains('open')) {
                    mainContent.style.marginLeft = '280px';
                    topNavbar.style.left = '280px';
                } else {
                    mainContent.style.marginLeft = '0';
                    topNavbar.style.left = '0';
                }
            }
        }

        // Auto-collapse sidebar on mobile
        function checkScreenSize() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const topNavbar = document.querySelector('.top-navbar');
            
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('open');
                mainContent.style.marginLeft = '0';
                topNavbar.style.left = '0';
            } else {
                sidebar.classList.add('open');
                mainContent.style.marginLeft = '280px';
                topNavbar.style.left = '280px';
            }
        }

        // Loading animation for stats
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-value');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.ceil(current).toLocaleString();
                    }
                }, 40);
            });
        }

        // Handle click events for detailed views
        function showMonthlyArticles(month, year) {
            openModal(`Artikel Bulan ${getMonthName(month)} ${year}`, 'calendar-alt');
            
            // API call to get monthly articles
            fetch(`/admin/analytics/api/monthly/${year}/${month}`)
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('modalBody');
                    if (data.success && data.data.length > 0) {
                        modalBody.innerHTML = `
                            <div style="margin-bottom: 1rem; text-align: center; color: #6b7280;">
                                <i class="fas fa-info-circle"></i>
                                Total: ${data.total} artikel di ${data.month_name}
                            </div>
                            <div class="modal-article-grid">
                                ${data.data.map(article => `
                                    <div class="modal-article-card">
                                        <div class="modal-article-title">${article.title}</div>
                                        <div class="modal-article-meta">
                                            <span><i class="fas fa-calendar"></i> ${article.created_at}</span>
                                            <span><i class="fas fa-user"></i> ${article.author}</span>
                                            <span><i class="fas fa-tag"></i> ${article.category}</span>
                                        </div>
                                        <div class="modal-article-stats">
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-eye"></i> ${numberFormat(article.views)} views
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-comments"></i> ${numberFormat(article.comments_count)} comments
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-heart"></i> ${numberFormat(article.likes)} likes
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-share-alt"></i> ${numberFormat(article.shares)} shares
                                            </span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    } else {
                        modalBody.innerHTML = `
                            <div class="no-data">
                                <i class="fas fa-calendar-times"></i>
                                <p>Tidak ada artikel di bulan ini</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML = `
                        <div class="no-data">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        function showCategoryArticles(categoryName) {
            openModal(`Artikel Kategori: ${categoryName}`, 'tags');
            
            // API call to get category articles
            fetch(`/admin/analytics/api/category/${encodeURIComponent(categoryName)}`)
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('modalBody');
                    if (data.success && data.data.length > 0) {
                        modalBody.innerHTML = `
                            <div style="margin-bottom: 1rem; text-align: center; color: #6b7280;">
                                <i class="fas fa-info-circle"></i>
                                Total: ${data.total} artikel dalam kategori "${data.category}"
                            </div>
                            <div class="modal-article-grid">
                                ${data.data.map(article => `
                                    <div class="modal-article-card">
                                        <div class="modal-article-title">${article.title}</div>
                                        <div class="modal-article-meta">
                                            <span><i class="fas fa-calendar"></i> ${article.created_at}</span>
                                            <span><i class="fas fa-user"></i> ${article.author}</span>
                                        </div>
                                        <div class="modal-article-stats">
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-eye"></i> ${numberFormat(article.views)} views
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-comments"></i> ${numberFormat(article.comments_count)} comments
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-heart"></i> ${numberFormat(article.likes)} likes
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-share-alt"></i> ${numberFormat(article.shares)} shares
                                            </span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    } else {
                        modalBody.innerHTML = `
                            <div class="no-data">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada artikel dalam kategori ini</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML = `
                        <div class="no-data">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        function showTopViewedDetails() {
            openModal('Semua Artikel Terbanyak Dilihat', 'trophy');
            
            // API call to get top viewed articles
            fetch('/admin/analytics/api/top-viewed')
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('modalBody');
                    if (data.success && data.data.length > 0) {
                        modalBody.innerHTML = `
                            <div style="margin-bottom: 1rem; text-align: center; color: #6b7280;">
                                <i class="fas fa-info-circle"></i>
                                Top ${data.total} artikel dengan views tertinggi
                            </div>
                            <div class="modal-article-grid">
                                ${data.data.map(article => `
                                    <div class="modal-article-card">
                                        <div class="modal-article-title">
                                            <span style="color: #2563eb; font-weight: bold;">#${article.rank}</span>
                                            ${article.title}
                                        </div>
                                        <div class="modal-article-meta">
                                            <span><i class="fas fa-calendar"></i> ${article.created_at}</span>
                                            <span><i class="fas fa-user"></i> ${article.author}</span>
                                            <span><i class="fas fa-tag"></i> ${article.category}</span>
                                        </div>
                                        <div class="modal-article-stats">
                                            <span class="modal-stat-badge" style="background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white;">
                                                <i class="fas fa-trophy"></i> Rank #${article.rank}
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-eye"></i> ${numberFormat(article.views)} views
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-comments"></i> ${numberFormat(article.comments_count)} comments
                                            </span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    } else {
                        modalBody.innerHTML = `
                            <div class="no-data">
                                <i class="fas fa-chart-line"></i>
                                <p>Belum ada data artikel</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML = `
                        <div class="no-data">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        function showEngagementDetails() {
            openModal('Detail Engagement Artikel', 'fire');
            
            // API call to get engagement details
            fetch('/admin/analytics/api/engagement')
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('modalBody');
                    if (data.success && data.data.length > 0) {
                        modalBody.innerHTML = `
                            <div style="margin-bottom: 1rem; text-align: center; color: #6b7280;">
                                <i class="fas fa-info-circle"></i>
                                Top ${data.total} artikel dengan engagement tertinggi
                            </div>
                            <div class="modal-article-grid">
                                ${data.data.map(article => `
                                    <div class="modal-article-card">
                                        <div class="modal-article-title">
                                            <span style="color: #ef4444; font-weight: bold;">#${article.rank}</span>
                                            ${article.title}
                                        </div>
                                        <div class="modal-article-meta">
                                            <span><i class="fas fa-calendar"></i> ${article.created_at}</span>
                                            <span><i class="fas fa-user"></i> ${article.author}</span>
                                            <span><i class="fas fa-tag"></i> ${article.category}</span>
                                        </div>
                                        <div class="modal-article-stats">
                                            <span class="modal-stat-badge" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white;">
                                                <i class="fas fa-fire"></i> Rank #${article.rank}
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-chart-line"></i> ${numberFormat(article.total_engagement)} total
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-eye"></i> ${numberFormat(article.views)}
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-comments"></i> ${numberFormat(article.comments_count)}
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-heart"></i> ${numberFormat(article.likes)}
                                            </span>
                                            <span class="modal-stat-badge">
                                                <i class="fas fa-share-alt"></i> ${numberFormat(article.shares)}
                                            </span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    } else {
                        modalBody.innerHTML = `
                            <div class="no-data">
                                <i class="fas fa-fire"></i>
                                <p>Belum ada data engagement</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML = `
                        <div class="no-data">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        // Modal functions
        function openModal(title, icon = 'chart-line') {
            const modal = document.getElementById('analyticsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            
            modalTitle.innerHTML = `<i class="fas fa-${icon}"></i> ${title}`;
            modalBody.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner"></div>
                </div>
            `;
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('analyticsModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function getMonthName(monthNumber) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            return months[parseInt(monthNumber) - 1] || monthNumber;
        }

        function numberFormat(num) {
            return parseInt(num).toLocaleString('id-ID');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('analyticsModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Add click handlers to stat items
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers for monthly stats
            const monthlyStats = document.querySelectorAll('.monthly-stat-item');
            monthlyStats.forEach(item => {
                item.style.cursor = 'pointer';
                item.addEventListener('click', function() {
                    const month = this.dataset.month;
                    const year = this.dataset.year;
                    if (month && year) {
                        showMonthlyArticles(month, year);
                    }
                });
            });

            // Add hover effect for clickable items
            const clickableItems = document.querySelectorAll('.stat-item[data-clickable="true"]');
            clickableItems.forEach(item => {
                item.style.cursor = 'pointer';
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px) scale(1.02)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-4px) scale(1)';
                });
            });

            checkScreenSize();
            setTimeout(animateCounters, 500);
        });
        
        window.addEventListener('resize', checkScreenSize);
    </script>
</body>
</html>
