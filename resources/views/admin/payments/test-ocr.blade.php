@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Top Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">OCR Payment Proof Tester</h1>
                    <p class="text-sm text-slate-500 mt-1">Test the OCR functionality for processing payment proofs</p>
                </div>
                <a href="{{ route('admin.payments.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-6 py-3 text-sm font-semibold shadow-sm hover:shadow-md transform hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-arrow-left"></i>
                    Back to Payments
                </a>
            </div>

            <!-- Quick Instructions Banner -->
            <div
                class="bg-gradient-to-r from-teal-50 via-emerald-50 to-teal-50 border border-teal-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-lightbulb text-teal-600 mr-2"></i>
                            <h3 class="text-sm font-semibold text-slate-900">Quick Guidelines</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-slate-700">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-teal-500 rounded-full mr-2"></span>
                                <span><strong>Reference ID:</strong> 10-digit number</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                <span><strong>Date/Time:</strong> Transaction timestamp</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                <span><strong>Amount:</strong> Total payment (₱)</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="openInstructionsModal()"
                        class="ml-4 inline-flex items-center gap-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 text-xs font-semibold shadow-sm hover:shadow-md transition-all duration-200 whitespace-nowrap">
                        <i class="fas fa-info-circle"></i>
                        View Full Guidelines
                    </button>
                </div>
            </div>

            <!-- Main Card -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 flex items-center">
                                <i class="fas fa-camera mr-3 text-teal-600"></i>
                                Upload Payment Proof
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Upload an image to test OCR extraction</p>
                        </div>

                    </div>
                </div>

                <div class="p-6">
                    <form id="ocrTestForm" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                            <!-- Upload Section -->
                            <div class="flex flex-col h-full">
                                <div
                                    class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden flex flex-col h-full">
                                    <div class="border-b border-slate-100 px-6 py-4">
                                        <h3 class="text-base font-semibold text-slate-900 flex items-center">
                                            <i class="fas fa-upload mr-2 text-teal-600"></i>
                                            Upload Payment Proof
                                        </h3>
                                    </div>
                                    <div class="flex-1 p-6 flex flex-col">
                                        <div
                                            class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-teal-400 transition-colors bg-slate-50 flex-1 flex flex-col justify-center">
                                            <div class="space-y-4">
                                                <div id="uploadArea">
                                                    <div
                                                        class="mx-auto w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-xl flex items-center justify-center mb-4">
                                                        <i class="fas fa-cloud-upload-alt text-teal-600 text-2xl"></i>
                                                    </div>
                                                    <div class="mt-4">
                                                        <label for="payment_proof" class="cursor-pointer">
                                                            <span
                                                                class="mt-2 block text-sm font-semibold text-slate-900">Click
                                                                to upload
                                                                payment proof image</span>
                                                            <input type="file" id="payment_proof" name="payment_proof"
                                                                accept="image/*" class="hidden" required>
                                                            <span class="mt-1 block text-sm text-slate-500">PNG, JPG, GIF up
                                                                to
                                                                5MB</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="imagePreview" class="hidden">
                                                    <div
                                                        class="relative rounded-lg overflow-hidden border-2 border-teal-200 bg-white">
                                                        <img id="previewImage" src="" alt="Payment proof preview"
                                                            class="w-full h-auto max-h-80 object-contain">
                                                        <button type="button" id="removeImage"
                                                            class="absolute top-2 right-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full p-2 shadow-lg transition-colors">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <p class="text-xs text-slate-500 mt-2 text-center" id="fileName"></p>
                                                </div>
                                                <button type="submit" id="processBtn"
                                                    class="w-full bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed mt-auto">
                                                    <i class="fas fa-bolt mr-2"></i>
                                                    Process with OCR
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Results Section -->
                            <div class="flex flex-col h-full">
                                <div
                                    class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden flex flex-col h-full">
                                    <div class="border-b border-slate-100 px-6 py-4">
                                        <h3 class="text-base font-semibold text-slate-900 flex items-center">
                                            <i class="fas fa-file-alt mr-2 text-teal-600"></i>
                                            Extracted Data
                                        </h3>
                                    </div>
                                    <div class="flex-1 p-6">
                                        <div id="resultsContainer"
                                            class="bg-slate-50 rounded-xl p-6 min-h-full border border-slate-200 h-full flex flex-col">
                                            <div class="text-center text-slate-500 flex-1 flex flex-col justify-center">
                                                <div
                                                    class="mx-auto w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mb-4">
                                                    <i class="fas fa-file-alt text-slate-400 text-2xl"></i>
                                                </div>
                                                <p class="text-sm font-medium">Upload an image to see OCR results</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions Modal -->
            <div id="instructionsModal"
                class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-transparent">
                <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                    <div
                        class="border-b border-slate-100 px-6 py-5 bg-gradient-to-r from-teal-50 to-emerald-50 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-900 flex items-center">
                            <i class="fas fa-info-circle mr-3 text-teal-600"></i>
                            Instructions & Guidelines
                        </h2>
                        <button type="button" onclick="closeInstructionsModal()"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-slate-900 mb-4 flex items-center text-lg">
                                    <i class="fas fa-list-check mr-2 text-teal-600"></i>
                                    Expected Data Fields
                                </h4>
                                <ul class="space-y-4 text-sm text-slate-600">
                                    <li class="flex items-start p-3 bg-teal-50 rounded-lg border border-teal-100">
                                        <span class="w-3 h-3 bg-teal-500 rounded-full mr-3 mt-1 flex-shrink-0"></span>
                                        <div>
                                            <strong class="text-slate-900 block mb-1">Reference ID</strong>
                                            <span class="block text-xs text-slate-500">10-digit transaction number that
                                                uniquely identifies the payment</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start p-3 bg-amber-50 rounded-lg border border-amber-100">
                                        <span class="w-3 h-3 bg-amber-500 rounded-full mr-3 mt-1 flex-shrink-0"></span>
                                        <div>
                                            <strong class="text-slate-900 block mb-1">Date/Time</strong>
                                            <span class="block text-xs text-slate-500">Transaction date and time when the
                                                payment was made</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                        <span class="w-3 h-3 bg-emerald-500 rounded-full mr-3 mt-1 flex-shrink-0"></span>
                                        <div>
                                            <strong class="text-slate-900 block mb-1">Total Amount</strong>
                                            <span class="block text-xs text-slate-500">Amount sent in Philippine Peso (₱) -
                                                must be clearly visible</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-900 mb-4 flex items-center text-lg">
                                    <i class="fas fa-shield-alt mr-2 text-teal-600"></i>
                                    Validation Rules
                                </h4>
                                <ul class="space-y-4 text-sm text-slate-600">
                                    <li class="flex items-start p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                        <i class="fas fa-check-circle text-emerald-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-slate-900 block mb-1">All fields required</strong>
                                            <span class="block text-xs text-slate-500">All three fields must be present and
                                                non-empty for successful validation</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                        <i class="fas fa-check-circle text-emerald-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-slate-900 block mb-1">Reference ID format</strong>
                                            <span class="block text-xs text-slate-500">Must be exactly 10 digits - no
                                                letters or special characters</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                        <i class="fas fa-check-circle text-emerald-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-slate-900 block mb-1">Image quality</strong>
                                            <span class="block text-xs text-slate-500">Clear, high-resolution images with
                                                good contrast work best for accurate OCR</span>
                                        </div>
                                    </li>
                                </ul>
                                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-lightbulb text-blue-600 mr-3 mt-0.5"></i>
                                        <div>
                                            <strong class="text-blue-900 block mb-1">Pro Tip</strong>
                                            <p class="text-xs text-blue-700">Ensure the payment proof image is well-lit, in
                                                focus, and all text is clearly readable before uploading.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50 flex justify-end">
                        <button type="button" onclick="closeInstructionsModal()"
                            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-check"></i>
                            Got it!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        let uploadedImageBase64 = null;

        // Instructions Modal Functions
        function openInstructionsModal() {
            document.getElementById('instructionsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeInstructionsModal() {
            document.getElementById('instructionsModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal on outside click
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('instructionsModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeInstructionsModal();
                    }
                });
            }

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeInstructionsModal();
                }
            });
        });

        // Handle image preview
        document.getElementById('payment_proof').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImageBase64 = e.target.result;
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('uploadArea').classList.add('hidden');
                    document.getElementById('fileName').textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image preview
        document.getElementById('removeImage').addEventListener('click', function() {
            document.getElementById('payment_proof').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadArea').classList.remove('hidden');
            uploadedImageBase64 = null;
        });

        function displayResults(ocrResult, validation, imageBase64 = null) {
            const resultsContainer = document.getElementById('resultsContainer');
            const hasImage = imageBase64 || uploadedImageBase64;

            // Ensure results container maintains full height
            resultsContainer.classList.add('flex', 'flex-col', 'overflow-y-auto');

            let html = '<div class="space-y-4 flex-1 overflow-y-auto">';

            // Payment Proof Image Section (if available, shown at top)
            if (hasImage) {
                html += '<div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">';
                html += '<h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center">';
                html += '<i class="fas fa-image mr-2 text-teal-600"></i>';
                html += 'Payment Proof Image</h4>';
                html += '<div class="rounded-lg overflow-hidden border border-slate-200 bg-slate-50 p-2">';
                html +=
                    `<img src="${imageBase64 || uploadedImageBase64}" alt="Payment proof" class="w-full h-auto max-h-64 object-contain mx-auto">`;
                html += '</div>';
                html += '</div>';
            }

            // Transaction Details Section - Organized to match payment proof structure
            html += '<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">';
            html += '<h3 class="font-semibold text-slate-900 mb-4 flex items-center">';
            html += '<i class="fas fa-receipt mr-2 text-teal-600"></i>';
            html += 'Extracted Transaction Details</h3>';

            // Group 1: Reference ID (typically at top of payment proof)
            html += '<div class="mb-4 pb-4 border-b border-slate-200">';
            html +=
                '<div class="flex items-center justify-between p-3 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-lg border border-teal-100">';
            html += '<div class="flex items-center space-x-3">';
            html +=
                '<div class="w-10 h-10 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">';
            html += '<i class="fas fa-hashtag text-teal-600"></i>';
            html += '</div>';
            html += '<div>';
            html += '<p class="text-xs text-slate-500 font-medium">Reference Number</p>';
            const refId = ocrResult['reference_id'] || 'Not found';
            const refIdValid = validation.valid || !validation.errors.some(err => err.includes('Reference'));
            html += `<p class="text-base font-bold text-slate-900 ${refIdValid ? '' : 'text-rose-600'}">${refId}</p>`;
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            // Group 2: Date/Time (typically in middle section)
            html += '<div class="mb-4 pb-4 border-b border-slate-200">';
            html +=
                '<div class="flex items-center justify-between p-3 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-100">';
            html += '<div class="flex items-center space-x-3">';
            html +=
                '<div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">';
            html += '<i class="fas fa-calendar-alt text-amber-600"></i>';
            html += '</div>';
            html += '<div>';
            html += '<p class="text-xs text-slate-500 font-medium">Transaction Date & Time</p>';
            const dateTime = ocrResult['date_time'] || 'Not found';
            const dateTimeValid = validation.valid || !validation.errors.some(err => err.includes('Date') || err.includes(
                'Time'));
            html += `<p class="text-base font-bold text-slate-900 ${dateTimeValid ? '' : 'text-rose-600'}">${dateTime}</p>`;
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            // Group 3: Amount (typically prominent, at bottom or center)
            html += '<div class="mb-4">';
            html +=
                '<div class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg border-2 border-emerald-200">';
            html += '<div class="flex items-center space-x-3">';
            html +=
                '<div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">';
            html += '<i class="fas fa-peso-sign text-emerald-600 text-lg"></i>';
            html += '</div>';
            html += '<div>';
            html += '<p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Total Amount</p>';
            const amount = ocrResult['total_amount'] || 'Not found';
            const amountValid = validation.valid || !validation.errors.some(err => err.includes('Amount'));
            html +=
                `<p class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 ${amountValid ? '' : '!text-rose-600'}">₱${amount}</p>`;
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            html += '</div>'; // Close transaction details card

            // Validation Status Section
            html += '<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">';
            html += '<h3 class="font-semibold text-slate-900 mb-4 flex items-center">';
            if (validation.valid) {
                html += '<i class="fas fa-check-circle mr-2 text-emerald-600"></i>';
                html += 'Validation Status: Passed';
            } else {
                html += '<i class="fas fa-exclamation-circle mr-2 text-rose-600"></i>';
                html += 'Validation Status: Failed';
            }
            html += '</h3>';

            if (validation.errors && validation.errors.length > 0) {
                html += '<div class="bg-rose-50 border border-rose-200 rounded-lg p-4">';
                html += '<ul class="space-y-2">';
                validation.errors.forEach(error => {
                    html += `<li class="text-rose-700 text-sm flex items-start">`;
                    html += '<i class="fas fa-times-circle mr-2 mt-0.5 flex-shrink-0"></i>';
                    html += `<span>${error}</span>`;
                    html += '</li>';
                });
                html += '</ul>';
                html += '</div>';
            } else {
                html += '<div class="flex items-center space-x-2 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">';
                html += '<i class="fas fa-check-circle text-emerald-600"></i>';
                html +=
                    '<p class="text-sm font-medium text-emerald-700">All validation checks passed! Payment proof is valid.</p>';
                html += '</div>';
            }

            html += '</div>';
            html += '</div>'; // Close main space-y-4 div

            resultsContainer.innerHTML = html;

            // Ensure the results container maintains its height
            resultsContainer.classList.remove('justify-center');
        }

        document.getElementById('ocrTestForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const processBtn = document.getElementById('processBtn');
            const resultsContainer = document.getElementById('resultsContainer');

            // Show loading state
            processBtn.disabled = true;
            processBtn.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i>
        Processing...
    `;
            resultsContainer.innerHTML = `
        <div class="text-center text-slate-600">
            <div class="mx-auto w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-spinner fa-spin text-teal-600 text-2xl"></i>
            </div>
            <p class="text-sm font-medium">Processing image with OCR...</p>
        </div>
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
            <i class="fas fa-bolt mr-2"></i>
            Process with OCR
        `;

                if (response.status === 422) {
                    // Validation errors from Laravel
                    resultsContainer.innerHTML = `
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-5">
                    <h3 class="font-semibold text-rose-900 mb-3 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Validation Errors
                    </h3>
                    <ul class="space-y-2">${data.errors.map(err => `<li class="text-rose-700 text-sm flex items-start"><i class="fas fa-times-circle mr-2 mt-0.5"></i>${err}</li>`).join('')}</ul>
                </div>
            `;
                } else if (data.success) {
                    displayResults(data.ocr_result, data.validation, uploadedImageBase64);
                } else {
                    resultsContainer.innerHTML = `
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-5">
                    <h3 class="font-semibold text-rose-900 mb-3 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        OCR Failed
                    </h3>
                    <ul class="space-y-2">${data.errors.map(err => `<li class="text-rose-700 text-sm flex items-start"><i class="fas fa-times-circle mr-2 mt-0.5"></i>${err}</li>`).join('')}</ul>
                </div>
            `;
                }

            } catch (err) {
                processBtn.disabled = false;
                processBtn.innerHTML = `
            <i class="fas fa-bolt mr-2"></i>
            Process with OCR
        `;

                resultsContainer.innerHTML = `
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-5">
                <h3 class="font-semibold text-rose-900 mb-3 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Network Error
                </h3>
                <p class="text-rose-700 text-sm">${err.message}</p>
            </div>
        `;
            }
        });
    </script>
@endsection
