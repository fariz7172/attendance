@extends('layouts.admin')

@section('title', 'Registrasi Wajah')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.employees.index') }}">Karyawan</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Registrasi Wajah</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Registrasi Wajah</h1>
        <p class="page-description">Daftarkan wajah {{ $employee->name }} untuk sistem absensi</p>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Camera Section -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-camera" style="color: var(--color-primary); margin-right: 8px;"></i>
                        Kamera
                    </h3>
                    <div id="modelStatus" style="display: flex; align-items: center; gap: 8px;">
                        <div class="loading-spinner"></div>
                        <span style="font-size: 13px; color: var(--color-text-muted);">Memuat model AI...</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="cameraContainer" style="position: relative; width: 100%; max-width: 640px; margin: 0 auto; border-radius: var(--border-radius); overflow: hidden; background: #000;">
                        <video id="video" autoplay muted playsinline style="width: 100%; display: block;"></video>
                        <canvas id="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
                        
                        <!-- Face Detection Box -->
                        <div id="faceBox" style="display: none; position: absolute; border: 3px solid var(--color-success); border-radius: 8px; transition: all 0.1s;"></div>
                        
                        <!-- Status Overlay -->
                        <div id="statusOverlay" style="position: absolute; bottom: 16px; left: 16px; right: 16px; background: rgba(0,0,0,0.7); color: white; padding: 12px 16px; border-radius: var(--border-radius); display: none;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div id="statusIcon"><i class="fas fa-spinner fa-spin"></i></div>
                                <div>
                                    <div id="statusTitle" style="font-weight: 600;">Mendeteksi wajah...</div>
                                    <div id="statusDesc" style="font-size: 12px; opacity: 0.8;">Posisikan wajah Anda di tengah kamera</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div id="instructions" style="margin-top: 20px; padding: 16px; background: var(--color-bg-primary); border-radius: var(--border-radius);">
                        <h4 style="margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-info-circle" style="color: var(--color-primary);"></i>
                            Petunjuk Registrasi
                        </h4>
                        <ul style="margin: 0; padding-left: 20px; color: var(--color-text-secondary); font-size: 14px;">
                            <li>Pastikan wajah terlihat jelas di kamera</li>
                            <li>Posisikan wajah di tengah frame</li>
                            <li>Hindari latar belakang yang terlalu terang</li>
                            <li>Lepaskan kacamata jika memungkinkan</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div style="margin-top: 20px; display: flex; gap: 12px; justify-content: center;">
                        <button id="captureBtn" class="btn btn-primary" disabled style="padding: 14px 32px;">
                            <i class="fas fa-camera"></i>
                            Ambil Foto & Daftarkan
                        </button>
                        <button id="retakeBtn" class="btn btn-secondary" style="display: none; padding: 14px 32px;">
                            <i class="fas fa-redo"></i>
                            Ulangi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Employee Info -->
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 24px;">
                    <div class="avatar" style="width: 80px; height: 80px; margin: 0 auto 16px; font-size: 32px;">
                        @if($employee->face_photo_path)
                            <img src="{{ asset($employee->face_photo_path) }}" alt="{{ $employee->name }}" id="currentPhoto">
                        @else
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        @endif
                    </div>
                    <h3 style="margin-bottom: 4px;">{{ $employee->name }}</h3>
                    <p style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 16px;">
                        {{ $employee->employee_number }}
                    </p>
                    
                    <div id="faceStatusBadge">
                        @if($employee->hasFaceRegistered())
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle" style="margin-right: 4px;"></i>
                                Wajah Sudah Terdaftar
                            </span>
                        @else
                            <span class="badge badge-warning">
                                <i class="fas fa-exclamation-triangle" style="margin-right: 4px;"></i>
                                Wajah Belum Terdaftar
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Capture Preview -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h3 class="card-title">Preview Foto</h3>
                </div>
                <div class="card-body">
                    <div id="previewContainer" style="display: none;">
                        <img id="capturedImage" src="" alt="Captured" style="width: 100%; border-radius: var(--border-radius);">
                        <div id="descriptorInfo" style="margin-top: 12px; padding: 12px; background: var(--color-bg-primary); border-radius: var(--border-radius); font-size: 13px; color: var(--color-text-secondary);">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <i class="fas fa-fingerprint" style="color: var(--color-primary);"></i>
                                <span>Face Descriptor</span>
                            </div>
                            <div id="descriptorStatus">Belum ada data</div>
                        </div>
                    </div>
                    <div id="previewPlaceholder" style="text-align: center; padding: 40px 20px; color: var(--color-text-muted);">
                        <i class="fas fa-image" style="font-size: 48px; margin-bottom: 12px; opacity: 0.3;"></i>
                        <p style="margin: 0;">Foto akan muncul di sini</p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary" style="width: 100%; margin-top: 24px;">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <canvas id="captureCanvas" style="display: none;"></canvas>
@endsection

@push('styles')
<style>
    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid var(--color-bg-tertiary);
        border-top-color: var(--color-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 1024px) {
        .page-content > div:first-of-type {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Face-api.js -->
<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const faceBox = document.getElementById('faceBox');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const captureCanvas = document.getElementById('captureCanvas');
    const capturedImage = document.getElementById('capturedImage');
    const previewContainer = document.getElementById('previewContainer');
    const previewPlaceholder = document.getElementById('previewPlaceholder');
    const modelStatus = document.getElementById('modelStatus');
    const statusOverlay = document.getElementById('statusOverlay');
    const statusTitle = document.getElementById('statusTitle');
    const statusDesc = document.getElementById('statusDesc');
    const statusIcon = document.getElementById('statusIcon');
    const descriptorStatus = document.getElementById('descriptorStatus');
    const faceStatusBadge = document.getElementById('faceStatusBadge');

    let faceDescriptor = null;
    let isCapturing = false;
    let stream = null;

    // Load face-api.js models
    async function loadModels() {
        const MODEL_URL = '/models';
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            modelStatus.innerHTML = '<i class="fas fa-check-circle" style="color: var(--color-success);"></i> <span style="font-size: 13px; color: var(--color-success);">Model AI siap</span>';
            startVideo();
        } catch (error) {
            console.error('Error loading models:', error);
            modelStatus.innerHTML = '<i class="fas fa-exclamation-triangle" style="color: var(--color-danger);"></i> <span style="font-size: 13px; color: var(--color-danger);">Gagal memuat model</span>';
        }
    }

    // Start webcam
    async function startVideo() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user'
                } 
            });
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                overlay.width = video.videoWidth;
                overlay.height = video.videoHeight;
                detectFace();
            };
        } catch (error) {
            console.error('Error accessing camera:', error);
            statusOverlay.style.display = 'block';
            statusIcon.innerHTML = '<i class="fas fa-exclamation-triangle" style="color: var(--color-danger);"></i>';
            statusTitle.textContent = 'Tidak dapat mengakses kamera';
            statusDesc.textContent = 'Pastikan Anda mengizinkan akses kamera';
        }
    }

    // Detect face in real-time
    async function detectFace() {
        if (isCapturing) return;

        const detection = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        const ctx = overlay.getContext('2d');
        ctx.clearRect(0, 0, overlay.width, overlay.height);

        if (detection) {
            const box = detection.detection.box;
            
            // Draw face box
            ctx.strokeStyle = '#22c55e';
            ctx.lineWidth = 3;
            ctx.strokeRect(box.x, box.y, box.width, box.height);
            
            // Draw landmarks
            const landmarks = detection.landmarks;
            ctx.fillStyle = '#22c55e';
            landmarks.positions.forEach(point => {
                ctx.beginPath();
                ctx.arc(point.x, point.y, 2, 0, 2 * Math.PI);
                ctx.fill();
            });

            // Enable capture button
            captureBtn.disabled = false;
            statusOverlay.style.display = 'block';
            statusIcon.innerHTML = '<i class="fas fa-check-circle" style="color: var(--color-success);"></i>';
            statusTitle.textContent = 'Wajah terdeteksi';
            statusDesc.textContent = 'Klik "Ambil Foto" untuk mendaftarkan wajah';
        } else {
            captureBtn.disabled = true;
            statusOverlay.style.display = 'block';
            statusIcon.innerHTML = '<i class="fas fa-search" style="color: var(--color-warning);"></i>';
            statusTitle.textContent = 'Mencari wajah...';
            statusDesc.textContent = 'Posisikan wajah Anda di tengah kamera';
        }

        requestAnimationFrame(detectFace);
    }

    // Capture and register face
    captureBtn.addEventListener('click', async function() {
        if (isCapturing) return;
        isCapturing = true;

        captureBtn.disabled = true;
        captureBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

        try {
            // Capture image
            captureCanvas.width = video.videoWidth;
            captureCanvas.height = video.videoHeight;
            const ctx = captureCanvas.getContext('2d');
            ctx.drawImage(video, 0, 0);

            // Get face descriptor
            const detection = await faceapi
                .detectSingleFace(captureCanvas, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                throw new Error('Wajah tidak terdeteksi');
            }

            faceDescriptor = Array.from(detection.descriptor);
            const imageData = captureCanvas.toDataURL('image/jpeg', 0.8);

            // Show preview
            capturedImage.src = imageData;
            previewContainer.style.display = 'block';
            previewPlaceholder.style.display = 'none';
            descriptorStatus.innerHTML = '<span style="color: var(--color-success);"><i class="fas fa-check"></i> 128 nilai descriptor berhasil dibuat</span>';

            // Stop video stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // Save to server
            const response = await fetch(`{{ route('admin.employees.store-face-descriptor', $employee) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    face_descriptors: [faceDescriptor],
                    face_photo: imageData
                })
            });

            const result = await response.json();

            if (result.success) {
                // Show success
                statusOverlay.style.display = 'block';
                statusIcon.innerHTML = '<i class="fas fa-check-circle" style="color: var(--color-success);"></i>';
                statusTitle.textContent = 'Berhasil!';
                statusDesc.textContent = 'Wajah berhasil didaftarkan';

                faceStatusBadge.innerHTML = '<span class="badge badge-success"><i class="fas fa-check-circle" style="margin-right: 4px;"></i>Wajah Sudah Terdaftar</span>';

                captureBtn.style.display = 'none';
                retakeBtn.style.display = 'inline-flex';
            } else {
                throw new Error(result.message || 'Gagal menyimpan data');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            isCapturing = false;
            captureBtn.disabled = false;
            captureBtn.innerHTML = '<i class="fas fa-camera"></i> Ambil Foto & Daftarkan';
        }
    });

    // Retake photo
    retakeBtn.addEventListener('click', function() {
        isCapturing = false;
        faceDescriptor = null;
        previewContainer.style.display = 'none';
        previewPlaceholder.style.display = 'block';
        captureBtn.style.display = 'inline-flex';
        captureBtn.innerHTML = '<i class="fas fa-camera"></i> Ambil Foto & Daftarkan';
        retakeBtn.style.display = 'none';
        startVideo();
    });

    // Initialize
    loadModels();
});
</script>
@endpush
