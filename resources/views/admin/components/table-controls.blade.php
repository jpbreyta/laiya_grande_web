<div class="flex flex-col gap-4 mb-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 w-full">
        {{-- PAGE TITLE ON TOP --}}
        @isset($title)
            <h2 class="text-2xl font-bold text-gray-800">{{ $title }}</h2>
        @endisset

        @isset($actions)
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        @endisset

    </div>

    {{-- CONTROLS: Actions, Show entries, Search --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 w-full">


        <div class="flex items-center justify-between gap-3 w-full">

            {{-- SHOW ENTRIES --}}
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

            {{-- SEARCH BAR --}}
            @if (!empty($search))
                <input 
                    type="text" 
                    name="search"
                    placeholder="Search..."
                    value="{{ request('search') ?? '' }}"
                    class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none" />
            @endif
        </div>
    </div>
</div>
