@extends('user.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-100">
                    <h1 class="text-4xl font-extrabold mb-8 text-gray-900 tracking-tight">
                        Find Your Reservation or Booking
                    </h1>

                    <!-- Reservation/Booking Code Lookup Form -->
                    <div class="mb-10">
                        <h2 class="text-2xl font-semibold mb-3 text-gray-800">Search by Reservation Code</h2>
                        <p class="text-gray-600 mb-6">Enter your reservation or booking code to view your details.</p>

                        <form id="searchForm" class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg shadow-sm border border-gray-200">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="reservation_code"
                                        class="block text-sm font-medium text-gray-700 mb-2">Reservation/Booking Code
                                    </label>
                                    <input type="text" id="reservation_code" name="reservation_code" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                                        placeholder="Enter your code (RSV- or BK-...)">
                                </div>
                            </div>
                            <button type="submit"
                                class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition duration-300 ease-in-out">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
        fetch("{{ route('search.byCode') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ reservation_code: reservationCode })
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Found!',
                        text: 'Redirecting you to the verification page...',
                        timer: 2000,
                        showConfirmButton: false,
                        didClose: () => {
                             window.location.href = data.redirect_url;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found',
                        text: data.message || 'No reservation or booking found with the provided code.'
                    });
                }
            })
            .catch(error => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: 'Please try again later.'
                });
                console.error('Error:', error);
            });
    });
</script>
@endpush

