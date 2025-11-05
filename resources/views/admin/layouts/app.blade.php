<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Admin Dashboard' }} - Laiya Grande</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --teal-primary: #2C5F5F;
            --teal-dark: #1A4A4A;
            --blue-dark: #1E3A5F;
            --accent-yellow: #F4D03F;
            --accent-red: #E74C3C;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .main-content::-webkit-scrollbar { width: 6px; }
        .main-content::-webkit-scrollbar-track { background: #f1f1f1; }
        .main-content::-webkit-scrollbar-thumb {
            background: var(--teal-primary);
            border-radius: 3px;
        }
        .main-content::-webkit-scrollbar-thumb:hover {
            background: var(--teal-dark);
        }

        .dashboard-card {
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar (fixed width) --}}
        @include('admin.layouts.nav')

        {{-- Main Section --}}
        <div class="flex flex-col flex-1 h-full">

            {{-- Header (now beside nav, top of content) --}}
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" placeholder="Search"
                               class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--teal-primary)] focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">

                    <button class="relative text-gray-600 hover:text-[var(--teal-primary)] transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="notification-badge absolute -top-1 -right-1 bg-[var(--accent-red)] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </button>
                </div>
            </header>

            {{-- Page Body --}}
            <main class="main-content flex-1 bg-gray-50 p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', e => {
                    console.log('Searching for:', e.target.value);
                });
            }
        });
    </script>
</body>
</html>
