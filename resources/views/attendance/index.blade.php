<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi - Face Attendance System</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Pastel Colors - 60-30-10 Rule */
            --color-bg: #f0f4f8;
            --color-bg-card: #ffffff;
            --color-bg-glass: rgba(255, 255, 255, 0.8);
            
            --color-pastel-lavender: #e8e0f0;
            --color-pastel-mint: #d4f5e9;
            --color-pastel-peach: #fce8e0;
            --color-pastel-sky: #dbeafe;
            
            --color-primary: #6366f1;
            --color-primary-dark: #4f46e5;
            
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-info: #3b82f6;
            
            --color-text: #1e293b;
            --color-text-secondary: #64748b;
            --color-text-muted: #94a3b8;
            
            --border-radius: 16px;
            --border-radius-lg: 24px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--color-pastel-lavender) 0%, var(--color-pastel-sky) 50%, var(--color-pastel-mint) 100%);
            min-height: 100vh;
            color: var(--color-text);
            overflow-x: hidden;
        }

        /* Animated Background Blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 15s infinite ease-in-out;
            z-index: 0;
        }

        .blob-1 {
            width: 600px;
            height: 600px;
            background: var(--color-pastel-lavender);
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 500px;
            height: 500px;
            background: var(--color-pastel-mint);
            bottom: -150px;
            left: -150px;
            animation-delay: -5s;
        }

        .blob-3 {
            width: 400px;
            height: 400px;
            background: var(--color-pastel-peach);
            top: 50%;
            left: 50%;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 30px) scale(1.02); }
        }

        /* Main Container */
        .container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--color-primary) 0%, #a855f7 100%);
            border-radius: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-lg);
            animation: pulse 2s infinite;
        }

        .logo i {
            font-size: 36px;
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            50% { transform: scale(1.02); box-shadow: 0 0 0 20px rgba(99, 102, 241, 0); }
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--color-primary) 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: var(--color-text-secondary);
            font-size: 16px;
        }

        /* Current Time */
        .current-time {
            background: var(--color-bg-glass);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 16px 32px;
            display: inline-flex;
            align-items: center;
            gap: 16px;
            margin-top: 20px;
            box-shadow: var(--shadow);
        }

        .time-display {
            font-size: 48px;
            font-weight: 800;
            color: var(--color-text);
        }

        .date-display {
            text-align: left;
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        /* Main Content Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 32px;
            flex: 1;
        }

        /* Camera Section */
        .camera-section {
            background: var(--color-bg-card);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .camera-header {
            padding: 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .camera-header h2 {
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .camera-header h2 i {
            color: var(--color-primary);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge.loading {
            background: var(--color-pastel-sky);
            color: var(--color-info);
        }

        .status-badge.ready {
            background: var(--color-pastel-mint);
            color: var(--color-success);
        }

        .status-badge.error {
            background: var(--color-pastel-peach);
            color: var(--color-danger);
        }

        .camera-container {
            position: relative;
            background: #0f172a;
            aspect-ratio: 4/3;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        /* Face Detection Indicator */
        .face-indicator {
            position: absolute;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            color: white;
            padding: 16px 24px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .face-indicator.detected {
            background: rgba(16, 185, 129, 0.9);
        }

        .face-indicator-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .face-indicator-text h4 {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .face-indicator-text p {
            font-size: 13px;
            opacity: 0.8;
        }

        /* Action Buttons */
        .action-buttons {
            padding: 24px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .action-btn {
            padding: 20px 16px;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 0%, rgba(255,255,255,0.2) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .action-btn:hover::before {
            opacity: 1;
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .action-btn:not(:disabled):hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .action-btn i {
            font-size: 24px;
        }

        .action-btn.clock-in {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
        }

        .action-btn.clock-out {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            color: white;
        }

        .action-btn.break-start {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            color: white;
        }

        .action-btn.break-end {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Recognized Employee Card */
        .employee-card {
            background: var(--color-bg-card);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .employee-card.recognized {
            border: 3px solid var(--color-success);
            animation: recognizedPulse 2s infinite;
        }

        @keyframes recognizedPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        }

        .employee-card-header {
            padding: 24px;
            text-align: center;
            background: linear-gradient(135deg, var(--color-pastel-lavender) 0%, var(--color-pastel-sky) 100%);
        }

        .employee-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 16px;
            border: 4px solid white;
            box-shadow: var(--shadow);
            overflow: hidden;
            background: linear-gradient(135deg, var(--color-primary) 0%, #a855f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: 700;
        }

        .employee-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .employee-name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .employee-id {
            color: var(--color-text-secondary);
            font-size: 14px;
        }

        .match-score {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            padding: 8px 16px;
            background: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: var(--color-success);
        }

        .employee-card-body {
            padding: 24px;
        }

        .no-employee {
            text-align: center;
            padding: 40px 24px;
            color: var(--color-text-muted);
        }

        .no-employee i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        /* Today's Status */
        .today-status {
            background: var(--color-bg-card);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            padding: 24px;
        }

        .today-status h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .today-status h3 i {
            color: var(--color-primary);
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-item-label {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--color-text-secondary);
        }

        .status-item-label i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .status-item-label.clock-in i { background: var(--color-pastel-mint); color: var(--color-success); }
        .status-item-label.clock-out i { background: var(--color-pastel-peach); color: var(--color-danger); }
        .status-item-label.break-start i { background: #fef3c7; color: var(--color-warning); }
        .status-item-label.break-end i { background: var(--color-pastel-sky); color: var(--color-info); }

        .status-item-value {
            font-weight: 600;
            font-size: 16px;
        }

        .status-item-value.pending {
            color: var(--color-text-muted);
            font-weight: 400;
        }

        /* Success Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 48px;
            text-align: center;
            max-width: 400px;
            animation: modalSlide 0.3s ease;
        }

        @keyframes modalSlide {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
        }

        .modal-icon.success {
            background: var(--color-pastel-mint);
            color: var(--color-success);
        }

        .modal-icon.danger {
            background: var(--color-pastel-peach);
            color: var(--color-danger);
        }

        .modal-icon.warning {
            background: #fef3c7;
            color: var(--color-warning);
        }

        .modal-icon.info {
            background: var(--color-pastel-sky);
            color: var(--color-info);
        }

        .modal-icon.secondary {
            background: #f1f5f9;
            color: var(--color-text-secondary);
        }

        /* Legacy support if needed, map error to danger */
        .modal-icon.error {
            background: var(--color-pastel-peach);
            color: var(--color-danger);
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .modal-message {
            color: var(--color-text-secondary);
            margin-bottom: 24px;
        }

        .modal-btn {
            padding: 14px 32px;
            border-radius: var(--border-radius);
            border: none;
            background: var(--color-primary);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-btn:hover {
            background: var(--color-primary-dark);
        }

        /* Admin Link */
        .admin-link {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 12px 20px;
            background: var(--color-bg-glass);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            text-decoration: none;
            color: var(--color-text);
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            z-index: 100;
        }

        .admin-link:hover {
            background: var(--color-primary);
            color: white;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .employee-card, .today-status {
                flex: 1;
                min-width: 280px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .time-display {
                font-size: 36px;
            }

            .action-buttons {
                grid-template-columns: repeat(2, 1fr);
            }

            .current-time {
                flex-direction: column;
                gap: 8px;
                padding: 16px 24px;
            }

            .face-indicator {
                left: 16px;
                right: 16px;
                transform: none;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <i class="fas fa-fingerprint"></i>
            </div>
            <h1>Face Attendance System</h1>
            <p>Sistem Absensi Wajah</p>
            
            <div class="current-time">
                <div class="time-display" id="currentTime">00:00:00</div>
                <div class="date-display" id="currentDate">
                    <div style="font-weight: 600;" id="dayName">Senin</div>
                    <div id="fullDate">01 Januari 2024</div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="main-grid">
            <!-- Camera Section -->
            <div class="camera-section">
                <div class="camera-header">
                    <h2>
                        <i class="fas fa-video"></i>
                        Kamera Absensi
                    </h2>
                    <div id="modelStatus" class="status-badge loading">
                        <div class="loading-spinner"></div>
                        <span>Memuat AI...</span>
                    </div>
                </div>

                <div class="camera-container">
                    <video id="video" autoplay muted playsinline></video>
                    <canvas id="overlay"></canvas>
                    
                    <div class="face-indicator" id="faceIndicator">
                        <div class="face-indicator-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="face-indicator-text">
                            <h4 id="indicatorTitle">Mencari wajah...</h4>
                            <p id="indicatorDesc">Posisikan wajah Anda di depan kamera</p>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="action-btn clock-in" id="btnClockIn" disabled>
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                    <button class="action-btn clock-out" id="btnClockOut" disabled>
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Pulang</span>
                    </button>
                    <button class="action-btn break-start" id="btnBreakStart" disabled>
                        <i class="fas fa-coffee"></i>
                        <span>Mulai Istirahat</span>
                    </button>
                    <button class="action-btn break-end" id="btnBreakEnd" disabled>
                        <i class="fas fa-play"></i>
                        <span>Selesai Istirahat</span>
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Recognized Employee -->
                <div class="employee-card" id="employeeCard">
                    <div class="no-employee" id="noEmployee">
                        <i class="fas fa-user-circle"></i>
                        <p>Wajah tidak terdeteksi<br>Silakan posisikan wajah Anda</p>
                    </div>
                    <div id="employeeInfo" style="display: none;">
                        <div class="employee-card-header">
                            <div class="employee-avatar" id="employeeAvatar">
                                <span id="employeeInitial">?</span>
                            </div>
                            <div class="employee-name" id="employeeName">-</div>
                            <div class="employee-id" id="employeeNumber">-</div>
                            <div class="match-score" id="matchScoreDisplay">
                                <i class="fas fa-check-circle"></i>
                                <span id="matchScoreValue">0%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Status -->
                <div class="today-status">
                    <h3>
                        <i class="fas fa-calendar-check"></i>
                        Status Hari Ini
                    </h3>
                    <div class="status-item">
                        <div class="status-item-label clock-in">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Masuk</span>
                        </div>
                        <div class="status-item-value pending" id="clockInTime">-</div>
                    </div>
                    <div class="status-item">
                        <div class="status-item-label break-start">
                            <i class="fas fa-coffee"></i>
                            <span>Mulai Istirahat</span>
                        </div>
                        <div class="status-item-value pending" id="breakStartTime">-</div>
                    </div>
                    <div class="status-item">
                        <div class="status-item-label break-end">
                            <i class="fas fa-play"></i>
                            <span>Selesai Istirahat</span>
                        </div>
                        <div class="status-item-value pending" id="breakEndTime">-</div>
                    </div>
                    <div class="status-item">
                        <div class="status-item-label clock-out">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Pulang</span>
                        </div>
                        <div class="status-item-value pending" id="clockOutTime">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Modal -->
    <div class="modal-overlay" id="resultModal">
        <div class="modal-content">
            <div class="modal-icon" id="modalIcon">
                <i class="fas fa-check"></i>
            </div>
            <div class="modal-title" id="modalTitle">Berhasil!</div>
            <p class="modal-message" id="modalMessage">Absensi berhasil dicatat.</p>
            <button class="modal-btn" onclick="closeModal()">Tutup</button>
        </div>
    </div>

    <!-- Admin Link -->
    <a href="{{ route('login') }}" class="admin-link">
        <i class="fas fa-cog"></i>
        Admin Panel
    </a>

    <!-- Face-api.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script>
        // DOM Elements
        const video = document.getElementById('video');
        const overlay = document.getElementById('overlay');
        const modelStatus = document.getElementById('modelStatus');
        const faceIndicator = document.getElementById('faceIndicator');
        const indicatorTitle = document.getElementById('indicatorTitle');
        const indicatorDesc = document.getElementById('indicatorDesc');
        const employeeCard = document.getElementById('employeeCard');
        const noEmployee = document.getElementById('noEmployee');
        const employeeInfo = document.getElementById('employeeInfo');
        const employeeAvatar = document.getElementById('employeeAvatar');
        const employeeInitial = document.getElementById('employeeInitial');
        const employeeName = document.getElementById('employeeName');
        const employeeNumber = document.getElementById('employeeNumber');
        const matchScoreValue = document.getElementById('matchScoreValue');

        const btnClockIn = document.getElementById('btnClockIn');
        const btnClockOut = document.getElementById('btnClockOut');
        const btnBreakStart = document.getElementById('btnBreakStart');
        const btnBreakEnd = document.getElementById('btnBreakEnd');

        // State
        let employees = [];
        let currentEmployee = null;
        let currentMatchScore = 0;
        let faceMatcher = null;
        let isProcessing = false;

        // Update current time
        function updateTime() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
            document.getElementById('dayName').textContent = days[now.getDay()];
            document.getElementById('fullDate').textContent = `${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Load face-api.js models
        async function loadModels() {
            const MODEL_URL = '/models';
            try {
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);
                modelStatus.className = 'status-badge ready';
                modelStatus.innerHTML = '<i class="fas fa-check-circle"></i> <span>AI Siap</span>';
                await loadEmployees();
                startVideo();
            } catch (error) {
                console.error('Error loading models:', error);
                modelStatus.className = 'status-badge error';
                modelStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Error</span>';
            }
        }

        // Load employees with face descriptors
        async function loadEmployees() {
            try {
                const response = await fetch('/api/attendance/employees/descriptors');
                const data = await response.json();
                employees = data.employees || [];
                
                if (employees.length > 0) {
                    const labeledDescriptors = employees
                        .filter(emp => emp.face_descriptors && emp.face_descriptors.length > 0)
                        .map(emp => {
                            const descriptors = emp.face_descriptors.map(d => new Float32Array(d));
                            return new faceapi.LabeledFaceDescriptors(String(emp.id), descriptors);
                        });
                    
                    if (labeledDescriptors.length > 0) {
                        faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);
                    }
                }
            } catch (error) {
                console.error('Error loading employees:', error);
            }
        }

        // Start webcam
        async function startVideo() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
                });
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    overlay.width = video.videoWidth;
                    overlay.height = video.videoHeight;
                    detectFace();
                };
            } catch (error) {
                console.error('Error accessing camera:', error);
                indicatorTitle.textContent = 'Error Kamera';
                indicatorDesc.textContent = 'Tidak dapat mengakses kamera';
            }
        }

        // Detect and recognize face
        async function detectFace() {
            if (isProcessing) {
                requestAnimationFrame(detectFace);
                return;
            }

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            const ctx = overlay.getContext('2d');
            ctx.clearRect(0, 0, overlay.width, overlay.height);

            if (detection) {
                // Draw face box
                const box = detection.detection.box;
                ctx.strokeStyle = '#10b981';
                ctx.lineWidth = 3;
                ctx.strokeRect(box.x, box.y, box.width, box.height);

                // Draw face landmarks
                ctx.fillStyle = '#10b981';
                detection.landmarks.positions.forEach(point => {
                    ctx.beginPath();
                    ctx.arc(point.x, point.y, 2, 0, 2 * Math.PI);
                    ctx.fill();
                });

                // Try to match face
                if (faceMatcher) {
                    const match = faceMatcher.findBestMatch(detection.descriptor);
                    
                    if (match.label !== 'unknown') {
                        const employeeId = parseInt(match.label);
                        const employee = employees.find(e => e.id === employeeId);
                        
                        if (employee) {
                            currentEmployee = employee;
                            currentMatchScore = 1 - match.distance;
                            showEmployee(employee, currentMatchScore);
                            enableButtons(true);
                            
                            faceIndicator.classList.add('detected');
                            indicatorTitle.textContent = 'Wajah Dikenali';
                            indicatorDesc.textContent = employee.name;
                        }
                    } else {
                        clearEmployee();
                        faceIndicator.classList.remove('detected');
                        indicatorTitle.textContent = 'Wajah Tidak Dikenali';
                        indicatorDesc.textContent = 'Wajah Anda tidak terdaftar';
                    }
                } else {
                    faceIndicator.classList.remove('detected');
                    indicatorTitle.textContent = 'Wajah Terdeteksi';
                    indicatorDesc.textContent = 'Tidak ada data wajah terdaftar';
                }
            } else {
                clearEmployee();
                faceIndicator.classList.remove('detected');
                indicatorTitle.textContent = 'Mencari wajah...';
                indicatorDesc.textContent = 'Posisikan wajah Anda di depan kamera';
            }

            requestAnimationFrame(detectFace);
        }

        // Show recognized employee
        function showEmployee(employee, matchScore) {
            noEmployee.style.display = 'none';
            employeeInfo.style.display = 'block';
            employeeCard.classList.add('recognized');
            
            employeeName.textContent = employee.name;
            employeeNumber.textContent = employee.employee_number;
            matchScoreValue.textContent = Math.round(matchScore * 100) + '%';
            
            if (employee.face_photo) {
                employeeAvatar.innerHTML = `<img src="${employee.face_photo}" alt="${employee.name}">`;
            } else {
                employeeAvatar.innerHTML = `<span>${employee.name.charAt(0).toUpperCase()}</span>`;
            }
        }

        // Clear employee display
        function clearEmployee() {
            currentEmployee = null;
            currentMatchScore = 0;
            noEmployee.style.display = 'block';
            employeeInfo.style.display = 'none';
            employeeCard.classList.remove('recognized');
            enableButtons(false);
        }

        // Enable/disable action buttons
        function enableButtons(enabled) {
            btnClockIn.disabled = !enabled;
            btnClockOut.disabled = !enabled;
            btnBreakStart.disabled = !enabled;
            btnBreakEnd.disabled = !enabled;
        }

        // Handle attendance submission
        async function submitAttendance(type) {
            if (!currentEmployee || isProcessing) return;
            
            isProcessing = true;
            const btn = document.getElementById(`btn${type.charAt(0).toUpperCase() + type.slice(1).replace(/_([a-z])/g, g => g[1].toUpperCase())}`);
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            try {
                // Capture photo
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                const photo = canvas.toDataURL('image/jpeg', 0.8);

                const response = await fetch('/api/attendance/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        employee_id: currentEmployee.id,
                        type: type,
                        face_match_score: currentMatchScore,
                        photo: photo
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Use status_color from response, default to success
                    const color = result.status_color || 'success';
                    showModal(true, 'Berhasil!', result.message, color);
                    updateTodayStatus(result.attendance);
                } else {
                    showModal(false, 'Gagal', result.message, 'danger');
                }
            } catch (error) {
                console.error('Error:', error);
                showModal(false, 'Error', 'Terjadi kesalahan. Silakan coba lagi.', 'danger');
            } finally {
                isProcessing = false;
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        }

        // Show result modal
        function showModal(success, title, message, color = null) {
            const modal = document.getElementById('resultModal');
            const icon = document.getElementById('modalIcon');
            const titleEl = document.getElementById('modalTitle');
            const messageEl = document.getElementById('modalMessage');

            // Determine color class
            let colorClass = color;
            if (!colorClass) {
                colorClass = success ? 'success' : 'danger';
            }

            // Map error to danger ensures compatibility
            if (colorClass === 'error') colorClass = 'danger';

            icon.className = `modal-icon ${colorClass}`;
            
            // Icon content: check for success, times for error/danger
            // But we might want 'check' for late/early (which are success=true)
            if (success) {
                icon.innerHTML = '<i class="fas fa-check"></i>';
            } else {
                icon.innerHTML = '<i class="fas fa-times"></i>';
            }

            titleEl.textContent = title;
            messageEl.textContent = message;
            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('resultModal').classList.remove('active');
        }

        // Update today's status display
        function updateTodayStatus(attendance) {
            const typeMap = {
                'clock_in': 'clockInTime',
                'clock_out': 'clockOutTime',
                'break_start': 'breakStartTime',
                'break_end': 'breakEndTime'
            };

            const elementId = typeMap[attendance.type.replace(/([A-Z])/g, '_$1').toLowerCase()];
            if (elementId) {
                const el = document.getElementById(elementId);
                if (el) {
                    el.textContent = attendance.time;
                    el.classList.remove('pending');
                }
            }
        }

        // Event listeners
        btnClockIn.addEventListener('click', () => submitAttendance('clock_in'));
        btnClockOut.addEventListener('click', () => submitAttendance('clock_out'));
        btnBreakStart.addEventListener('click', () => submitAttendance('break_start'));
        btnBreakEnd.addEventListener('click', () => submitAttendance('break_end'));

        // Initialize
        document.addEventListener('DOMContentLoaded', loadModels);
    </script>

    <style>
        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</body>
</html>
