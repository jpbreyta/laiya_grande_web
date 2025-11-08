@extends('admin.layouts.app')

@section('content')
    <section class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">QR Code Scanner</h1>
                <p class="text-gray-600">Scan guest QR codes to generate booking confirmation vouchers</p>
            </div>

            <!-- Scanner Interface -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <!-- Camera/Upload Section -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <!-- Camera Scanner -->
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Camera Scanner</label>
                                <div id="scanner-container" class="relative bg-gray-100 rounded-lg overflow-hidden"
                                    style="height: 300px;">
                                    <video id="qr-video" class="w-full h-full object-cover" autoplay muted
                                        playsinline></video>
                                    <div id="qr-overlay"
                                        class="absolute inset-0 border-2 border-blue-500 rounded-lg pointer-events-none">
                                        <div
                                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-white rounded-lg">
                                        </div>
                                    </div>
                                    <div id="scanner-status"
                                        class="absolute bottom-4 left-4 right-4 bg-black/75 text-white px-3 py-2 rounded text-sm">
                                        Initializing camera...
                                    </div>
                                </div>
                                <div class="mt-2 flex gap-2">
                                    <button id="start-scan"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Start Scanning
                                    </button>
                                    <button id="stop-scan"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
                                        disabled>
                                        Stop Scanning
                                    </button>
                                </div>
                            </div>

                            <!-- Manual Input & Upload -->
                            <div class="md:w-80">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Manual Entry</label>
                                <div class="space-y-3">
                                    <input type="text" id="manual-qr" placeholder="Enter reservation code or ID"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button id="manual-submit"
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        Process Code
                                    </button>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload QR Code Image</label>
                                    <input type="file" id="qr-upload" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button id="upload-submit"
                                        class="w-full mt-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                        Process Image
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div id="results-section" class="hidden">
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>

                            <!-- Booking Info Card -->
                            <div id="booking-card"
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm font-medium text-blue-600 uppercase tracking-wider">Guest Name
                                        </div>
                                        <div id="guest-name" class="text-lg font-semibold text-gray-900">-</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-blue-600 uppercase tracking-wider">Reservation
                                            Code</div>
                                        <div id="reservation-code" class="text-lg font-semibold text-gray-900">-</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-blue-600 uppercase tracking-wider">Room</div>
                                        <div id="room-name" class="text-lg font-semibold text-gray-900">-</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-blue-600 uppercase tracking-wider">Check-in
                                            Date</div>
                                        <div id="check-in-date" class="text-lg font-semibold text-gray-900">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                <a id="generate-pdf-btn" href="#" target="_blank"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Generate PDF Voucher
                                </a>
                                <a id="preview-pdf-btn" href="#" target="_blank"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Preview PDF
                                </a>
                                <a id="checkin-btn" href="#"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
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
                    <div id="error-message" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span id="error-text" class="text-red-700"></span>
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

            let codeReader = null;
            let isScanning = false;

            // Initialize ZXing QR Code Reader
            function initQRReader() {
                if (!codeReader) {
                    codeReader = new ZXing.BrowserQRCodeReader();
                }
            }

            // Start scanning
            startScanBtn.addEventListener('click', async function() {
                if (isScanning) return;

                try {
                    initQRReader();
                    scannerStatus.textContent = 'Starting camera...';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-blue-500/75 text-white px-3 py-2 rounded text-sm';

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
                        'absolute bottom-4 left-4 right-4 bg-green-500/75 text-white px-3 py-2 rounded text-sm';

                } catch (err) {
                    console.error('Error starting scanner:', err);
                    scannerStatus.textContent = 'Error: ' + err.message;
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-red-500/75 text-white px-3 py-2 rounded text-sm';
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
                        'absolute bottom-4 left-4 right-4 bg-gray-500/75 text-white px-3 py-2 rounded text-sm';
                }
            });

            // Manual QR code submission
            manualSubmitBtn.addEventListener('click', function() {
                const qrCode = manualQrInput.value.trim();
                if (qrCode) {
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

            uploadSubmitBtn.addEventListener('click', async function() {
                const file = qrUploadInput.files[0];
                if (!file) {
                    showError('Please select a QR code image to upload.');
                    return;
                }

                try {
                    // Initialize codeReader if not already done
                    initQRReader();

                    scannerStatus.textContent = 'Processing uploaded image...';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-blue-500/75 text-white px-3 py-2 rounded text-sm';

                    // Create an image element to load the file
                    const img = new Image();
                    const url = URL.createObjectURL(file);

                    img.onload = async function() {
                        try {
                            const result = await codeReader.decodeFromImage(img);
                            URL.revokeObjectURL(url); // Clean up the blob URL
                            processQRCode(result.text);
                        } catch (decodeError) {
                            URL.revokeObjectURL(url);
                            console.error('Error decoding QR code from image:', decodeError);
                            showError('No QR code found in the uploaded image.');
                            scannerStatus.textContent = 'No QR code detected';
                            scannerStatus.className =
                                'absolute bottom-4 left-4 right-4 bg-red-500/75 text-white px-3 py-2 rounded text-sm';
                        }
                    };

                    img.onerror = function() {
                        URL.revokeObjectURL(url);
                        showError('Failed to load the uploaded image. Please try again.');
                        scannerStatus.textContent = 'Error loading image';
                        scannerStatus.className =
                            'absolute bottom-4 left-4 right-4 bg-red-500/75 text-white px-3 py-2 rounded text-sm';
                    };

                    img.src = url;

                } catch (error) {
                    console.error('Error processing uploaded image:', error);
                    showError('Failed to process the uploaded image. Please try again.');
                    scannerStatus.textContent = 'Error processing image';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-red-500/75 text-white px-3 py-2 rounded text-sm';
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
                        'absolute bottom-4 left-4 right-4 bg-blue-500/75 text-white px-3 py-2 rounded text-sm';

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
                            'absolute bottom-4 left-4 right-4 bg-green-500/75 text-white px-3 py-2 rounded text-sm';
                    } else {
                        showError(data.message || 'Invalid QR code');
                        scannerStatus.textContent = 'QR code not recognized';
                        scannerStatus.className =
                            'absolute bottom-4 left-4 right-4 bg-red-500/75 text-white px-3 py-2 rounded text-sm';
                    }

                } catch (error) {
                    console.error('Error processing QR code:', error);
                    showError('Failed to process QR code. Please try again.');
                    scannerStatus.textContent = 'Error processing QR code';
                    scannerStatus.className =
                        'absolute bottom-4 left-4 right-4 bg-red-500/75 text-white px-3 py-2 rounded text-sm';
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
