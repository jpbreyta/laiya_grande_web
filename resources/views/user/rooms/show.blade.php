@extends('user.layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap');
    
    .font-heading { font-family: 'Playfair Display', serif; }
    .font-body { font-family: 'Inter', sans-serif; }
    
    html { scroll-behavior: smooth; }


    .slider-track { display: flex; transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); }

    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
        background-color: #0f766e !important;
        box-shadow: none !important;
    }
</style>

<div class="bg-slate-50 min-h-screen font-body text-slate-600 pb-20">

    <div class="max-w-7xl mx-auto px-6 pt-8 pb-6">
        <a href="{{ route('user.rooms.index') }}" 
           class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-teal-700 transition-colors group">
            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-teal-500 shadow-sm">
                <i class="fas fa-arrow-left text-xs group-hover:text-teal-600"></i>
            </div>
            Back to Accommodations
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-12">

        <div class="lg:col-span-2 space-y-10">
            
            <div class="relative group rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 bg-slate-200 h-[400px] md:h-[500px]">
                <div id="slider" class="slider-track h-full">
                    @if(!empty($room->images) && count($room->images) > 0)
                        @foreach($room->images as $img)
                            <img src="{{ asset($img) }}" class="w-full h-full object-cover flex-shrink-0">
                        @endforeach
                    @else
                        <img src="{{ asset($room->image ?? 'images/default-room.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                    @endif
                </div>
                
                @if(!empty($room->images) && count($room->images) > 1)
                    <div class="absolute inset-0 flex items-center justify-between p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                        <button onclick="slide(-1)" class="pointer-events-auto w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm text-slate-800 hover:text-teal-700 shadow-lg flex items-center justify-center hover:scale-110 transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button onclick="slide(1)" class="pointer-events-auto w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm text-slate-800 hover:text-teal-700 shadow-lg flex items-center justify-center hover:scale-110 transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        @foreach($room->images as $index => $img)
                            <div class="w-2 h-2 rounded-full bg-white/50 dot-indicator" data-index="{{ $index }}"></div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
                    <div>
                        <h1 class="text-4xl font-heading font-bold text-slate-900 mb-2">{{ $room->name }}</h1>
                        <div class="flex items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-400"></i> 5.0 (Reviews)</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>{{ $room->location ?? 'Main Wing' }}</span>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200 mb-8">

                <div class="prose prose-slate max-w-none mb-10 text-lg leading-relaxed text-slate-600">
                    <p>{{ $room->full_description }}</p>
                </div>

                <h3 class="text-xl font-heading font-bold text-slate-900 mb-6">Room Amenities</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-white text-teal-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Capacity</p>
                            <p class="text-slate-800 font-medium">{{ $room->capacity }} Guests</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-white text-teal-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Bedding</p>
                            <p class="text-slate-800 font-medium">{{ $room->bed_type ?? 'Standard' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-white text-teal-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Room Size</p>
                            <p class="text-slate-800 font-medium">{{ $room->size ?? 'N/A' }} m²</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-white text-teal-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-bath"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Bathroom</p>
                            <p class="text-slate-800 font-medium">{{ $room->has_private_cr ? 'Private En-suite' : 'Shared' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-white text-teal-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-snowflake"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Climate</p>
                            <p class="text-slate-800 font-medium">{{ $room->has_aircon ? 'Air Conditioned' : 'Fan Cooled' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-white text-teal-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-parking"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Parking</p>
                            <p class="text-slate-800 font-medium">{{ $room->has_free_parking ? 'Free Slot' : 'Paid' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-8 bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 lg:p-8">
                
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <span class="text-sm text-slate-400 font-medium">Price per night</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-heading font-bold text-slate-900">₱{{ number_format($room->price, 0) }}</span>
                        </div>
                    </div>
                    <span class="bg-teal-50 text-teal-700 text-xs font-bold px-2 py-1 rounded-md">
                        Best Value
                    </span>
                </div>

                <button onclick="addToCart({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $room->price }})"
                        class="w-full bg-teal-700 hover:bg-teal-800 text-white text-lg font-bold py-4 rounded-xl shadow-lg shadow-teal-900/10 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                    <span>Add to Selection</span>
                    <i class="fas fa-plus"></i>
                </button>

                <div class="mt-6 space-y-4">
                    <div class="flex items-start gap-3 text-sm text-slate-500">
                        <i class="fas fa-check text-teal-500 mt-1"></i>
                        <span><strong>Free Cancellation</strong> up to 48 hours before check-in.</span>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-slate-500">
                        <i class="fas fa-check text-teal-500 mt-1"></i>
                        <span><strong>Instant Confirmation</strong> upon checkout.</span>
                    </div>
                </div>

                <hr class="my-6 border-slate-100">

                <div class="text-center">
                    <p class="text-xs text-slate-400 mb-2">Need help booking?</p>
                    <a href="tel:+123456789" class="text-teal-600 font-bold hover:underline">Call Front Desk</a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    let currentSlide = 0;
    const slider = document.getElementById('slider');
    const slides = slider ? slider.children : [];
    const totalSlides = slides.length;

    function slide(direction) {
        if(totalSlides <= 1) return;
        
        currentSlide += direction;
        
        if (currentSlide >= totalSlides) {
            currentSlide = 0;
        } else if (currentSlide < 0) {
            currentSlide = totalSlides - 1;
        }
        
        const offset = currentSlide * -100;
        slider.style.transform = `translateX(${offset}%)`;
        

        document.querySelectorAll('.dot-indicator').forEach((dot, idx) => {
            dot.style.backgroundColor = idx === currentSlide ? 'white' : 'rgba(255,255,255,0.5)';
        });
    }


    if(document.querySelector('.dot-indicator')) {
        document.querySelector('.dot-indicator').style.backgroundColor = 'white';
    }


    function addToCart(roomId, roomName, roomPrice) {
        Swal.fire({
            title: 'Adding to cart...',
            html: 'Please wait a moment',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                room_id: roomId,
                room_name: roomName,
                room_price: roomPrice,
                quantity: 1
            })
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.success) {
                Swal.fire({
                    title: "Added to Selection",
                    html: `<div style="font-family:'Inter',sans-serif; font-size:15px; color:#475569; margin-top:8px;"><strong>${roomName}</strong> is now in your cart.</div>`,
                    icon: "success",
                    confirmButtonColor: "#0f766e", 
                    confirmButtonText: "Continue Browsing",
                    showCancelButton: true,
                    cancelButtonText: "Go to Checkout",
                    cancelButtonColor: "#334155"
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = '{{ route("user.booking.book") }}'; 
                    }
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: data.message || "Something went wrong.",
                    icon: "error",
                    confirmButtonColor: "#ef4444"
                });
            }
        })
        .catch(err => {
            Swal.close();
            console.error(err);
            Swal.fire({
                title: "Connection Error",
                text: "Please check your internet connection.",
                icon: "error",
                confirmButtonColor: "#ef4444"
            });
        });
    }
</script>
@endpush

@endsection