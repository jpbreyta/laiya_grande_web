<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laiya Grande Resort')</title>

    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('user.layouts.style')
</head>

<body class="bg-white text-gray-900 antialiased">
    @include('user.layouts.nav')

    <main class="py-0">
        @yield('content')
    </main>

    @include('user.layouts.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('alert'))
                const alertData = @json(session('alert'));
                Swal.fire({
                    icon: alertData.type,
                    title: alertData.type.charAt(0).toUpperCase() + alertData.type.slice(1),
                    text: alertData.message,
                    confirmButtonColor: '#0E7C7B'
                });
            @endif
        });
    </script>
    
    @stack('scripts')
</body>

</html>