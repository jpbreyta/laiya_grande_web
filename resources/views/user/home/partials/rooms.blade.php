@if ($rooms->count())
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Our Rooms</h2>
                <div class="w-24 h-1 bg-teal-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div x-data="{
                current: 0,
                rooms: {{ $rooms->toJson() }},
                next() { if (this.current < this.rooms.length - 3) this.current++ },
                prev() { if (this.current > 0) this.current-- }
            }" class="relative group/carousel">

                <div class="flex -mx-4 overflow-hidden pb-4">
                    <template x-for="(room, index) in rooms" :key="index">
                        <div x-show="index >= current && index < current + 3"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="w-full md:w-1/3 px-4 flex-shrink-0">

                            <div
                                class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 overflow-hidden h-full flex flex-col group">
                                <div class="relative overflow-hidden h-64">
                                    <img :src="room.image_url" :alt="room.name"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm">
                                        <span class="text-teal-600 font-bold"
                                            x-text="'₱' + Number(room.price).toLocaleString()"></span>
                                        <span class="text-xs text-slate-500 font-medium">/night</span>
                                    </div>
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-slate-800 mb-2" x-text="room.name"></h3>
                                        <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed"
                                            x-text="room.short_description"></p>

                                        <!-- Rating Display -->
                                        <div class="flex items-center gap-2 mt-2">
                                            <template x-if="room.total_ratings > 0">
                                                <div class="flex items-center gap-1">
                                                    <template x-for="i in 5" :key="i">
                                                        <i :class="i <= Math.floor(room.average_rating) ?
                                                            'fas fa-star text-yellow-400' :
                                                            (i - 0.5 <= room.average_rating ?
                                                                'fas fa-star-half-alt text-yellow-400' :
                                                                'far fa-star text-gray-300')"
                                                            class="text-sm"></i>
                                                    </template>
                                                    <span class="text-sm font-semibold text-slate-700 ml-1"
                                                        x-text="room.average_rating"></span>
                                                    <span class="text-xs text-slate-500"
                                                        x-text="`(${room.total_ratings} ${room.total_ratings == 1 ? 'review' : 'reviews'})`"></span>
                                                </div>
                                            </template>
                                            <template x-if="!room.total_ratings || room.total_ratings === 0">
                                                <span class="text-xs text-slate-400">No ratings yet</span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 mb-6">
                                        <span x-show="room.has_aircon"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-snowflake mr-1.5 text-teal-500"></i>Aircon
                                        </span>
                                        <span x-show="room.has_private_cr"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-bath mr-1.5 text-teal-500"></i>Private CR
                                        </span>
                                        <span x-show="room.has_kitchen"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-utensils mr-1.5 text-teal-500"></i>Kitchen
                                        </span>
                                        <span x-show="room.has_free_parking"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-car mr-1.5 text-teal-500"></i>Parking
                                        </span>
                                        <span x-show="room.no_entrance_fee"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                            <i class="fas fa-check mr-1.5"></i>No Entrance
                                        </span>
                                    </div>

                                    <div class="mt-auto pt-4 border-t border-slate-100 space-y-2">
                                        <button type="button" @click="addToCart(room.id, room.name, room.price)"
                                            class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-xl transition-colors duration-200">
                                            <span class="mr-2"><i class="fas fa-shopping-cart"></i></span>
                                            Add to Cart
                                        </button>
                                        <button type="button" @click="openRatingModal(room.id, room.name)"
                                            class="block w-full text-center border-2 border-teal-600 text-teal-600 hover:bg-teal-50 font-semibold py-2.5 rounded-xl transition-colors duration-200">
                                            <span class="mr-2"><i class="fas fa-star"></i></span>
                                            Rate This Room
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button @click="prev()" :class="{ 'opacity-50 cursor-not-allowed': current === 0 }"
                    class="absolute -left-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-lg text-slate-700 hover:text-teal-600 hover:scale-110 transition-all duration-200 z-10 focus:outline-none hidden md:flex">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                <button @click="next()" :class="{ 'opacity-50 cursor-not-allowed': current >= rooms.length - 3 }"
                    class="absolute -right-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-lg text-slate-700 hover:text-teal-600 hover:scale-110 transition-all duration-200 z-10 focus:outline-none hidden md:flex">
                    <i class="fas fa-chevron-right text-lg"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Rating Modal -->
    <div id="ratingModal"
        class="fixed inset-0 z-[999] hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-slate-800 font-heading">Rate This Room</h3>
                <button onclick="closeRatingModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="ratingForm" onsubmit="submitRating(event)">
                <input type="hidden" id="rating_room_id" name="room_id">

                <div class="mb-4">
                    <p class="text-sm text-slate-600 mb-2" id="rating_room_name"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Rating *</label>
                    <div class="flex gap-2" id="starRating">
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="1"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="2"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="3"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="4"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="5"></i>
                    </div>
                    <input type="hidden" id="rating_value" name="rating" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Email *</label>
                    <input type="email" id="guest_email" name="guest_email" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="your@email.com">
                    <p class="text-xs text-slate-500 mt-1">We use this to track ratings and prevent duplicates</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Name (Optional)</label>
                    <input type="text" id="guest_name" name="guest_name"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="John Doe">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Comment (Optional)</label>
                    <textarea id="rating_comment" name="comment" rows="3"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="Share your experience..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRatingModal()"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 transition-colors">
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedRating = 0;

        function openRatingModal(roomId, roomName) {
            document.getElementById('rating_room_id').value = roomId;
            document.getElementById('rating_room_name').textContent = roomName;
            document.getElementById('ratingModal').classList.remove('hidden');
            selectedRating = 0;
            updateStars(0);
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').classList.add('hidden');
            document.getElementById('ratingForm').reset();
            selectedRating = 0;
            updateStars(0);
        }

        // Star rating interaction
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#starRating i');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedRating = parseInt(this.dataset.rating);
                    document.getElementById('rating_value').value = selectedRating;
                    updateStars(selectedRating);
                });

                star.addEventListener('mouseenter', function() {
                    const hoverRating = parseInt(this.dataset.rating);
                    updateStars(hoverRating);
                });
            });

            document.getElementById('starRating').addEventListener('mouseleave', function() {
                updateStars(selectedRating);
            });
        });

        function updateStars(rating) {
            const stars = document.querySelectorAll('#starRating i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        function submitRating(event) {
            event.preventDefault();

            if (selectedRating === 0) {
                Swal.fire({
                    title: 'Rating Required',
                    text: 'Please select a star rating',
                    icon: 'warning',
                    confirmButtonColor: '#0f766e'
                });
                return;
            }

            const formData = {
                room_id: document.getElementById('rating_room_id').value,
                guest_email: document.getElementById('guest_email').value,
                guest_name: document.getElementById('guest_name').value,
                rating: selectedRating,
                comment: document.getElementById('rating_comment').value
            };

            Swal.fire({
                title: 'Submitting...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('{{ route('ratings.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        Swal.fire({
                            title: 'Thank You!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#0f766e'
                        }).then(() => {
                            closeRatingModal();
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
        }

        function addToCart(roomId, roomName, roomPrice) {
            Swal.fire({
                title: 'Adding to cart...',
                html: 'Please wait a moment',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('{{ route('cart.add') }}', {
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
                        window.dispatchEvent(new CustomEvent('cart-updated'));
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
                                window.location.href = '{{ route('cart.index') }}';
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
@endif
