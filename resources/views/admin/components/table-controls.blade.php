<div class="flex flex-col gap-4 mb-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 w-full">
        {{-- PAGE TITLE --}}
        @isset($title)
            <h2 class="text-2xl font-bold text-gray-800">{{ $title }}</h2>
        @endisset

        {{-- DYNAMIC MORE DROPDOWN --}}
        @isset($more)
            <div class="relative">
                <button id="moreButton"
                    class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium focus:outline-none focus:ring focus:ring-gray-300">
                    More
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- DROPDOWN MENU --}}
                <div id="moreDropdown"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    @foreach ($more as $item)
                        <a href="{{ $item['route'] ?? '#' }}" target="{{ $item['target'] ?? '_self' }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endisset
    </div>

    {{-- CONTROLS (Show entries, Button, Search) --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 w-full">
        <div class="flex items-center gap-3 w-full">

            {{-- GROUP: Show Entries + Button --}}
            <div class="flex items-center gap-2">
                @if (!empty($entries))
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Show:</span>
                        <select
                            class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option selected>100</option>
                            <option>All</option>
                        </select>
                    </div>
                @endif

                @isset($button)
                    @php
                        $route = $button['route'] ?? '#';
                        $text = $button['text'] ?? 'Button';
                        $icon = $button['icon'] ?? null; // optional
                        $color = $button['color'] ?? 'bg-gray-600 hover:bg-gray-700'; // fully dynamic tailwind
                    @endphp

                    <a href="{{ route($route) }}"
                        class="inline-flex items-center {{ $color }} text-white px-4 py-2 rounded-lg 
              text-sm font-small font-bold shadow-md hover:shadow-lg transform hover:scale-105 
              transition-all duration-200">

                        @if ($icon)
                            <i class="fas {{ $icon }} mr-2"></i>
                        @endif

                        {{ $text }}
                    </a>
                @endisset

            </div>

            {{-- SEARCH BAR --}}
            @if (!empty($search))
                <input type="text" name="search" placeholder="Search..." value="{{ request('search') ?? '' }}"
                    class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none ml-auto" />
            @endif
        </div>
    </div>
</div>

{{-- DROPDOWN TOGGLE SCRIPT --}}
<script>
    const moreButton = document.getElementById('moreButton');
    const moreDropdown = document.getElementById('moreDropdown');

    moreButton?.addEventListener('click', () => {
        moreDropdown.classList.toggle('hidden');
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', function(event) {
        if (!moreButton.contains(event.target) && !moreDropdown.contains(event.target)) {
            moreDropdown.classList.add('hidden');
        }
    });
</script>
