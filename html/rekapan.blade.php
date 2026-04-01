<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <title>📊 Dashboard Guru - Sistem Pernapasan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #7209b7;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #f94144;
        }
        
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--accent));
        }
        
        .score-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .data-table th {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }
        
        .data-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s ease;
        }
        
        .data-table tr:hover td {
            background: linear-gradient(90deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02));
        }
        
        .score-progress {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
            position: relative;
        }
        
        .score-progress-bar {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.05);
        }
        
        .ranking-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
        }
        
        .ranking-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #7c2d12;
        }
        
        .ranking-2 {
            background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
            color: #374151;
        }
        
        .ranking-3 {
            background: linear-gradient(135deg, #CD7F32, #A0522D);
            color: white;
        }
        
        .ranking-other {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            color: #4b5563;
        }
        
        .quiz-card {
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }
        
        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-color: #3B82F6;
        }
        
        .tab-button {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: transparent;
            border: none;
        }
        
        .tab-button.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out; }
        
        .chart-bar:hover {
            cursor: pointer;
            opacity: 0.8;
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .kkm-slider {
            width: 100%;
            height: 10px;
            border-radius: 5px;
            background: #e5e7eb;
            outline: none;
            -webkit-appearance: none;
        }
        
        .kkm-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        
        .notification.success { background: linear-gradient(135deg, #10b981, #059669); }
        .notification.error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .notification.info { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .lembar-kerja-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .lembar-kerja-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
        }
        
        .kkm-slider-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 20px;
            color: white;
        }
        
        .kkm-value-display {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            background: white;
            color: #3b82f6;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        .kkm-impact {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 0.875rem;
        }
        
        .kkm-impact-item {
            text-align: center;
        }
        
        .kkm-impact-value {
            font-weight: bold;
            font-size: 1.25rem;
        }
        
        .stored-lembar-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .stored-lembar-item {
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }
        
        .stored-lembar-item:hover {
            background-color: #f8fafc;
            transform: translateX(5px);
        }
        
        .materi-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        
        /* ========== RESPONSIVE MOBILE TAMBAHAN ========== */
        
        /* Mobile Menu Button */
        .mobile-menu-toggle {
            display: none;
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 10px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .mobile-menu-toggle:active {
            background: rgba(255,255,255,0.3);
        }
        
        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
            backdrop-filter: blur(2px);
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Mobile Styles - iPhone & All Mobile */
        @media (max-width: 768px) {
            /* Sidebar */
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                transition: left 0.3s ease-in-out;
                z-index: 1000;
                overflow-y: auto;
                width: 280px !important;
            }
            
            .sidebar.open {
                left: 0;
            }
            
            .md\:ml-64, .lg\:ml-72 {
                margin-left: 0 !important;
            }
            
            .mobile-menu-toggle {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
            
            /* Tabs scroll horizontal */
            .bg-white.border-b.border-gray-200.px-6.pt-4 {
                overflow-x: auto;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .tab-button {
                display: inline-flex;
                white-space: nowrap;
                padding: 0.5rem 1rem;
                font-size: 0.7rem;
            }
            
            /* Dashboard header */
            .dashboard-header {
                padding: 1rem !important;
            }
            
            .dashboard-header .flex.flex-col.lg\:flex-row {
                flex-direction: column;
                gap: 1rem;
            }
            
            .dashboard-header .flex.gap-3 {
                width: 100%;
            }
            
            .dashboard-header button {
                flex: 1;
                text-align: center;
                padding: 0.5rem;
                font-size: 0.7rem;
            }
            
            /* Stat cards */
            .stat-card {
                padding: 1rem;
            }
            
            .stat-card .text-2xl {
                font-size: 1.1rem;
            }
            
            /* Charts */
            .grid-cols-1.lg\:grid-cols-2 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            [style*="height: 300px"] {
                height: 220px !important;
            }
            
            /* Table */
            .overflow-x-auto {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .data-table {
                min-width: 550px;
            }
            
            .data-table th,
            .data-table td {
                padding: 0.6rem 0.5rem;
                font-size: 0.7rem;
            }
            
            /* KKM Slider */
            .kkm-slider-container {
                padding: 1rem;
            }
            
            .kkm-value-display {
                width: 55px;
                height: 55px;
                font-size: 1.25rem;
            }
            
            .kkm-impact {
                font-size: 0.7rem;
            }
            
            .kkm-impact-value {
                font-size: 0.9rem;
            }
            
            /* Quiz cards */
            .grid.grid-cols-1.sm\:grid-cols-2.lg\:grid-cols-3.xl\:grid-cols-4 {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .quiz-card {
                padding: 0.75rem;
            }
            
            .quiz-card .text-4xl {
                font-size: 1.5rem;
            }
            
            .quiz-card h3 {
                font-size: 0.8rem;
            }
            
            .quiz-card .text-sm {
                font-size: 0.65rem;
            }
            
            /* Filter section */
            .flex.flex-col.md\:flex-row.justify-between {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            #searchInput, #filterKelas {
                width: 100%;
                font-size: 14px !important;
            }
            
            .flex.items-center.gap-4 {
                flex-wrap: wrap;
                width: 100%;
            }
            
            /* Modal */
            .max-w-4xl {
                max-width: 95%;
                margin: 1rem;
            }
            
            /* Text sizes */
            .text-3xl {
                font-size: 1.25rem !important;
            }
            
            .text-2xl {
                font-size: 1.125rem !important;
            }
            
            .text-xl {
                font-size: 1rem !important;
            }
            
            .text-lg {
                font-size: 0.9rem !important;
            }
            
            .text-base {
                font-size: 0.85rem !important;
            }
            
            .text-sm {
                font-size: 0.75rem !important;
            }
            
            .text-xs {
                font-size: 0.65rem !important;
            }
            
            /* Padding margins */
            .p-6 {
                padding: 1rem !important;
            }
            
            .p-8 {
                padding: 1rem !important;
            }
            
            .px-6 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .mb-8 {
                margin-bottom: 1rem !important;
            }
            
            .gap-6 {
                gap: 1rem !important;
            }
            
            /* Wrapper cards */
            .bg-white.rounded-2xl {
                border-radius: 1rem !important;
            }
            
            /* Score progress */
            .w-20 {
                width: 50px !important;
            }
            
            /* Notification */
            .notification {
                top: 70px;
                right: 10px;
                left: 10px;
                max-width: none;
                text-align: center;
                font-size: 0.8rem;
            }
        }
        
        /* iPhone SE, 5, 6, 7, 8 (375px ke bawah) */
        @media (max-width: 390px) {
            .sidebar {
                width: 85% !important;
                max-width: 280px;
            }
            
            .tab-button {
                padding: 0.4rem 0.7rem;
                font-size: 0.65rem;
            }
            
            .dashboard-header h1 {
                font-size: 1rem !important;
            }
            
            .quiz-card .text-4xl {
                font-size: 1.25rem;
            }
            
            .quiz-card h3 {
                font-size: 0.7rem;
            }
            
            .data-table th,
            .data-table td {
                padding: 0.5rem 0.35rem;
                font-size: 0.65rem;
            }
            
            .kkm-value-display {
                width: 50px;
                height: 50px;
                font-size: 1.1rem;
            }
            
            .w-16.h-16 {
                width: 3rem !important;
                height: 3rem !important;
            }
            
            .w-16.h-16 i {
                font-size: 1.25rem !important;
            }
            
            .gap-4 {
                gap: 0.75rem !important;
            }
        }
        
        /* iPhone 12, 13, 14, 15 (390px - 430px) */
        @media (min-width: 391px) and (max-width: 430px) {
            .tab-button {
                padding: 0.5rem 0.85rem;
                font-size: 0.7rem;
            }
            
            .data-table th,
            .data-table td {
                padding: 0.6rem 0.45rem;
                font-size: 0.7rem;
            }
        }
        
        /* iPad & Tablet (768px - 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar {
                width: 240px !important;
            }
            
            .md\:ml-64 {
                margin-left: 240px !important;
            }
            
            .grid-cols-1.lg\:grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quiz-card {
                padding: 1rem;
            }
            
            .quiz-card .text-4xl {
                font-size: 2rem;
            }
        }
        
        /* Desktop tetap normal */
        @media (min-width: 1025px) {
            .sidebar-overlay {
                display: none !important;
            }
        }
        
        /* Touch-friendly untuk mobile */
        @media (hover: none) and (pointer: coarse) {
            button, 
            .quiz-card, 
            .student-row, 
            .tab-button,
            a {
                cursor: pointer;
                touch-action: manipulation;
            }
            
            button:active,
            .quiz-card:active,
            .student-row:active {
                opacity: 0.7;
                transition: opacity 0.1s;
            }
        }
        
        /* Prevent zoom on input focus for iOS */
        input, select, textarea {
            font-size: 16px !important;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Better touch scrolling */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        
        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Sidebar Overlay untuk mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>
    
    <!-- SIDEBAR GURU -->
    <div class="sidebar w-64 md:w-72 bg-gradient-to-b from-blue-600 to-blue-800 text-white shadow-2xl fixed h-full z-40" id="sidebar">
        <div class="p-6 border-b border-white/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">👨‍🏫</span>
                    </div>
                    <div>
                        <h2 class="font-poppins font-bold text-lg">Panel Guru</h2>
                        <p class="text-sm text-white/80">
                            @if(session()->has('guru_aktif'))
                                {{ session('guru_aktif.nama') }}
                            @endif
                        </p>
                    </div>
                </div>
                <!-- Tombol close sidebar di mobile -->
                <button class="md:hidden text-white/80 hover:text-white" onclick="closeMobileSidebar()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-4 space-y-1">
            <a href="/Home" class="sidebar-item flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10">
                <i class="fas fa-home w-5 h-5"></i>
                <span>Beranda</span>
            </a>
            <a href="/cptp" class="sidebar-item flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10">
                <i class="fas fa-file-alt w-5 h-5"></i>
                <span>Kurikulum</span>
            </a>
            <a href="/materiopening" class="sidebar-item flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10">
                <i class="fas fa-book w-5 h-5"></i>
                <span>Materi</span>
            </a>
            <a href="/admin/questions" class="sidebar-item flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10">
                <i class="fas fa-clipboard-check w-5 h-5"></i>
                <span>Evaluasi</span>
            </a>
            <a href="/guru/rekapan" class="sidebar-item flex items-center space-x-3 p-3 rounded-lg bg-white/10">
                <i class="fas fa-chart-bar w-5 h-5"></i>
                <span>Rekapan Nilai</span>
            </a>
            <a href="/guru/data-siswa" class="sidebar-item flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10">
                <i class="fas fa-users w-5 h-5"></i>
                <span>Data Siswa</span>
            </a>
            <a href="/profile" class="sidebar-item flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10">
                <i class="fas fa-user w-5 h-5"></i>
                <span>Profil</span>
            </a>
        </div>

        <div class="p-4 border-t border-white/20 mt-4">
            <a href="{{ route('guru.logout') }}" 
               class="flex items-center space-x-3 p-3 bg-white/10 hover:bg-white/20 rounded-lg transition-all">
                <i class="fas fa-sign-out-alt w-5 h-5"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="md:ml-64 lg:ml-72 transition-all duration-300" id="mainContent">
        <!-- Top Bar -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 shadow-lg sticky top-0 z-20">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <!-- Tombol menu mobile -->
                    <button class="mobile-menu-toggle" onclick="toggleMobileSidebar()">
                        <i class="fas fa-bars text-white text-lg"></i>
                    </button>
                    <div>
                        <h1 class="font-poppins text-xl font-bold">📊 Dashboard Guru - Rekapan Nilai</h1>
                        <p class="text-sm text-white/90">Sistem Pernapasan Manusia - Kelas VIII</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden md:block">
                        <p class="font-semibold">
                            @if(session()->has('guru_aktif'))
                                {{ session('guru_aktif.nama') }}
                            @endif
                        </p>
                        <p class="text-sm text-white/80">Guru</p>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation - Scrollable on mobile -->
        <div class="bg-white border-b border-gray-200 px-6 pt-4 sticky top-[57px] md:top-[65px] z-10" style="overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch;">
            <div class="flex space-x-2" style="min-width: min-content;">
                <button id="tabDashboard" class="tab-button active" onclick="showTab('dashboard')">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Kelas
                </button>
                <button id="tabQuiz" class="tab-button" onclick="showTab('quiz')">
                    <i class="fas fa-list-check mr-2"></i>Rekapan per Quiz
                </button>
                <button id="tabExport" class="tab-button" onclick="showTab('export')">
                    <i class="fas fa-file-export mr-2"></i>Ekspor Data
                </button>
                <button id="tabLembarKerja" class="tab-button" onclick="showTab('lembarKerja')">
                    <i class="fas fa-file-alt mr-2"></i>Lembar Kerja
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <!-- Dashboard Tab -->
            <div id="dashboardContent" class="tab-content animate-fadeInUp">
                @php
                    use App\Models\Siswa;
                    use App\Models\QuizHidungScore;
                    use App\Models\QuizFaringScore;
                    use App\Models\QuizLaringScore;
                    use App\Models\QuizTrakeaScore;
                    use App\Models\QuizBronkusScore;
                    use App\Models\QuizBronkiolusScore;
                    use App\Models\QuizAlveolusScore;
                    use App\Models\QuizMekanismeScore;
                    use App\Models\QuizFrekuensiScore;
                    use App\Models\QuizVolumeScore;
                    use App\Models\QuizGangguanScore;
                    use App\Models\QuizAttempt;
                    
                    $allSiswas = Siswa::all();
                    
                    $bobot = [
                        'hidung' => 7, 'faring' => 7, 'laring' => 7, 'trakea' => 7,
                        'bronkus' => 7, 'bronkiolus' => 7, 'alveolus' => 7, 'mekanisme' => 7,
                        'frekuensi' => 7, 'volume' => 7, 'gangguan' => 10, 'evaluasi' => 20
                    ];
                    
                    $totalBobot = array_sum($bobot);
                    $siswasWithScores = [];
                    
                    // Default KKM
                    $defaultKKM = 70;
                    
                    foreach ($allSiswas as $siswa) {
                        $siswaId = $siswa->id;
                        $siswaNama = $siswa->nama;
                        
                        $hidungScore = QuizHidungScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $faringScore = QuizFaringScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $laringScore = QuizLaringScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $trakeaScore = QuizTrakeaScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $bronkusScore = QuizBronkusScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $bronkiolusScore = QuizBronkiolusScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $alveolusScore = QuizAlveolusScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $mekanismeScore = QuizMekanismeScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $frekuensiScore = QuizFrekuensiScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $volumeScore = QuizVolumeScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $gangguanScore = QuizGangguanScore::where('siswa_id', $siswaId)->orderBy('completed_at', 'desc')->first();
                        $evaluasiScore = QuizAttempt::where('student_name', $siswaNama)->latest()->first();
                        
                        $hidungPercentage = $hidungScore ? round(($hidungScore->score / $hidungScore->total_questions) * 100) : 0;
                        $faringPercentage = $faringScore ? round(($faringScore->score / $faringScore->total_questions) * 100) : 0;
                        $laringPercentage = $laringScore ? round(($laringScore->score / $laringScore->total_questions) * 100) : 0;
                        $trakeaPercentage = $trakeaScore ? round(($trakeaScore->score / $trakeaScore->total_questions) * 100) : 0;
                        $bronkusPercentage = $bronkusScore ? round(($bronkusScore->score / $bronkusScore->total_questions) * 100) : 0;
                        $bronkiolusPercentage = $bronkiolusScore ? round(($bronkiolusScore->score / $bronkiolusScore->total_questions) * 100) : 0;
                        $alveolusPercentage = $alveolusScore ? round(($alveolusScore->score / $alveolusScore->total_questions) * 100) : 0;
                        $mekanismePercentage = $mekanismeScore ? round(($mekanismeScore->score / $mekanismeScore->total_questions) * 100) : 0;
                        $frekuensiPercentage = $frekuensiScore ? round(($frekuensiScore->score / $frekuensiScore->total_questions) * 100) : 0;
                        $volumePercentage = $volumeScore ? round(($volumeScore->score / $volumeScore->total_questions) * 100) : 0;
                        $gangguanPercentage = $gangguanScore ? round(($gangguanScore->score / $gangguanScore->total_questions) * 100) : 0;
                        $evaluasiPercentage = 0;
                        
                        if ($evaluasiScore) {
                            $evaluasiTotalQuestions = 20;
                            $evaluasiCorrectAnswers = $evaluasiScore->score;
                            $evaluasiPercentage = $evaluasiTotalQuestions > 0 ? round(($evaluasiCorrectAnswers / $evaluasiTotalQuestions) * 100) : 0;
                        }
                        
                        $nilaiAkhir = (
                            ($hidungPercentage * $bobot['hidung']) +
                            ($faringPercentage * $bobot['faring']) +
                            ($laringPercentage * $bobot['laring']) +
                            ($trakeaPercentage * $bobot['trakea']) +
                            ($bronkusPercentage * $bobot['bronkus']) +
                            ($bronkiolusPercentage * $bobot['bronkiolus']) +
                            ($alveolusPercentage * $bobot['alveolus']) +
                            ($mekanismePercentage * $bobot['mekanisme']) +
                            ($frekuensiPercentage * $bobot['frekuensi']) +
                            ($volumePercentage * $bobot['volume']) +
                            ($gangguanPercentage * $bobot['gangguan']) +
                            ($evaluasiPercentage * $bobot['evaluasi'])
                        ) / $totalBobot;
                        
                        $nilaiAkhir = round($nilaiAkhir, 2);
                        
                        $completed = 0;
                        $passed = 0;
                        $failed = 0;
                        $totalKuis = 12;
                        
                        $allPercentages = [
                            $hidungPercentage, $faringPercentage, $laringPercentage, $trakeaPercentage,
                            $bronkusPercentage, $bronkiolusPercentage, $alveolusPercentage, $mekanismePercentage,
                            $frekuensiPercentage, $volumePercentage, $gangguanPercentage, $evaluasiPercentage
                        ];
                        
                        // Hitung bobot tercapai (jumlah bobot dari materi yang lulus)
                        $bobotTercapai = 0;
                        $materiKeys = ['hidung', 'faring', 'laring', 'trakea', 'bronkus', 'bronkiolus', 'alveolus', 'mekanisme', 'frekuensi', 'volume', 'gangguan', 'evaluasi'];
                        $nilaiMateri = [
                            $hidungPercentage, $faringPercentage, $laringPercentage, $trakeaPercentage,
                            $bronkusPercentage, $bronkiolusPercentage, $alveolusPercentage, $mekanismePercentage,
                            $frekuensiPercentage, $volumePercentage, $gangguanPercentage, $evaluasiPercentage
                        ];
                        for ($i = 0; $i < count($materiKeys); $i++) {
                            if ($nilaiMateri[$i] >= $defaultKKM) {
                                $bobotTercapai += $bobot[$materiKeys[$i]];
                            }
                        }
                        
                        foreach ($allPercentages as $percentage) {
                            if ($percentage > 0) {
                                $completed++;
                                if ($percentage >= $defaultKKM) {
                                    $passed++;
                                } else {
                                    $failed++;
                                }
                            }
                        }
                        
                        $notCompleted = $totalKuis - $completed;
                        $completionRate = $totalKuis > 0 ? round(($completed / $totalKuis) * 100, 2) : 0;
                        
                        // Hitung rata-rata tingkat kesulitan untuk siswa ini
                        $totalKesulitan = 0;
                        $countKesulitan = 0;
                        foreach ($allPercentages as $percentage) {
                            if ($percentage > 0) {
                                $totalKesulitan += (100 - $percentage);
                                $countKesulitan++;
                            }
                        }
                        $rataKesulitan = $countKesulitan > 0 ? round($totalKesulitan / $countKesulitan, 2) : 0;
                        
                        // Hitung jumlah bobot nilai (total bobot dari materi yang sudah dikerjakan)
                        $jumlahBobotNilai = 0;
                        $nilaiMateri = [
                            $hidungPercentage, $faringPercentage, $laringPercentage, $trakeaPercentage,
                            $bronkusPercentage, $bronkiolusPercentage, $alveolusPercentage, $mekanismePercentage,
                            $frekuensiPercentage, $volumePercentage, $gangguanPercentage, $evaluasiPercentage
                        ];
                        for ($i = 0; $i < count($materiKeys); $i++) {
                            if ($nilaiMateri[$i] > 0) {
                                $jumlahBobotNilai += $bobot[$materiKeys[$i]];
                            }
                        }
                        
                        $detail = [
                            'hidung' => $hidungPercentage,
                            'faring' => $faringPercentage,
                            'laring' => $laringPercentage,
                            'trakea' => $trakeaPercentage,
                            'bronkus' => $bronkusPercentage,
                            'bronkiolus' => $bronkiolusPercentage,
                            'alveolus' => $alveolusPercentage,
                            'mekanisme' => $mekanismePercentage,
                            'frekuensi' => $frekuensiPercentage,
                            'volume' => $volumePercentage,
                            'gangguan' => $gangguanPercentage,
                            'evaluasi' => $evaluasiPercentage
                        ];
                        
                        $siswasWithScores[] = [
                            'id' => $siswa->id,
                            'nama' => $siswa->nama,
                            'nisn' => $siswa->nisn,
                            'kelas' => $siswa->kelas,
                            'asal_sekolah' => $siswa->asal_sekolah,
                            'password' => $siswa->password,
                            'completed' => $completed,
                            'passed' => $passed,
                            'failed' => $failed,
                            'not_completed' => $notCompleted,
                            'nilai_akhir' => $nilaiAkhir,
                            'completion_rate' => $completionRate,
                            'total_kuis' => $totalKuis,
                            'rata_kesulitan' => $rataKesulitan,
                            'jumlah_bobot_nilai' => $jumlahBobotNilai,
                            'detail' => $detail,
                            'bobot' => $bobot,
                            'bobot_tercapai' => $bobotTercapai
                        ];
                    }
                    
                    // Urutkan berdasarkan nilai akhir
                    usort($siswasWithScores, function($a, $b) {
                        return $b['nilai_akhir'] <=> $a['nilai_akhir'];
                    });
                    
                    $rank = 1;
                    foreach ($siswasWithScores as &$siswa) {
                        $siswa['rank'] = $rank++;
                    }
                    
                    $totalSiswa = count($siswasWithScores);
                    $totalCompleted = array_sum(array_column($siswasWithScores, 'completed'));
                    $totalPassed = array_sum(array_column($siswasWithScores, 'passed'));
                    $totalFailed = array_sum(array_column($siswasWithScores, 'failed'));
                    $totalNilaiAkhir = $totalSiswa > 0 ? round(array_sum(array_column($siswasWithScores, 'nilai_akhir')) / $totalSiswa, 2) : 0;
                    
                    $rataKesulitanKelas = 0;
                    $countKesulitanKelas = 0;
                    foreach ($siswasWithScores as $siswa) {
                        if ($siswa['rata_kesulitan'] > 0) {
                            $rataKesulitanKelas += $siswa['rata_kesulitan'];
                            $countKesulitanKelas++;
                        }
                    }
                    $rataKesulitanKelas = $countKesulitanKelas > 0 ? round($rataKesulitanKelas / $countKesulitanKelas, 2) : 0;
                    
                    $totalCompletionRate = 0;
                    foreach ($siswasWithScores as $siswa) {
                        $totalCompletionRate += $siswa['completion_rate'];
                    }
                    $totalCompletionRate = $totalSiswa > 0 ? round($totalCompletionRate / $totalSiswa, 2) : 0;
                    
                    // Data untuk chart
                    $materiLabels = ['Hidung', 'Faring', 'Laring', 'Trakea', 'Bronkus', 'Bronkiolus', 
                                    'Alveolus', 'Mekanisme', 'Frekuensi', 'Volume', 'Gangguan', 'Evaluasi'];
                    
                    $bobotMateri = [7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 10, 20];
                    
                    $materiKesulitanBase = [];
                    $materiRemedialListBase = [];
                    
                    foreach ($materiLabels as $materi) {
                        $key = strtolower(str_replace(' ', '_', $materi));
                        $totalSiswaMengerjakan = 0;
                        $totalSiswaDiBawahKKM = 0;
                        $siswaRemedial = [];
                        
                        foreach ($siswasWithScores as $siswa) {
                            $nilai = $siswa['detail'][$key] ?? 0;
                            if ($nilai > 0) {
                                $totalSiswaMengerjakan++;
                                if ($nilai < $defaultKKM) {
                                    $totalSiswaDiBawahKKM++;
                                    $siswaRemedial[] = [
                                        'nama' => $siswa['nama'],
                                        'nilai' => $nilai,
                                        'kelas' => $siswa['kelas'],
                                        'nisn' => $siswa['nisn']
                                    ];
                                }
                            }
                        }
                        
                        $persenKesulitan = $totalSiswaMengerjakan > 0 ? 
                            round(($totalSiswaDiBawahKKM / $totalSiswaMengerjakan) * 100) : 0;
                        
                        $materiKesulitanBase[$materi] = $persenKesulitan;
                        $materiRemedialListBase[$materi] = $siswaRemedial;
                    }
                    
                    $materiCompletion = [];
                    $materiSiswaList = [];
                    $materiSudahCount = [];
                    $materiSiswaSudah = [];
                    $materiSiswaBelum = [];
                    
                    foreach ($materiLabels as $materi) {
                        $key = strtolower(str_replace(' ', '_', $materi));
                        $totalSiswaMengerjakan = 0;
                        $siswaList = [];
                        $siswaSudah = [];
                        
                        foreach ($siswasWithScores as $siswa) {
                            $nilai = $siswa['detail'][$key] ?? 0;
                            if ($nilai > 0) {
                                $totalSiswaMengerjakan++;
                                $siswaList[] = [
                                    'nama' => $siswa['nama'],
                                    'nilai' => $nilai
                                ];
                                $siswaSudah[] = $siswa['nama'];
                            }
                        }
                        
                        $materiCompletion[$materi] = $totalSiswaMengerjakan;
                        $materiSiswaList[$materi] = $siswaList;
                        $materiSudahCount[$materi] = $totalSiswaMengerjakan;
                        $materiSiswaSudah[$materi] = $siswaSudah;
                        
                        $semuaNama = array_column($siswasWithScores, 'nama');
                        $materiSiswaBelum[$materi] = array_values(array_diff($semuaNama, $siswaSudah));
                    }
                    
                    $kelasList = array_unique(array_column($siswasWithScores, 'kelas'));
                    
                    $storedLembarKerja = [
                        [
                            'id' => 1,
                            'siswa_id' => $siswasWithScores[0]['id'] ?? 1,
                            'siswa_nama' => $siswasWithScores[0]['nama'] ?? 'Contoh Siswa',
                            'tanggal' => date('Y-m-d'),
                            'rekomendasi' => 'Perlu meningkatkan pemahaman pada materi alveolus',
                            'status' => 'Tuntas'
                        ]
                    ];
                @endphp

                <!-- Header Stats -->
                <div class="dashboard-header rounded-2xl p-8 mb-8">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center border-2 border-white/30">
                                    <i class="fas fa-chalkboard-teacher text-2xl text-white"></i>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-white mb-2">Dashboard Kelas</h1>
                                    <p class="text-white/90">Sistem Pernapasan Manusia - Monitoring Kelas</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <div class="kkm-slider-container">
                                <h3 class="text-lg font-bold text-white mb-3">📏 Pengaturan KKM Kelas</h3>
                                <div class="kkm-value-display" id="kkmDisplay">70</div>
                                <input type="range" min="0" max="100" value="70" class="kkm-slider" id="kkmSlider" 
                                       oninput="updateKKMDisplay(this.value)" onchange="applyNewKKM(this.value)">
                                <div class="kkm-impact">
                                    <div class="kkm-impact-item">
                                        <div class="kkm-impact-value" id="tuntasCount">
                                            {{ count(array_filter($siswasWithScores, function($s) use ($defaultKKM) { return $s['nilai_akhir'] >= $defaultKKM; })) }}
                                        </div>
                                        <div>Tuntas</div>
                                    </div>
                                    <div class="kkm-impact-item">
                                        <div class="kkm-impact-value" id="remedialCount">
                                            {{ count(array_filter($siswasWithScores, function($s) use ($defaultKKM) { return $s['nilai_akhir'] < $defaultKKM && $s['nilai_akhir'] > 0; })) }}
                                        </div>
                                        <div>Remedial</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <button onclick="printReport()" class="px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition">
                                    <i class="fas fa-print mr-2"></i>Cetak Laporan
                                </button>
                                <button onclick="exportToExcel()" class="px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-800 rounded-lg font-medium transition">
                                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Tingkat Kesulitan (berdasarkan KKM) -->
                    <div>
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-full">
                            <h3 class="text-lg font-bold text-gray-800 mb-6">
                                <i class="fas fa-chart-line text-red-600 mr-2"></i>
                                Tingkat Kesulitan Materi (KKM)
                            </h3>
                            <div style="height: 300px;">
                                <canvas id="kesulitanChart"></canvas>
                            </div>
                            <p class="text-sm text-gray-500 mt-4">*Jumlah siswa dengan nilai di bawah KKM <span class="font-bold kkm-value-display-small">(70)</span></p>
                        </div>
                    </div>

                    <!-- Distribusi Pengerjaan -->
                    <div>
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-full">
                            <h3 class="text-lg font-bold text-gray-800 mb-6">
                                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                Jumlah Siswa Yang Sudah Mengerjakan Kuis Tiap Materi
                            </h3>
                            <div style="height: 300px;">
                                <canvas id="pengerjaanChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Diagram Lingkaran Dinamis - Pengerjaan per Materi -->
                    <div>
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-full">
                            <h3 class="text-lg font-bold text-gray-800 mb-6">
                                <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                                <select id="materiPieSelect" class="ml-2 p-1 border rounded text-sm">
                                    @foreach($materiLabels as $index => $materi)
                                        <option value="{{ $materi }}" {{ $index == 0 ? 'selected' : '' }}>
                                            {{ $materi }} (Bobot {{ $bobotMateri[$index] }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </h3>
                            <div style="height: 300px;">
                                <canvas id="dynamicPieChart"></canvas>
                            </div>
                            <p class="text-sm text-gray-500 mt-4">Total siswa: {{ $totalSiswa }} orang | Klik diagram untuk melihat daftar</p>
                        </div>
                    </div>
                </div>

                <!-- Student List Table (dengan kolom Bobot Tercapai) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">
                                    <i class="fas fa-list text-blue-600 mr-2"></i>
                                    Daftar Bobot Tercapai per Siswa
                                </h3>
                                <p class="text-gray-600 text-sm">Total {{ $totalSiswa }} siswa</p>
                            </div>
                            <div class="flex items-center gap-4 flex-wrap">
                                <div class="relative">
                                    <input type="text" id="searchInput" placeholder="Cari siswa..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <select id="filterKelas" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="data-table" id="studentsTable">
                            <thead>
                                                                    <th class="text-left">Nama Siswa</th>
                                    <th class="text-left">NISN</th>
                                    <th class="text-left">Kelas</th>
                                    <th class="text-left">Sekolah</th>
                                    <th class="text-left">Bobot Tercapai <span class="text-xs font-normal">(dari 100)</span></th>
                                    <th class="text-left">Kuis Selesai</th>
                                    <th class="text-left">Kuis Lulus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswasWithScores as $siswa)
                                @php
                                    $kesulitan = $siswa['rata_kesulitan'];
                                    if ($kesulitan >= 70) {
                                        $kategori = 'Sangat Sulit';
                                        $warna = 'bg-red-600';
                                        $teksWarna = 'text-red-600';
                                    } elseif ($kesulitan >= 50) {
                                        $kategori = 'Sulit';
                                        $warna = 'bg-orange-500';
                                        $teksWarna = 'text-orange-500';
                                    } elseif ($kesulitan >= 30) {
                                        $kategori = 'Sedang';
                                        $warna = 'bg-yellow-500';
                                        $teksWarna = 'text-yellow-600';
                                    } else {
                                        $kategori = 'Mudah';
                                        $warna = 'bg-green-500';
                                        $teksWarna = 'text-green-600';
                                    }
                                @endphp
                                <tr class="student-row cursor-pointer hover:bg-gray-50" data-student-id="{{ $siswa['id'] }}">
                                    <td>
                                        <div class="font-medium text-gray-900">{{ $siswa['nama'] }}</div>
                                    </td>
                                    <td class="text-gray-600">{{ $siswa['nisn'] }}</td>
                                    <td class="text-gray-600">{{ $siswa['kelas'] }}</td>
                                    <td class="text-gray-600">{{ $siswa['asal_sekolah'] }}</td>
                                    <td class="text-gray-600 font-medium">
                                        {{ $siswa['bobot_tercapai'] }}%
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-700">{{ $siswa['completed'] }}/{{ $siswa['total_kuis'] }}</span>
                                            <div class="w-20">
                                                <div class="score-progress">
                                                    <div class="score-progress-bar bg-blue-400" 
                                                         style="width: {{ $siswa['completion_rate'] }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-700">{{ $siswa['passed'] }}/{{ $siswa['total_kuis'] }}</span>
                                            <div class="w-20">
                                                <div class="score-progress">
                                                    <div class="score-progress-bar 
                                                        @if($siswa['passed'] == $siswa['total_kuis']) bg-green-500
                                                        @elseif($siswa['passed'] >= $siswa['total_kuis'] * 0.7) bg-green-400
                                                        @elseif($siswa['passed'] >= $siswa['total_kuis'] * 0.5) bg-yellow-400
                                                        @else bg-red-400 @endif" 
                                                         style="width: {{ $siswa['total_kuis'] > 0 ? round(($siswa['passed'] / $siswa['total_kuis']) * 100) : 0 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quiz Tab -->
            <div id="quizContent" class="tab-content hidden animate-fadeInUp">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Rekapan Nilai per Quiz</h2>
                    <p class="text-gray-600 mb-6">Klik pada quiz di bawah ini untuk melihat nilai siswa secara detail per materi</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach([
                            ['icon' => '👃', 'name' => 'Hidung', 'weight' => '7%', 'route' => '/guru/quiz-hidung'],
                            ['icon' => '👄', 'name' => 'Faring', 'weight' => '7%', 'route' => '/guru/quiz-faring'],
                            ['icon' => '🗣️', 'name' => 'Laring', 'weight' => '7%', 'route' => '/guru/quiz-laring'],
                            ['icon' => '🌬️', 'name' => 'Trakea', 'weight' => '7%', 'route' => '/guru/quiz-trakea'],
                            ['icon' => '🫁', 'name' => 'Bronkus', 'weight' => '7%', 'route' => '/guru/quiz-bronkus'],
                            ['icon' => '🌀', 'name' => 'Bronkiolus', 'weight' => '7%', 'route' => '/guru/quiz-bronkiolus'],
                            ['icon' => '💨', 'name' => 'Alveolus', 'weight' => '7%', 'route' => '/guru/quiz-alveolus'],
                            ['icon' => '⚙️', 'name' => 'Mekanisme', 'weight' => '7%', 'route' => '/guru/quiz-mekanisme'],
                            ['icon' => '📏', 'name' => 'Frekuensi', 'weight' => '7%', 'route' => '/guru/quiz-frekuensi'],
                            ['icon' => '📦', 'name' => 'Volume', 'weight' => '7%', 'route' => '/guru/quiz-volume'],
                            ['icon' => '🚨', 'name' => 'Gangguan', 'weight' => '10%', 'route' => '/guru/quiz-gangguan'],
                           
                        ] as $quiz)
                        <a href="{{ $quiz['route'] }}" class="block">
                            <div class="quiz-card bg-white rounded-xl p-6 shadow-md h-full flex flex-col items-center justify-center text-center border-2 border-blue-100 hover:border-blue-400">
                                <div class="text-4xl mb-4">{{ $quiz['icon'] }}</div>
                                <h3 class="font-poppins text-lg font-semibold text-gray-800 mb-2">Quiz {{ $quiz['name'] }}</h3>
                                <p class="text-gray-600 text-sm mb-4">Bobot: {{ $quiz['weight'] }}</p>
                                <div class="bg-blue-100 text-blue-800 font-semibold px-3 py-1 rounded-full text-xs">
                                    Lihat Detail
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-poppins text-blue-600 text-xl font-bold mb-3">📋 Informasi Bobot Nilai</h3>
                    <p class="text-gray-700 mb-4">Sistem penilaian menggunakan bobot berikut untuk menghitung nilai akhir:</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="font-bold text-blue-600 text-lg mb-1">10 Materi</div>
                            <div class="text-gray-700">7% x 10 = 70%</div>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="font-bold text-purple-600 text-lg mb-1">Gangguan</div>
                            <div class="text-gray-700">10%</div>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="font-bold text-green-600 text-lg mb-1">Evaluasi</div>
                            <div class="text-gray-700">20%</div>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="font-bold text-orange-600 text-lg mb-1">Total</div>
                            <div class="text-gray-700">100%</div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p><i class="fas fa-info-circle mr-2 text-blue-500"></i> <strong>Catatan:</strong> Total ada 12 kuis (11 materi + 1 evaluasi) yang dihitung dalam "Kuis Selesai".</p>
                    </div>
                </div>
            </div>

            <!-- Export Tab -->
            <div id="exportContent" class="tab-content hidden animate-fadeInUp">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">📥 Ekspor Data Nilai</h2>
                    <p class="text-gray-600 mb-8">Pilih format ekspor untuk mengunduh data nilai siswa untuk analisis lebih lanjut atau pelaporan:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition">
                            <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-file-excel text-3xl text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Export Excel</h3>
                            <p class="text-gray-600 mb-4">Ekspor data dalam format Excel (.xlsx) untuk analisis data lebih lanjut.</p>
                            <button onclick="exportToExcel()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-3 rounded-lg transition-all">
                                <i class="fas fa-download mr-2"></i>Unduh Excel
                            </button>
                        </div>
                        
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition">
                            <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-file-pdf text-3xl text-red-600"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Export PDF</h3>
                            <p class="text-gray-600 mb-4">Ekspor data dalam format PDF untuk laporan resmi dan dokumentasi.</p>
                            <button onclick="exportToPDF()" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-3 rounded-lg transition-all">
                                <i class="fas fa-download mr-2"></i>Unduh PDF
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">⚙️ Pengaturan Ekspor</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas</label>
                                <select id="exportKelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Format Data</label>
                                <select id="exportFormat" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="complete">Data Lengkap</option>
                                    <option value="summary">Ringkasan Saja</option>
                                    <option value="per_materi">Per Materi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lembar Kerja Tab (dengan akses cepat) -->
            <div id="lembarKerjaContent" class="tab-content hidden animate-fadeInUp">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <!-- Header -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">📝 Lembar Kerja Siswa</h2>
                            <p class="text-gray-600">Buat, simpan, dan kelola lembar kerja individual untuk setiap siswa</p>
                        </div>
                        <button onclick="showCreateLembarKerjaModal()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Buat Lembar Kerja Baru
                        </button>
                    </div>

                    <!-- Bagian Baru: Akses Cepat per Materi -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">🔗 Akses Cepat Lembar Kerja per Materi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @php
                            $materiRoutes = [
                                'Hidung' => [
                                    'index' => 'guru.lembarKerjaHidung.index',
                                    'export' => 'guru.lembarKerjaHidung.export',
                                ],
                                'Faring' => [
                                    'index' => 'guru.lembarKerjaFaring.index',
                                    'export' => 'guru.lembarKerjaFaring.export',
                                ],
                                'Laring' => [
                                    'index' => 'guru.lembarKerjaLaring.index',
                                    'export' => 'guru.lembarKerjaLaring.export',
                                ],
                                'Trakea' => [
                                    'index' => 'guru.lembarKerjaTrakea.index',
                                    'export' => 'guru.lembarKerjaTrakea.export',
                                ],
                                'Bronkus' => [
                                    'index' => 'guru.lembarKerjaBronkus.index',
                                    'export' => 'guru.lembarKerjaBronkus.export',
                                ],
                                'Bronkiolus' => [
                                    'index' => 'guru.lembarKerjaBronkiolus.index',
                                    'export' => 'guru.lembarKerjaBronkiolus.export',
                                ],
                                'Alveolus' => [
                                    'index' => 'guru.lembarKerjaAlveolus.index',
                                    'export' => 'guru.lembarKerjaAlveolus.export',
                                ],
                                'Mekanisme' => [
                                    'index' => 'guru.lembarKerjaMekanisme.index',
                                    'export' => 'guru.lembarKerjaMekanisme.export',
                                ],
                                'Gangguan' => [
                                    'index' => 'guru.lembarKerjaGangguan.index',
                                    'export' => 'guru.lembarKerjaGangguan.export',
                                ],
                                'Frekuensi' => [
                                    'index' => 'guru.lembarKerjaFrekuensi.index',
                                    'export' => 'guru.lembarKerjaFrekuensi.export',
                                ],
                                'Volume' => [
                                    'index' => 'guru.lembarKerjaVolume.index',
                                    'export' => 'guru.lembarKerjaVolume.export',
                                ],
                            ];
                            @endphp
                            @foreach($materiRoutes as $materi => $routes)
                            <div class="border rounded-lg p-4 hover:shadow-md transition bg-white">
                                <h4 class="font-bold text-gray-800 mb-2">{{ $materi }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route($routes['index']) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200 transition">
                                        <i class="fas fa-list mr-1"></i>Lihat
                                    </a>
                                    <a href="{{ route($routes['export']) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200 transition">
                                        <i class="fas fa-download mr-1"></i>Export
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Student Detail -->
    <div id="studentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Detail Nilai Siswa</h3>
                    <button onclick="closeStudentModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="studentDetailContent">
                    <!-- Akan diisi JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Remedial (juga untuk daftar siswa dari pie) -->
    <div id="remedialModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800" id="remedialModalTitle">Daftar Siswa</h3>
                    <button onclick="closeRemedialModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="remedialModalContent">
                    <!-- Akan diisi JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create Lembar Kerja -->
    <div id="createLembarKerjaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Buat Lembar Kerja Siswa</h3>
                    <button onclick="closeCreateLembarKerjaModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <!-- Pilih Siswa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa</label>
                        <select id="selectSiswa" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswasWithScores as $siswa)
                            <option value="{{ $siswa['id'] }}" data-nama="{{ $siswa['nama'] }}" data-nisn="{{ $siswa['nisn'] }}" data-kelas="{{ $siswa['kelas'] }}">
                                {{ $siswa['nama'] }} ({{ $siswa['nisn'] }}) - {{ $siswa['kelas'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Template Lembar Kerja -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Template Lembar Kerja</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 template-option" data-template="standard">
                                <div class="flex items-center mb-2">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">Standar</h4>
                                        <p class="text-sm text-gray-600">Rekap nilai + catatan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 template-option" data-template="detail">
                                <div class="flex items-center mb-2">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-chart-bar text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">Detail</h4>
                                        <p class="text-sm text-gray-600">Grafik + analisis mendalam</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customization -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customisasi</label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="includeChart" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="includeChart" class="ml-2 text-gray-700">Sertakan grafik performa</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="includeRekomendasi" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="includeRekomendasi" class="ml-2 text-gray-700">Sertakan rekomendasi</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="includeTandaTangan" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="includeTandaTangan" class="ml-2 text-gray-700">Sertakan kolom tanda tangan</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div id="lembarKerjaPreview" class="border rounded-lg p-6 bg-gray-50 min-h-[200px]">
                            <p class="text-gray-500 text-center">Pilih siswa untuk melihat preview</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button onclick="generateLembarKerja()" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all">
                            <i class="fas fa-magic mr-2"></i>Generate Lembar Kerja
                        </button>
                        <button onclick="saveLembarKerjaDraft()" class="flex-1 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all">
                            <i class="fas fa-save mr-2"></i>Simpan Draft
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Lembar Kerja -->
    <div id="viewLembarKerjaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800" id="viewLembarKerjaTitle">Lembar Kerja Siswa</h3>
                    <div class="flex gap-2">
                        <button onclick="printCurrentLembarKerja()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <button onclick="closeViewLembarKerjaModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-times mr-2"></i>Tutup
                        </button>
                    </div>
                </div>
                <div id="viewLembarKerjaContent">
                    <!-- Akan diisi JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <script>
        // Data dari PHP
        const siswaData = @json($siswasWithScores);
        const materiKesulitanBase = @json($materiKesulitanBase);
        const materiCompletion = @json($materiCompletion);
        const materiRemedialListBase = @json($materiRemedialListBase);
        const materiLabels = @json($materiLabels);
        const bobotMateri = @json($bobotMateri);
        const materiSiswaList = @json($materiSiswaList);
        const materiSudahCount = @json($materiSudahCount);
        const materiSiswaSudah = @json($materiSiswaSudah);
        const materiSiswaBelum = @json($materiSiswaBelum);
        const totalSiswa = {{ $totalSiswa }};
        
        // Variabel global
        let currentKKM = 70;
        let charts = {};
        let storedLembarKerja = @json($storedLembarKerja);
        let currentLembarKerjaData = null;
        let materiRemedialList = { ...materiRemedialListBase };

        // Mobile sidebar functions
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) {
                sidebar.classList.toggle('open');
                if (overlay) overlay.classList.toggle('active');
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) {
                sidebar.classList.remove('open');
                if (overlay) overlay.classList.remove('active');
            }
        }

        // Tab Navigation
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            document.getElementById(tabName + 'Content').classList.remove('hidden');
            
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            const tabId = 'tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1);
            const tabElement = document.getElementById(tabId);
            if (tabElement) {
                tabElement.classList.add('active');
            }
        }

        // Update KKM Display
        function updateKKMDisplay(value) {
            document.getElementById('kkmDisplay').textContent = value;
        }

        // Apply New KKM
        function applyNewKKM(value) {
            currentKKM = parseInt(value);
            
            document.querySelectorAll('.kkm-value').forEach(el => {
                el.textContent = currentKKM;
            });
            
            document.querySelectorAll('.kkm-value-display-small').forEach(el => {
                el.textContent = currentKKM;
            });
            
            recalculateWithNewKKM();
            updateChartsWithNewKKM();
            updateBobotTercapai();
            
            showNotification(`KKM berhasil diubah menjadi ${currentKKM}`, 'success');
        }

        function recalculateWithNewKKM() {
            const tuntas = siswaData.filter(s => s.nilai_akhir >= currentKKM).length;
            const remedial = siswaData.filter(s => s.nilai_akhir < currentKKM && s.nilai_akhir > 0).length;
            
            document.getElementById('tuntasCount').textContent = tuntas;
            document.getElementById('remedialCount').textContent = remedial;
        }

        function updateBobotTercapai() {
            const rows = document.querySelectorAll('#studentsTable tbody tr');
            rows.forEach((row, index) => {
                const siswa = siswaData[index];
                if (!siswa) return;
                
                let bobotTercapai = 0;
                const materiKeys = ['hidung', 'faring', 'laring', 'trakea', 'bronkus', 'bronkiolus', 'alveolus', 'mekanisme', 'frekuensi', 'volume', 'gangguan', 'evaluasi'];
                const bobot = siswa.bobot;
                for (let i = 0; i < materiKeys.length; i++) {
                    const nilai = siswa.detail[materiKeys[i]] || 0;
                    if (nilai >= currentKKM) {
                        bobotTercapai += bobot[materiKeys[i]];
                    }
                }
                
                const cell = row.cells[4];
                if (cell) cell.textContent = bobotTercapai + '%';
            });
        }

        function updateChartsWithNewKKM() {
            const newMateriRemedialList = {};
            
            materiLabels.forEach(materi => {
                const key = materi.toLowerCase().replace(' ', '_');
                const siswaRemedial = [];
                
                siswaData.forEach(siswa => {
                    const nilai = siswa.detail[key] || 0;
                    if (nilai > 0 && nilai < currentKKM) {
                        siswaRemedial.push({
                            nama: siswa.nama,
                            nilai: nilai,
                            kelas: siswa.kelas,
                            nisn: siswa.nisn
                        });
                    }
                });
                
                newMateriRemedialList[materi] = siswaRemedial;
            });
            
            if (charts.kesulitanChart) {
                const jumlahRemedial = Object.values(newMateriRemedialList).map(list => list.length);
                charts.kesulitanChart.data.datasets[0].data = jumlahRemedial;
                charts.kesulitanChart.update();
            }
            
            materiRemedialList = newMateriRemedialList;
        }

        function showStudentDetail(studentId) {
            const siswa = siswaData.find(s => s.id == studentId);
            
            if (!siswa) {
                document.getElementById('studentDetailContent').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-4xl text-red-500 mb-4"></i>
                        <p class="text-gray-600">Data siswa tidak ditemukan</p>
                    </div>
                `;
                return;
            }
            
            let detailHTML = `
                <div class="mb-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">${siswa.nama}</h4>
                    <div class="flex flex-wrap gap-3 mb-6">
                        <span class="text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full">
                            <i class="fas fa-id-card mr-2"></i>${siswa.nisn}
                        </span>
                        <span class="text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full">
                            <i class="fas fa-school mr-2"></i>${siswa.kelas}
                        </span>
                        <span class="text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full">
                            <i class="fas fa-building mr-2"></i>${siswa.asal_sekolah}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">${siswa.passed}/${siswa.total_kuis}</div>
                            <div class="text-sm text-green-800">Kuis Lulus</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">${siswa.completed}/${siswa.total_kuis}</div>
                            <div class="text-sm text-purple-800">Kuis Selesai</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">${siswa.detail.evaluasi}%</div>
                            <div class="text-sm text-yellow-800">Evaluasi</div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-indigo-600">${siswa.jumlah_bobot_nilai}</div>
                            <div class="text-sm text-indigo-800">Jumlah Bobot Nilai</div>
                        </div>
                    </div>
                    
                    <h5 class="font-bold text-gray-700 mb-4">Detail Nilai per Materi</h5>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-2 px-4 text-left border-b">Materi</th>
                                    <th class="py-2 px-4 text-center border-b">Nilai</th>
                                    <th class="py-2 px-4 text-center border-b">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            const materiList = [
                { key: 'hidung', label: 'Hidung', icon: '👃' },
                { key: 'faring', label: 'Faring', icon: '👅' },
                { key: 'laring', label: 'Laring', icon: '🎤' },
                { key: 'trakea', label: 'Trakea', icon: '🎋' },
                { key: 'bronkus', label: 'Bronkus', icon: '🌳' },
                { key: 'bronkiolus', label: 'Bronkiolus', icon: '🌿' },
                { key: 'alveolus', label: 'Alveolus', icon: '🫧' },
                { key: 'mekanisme', label: 'Mekanisme', icon: '🫁' },
                { key: 'frekuensi', label: 'Frekuensi', icon: '📈' },
                { key: 'volume', label: 'Volume', icon: '📊' },
                { key: 'gangguan', label: 'Gangguan', icon: '⚠️' },
                { key: 'evaluasi', label: 'Evaluasi', icon: '🎯' }
            ];
            
            materiList.forEach(materi => {
                const nilai = siswa.detail[materi.key] || 0;
                const bobotMateri = siswa.bobot[materi.key];
                const colorClass = nilai >= currentKKM ? 'text-green-600' : (nilai > 0 ? 'text-red-600' : 'text-gray-400');
                
                detailHTML += `
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">${materi.icon}</span>
                                <span>${materi.label}</span>
                            </div>
                        </td>
                        <td class="py-2 px-4 text-center border-b ${colorClass} font-medium">
                            ${nilai}%
                        </td>
                        <td class="py-2 px-4 text-center border-b">${bobotMateri}%</td>
                    </tr>
                `;
            });
            
            detailHTML += `
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex gap-4 flex-wrap">
                        <button onclick="generateLembarKerjaForStudent(${siswa.id})" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            <i class="fas fa-file-alt mr-2"></i>Buat Lembar Kerja
                        </button>
                        <button onclick="printStudentReport(${siswa.id})" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            <i class="fas fa-print mr-2"></i>Cetak Detail
                        </button>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-600 bg-blue-50 p-4 rounded-lg">
                        <p class="mb-2"><strong>Keterangan:</strong></p>
                        <p>1. <strong>Kuis Selesai</strong> = ${siswa.completed} dari ${siswa.total_kuis} kuis (11 materi + 1 evaluasi)</p>
                        <p>2. <strong>Kuis Lulus</strong> = Kuis dengan nilai ≥ ${currentKKM}</p>
                        <p>3. <strong>Jumlah Bobot Nilai</strong> = Total bobot dari materi yang sudah dikerjakan (maksimal 100)</p>
                        <p>4. <strong>Bobot Tercapai</strong> = Jumlah bobot dari materi yang lulus (≥ KKM)</p>
                    </div>
                </div>
            `;
            
            document.getElementById('studentDetailContent').innerHTML = detailHTML;
            showModal('studentModal');
        }

        function setupTableFilter() {
            const searchInput = document.getElementById('searchInput');
            const filterKelas = document.getElementById('filterKelas');
            
            if (searchInput && filterKelas) {
                const rows = document.querySelectorAll('#studentsTable tbody tr');
                
                function filterTable() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const selectedKelas = filterKelas.value;
                    
                    rows.forEach(row => {
                        const nama = row.cells[0].textContent.toLowerCase();
                        const kelas = row.cells[2].textContent;
                        const showRow = 
                            (searchTerm === '' || nama.includes(searchTerm)) &&
                            (selectedKelas === '' || kelas === selectedKelas);
                        
                        row.style.display = showRow ? '' : 'none';
                    });
                }
                
                searchInput.addEventListener('input', filterTable);
                filterKelas.addEventListener('change', filterTable);
            }
        }

        function initializeCharts() {
            Object.values(charts).forEach(chart => {
                if (chart) chart.destroy();
            });
            
            // Chart Kesulitan
            const ctx1 = document.getElementById('kesulitanChart').getContext('2d');
            charts.kesulitanChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: Object.keys(materiKesulitanBase),
                    datasets: [{
                        label: 'Jumlah Siswa Remedial',
                        data: Object.values(materiRemedialList).map(list => list.length),
                        backgroundColor: '#ef4444',
                        borderColor: '#dc2626',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const materi = context.label;
                                    const remedialList = materiRemedialList[materi] || [];
                                    const jumlah = remedialList.length;
                                    return `${jumlah} siswa`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: totalSiswa,
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            ticks: { maxRotation: 45, minRotation: 45 }
                        }
                    }
                }
            });

            // Chart Pengerjaan
            const ctx2 = document.getElementById('pengerjaanChart').getContext('2d');
            charts.pengerjaanChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: Object.keys(materiCompletion),
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: Object.values(materiCompletion),
                        backgroundColor: '#3b82f6',
                        borderColor: '#1d4ed8',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                        x: { ticks: { maxRotation: 45, minRotation: 45 } }
                    }
                }
            });

            // Diagram Lingkaran Dinamis
            const ctx4 = document.getElementById('dynamicPieChart').getContext('2d');
            const initialMateri = document.getElementById('materiPieSelect').value;
            const sudah = materiSudahCount[initialMateri] || 0;
            const belum = totalSiswa - sudah;
            
            charts.dynamicPieChart = new Chart(ctx4, {
                type: 'pie',
                data: {
                    labels: ['Sudah Mengerjakan', 'Belum Mengerjakan'],
                    datasets: [{
                        data: [sudah, belum],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 20 } }
                    }
                }
            });

            document.getElementById('materiPieSelect').addEventListener('change', function() {
                const materi = this.value;
                const sudahBaru = materiSudahCount[materi] || 0;
                const belumBaru = totalSiswa - sudahBaru;
                
                charts.dynamicPieChart.data.datasets[0].data = [sudahBaru, belumBaru];
                charts.dynamicPieChart.update();
            });
        }

        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeStudentModal() {
            document.getElementById('studentModal').classList.add('hidden');
            document.getElementById('studentModal').classList.remove('flex');
        }

        function closeRemedialModal() {
            document.getElementById('remedialModal').classList.add('hidden');
            document.getElementById('remedialModal').classList.remove('flex');
        }

        function closeCreateLembarKerjaModal() {
            document.getElementById('createLembarKerjaModal').classList.add('hidden');
            document.getElementById('createLembarKerjaModal').classList.remove('flex');
        }

        function closeViewLembarKerjaModal() {
            document.getElementById('viewLembarKerjaModal').classList.add('hidden');
            document.getElementById('viewLembarKerjaModal').classList.remove('flex');
        }

        function showNotification(message, type = 'success') {
            const existingNotification = document.querySelector('.notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function exportToExcel() {
            showNotification('Data berhasil diekspor ke Excel', 'success');
        }

        function exportToPDF() {
            showNotification('Data berhasil diekspor ke PDF', 'success');
        }

        function printReport() {
            window.print();
        }

        function generateLembarKerja() { showNotification('Generate lembar kerja (demo)', 'info'); }
        function generateLembarKerjaForStudent(id) { showNotification('Generate untuk siswa ' + id, 'info'); }
        function saveLembarKerjaDraft() { showNotification('Simpan draft (demo)', 'info'); }
        function viewLembarKerja(id) { showNotification('Lihat lembar kerja ' + id, 'info'); }
        function printCurrentLembarKerja() { window.print(); }
        function deleteLembarKerja(id) { showNotification('Hapus lembar kerja ' + id, 'info'); }
        function printStudentReport(id) { showNotification('Cetak laporan siswa ' + id, 'info'); }
        function showCreateLembarKerjaModal() { showModal('createLembarKerjaModal'); }

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setupTableFilter();
            
            const stored = JSON.parse(localStorage.getItem('lembarKerja') || '[]');
            storedLembarKerja = stored;
            
            document.querySelectorAll('.student-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
                    const studentId = this.getAttribute('data-student-id');
                    showStudentDetail(studentId);
                });
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeStudentModal();
                    closeRemedialModal();
                    closeCreateLembarKerjaModal();
                    closeViewLembarKerjaModal();
                    closeMobileSidebar();
                }
            });
            
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        const modalId = this.id;
                        if (modalId === 'studentModal') closeStudentModal();
                        if (modalId === 'remedialModal') closeRemedialModal();
                        if (modalId === 'createLembarKerjaModal') closeCreateLembarKerjaModal();
                        if (modalId === 'viewLembarKerjaModal') closeViewLembarKerjaModal();
                    }
                });
            });
            
            document.querySelectorAll('.template-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.template-option').forEach(opt => {
                        opt.classList.remove('border-blue-500', 'bg-blue-50');
                    });
                    this.classList.add('border-blue-500', 'bg-blue-50');
                });
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                const sidebar = document.getElementById('sidebar');
                const menuBtn = document.querySelector('.mobile-menu-toggle');
                if (window.innerWidth < 768 && sidebar && sidebar.classList.contains('open')) {
                    if (!sidebar.contains(e.target) && !menuBtn?.contains(e.target)) {
                        closeMobileSidebar();
                    }
                }
            });
            
            // Close sidebar on resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    closeMobileSidebar();
                }
            });
        });
    </script>
</body>
</html>