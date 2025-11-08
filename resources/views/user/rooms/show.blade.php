@extends('user.layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">

        <!-- Panel: Image Slider -->

        <!-- Back to Rooms Button -->
        <div class="max-w-5xl mx-auto mt-6 px-4">
            <a href="/rooms"
                class="inline-block text-sm font-medium text-teal-600 bg-white px-4 py-2 rounded-full shadow hover:bg-teal-50 transition">
                ← Back to Rooms
            </a>
        </div>

        <section class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="relative slider-container group">
                <div id="slider" class="flex transition-transform duration-500 ease-in-out">
                    <img src="https://www.landmarklondon.co.uk/wp-content/uploads/2019/05/Superior-Room-1800x1200.jpg"
                        class="w-full h-64 object-cover flex-shrink-0">
                    <img src="https://atrium-hotel.com.gr/wp-content/uploads/2019/02/superior-double-room_003.jpg"
                        class="w-full h-64 object-cover flex-shrink-0">
                    <img src="https://www.grandhotelgardone.it/images/slide/rooms/superior-double-room/double-room-superior.jpg"
                        class="w-full h-64 object-cover flex-shrink-0">
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

        <!-- Panel: Room Details -->
        <section class="bg-white rounded-xl shadow-md px-6 py-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold text-teal-700">Superior Double Room</h2>
                <span class="text-lg font-medium text-gray-700">₱7,000 / night</span>
            </div>
            <p class="text-sm text-gray-500 mb-6">
                Reserve a superior room with a total of 29 square-meters and it comes with 2 Double beds. It can accommodate
                a maximum of two guests comfortably.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm text-gray-600 mb-6">
                <div><span class="font-medium text-gray-700">Sleeps:</span> 2</div>
                <div><span class="font-medium text-gray-700">Beds:</span> 2 Double Beds</div>
                <div><span class="font-medium text-gray-700">Bathroom:</span> 1</div>
                <div><span class="font-medium text-gray-700">Size:</span> 29m²</div>
                <div><span class="font-medium text-gray-700">View:</span> City view</div>
                <div><span class="font-medium text-gray-700">Smoking:</span> Non-smoking</div>
                <div><span class="font-medium text-gray-700">Air Conditioning:</span> Yes</div>
                <div><span class="font-medium text-gray-700">Internet:</span> Access included</div>
                <div><span class="font-medium text-gray-700">TV:</span> Cable/Satellite</div>
                <div><span class="font-medium text-gray-700">Mini Bar:</span> Included</div>
                <div class="sm:col-span-2"><span class="font-medium text-gray-700">Bathroom Type:</span> En-suite</div>
            </div>
            <button
                class="flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-medium px-5 py-2 rounded-full w-full transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13a1 1 0 001-1v-1H6.5M7 13l-4-8m0 0H1"></path>
                </svg>
                Add to Cart
            </button>
        </section>

        <!-- Panel: Add-on Services -->
        <section class="bg-white rounded-xl shadow-md px-6 py-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Add-on Services</h3>
            <div class="space-y-4">
                <div
                    class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition flex justify-between items-start">

                    <!-- Add-on 2 -->
                    <div
                        class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-700">Airport Shuttle</div>
                            <p class="text-sm text-gray-500">Roundtrip shuttle service from Manila to Laiya Grande Resort.
                            </p>
                        </div>
                        <button class="text-teal-500 hover:text-teal-600 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13a1 1 0 001-1v-1H6.5M7 13l-4-8m0 0H1">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Add-on 3 -->
                    <div
                        class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-700">Spa Package</div>
                            <p class="text-sm text-gray-500">Includes 60-minute massage and access to sauna and jacuzzi.</p>
                        </div>
                        <button class="text-teal-500 hover:text-teal-600 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13a1 1 0 001-1v-1H6.5M7 13l-4-8m0 0H1">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>

  @endsection