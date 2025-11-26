<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laiya Grande Resort')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('user.layouts.style')
</head>

<body class="bg-white text-gray-900 antialiased">
    @include('user.layouts.nav')

    <main class="py-0">
        @yield('content')
    </main>

    @include('user.layouts.footer')

    <script>
        @if(session('alert'))
            var alertData = @json(session('alert'));
            Swal.fire({
                icon: alertData.type,
                title: alertData.type.charAt(0).toUpperCase() + alertData.type.slice(1),
                text: alertData.message,
            });
        @endif
    </script>
    @stack('scripts')
</body>

</html>