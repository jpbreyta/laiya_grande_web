@props([
    'code' => '404',
    'title' => 'Page Not Found',
    'message' => 'The page you\'re looking for doesn\'t exist or has been moved.',
    'icon' => 'file',
    'showBack' => true,
    'showHome' => true,
])

<div class="max-w-2xl w-full">
    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center">
        <!-- Icon -->
        <div class="inline-flex items-center justify-center w-20 h-20 bg-teal-100 rounded-2xl mb-6">
            @if ($icon === 'file')
                <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            @elseif($icon === 'lock')
                <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            @elseif($icon === 'warning')
                <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            @elseif($icon === 'server')
                <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                </svg>
            @endif
        </div>

        <!-- Error Code -->
        <h1 class="text-7xl md:text-8xl font-extrabold text-teal-600 mb-4">
            {{ $code }}
        </h1>

        <!-- Error Label -->
        <p class="text-sm uppercase tracking-wider text-gray-500 font-semibold mb-3">
            ERROR
        </p>

        <!-- Title -->
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
            {{ $title }}
        </h2>

        <!-- Message -->
        <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto leading-relaxed">
            {{ $message }}
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            @if ($showBack)
                <button onclick="window.history.back()"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-gray-300 bg-white px-6 py-3 font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Go Back</span>
                </button>
            @endif

            @if ($showHome)
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-teal-600 px-6 py-3 font-semibold text-white shadow-lg hover:bg-teal-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Go to Homepage</span>
                </a>
            @endif
        </div>

        <!-- Additional Help Text -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Need help?
                <a href="{{ url('/contact') }}" class="text-teal-600 hover:text-teal-700 font-medium underline">
                    Contact our support team
                </a>
            </p>
        </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-10 left-10 w-4 h-4 bg-teal-400/30 rounded-full animate-pulse"></div>
    <div class="absolute top-20 right-20 w-6 h-6 bg-yellow-400/20 rounded-full animate-bounce"
        style="animation-delay: 1s;"></div>
    <div class="absolute bottom-20 left-20 w-3 h-3 bg-teal-400/40 rounded-full animate-pulse"
        style="animation-delay: 2s;"></div>
    <div class="absolute bottom-10 right-10 w-5 h-5 bg-yellow-300/25 rounded-full animate-bounce"
        style="animation-delay: 3s;"></div>
</div>
