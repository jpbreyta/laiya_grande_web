<footer class="bg-gradient-to-r from-sky-500 via-cyan-500 to-teal-600 text-white mt-10">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Resort Info -->
        <div>
            <h2 class="text-2xl font-bold">Laiya Grande</h2>
            <p class="mt-3 text-sm">Escape to paradise at Laiya, San Juan, Philippines 🌴</p>
            <div class="mt-4">
                <p class="flex items-center gap-2"><span>📍</span> Laiya, San Juan, Philippines, 4226</p>
                <p class="flex items-center gap-2"><span>📞</span> 0963 033 7629</p>
                <p class="flex items-center gap-2"><span>📧</span> laiyagrandebr22@gmail.com</p>
            </div>
        </div>

        <!-- Quick Links -->
        <div>
            <h2 class="text-xl font-semibold mb-3">Explore</h2>
            <ul class="space-y-2">
                <li><a href="{{ url('/') }}" class="hover:text-yellow-200 transition">Home</a></li>
                <li><a href="{{ url('/rooms') }}" class="hover:text-yellow-200 transition">Rooms</a></li>
                <li><a href="{{ url('/amenities') }}" class="hover:text-yellow-200 transition">Amenities</a></li>
                <li><a href="{{ url('/gallery') }}" class="hover:text-yellow-200 transition">Gallery</a></li>
                <li><a href="{{ url('/contact') }}" class="hover:text-yellow-200 transition">Contact</a></li>
            </ul>
        </div>

        <!-- Social Media -->
        <div>
            <h2 class="text-xl font-semibold mb-3">Follow Us</h2>
            <div class="flex gap-4">
                <a href="https://www.facebook.com/laiyagrande" target="_blank" class="hover:text-yellow-200 text-2xl">
                    👍
                </a>
                <!-- Add more socials if needed -->
            </div>
        </div>

    </div>

    <!-- Bottom Bar -->
    <div class="bg-teal-700 text-center py-3 text-sm">
        © {{ date('Y') }} Laiya Grande. All rights reserved.
    </div>
</footer>
