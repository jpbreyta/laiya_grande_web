@extends('user.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-3xl font-bold mb-6 text-gray-800">Find Your Reservation or Booking</h1>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Reservation/Booking Code Lookup Form -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Search by Reservation Code</h2>
                    <p class="text-gray-600 mb-6">Enter your reservation or booking code to view your details.</p>

                    <form id="searchForm" class="bg-gray-50 p-6 rounded-lg">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="reservation_code"
                                    class="block text-sm font-medium text-gray-700 mb-2">Reservation/Booking Code
                                </label>
                                <input type="text" id="reservation_code" name="reservation_code" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter your code (RSV- or BK-...)">
                            </div>
                        </div>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md transition duration-300">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </form>
                </div>

                <!-- Results Container -->
                <div id="resultsContainer" class="hidden">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Results</h2>
                    <div id="resultsDetails"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const reservationCode = formData.get('reservation_code');

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Searching...';
            submitBtn.disabled = true;

            // Make AJAX request to search
            fetch('/search/by-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        reservation_code: reservationCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    if (data.success) {
                        displayResult(data.data, data.type);
                    } else {
                        showError(data.message || 'No reservation or booking found with the provided code.');
                    }
                })
                .catch(error => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    showError('An error occurred while searching.');
                    console.error('Error:', error);
                });
        });

        function displayResult(data, type) {
            const container = document.getElementById('resultsContainer');
            const details = document.getElementById('resultsDetails');

            const checkIn = data.check_in ? new Date(data.check_in).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            }) : 'N/A';
            const checkOut = data.check_out ? new Date(data.check_out).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            }) : 'N/A';

            const typeLabel = type === 'reservation' ? 'Reservation' : 'Booking';

            details.innerHTML = `
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer" onclick="window.location.href='/search/${data.id}/${type}'">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-1">
                                ${data.firstname} ${data.lastname}
                            </h3>
                            <p class="text-sm text-gray-600">${typeLabel} #${data.reservation_number || data.id}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            ${data.status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' :
                              data.status === 'paid' ? 'bg-blue-100 text-blue-800 border border-blue-300' :
                              data.status === 'confirmed' ? 'bg-green-100 text-green-800 border border-green-300' :
                              'bg-red-100 text-red-800 border border-red-300'}">
                            ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-500">Room:</span>
                            <span class="text-gray-900">${data.room ? data.room.name : 'N/A'}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Check-in:</span>
                            <span class="text-gray-900">${checkIn}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Check-out:</span>
                            <span class="text-gray-900">${checkOut}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Total:</span>
                            <span class="text-green-600 font-semibold">₱${parseFloat(data.total_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                        </div>
                    </div>

                    <div class="mt-3 text-right">
                        <span class="text-blue-600 hover:text-blue-800 text-sm font-medium">Click to view details →</span>
                    </div>
                </div>
            `;

            container.classList.remove('hidden');
            container.scrollIntoView({
                behavior: 'smooth'
            });
        }

        function showError(message) {
            const container = document.getElementById('resultsContainer');
            const details = document.getElementById('resultsDetails');

            details.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-red-800">Not Found</h3>
                            <p class="text-red-700 mt-1">${message}</p>
                        </div>
                    </div>
                </div>
            `;

            container.classList.remove('hidden');
        }
    </script>
@endsection
