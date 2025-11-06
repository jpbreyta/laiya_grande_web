<footer class="relative bg-white text-slate-700 mt-10 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Logo + Resort Info -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ asset('images/laiyagrande-logo.png') }}" alt="Laiya Grande Beach Resort" class="h-10 w-auto">
                <h2 class="text-2xl font-bold font-heading text-teal-700">Laiya Grande</h2>
            </div>
            <p class="mt-2 text-sm text-slate-500">Escape to paradise at Laiya, San Juan, Philippines</p>
            <div class="mt-4 space-y-1 text-sm text-slate-600">
                <p class="flex items-center gap-2">📍 Laiya, San Juan, Philippines, 4226</p>
                <p class="flex items-center gap-2">📞 0963 033 7629</p>
                <p class="flex items-center gap-2">✉️ laiyagrandebr22@gmail.com</p>
            </div>
        </div>

        <!-- Explore Links -->
        <div>
            <h2 class="text-xl font-semibold mb-4 font-heading text-teal-700">Explore</h2>
            <ul class="space-y-2 text-slate-600">
                <li><a href="{{ url('/') }}" class="hover:text-teal-500 transition">Home</a></li>
                <li><a href="{{ url('/rooms') }}" class="hover:text-teal-500 transition">Rooms</a></li>
                <li><a href="{{ url('/gallery') }}" class="hover:text-teal-500 transition">Gallery</a></li>
                <li><a href="{{ url('/location') }}" class="hover:text-teal-500 transition">Location</a></li>
            </ul>
        </div>

        <!-- Social -->
        <div>
            <h2 class="text-xl font-semibold mb-4 font-heading text-teal-700">Follow Us</h2>
            <div class="flex items-center gap-3">
                <a href="https://web.facebook.com/laiyagrande" target="_blank" class="text-slate-600 hover:text-blue-500 transition-colors">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <span class="text-sm font-medium text-slate-600">LAIYA GRANDE BEACH RESORT</span>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-sky-500 text-center py-3 text-sm text-white">
        © {{ date('Y') }} Laiya Grande. All rights reserved.
    </div>
</footer>

<button id="backToTopBtn" 
    class="hidden fixed bottom-6 right-6 rounded-full shadow-lg flex items-center justify-center transition duration-300"
    aria-label="Back to top">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
    </svg>
</button>

<style>
#backToTopBtn {
  width: 55px;
  height: 55px;
  background: linear-gradient(145deg, #2d2d2d, #1a1a1a);
  border: none;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
  cursor: pointer;
  transition: all 0.3s ease;
}

#backToTopBtn:hover {
  background: linear-gradient(145deg, #1a1a1a, #000000);
  transform: translateY(-5px);
  box-shadow: 0 10px 18px rgba(0, 0, 0, 0.4);
}

@keyframes bounceIn {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-10px);
  }
  60% {
    transform: translateY(-5px);
  }
}
.animate-bounce-in {
  animation: bounceIn 1s ease;
}

@keyframes bounceClick {
  0% { transform: translateY(0); }
  30% { transform: translateY(-10px); }
  50% { transform: translateY(0); }
  70% { transform: translateY(-5px); }
  100% { transform: translateY(0); }
}
.animate-bounce-click {
  animation: bounceClick 0.6s ease;
}
</style>

<script>
const backToTopBtn = document.getElementById('backToTopBtn');

window.addEventListener('scroll', () => {
  if (window.scrollY > 200) {
    backToTopBtn.classList.remove('hidden');
    backToTopBtn.classList.add('animate-bounce-in');
  } else {
    backToTopBtn.classList.add('hidden');
    backToTopBtn.classList.remove('animate-bounce-in');
  }
});

backToTopBtn.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
  backToTopBtn.classList.add('animate-bounce-click');
  setTimeout(() => backToTopBtn.classList.remove('animate-bounce-click'), 600);
});
</script>

