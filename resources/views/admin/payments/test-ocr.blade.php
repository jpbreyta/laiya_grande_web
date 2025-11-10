@extends('admin.layouts.app')

@section('content')
    <section class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        OCR Payment Proof Tester
                    </h1>
                    <p class="text-blue-100 mt-1">Test the OCR functionality for processing payment proofs</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Upload Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Upload Payment Proof</h3>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <form id="ocrTestForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="mt-4">
                                                <label for="payment_proof" class="cursor-pointer">
                                                    <span class="mt-2 block text-sm font-medium text-gray-900">Upload
                                                        payment proof image</span>
                                                    <input type="file" id="payment_proof" name="payment_proof"
                                                        accept="image/*" class="hidden" required>
                                                    <span class="mt-1 block text-sm text-gray-500">PNG, JPG, GIF up to
                                                        5MB</span>
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" id="processBtn"
                                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Process with OCR
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Results Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">OCR Results</h3>
                            <div id="resultsContainer" class="bg-gray-50 rounded-lg p-4 min-h-[300px]">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Upload an image to see OCR results</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Instructions
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Expected Data Fields:</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                    <strong>Reference ID:</strong> 10-digit number
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-3"></span>
                                    <strong>Date/Time:</strong> Transaction date and time
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    <strong>Total Amount:</strong> Amount sent (₱)
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Validation Rules:</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    All fields must be present and non-empty
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Reference ID must be exactly 10 digits
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function displayResults(ocrResult, validation) {
            const resultsContainer = document.getElementById('resultsContainer');

            let html = '<div class="space-y-4">';

            // OCR Results Section
            html += '<div class="bg-white rounded-lg p-4 border">';
            html += '<h3 class="font-semibold text-gray-900 mb-3 flex items-center">';
            html += '<svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            html +=
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';
            html += '</svg>OCR Extracted Data</h3>';

            const fields = [{
                    key: 'reference_id',
                    label: 'Reference ID',
                    icon: 'text-blue-500'
                },
                {
                    key: 'date_time',
                    label: 'Date/Time',
                    icon: 'text-orange-500'
                },
                {
                    key: 'total_amount',
                    label: 'Total Amount',
                    icon: 'text-green-500'
                }
            ];

            fields.forEach(field => {
                const value = ocrResult[field.key] || 'Not found';
                const isValid = validation.valid || !validation.errors.some(err => err.includes(field.label));
                html +=
                    `<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">`;
                html += `<span class="font-medium text-gray-700">${field.label}:</span>`;
                html += `<span class="text-gray-900 ${isValid ? '' : 'text-red-600'}">${value}</span>`;
                html += '</div>';
            });

            html += '</div>';

            // Validation Results Section
            html += '<div class="bg-white rounded-lg p-4 border">';
            html += '<h3 class="font-semibold text-gray-900 mb-3 flex items-center">';
            if (validation.valid) {
                html += '<svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                html +=
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                html += '</svg>Validation Passed';
            } else {
                html += '<svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                html +=
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                html += '</svg>Validation Failed';
            }
            html += '</h3>';

            if (validation.errors && validation.errors.length > 0) {
                html += '<ul class="space-y-1">';
                validation.errors.forEach(error => {
                    html += `<li class="text-red-600 text-sm flex items-center">`;
                    html += '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">';
                    html +=
                        '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>';
                    html += '</svg>';
                    html += `${error}</li>`;
                });
                html += '</ul>';
            } else {
                html += '<p class="text-green-600 text-sm">All validation checks passed!</p>';
            }

            html += '</div>';
            html += '</div>';

            resultsContainer.innerHTML = html;
        }

        document.getElementById('ocrTestForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const processBtn = document.getElementById('processBtn');
            const resultsContainer = document.getElementById('resultsContainer');

            // Show loading state
            processBtn.disabled = true;
            processBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing...
    `;
            resultsContainer.innerHTML = `
        <div class="text-center text-gray-600">Processing image with OCR...</div>
    `;

            try {
                const response = await fetch('{{ route('admin.test-process-payment-proof') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                // Reset button
                processBtn.disabled = false;
                processBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Process with OCR
        `;

                if (response.status === 422) {
                    // Validation errors from Laravel
                    resultsContainer.innerHTML = `
                <div class="text-red-600">
                    <h3 class="font-semibold mb-2">Validation Errors</h3>
                    <ul>${data.errors.map(err => `<li>• ${err}</li>`).join('')}</ul>
                </div>
            `;
                } else if (data.success) {
                    displayResults(data.ocr_result, data.validation);
                } else {
                    resultsContainer.innerHTML = `
                <div class="text-red-600">
                    <h3 class="font-semibold mb-2">OCR Failed</h3>
                    <ul>${data.errors.map(err => `<li>• ${err}</li>`).join('')}</ul>
                </div>
            `;
                }

            } catch (err) {
                processBtn.disabled = false;
                processBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Process with OCR
        `;

                resultsContainer.innerHTML = `
            <div class="text-red-600">
                <h3 class="font-semibold mb-2">Network Error</h3>
                <p>${err.message}</p>
            </div>
        `;
            }
        });
    </script>
@endsection
