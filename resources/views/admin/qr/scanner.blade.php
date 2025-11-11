@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">QR Code Scanner</h1>
                    <p class="text-sm text-slate-500 mt-1">Scan guest QR codes to generate booking confirmation vouchers</p>
                </div>
            </div>

            <!-- Scanner Interface -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-6">
                    <!-- Page Status -->
                    <div id="page-status" class="mb-4 inline-flex items-center gap-2 rounded-full bg-slate-50 ring-1 ring-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                        <span id="page-status-dot" class="h-2 w-2 rounded-full bg-slate-400"></span>
                        <span id="page-status-text">Idle</span>
                    </div>

                    <!-- Feedback Banner -->
                    <div id="feedback-banner" class="hidden mb-4 rounded-xl border px-4 py-3">
                        <div class="flex items-start gap-3">
                            <div id="feedback-icon" class="mt-0.5 h-5 w-5"></div>
                            <div class="flex-1">
                                <p id="feedback-title" class="text-sm font-semibold"></p>
                                <p id="feedback-message" class="text-xs mt-0.5"></p>
                            </div>
                            <button id="feedback-dismiss" type="button" class="text-xs text-slate-500 hover:text-slate-700">Dismiss</button>
                        </div>
                    </div>
                    <!-- Camera Section -->
                    <div class="mb-6">
                        <div class="flex flex-col gap-4">
                            <!-- Camera Scanner -->
                            <div class="w-full">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Camera Scanner</label>
                                <div id="scanner-container" class="relative bg-slate-100 rounded-xl overflow-hidden"
                                    style="height: 300px;">
                                    <video id="qr-video" class="w-full h-full object-cover" autoplay muted
                                        playsinline></video>
                                    <div id="qr-overlay"
                                        class="absolute inset-0 border-2 border-teal-500 rounded-xl pointer-events-none">
                                        <div
                                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-white rounded-xl">
                                        </div>
                                    </div>
                                    <div id="scanner-status"
                                        class="absolute bottom-4 left-4 right-4 bg-black/75 text-white px-3 py-2 rounded text-sm">
                                        Initializing camera...
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-center items-center gap-3 flex-wrap">
                                    <button id="start-scan"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                                        Start Scanning
                                    </button>
                                    <button id="stop-scan"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 text-sm font-semibold shadow-sm transition-all duration-200 disabled:opacity-60"
                                        disabled>
                                        Stop Scanning
                                    </button>
                                    <button id="open-input-modal"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-200">
                                        <i class="fas fa-keyboard"></i>
                                        Enter Code / Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal: Manual Entry & Upload -->
                    <div id="input-modal" class="hidden fixed inset-0 z-50">
                        <div id="input-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
                        <div class="relative mx-auto mt-16 w-full max-w-lg px-4">
                            <div class="rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200 overflow-hidden">
                                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                                    <div>
                                        <h3 class="text-base font-semibold text-slate-900">Enter Code or Upload Image</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">Manually input a reservation code or upload a QR image.</p>
                                    </div>
                                    <button id="close-input-modal" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-500 hover:text-slate-700 hover:bg-slate-100">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="px-5 py-4">
                                    <div class="mb-3">
                                        <div class="inline-flex rounded-xl bg-slate-100 p-1">
                                            <button id="tab-manual" type="button" class="tab-btn active inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white shadow-sm">
                                                <i class="fas fa-keyboard"></i>
                                                Manual
                                            </button>
                                            <button id="tab-upload" type="button" class="tab-btn inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                                <i class="fas fa-image"></i>
                                                Upload
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Manual Panel -->
                                    <div id="panel-manual" class="space-y-3">
                                        <label class="block text-sm font-medium text-slate-700">Manual Entry</label>
                                        <input type="text" id="manual-qr" placeholder="Enter reservation code or ID"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                                        <p class="text-xs text-slate-500">
                                            Tip: Enter the reservation code (e.g., LG-ABC12345) or booking ID number only. Avoid spaces or extra characters.
                                        </p>
                                        <button id="manual-submit"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-200">
                                            <i class="fas fa-arrow-right"></i>
                                            Process Code
                                        </button>
                                    </div>
                                    <!-- Upload Panel -->
                                    <div id="panel-upload" class="hidden space-y-3">
                                        <label class="block text-sm font-medium text-slate-700">Upload QR Code Image</label>
                                        <input type="file" id="qr-upload" accept="image/*"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                                        <p class="text-xs text-slate-500">
                                            Use a clear, well-lit photo or screenshot of the QR code (JPG/PNG, minimum ~600px). Keep the code flat, in focus, and avoid glare or cropping.
                                        </p>
                                        <div id="upload-preview-wrap" class="hidden">
                                            <div class="mt-1 flex items-center gap-3">
                                                <img id="upload-preview" class="h-16 w-16 rounded-lg object-cover ring-1 ring-slate-200" alt="Preview">
                                                <div class="text-xs text-slate-600">
                                                    <p id="upload-filename" class="font-semibold"></p>
                                                    <p id="upload-filesize"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <button id="upload-submit"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-200">
                                            <i class="fas fa-magic"></i>
                                            Process Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Results Section -->
                    <div id="results-section" class="hidden">
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Booking Details</h3>

                            <!-- Decoded QR preview -->
                            <div id="qr-preview" class="hidden mb-4 rounded-xl bg-slate-50 ring-1 ring-slate-200 p-3">
                                <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-1">Decoded QR</div>
                                <div id="qr-preview-text" class="text-sm text-slate-800 break-all"></div>
                            </div>

                            <!-- Booking Info Card -->
                            <div id="booking-card"
                                class="rounded-xl p-4 mb-4 bg-gradient-to-br from-[#5f9ea0] to-[#99e6b3] text-white shadow-md">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-widest opacity-90">Guest Name
                                        </div>
                                        <div id="guest-name" class="text-lg font-bold">-</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-widest opacity-90">Reservation
                                            Code</div>
                                        <div id="reservation-code" class="text-lg font-bold">-</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-widest opacity-90">Room</div>
                                        <div id="room-name" class="text-lg font-bold">-</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-widest opacity-90">Check-in
                                            Date</div>
                                        <div id="check-in-date" class="text-lg font-bold">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                <a id="generate-pdf-btn" href="#" target="_blank"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Generate PDF Voucher
                                </a>
                                <a id="preview-pdf-btn" href="#" target="_blank"
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 text-sm font-semibold shadow-sm transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Preview PDF
                                </a>
                                <a id="checkin-btn" href="#"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Check-in Guest
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="hidden mt-4 p-4 bg-rose-50 border border-rose-200 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-rose-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span id="error-text" class="text-rose-700"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QR Code Scanner Script -->
    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('qr-video');
            const scannerContainer = document.getElementById('scanner-container');
            const scannerStatus = document.getElementById('scanner-status');
            const startScanBtn = document.getElementById('start-scan');
            const stopScanBtn = document.getElementById('stop-scan');
            const manualQrInput = document.getElementById('manual-qr');
            const manualSubmitBtn = document.getElementById('manual-submit');
            const resultsSection = document.getElementById('results-section');
            const errorMessage = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            const pageStatus = document.getElementById('page-status');
            const pageStatusDot = document.getElementById('page-status-dot');
            const pageStatusText = document.getElementById('page-status-text');
            const feedbackBanner = document.getElementById('feedback-banner');
            const feedbackIcon = document.getElementById('feedback-icon');
            const feedbackTitle = document.getElementById('feedback-title');
            const feedbackMessage = document.getElementById('feedback-message');
            const feedbackDismiss = document.getElementById('feedback-dismiss');
            const tabManual = document.getElementById('tab-manual');
            const tabUpload = document.getElementById('tab-upload');
            const panelManual = document.getElementById('panel-manual');
            const panelUpload = document.getElementById('panel-upload');
            const openInputModalBtn = document.getElementById('open-input-modal');
            const inputModal = document.getElementById('input-modal');
            const inputModalBackdrop = document.getElementById('input-modal-backdrop');
            const closeInputModalBtn = document.getElementById('close-input-modal');
            const qrPreview = document.getElementById('qr-preview');
            const qrPreviewText = document.getElementById('qr-preview-text');

            let codeReader = null;
            let isScanning = false;

            // Initialize ZXing QR Code Reader
            function initQRReader() {
                if (!codeReader) {
                    codeReader = new ZXing.BrowserQRCodeReader();
                }
            }

            // UI helpers
            function updatePageStatus(status, message) {
                pageStatusText.textContent = message || status;
                const map = {
                    idle: 'bg-slate-400',
                    info: 'bg-teal-500',
                    success: 'bg-emerald-500',
                    warn: 'bg-amber-500',
                    error: 'bg-rose-500'
                };
                pageStatusDot.className = 'h-2 w-2 rounded-full ' + (map[status] || map.idle);
            }

            function showFeedback(type, title, message) {
                const styles = {
                    success: { border: 'border-emerald-200', bg: 'bg-emerald-50', icon: '<i class="fas fa-check-circle text-emerald-600"></i>' },
                    error: { border: 'border-rose-200', bg: 'bg-rose-50', icon: '<i class="fas fa-times-circle text-rose-600"></i>' },
                    info: { border: 'border-blue-200', bg: 'bg-blue-50', icon: '<i class="fas fa-info-circle text-blue-600"></i>' }
                }[type] || { border: 'border-slate-200', bg: 'bg-slate-50', icon: '<i class="fas fa-info-circle text-slate-600"></i>' };

                feedbackBanner.className = `mb-4 rounded-xl border ${styles.border} ${styles.bg} px-4 py-3`;
                feedbackIcon.innerHTML = styles.icon;
                feedbackTitle.textContent = title || '';
                feedbackMessage.textContent = message || '';
                feedbackBanner.classList.remove('hidden');
            }

            function hideFeedback() {
                feedbackBanner.classList.add('hidden');
            }

            feedbackDismiss.addEventListener('click', hideFeedback);

            // Tabs
            function activateTab(which) {
                const active = which === 'upload' ? tabUpload : tabManual;
                const inactive = which === 'upload' ? tabManual : tabUpload;
                const activePanel = which === 'upload' ? panelUpload : panelManual;
                const inactivePanel = which === 'upload' ? panelManual : panelUpload;

                active.classList.add('bg-white', 'shadow-sm', 'text-slate-700');
                active.classList.remove('text-slate-600');
                inactive.classList.remove('bg-white', 'shadow-sm', 'text-slate-700');
                inactive.classList.add('text-slate-600');

                activePanel.classList.remove('hidden');
                inactivePanel.classList.add('hidden');
            }

            tabManual.addEventListener('click', () => activateTab('manual'));
            tabUpload.addEventListener('click', () => activateTab('upload'));
            // default
            activateTab('manual');

            // Modal controls
            function openInputModal() {
                inputModal.classList.remove('hidden');
                setTimeout(() => {
                    const el = document.getElementById('manual-qr');
                    if (el) el.focus();
                }, 0);
            }
            function closeInputModal() {
                inputModal.classList.add('hidden');
            }
            openInputModalBtn.addEventListener('click', openInputModal);
            closeInputModalBtn.addEventListener('click', closeInputModal);
            inputModalBackdrop.addEventListener('click', closeInputModal);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !inputModal.classList.contains('hidden')) {
                    closeInputModal();
                }
            });

            // Start scanning
            startScanBtn.addEventListener('click', async function() {
                if (isScanning) return;

                try {
                    initQRReader();
                    scannerStatus.textContent = 'Starting camera...';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-teal-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('info', 'Starting camera...');

                    const result = await codeReader.decodeFromVideoDevice(null, 'qr-video', (result,
                        err) => {
                        if (result) {
                            processQRCode(result.text);
                        }
                        if (err && !(err instanceof ZXing.NotFoundException)) {
                            console.error(err);
                        }
                    });

                    isScanning = true;
                    startScanBtn.disabled = true;
                    stopScanBtn.disabled = false;
                    scannerStatus.textContent = 'Scanning... Point camera at QR code';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-emerald-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('success', 'Scanner active');

                } catch (err) {
                    console.error('Error starting scanner:', err);
                    scannerStatus.textContent = 'Error: ' + err.message;
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-rose-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('error', 'Camera error');
                    showFeedback('error', 'Camera Error', err.message || 'Unable to start camera');
                }
            });

            // Stop scanning
            stopScanBtn.addEventListener('click', function() {
                if (codeReader && isScanning) {
                    codeReader.reset();
                    isScanning = false;
                    startScanBtn.disabled = false;
                    stopScanBtn.disabled = true;
                    scannerStatus.textContent = 'Scanner stopped';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-slate-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('idle', 'Scanner stopped');
                }
            });

            // Manual QR code submission
            manualSubmitBtn.addEventListener('click', function() {
                const qrCode = manualQrInput.value.trim();
                if (qrCode) {
                    qrPreviewText.textContent = qrCode;
                    qrPreview.classList.remove('hidden');
                    updatePageStatus('info', 'Processing manual input...');
                    processQRCode(qrCode);
                }
            });

            manualQrInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    manualSubmitBtn.click();
                }
            });

            // QR code image upload processing
            const uploadSubmitBtn = document.getElementById('upload-submit');
            const qrUploadInput = document.getElementById('qr-upload');
            const uploadPreviewWrap = document.getElementById('upload-preview-wrap');
            const uploadPreview = document.getElementById('upload-preview');
            const uploadFilename = document.getElementById('upload-filename');
            const uploadFilesize = document.getElementById('upload-filesize');

            qrUploadInput.addEventListener('change', function() {
                const file = qrUploadInput.files[0];
                if (!file) {
                    uploadPreviewWrap.classList.add('hidden');
                    return;
                }
                uploadFilename.textContent = file.name;
                uploadFilesize.textContent = Math.round(file.size / 1024) + ' KB';
                const url = URL.createObjectURL(file);
                uploadPreview.src = url;
                uploadPreviewWrap.classList.remove('hidden');
            });

            uploadSubmitBtn.addEventListener('click', async function() {
                const file = qrUploadInput.files[0];
                if (!file) {
                    showError('Please select a QR code image to upload.');
                    showFeedback('error', 'No Image Selected', 'Please choose an image containing a QR code.');
                    updatePageStatus('error', 'Upload missing image');
                    return;
                }

                try {
                    // Initialize codeReader if not already done
                    initQRReader();

                    scannerStatus.textContent = 'Processing uploaded image...';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-teal-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('info', 'Decoding uploaded image...');

                    // Create an image element to load the file
                    const img = new Image();
                    const url = URL.createObjectURL(file);

                    img.onload = async function() {
                        try {
                            const result = await codeReader.decodeFromImage(img);
                            URL.revokeObjectURL(url); // Clean up the blob URL
                            qrPreviewText.textContent = result.text || '';
                            qrPreview.classList.remove('hidden');
                            processQRCode(result.text);
                        } catch (decodeError) {
                            URL.revokeObjectURL(url);
                            console.error('Error decoding QR code from image:', decodeError);
                            showError('No QR code found in the uploaded image.');
                            scannerStatus.textContent = 'No QR code detected';
                            scannerStatus.className =
                                'absolute bottom-4 left-4 right-4 bg-rose-500/75 text-white px-3 py-2 rounded text-sm';
                            updatePageStatus('error', 'No QR code detected');
                            showFeedback('error', 'Decoding Failed', 'No QR code found in the uploaded image.');
                        }
                    };

                    img.onerror = function() {
                        URL.revokeObjectURL(url);
                        showError('Failed to load the uploaded image. Please try again.');
                        scannerStatus.textContent = 'Error loading image';
                        scannerStatus.className =
                            'absolute bottom-4 left-4 right-4 bg-rose-500/75 text-white px-3 py-2 rounded text-sm';
                        updatePageStatus('error', 'Error loading image');
                        showFeedback('error', 'Image Error', 'Failed to load the uploaded image.');
                    };

                    img.src = url;

                } catch (error) {
                    console.error('Error processing uploaded image:', error);
                    showError('Failed to process the uploaded image. Please try again.');
                    scannerStatus.textContent = 'Error processing image';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-rose-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('error', 'Error processing image');
                    showFeedback('error', 'Processing Error', 'Failed to process the uploaded image.');
                }
            });

            // Process QR code
            async function processQRCode(qrCode) {
                try {
                    // Stop scanning if active
                    if (isScanning) {
                        stopScanBtn.click();
                    }

                    scannerStatus.textContent = 'Processing QR code...';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-teal-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('info', 'Processing QR code...');

                    const response = await fetch('/admin/qr-scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            qr_code: qrCode
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        displayBookingDetails(data.booking);
                        scannerStatus.textContent = 'QR code processed successfully!';
                        scannerStatus.className =
                            'absolute bottom-4 left-4 right-4 bg-emerald-500/75 text-white px-3 py-2 rounded text-sm';
                        updatePageStatus('success', 'QR processed successfully');
                        showFeedback('success', 'Booking Found', 'Voucher links are now available below.');
                    } else {
                        showError(data.message || 'Invalid QR code');
                        scannerStatus.textContent = 'QR code not recognized';
                        scannerStatus.className =
                            'absolute bottom-4 left-4 right-4 bg-rose-500/75 text-white px-3 py-2 rounded text-sm';
                        updatePageStatus('error', 'QR not recognized');
                        showFeedback('error', 'Invalid QR', data.message || 'Please verify the code and try again.');
                    }

                } catch (error) {
                    console.error('Error processing QR code:', error);
                    showError('Failed to process QR code. Please try again.');
                    scannerStatus.textContent = 'Error processing QR code';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-rose-500/75 text-white px-3 py-2 rounded text-sm';
                    updatePageStatus('error', 'Error processing QR code');
                    showFeedback('error', 'Processing Error', 'An unexpected error occurred while processing the QR code.');
                }
            }

            // Display booking details
            function displayBookingDetails(booking) {
                document.getElementById('guest-name').textContent = `${booking.firstname} ${booking.lastname}`;
                document.getElementById('reservation-code').textContent = booking.reservation_number || booking.id;
                document.getElementById('room-name').textContent = booking.room ? booking.room.name : 'N/A';
                document.getElementById('check-in-date').textContent = new Date(booking.check_in)
                    .toLocaleDateString();

                // Update PDF links
                document.getElementById('generate-pdf-btn').href = `/admin/qr-pdf/${booking.id}`;
                document.getElementById('preview-pdf-btn').href = `/admin/qr-preview/${booking.id}`;
                document.getElementById('checkin-btn').href = `/admin/checkin/${booking.id}`;

                resultsSection.classList.remove('hidden');
                errorMessage.classList.add('hidden');
            }

            // Show error message
            function showError(message) {
                errorText.textContent = message;
                errorMessage.classList.remove('hidden');
                resultsSection.classList.add('hidden');
            }

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                if (codeReader) {
                    codeReader.reset();
                }
            });
        });
    </script>
@endsection
