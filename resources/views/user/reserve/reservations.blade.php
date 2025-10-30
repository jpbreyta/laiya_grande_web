@extends('user.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">My Reservations</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Email and Phone Verification Form -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Find Your Reservation</h2>
                <p class="text-gray-600 mb-6">Enter the email and phone number used for your reservation to view and manage your bookings.</p>

                <form id="reservationLookupForm" class="bg-gray-50 p-6 rounded-lg">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter your email">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter your phone number">
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md transition duration-300">
                        <i class="fas fa-search mr-2"></i>Find My Reservations
                    </button>
                </form>
            </div>

            <!-- Reservations will be loaded here via AJAX -->
            <div id="reservationsContainer" class="hidden">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Your Reservations</h2>
                <div id="reservationsList">
                    <!-- Reservations will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('reservationLookupForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const email = formData.get('email');
    const phone = formData.get('phone');

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Searching...';
    submitBtn.disabled = true;

    // Make AJAX request to find reservations
    fetch('/user/reservation/lookup', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            email: email,
            phone: phone
        })
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        if (data.success) {
            displayReservations(data.reservations);
        } else {
            showError(data.message || 'No reservations found with the provided details.');
        }
    })
    .catch(error => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        showError('An error occurred while searching for reservations.');
        console.error('Error:', error);
    });
});

function displayReservations(reservations) {
    const container = document.getElementById('reservationsContainer');
    const list = document.getElementById('reservationsList');

    if (reservations.length === 0) {
        list.innerHTML = `
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-calendar-times text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Reservations Found</h3>
                <p class="text-gray-500 mb-6">No reservations were found with the provided email and phone number.</p>
                <a href="{{ route('booking.index') }}"
                   class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors inline-block">
                    Make Your First Reservation
                </a>
            </div>
        `;
    } else {
        let html = '';
        reservations.forEach(reservation => {
            const checkIn = reservation.check_in ? new Date(reservation.check_in).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
            const checkOut = reservation.check_out ? new Date(reservation.check_out).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
            const expiresAt = reservation.expires_at ? new Date(reservation.expires_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' ' + new Date(reservation.expires_at).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : null;
            const isExpired = expiresAt && new Date(reservation.expires_at) < new Date();

            html += `
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow mb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Reservation #${reservation.id}
                            </h3>
                            <p class="text-gray-600">
                                ${reservation.firstname} ${reservation.lastname}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            ${reservation.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                              reservation.status === 'paid' ? 'bg-blue-100 text-blue-800' :
                              reservation.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                              'bg-red-100 text-red-800'}">
                            ${reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1)}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <strong class="text-gray-700">Room:</strong>
                            <p class="text-gray-600">${reservation.room ? reservation.room.name : 'N/A'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700">Check-in:</strong>
                            <p class="text-gray-600">${checkIn}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700">Check-out:</strong>
                            <p class="text-gray-600">${checkOut}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700">Guests:</strong>
                            <p class="text-gray-600">${reservation.number_of_guests}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700">Total:</strong>
                            <p class="text-gray-600">₱${parseFloat(reservation.total_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700">Payment Method:</strong>
                            <p class="text-gray-600">${reservation.payment_method ? reservation.payment_method.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'N/A'}</p>
                        </div>
                    </div>

                    ${expiresAt ? `
                    <div class="mb-4">
                        <strong class="text-gray-700">Expires:</strong>
                        <span class="text-gray-600">${expiresAt}</span>
                        ${isExpired && reservation.status === 'pending' ? '<span class="text-red-600 font-semibold">(Expired)</span>' : ''}
                    </div>
                    ` : ''}

                    <div class="flex flex-wrap gap-2">
                        ${reservation.status === 'pending' && (!expiresAt || !isExpired) ? `
                            <a href="/user/reservation/${reservation.id}/continue"
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                Continue Payment
                            </a>
                        ` : reservation.status === 'paid' ? `
                            <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-md">
                                Payment Submitted - Awaiting Confirmation
                            </span>
                        ` : reservation.status === 'confirmed' ? `
                            <span class="bg-green-100 text-green-800 px-4 py-2 rounded-md">
                                Confirmed - Ready to Check-in
                            </span>
                        ` : ''}

                        <a href="/user/reservation/${reservation.id}"
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            `;
        });
        list.innerHTML = html;
    }

    container.classList.remove('hidden');
}

function showError(message) {
    const container = document.getElementById('reservationsContainer');
    const list = document.getElementById('reservationsList');

    list.innerHTML = `
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>${message}
        </div>
    `;
    container.classList.remove('hidden');
}
</script>
@endsection
