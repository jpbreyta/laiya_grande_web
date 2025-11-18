<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --font-body: 'Poppins', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, 'Noto Sans', 'Helvetica Neue', sans-serif;
        }

        :root {
            --font-heading: 'Playfair Display', ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
        }

        :root {
            --font-ui: 'Roboto', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, 'Noto Sans', 'Helvetica Neue', sans-serif;
        }

        html,
        body {
            font-family: var(--font-body);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: var(--font-heading);
        }

        input,
        select,
        textarea,
        button {
            font-family: var(--font-ui);
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-up {
            animation: slide-up 1s ease-out forwards;
            opacity: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }

        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }
    </style>
</head>

<body class="bg-white text-gray-900 antialiased">
    @include('user.layouts.nav')

    <main class="py-0">
        @yield('content')
    </main>

    @include('user.layouts.footer')

    <!-- Page-specific scripts -->
    @stack('scripts')
</body>

</html>