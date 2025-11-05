<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Laiya Grande Resort</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="relative bg-gradient-to-br from-yellow-100 via-white to-blue-100 text-white min-h-screen flex items-center justify-center px-4">
    style
    <div
        class="w-full max-w-6xl bg-white shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row text-gray-800">
        <!-- Left Panel -->
        <div class="hidden md:flex md:w-1/2 bg-white items-center justify-center p-10">
            <img src="https://media.istockphoto.com/id/1438608740/vector/login-form-on-computer-screen-user-authorization-sign-in-to-account-authentication-page.jpg?s=612x612&amp;w=0&amp;k=20&amp;c=BCNL4MdGCN6e9zhUXQECoNTTrZrWsttwd_27YVwdbeI="
                alt="Security Icon" class="w-full max-w-sm h-auto object-contain">
        </div>

        <!-- Divider -->
        <div class="hidden md:flex items-center justify-center">
            <div class="h-32 w-px bg-gray-300 opacity-30"></div>
        </div>

        <!-- Right Panel -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="flex justify-center space-x-2 mb-2">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                </div>
                <h1 class="text-3xl font-bold text-teal-700">Laiya Grande Resort</h1>
            </div>

            <h2 class="text-lg text-center text-gray-700 mb-6 font-semibold">Login To Your Account</h2>

            @if ($errors->any())
                <div class="alert alert-danger mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="name@gmail.com" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-full focus:ring-teal-500 focus:border-teal-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="********" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-full focus:ring-teal-500 focus:border-teal-500">
                </div>
                <button type="submit"
                    class="w-full py-2 px-4 bg-teal-600 text-white font-semibold rounded-full hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    LOGIN
                </button>
        </div>
    </div>
</body>

</html>
