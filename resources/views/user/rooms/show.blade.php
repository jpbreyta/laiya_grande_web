@extends('user.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">
    <div class="max-w-5xl mx-auto mt-6 px-4">
        <a href="/rooms"
            class="inline-block text-sm font-medium text-teal-600 bg-white px-4 py-2 rounded-full shadow hover:bg-teal-50 transition">
            ← Back to Rooms
        </a>
    </div>

    <section class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="relative slider-container group">
            <div id="slider" class="flex transition-transform duration-500 ease-in-out">
                @if(!empty($room->images))
                    @foreach($room->images as $img)
                        <img src="{{ asset($img) }}" class="w-full h-64 object-cover flex-shrink-0">
                    @endforeach
                @else
                    <img src="{{ asset($room->image ?? 'images/default-room.jpg') }}" class="w-full h-64 object-cover flex-shrink-0">
                @endif
            </div>
            <button onclick="slide(-1)"
                class="slider-arrow absolute left-2 top-1/2 transform -translate-y-1/2 bg-white text-teal-600 p-2 rounded-full shadow hover:bg-teal-50 transition-opacity opacity-0">
                ←
            </button>
            <button onclick="slide(1)"
                class="slider-arrow absolute right-2 top-1/2 transform -translate-y-1/2 bg-white text-teal-600 p-2 rounded-full shadow hover:bg-teal-50 transition-opacity opacity-0">
                →
            </button>
        </div>
    </section>

    <section class="bg-white rounded-xl shadow-md px-6 py-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold text-teal-700">{{ $room->name }}</h2>
            <span class="text-lg font-medium text-gray-700">₱{{ number_format($room->price, 2) }} / night</span>
        </div>
        <p class="text-sm text-gray-500 mb-6">{{ $room->full_description }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm text-gray-600 mb-6">
            <div><span class="font-medium text-gray-700">Sleeps:</span> {{ $room->capacity }}</div>
            <div><span class="font-medium text-gray-700">Beds:</span> {{ $room->bed_type ?? 'N/A' }}</div>
            <div><span class="font-medium text-gray-700">Bathroom:</span> {{ $room->has_private_cr ? '1' : '0' }}</div>
            <div><span class="font-medium text-gray-700">Size:</span> {{ $room->size ?? 'N/A' }}m²</div>
            <div><span class="font-medium text-gray-700">Air Conditioning:</span> {{ $room->has_aircon ? 'Yes' : 'No' }}</div>
            <div><span class="font-medium text-gray-700">Free Parking:</span> {{ $room->has_free_parking ? 'Yes' : 'No' }}</div>
        </div>

        <button
            onclick="addToCart({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $room->price }})"
            class="flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-medium px-5 py-2 rounded-full w-full transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13a1 1 0 001-1v-1H6.5M7 13l-4-8m0 0H1"></path>
            </svg>
            Add to Cart
        </button>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function addToCart(roomId, roomName, roomPrice) {
    Swal.fire({
        title: 'Adding to cart...',
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
                icon: 'success',
                title: 'Added!',
                text: `${roomName} has been added to your cart.`,
                confirmButtonColor: '#3085d6'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Something went wrong.'
            });
        }
    })
    .catch(err => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong. Please try again.'
        });
    });
}
</script>
@endsection
