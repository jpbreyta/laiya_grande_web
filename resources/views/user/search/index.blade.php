@extends('user.layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col md:flex-row bg-white">
        
        <div class="relative hidden md:block w-full md:w-1/2 lg:w-3/5 xl:w-2/3 bg-gray-100">
            <img class="absolute inset-0 h-full w-full object-cover" 
                 src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" 
                 alt="Luxury resort background with pool and view">
            

            <div class="absolute inset-0 bg-gradient-to-t from-teal-900 to-teal-700 opacity-70"></div>
            

            <div class="absolute inset-0 flex flex-col justify-end p-8 lg:p-12 xl:p-20 text-white z-10">
                <p class="text-sm uppercase tracking-wider mb-2 text-teal-200">Your Journey Starts Here</p>
                <blockquote class="mb-6">
                    <p class="text-2xl lg:text-3xl font-serif italic leading-relaxed">
                        "Effortlessly access your reservations and embark on your next adventure."
                    </p>
                </blockquote>
                <div class="flex items-center space-x-3 text-sm opacity-90">
                    <i class="fas fa-star text-yellow-400"></i>
                    <p class="font-medium">Trusted by thousands Batangueños.</p>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-12 xl:px-20 bg-gradient-to-br from-white to-gray-50 z-20 shadow-xl md:shadow-none">
            <div class="mx-auto w-full max-w-md lg:max-w-lg">

                <div class="flex items-center gap-2 mb-10 text-center md:text-left">
                    <div class="h-10 w-10 bg-teal-600 rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight">Laiya Grande Beach Resort</span>
                </div>

                <div class="mb-8">
                    <h2 class="text-4xl font-extrabold text-gray-900 leading-tight">
                        Find Your <span class="text-teal-600">Reservation</span>
                    </h2>
                    <p class="mt-3 text-lg text-gray-600">
                        Enter your booking reference to view and manage your trip details.
                    </p>
                </div>


                <form id="searchForm" class="space-y-6 bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                    @csrf
                    
                    <div>
                        <label for="reservation_code" class="block text-sm font-semibold text-gray-700 mb-2">
                            Booking Reference Code
                        </label>
                        <div class="mt-1 relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                name="reservation_code" 
                                id="reservation_code" 
                                required
                                class="focus:ring-teal-500 focus:border-teal-500 block w-full pl-11 pr-4 py-4 sm:text-lg border-gray-300 rounded-lg placeholder-gray-400 transition-colors duration-200" 
                                placeholder="e.g. BKG-A7B8C9 or REF-XYZ123"
                            >
                        </div>
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1 text-teal-500"></i> You can find this code in your confirmation email.
                        </p>
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-arrow-right mr-3 text-lg"></i>
                            <span>Continue to Details</span>
                        </button>
                    </div>
                </form>
                

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Trouble finding your booking? 
                        <a href="#" class="font-medium text-teal-600 hover:text-teal-500 hover:underline transition-colors">
                            Contact Support
                        </a>
                    </p>
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
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML; 
        const inputField = document.getElementById('reservation_code');


        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3 text-lg"></i> Searching...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        inputField.disabled = true;
        inputField.classList.add('bg-gray-50');

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

                submitBtn.innerHTML = originalBtnHtml;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                inputField.disabled = false;
                inputField.classList.remove('bg-gray-50');

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Found!',
                        text: 'Redirecting to your reservation details...',
                        timer: 1500,
                        showConfirmButton: false,
                        didClose: () => { window.location.href = data.redirect_url; }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Not Found',
                        text: data.message || 'Please double-check the code and try again.',
                        confirmButtonColor: '#0d9488'
                    });
                }
            })
            .catch(error => {

                submitBtn.innerHTML = originalBtnHtml;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                inputField.disabled = false;
                inputField.classList.remove('bg-gray-50');
                
                console.error('Fetch Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'An unexpected error occurred. Please try again later.',
                    confirmButtonColor: '#0d9488'
                });
            });
    });
</script>
@endpush