@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">QR Code Scanner</h1>
                    <p class="text-sm text-slate-500 mt-1">Scan guest QR codes to update booking status</p>
                </div>
            </div>

            <!-- Scanner Interface -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-6">

                    <!-- Page Status -->
                    <div id="page-status"
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-slate-50 ring-1 ring-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                        <span id="page-status-dot" class="h-2 w-2 rounded-full bg-slate-400"></span>
                        <span id="page-status-text">Idle</span>
                    </div>

                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Checked-in!',
                                text: "{{ session('success') }}",
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        </script>
                    @endif

                    @if (session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: "{{ session('error') }}",
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        </script>
                    @endif


                    <!-- Camera Section -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Camera Scanner
                        </label>

                        <!-- Centered Scanner -->
                        <div class="flex justify-center">
                            <div id="scanner-container" class="relative bg-slate-100 rounded-xl overflow-hidden"
                                style="height: 300px; width:400px;">

                                <video id="qr-video" class="w-full h-full object-cover -scale-x-100" autoplay muted
                                    playsinline>
                                </video>

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
                        </div>

                        <!-- Buttons below the scanner -->
                        <div class="mt-2 flex justify-center items-center gap-3 flex-wrap">
                            <button id="start-scan"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                                Start Scanning</button>
                            <button id="stop-scan"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 text-sm font-semibold shadow-sm transition-all duration-200 disabled:opacity-60"
                                disabled>
                                Stop Scanning</button>
                            <button id="open-input-modal"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-200">
                                <i class="fas fa-keyboard"></i> Enter Code / Upload
                            </button>
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
                                        <p class="text-xs text-slate-500 mt-0.5">Manually input a reservation code or upload
                                            a QR image.</p>
                                    </div>
                                    <button id="close-input-modal"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-500 hover:text-slate-700 hover:bg-slate-100">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="px-5 py-4">
                                    <div class="mb-3">
                                        <div class="inline-flex rounded-xl bg-slate-100 p-1">
                                            <button id="tab-manual" type="button"
                                                class="tab-btn active inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white shadow-sm">
                                                <i class="fas fa-keyboard"></i> Manual
                                            </button>
                                            <button id="tab-upload" type="button"
                                                class="tab-btn inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                                <i class="fas fa-image"></i> Upload
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Manual Panel -->
                                    <div id="panel-manual" class="space-y-3">
                                        <form action="{{ route('admin.qr.scan') }}" method="POST">
                                            @csrf
                                            <label class="block text-sm font-medium text-slate-700">Manual Entry</label>
                                            <input type="text" name="qr_code" placeholder="Enter reservation code or ID"
                                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                                            <p class="text-xs text-slate-500">
                                                Tip: Enter the reservation code (e.g., LG-ABC12345) or booking ID number
                                                only. Avoid spaces or extra characters.
                                            </p>
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-200">
                                                <i class="fas fa-arrow-right"></i> Process Code
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Upload Panel -->
                                    <div id="panel-upload" class="hidden space-y-3">
                                        <form action="{{ route('admin.qr.scan') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <label class="block text-sm font-medium text-slate-700">Upload QR Code
                                                Image</label>
                                            <input type="file" name="qr_image" accept="image/*"
                                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm"
                                                required>
                                            <p class="text-xs text-slate-500">
                                                Use a clear, well-lit photo or screenshot of the QR code (JPG/PNG, minimum
                                                ~600px). Keep the code flat, in focus, and avoid glare or cropping.
                                            </p>
                                            <div id="upload-preview-wrap" class="hidden">
                                                <div class="mt-1 flex items-center gap-3">
                                                    <img id="upload-preview"
                                                        class="h-16 w-16 rounded-lg object-cover ring-1 ring-slate-200"
                                                        alt="Preview">
                                                    <div class="text-xs text-slate-600">
                                                        <p id="upload-filename" class="font-semibold"></p>
                                                        <p id="upload-filesize"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-200">
                                                <i class="fas fa-magic"></i> Process Image
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    @if (isset($booking))
                        <div id="results-section">
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold text-slate-900 mb-4">Booking Details</h3>
                                <div id="qr-preview" class="mb-4 rounded-xl bg-slate-50 ring-1 ring-slate-200 p-3">
                                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-1">
                                        Decoded QR</div>
                                    <div id="qr-preview-text" class="text-sm text-slate-800 break-all">
                                        {{ $booking->reservation_number }}</div>
                                </div>

                                <div
                                    class="rounded-xl p-4 mb-4 bg-gradient-to-br from-[#5f9ea0] to-[#99e6b3] text-white shadow-md">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold uppercase tracking-widest opacity-90">Guest
                                                Name</div>
                                            <div id="guest-name" class="text-lg font-bold">{{ $booking->guest_name }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold uppercase tracking-widest opacity-90">
                                                Reservation Code</div>
                                            <div id="reservation-code" class="text-lg font-bold">
                                                {{ $booking->reservation_number }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold uppercase tracking-widest opacity-90">Room
                                            </div>
                                            <div id="room-name" class="text-lg font-bold">
                                                {{ $booking->room->name ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold uppercase tracking-widest opacity-90">
                                                Check-in Date</div>
                                            <div id="check-in-date" class="text-lg font-bold">
                                                {{ $booking->check_in_date }}</div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('admin.guest-stays.index', $booking->id) }}"
                                    class="btn-primary inline-flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Check-in Guest
                                </a>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('qr-video');
            const startScanBtn = document.getElementById('start-scan');
            const stopScanBtn = document.getElementById('stop-scan');
            const openInputModalBtn = document.getElementById('open-input-modal');
            const inputModal = document.getElementById('input-modal');
            const closeInputModalBtn = document.getElementById('close-input-modal');
            const inputModalBackdrop = document.getElementById('input-modal-backdrop');
            const tabManual = document.getElementById('tab-manual');
            const tabUpload = document.getElementById('tab-upload');
            const panelManual = document.getElementById('panel-manual');
            const panelUpload = document.getElementById('panel-upload');

            let codeReader = null;
            let isScanning = false;

            function initQRReader() {
                if (!codeReader) codeReader = new ZXing.BrowserQRCodeReader();
            }

            startScanBtn.addEventListener('click', async () => {
                initQRReader();
                if (isScanning) return;
                isScanning = true;
                startScanBtn.disabled = true;
                stopScanBtn.disabled = false;
                try {
                    const deviceId = (await codeReader.listVideoInputDevices())[0]?.deviceId;
                    codeReader.decodeFromVideoDevice(deviceId, video, (result) => {
                        if (result) {
                            fetch('{{ route('admin.qr.scan') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        qr_code: result.text
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Checked-in!',
                                            'Guest has been checked-in', 'success');
                                    } else {
                                        Swal.fire('Error', data.message ||
                                            'Booking not found', 'error');
                                    }
                                });
                            // optionally, redirect to manual route
                            codeReader.reset();
                            isScanning = false;
                            startScanBtn.disabled = false;
                            stopScanBtn.disabled = true;
                        }
                    });
                } catch (e) {
                    alert(e.message);
                }
            });

            stopScanBtn.addEventListener('click', () => {
                if (codeReader) {
                    codeReader.reset();
                    isScanning = false;
                    startScanBtn.disabled = false;
                    stopScanBtn.disabled = true;
                }
            });

            openInputModalBtn.addEventListener('click', () => inputModal.classList.remove('hidden'));
            closeInputModalBtn.addEventListener('click', () => inputModal.classList.add('hidden'));
            inputModalBackdrop.addEventListener('click', () => inputModal.classList.add('hidden'));

            tabManual.addEventListener('click', () => {
                tabManual.classList.add('bg-white');
                panelManual.classList.remove('hidden');
                panelUpload.classList.add('hidden');
                tabUpload.classList.remove('bg-white');
            });
            tabUpload.addEventListener('click', () => {
                tabUpload.classList.add('bg-white');
                panelUpload.classList.remove('hidden');
                panelManual.classList.add('hidden');
                tabManual.classList.remove('bg-white');
            });
        });
    </script>
@endsection
