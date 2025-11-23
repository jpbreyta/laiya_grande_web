@extends('user.layouts.app')

@section('content')
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Please check your inputs',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#f59e0b'
            })
        </script>
    @endif

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-100">
                    <h1 class="text-3xl font-extrabold mb-6 text-gray-900 tracking-tight">
                        Verify Your Information
                    </h1>

                    <form 
                        id="verifyInfoForm" 
                        method="POST" 
                        action="{{ route('search.validateContactInformation') }}"
                        class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg shadow-sm border border-gray-200"
                    >
                        @csrf

                        <div class="mb-6">
                            <label for="reservation_code" class="block text-gray-700 font-semibold mb-2">
                                Booking or Reservation Code
                            </label>
                            <input 
                                type="text" 
                                name="reservation_code" 
                                id="reservation_code"
                                value="{{ request('reservation_code') }}"
                                class="w-full p-3 rounded border border-gray-300 focus:ring focus:ring-blue-200"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-gray-700 font-semibold mb-2">
                                Email Address
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                value="{{ old('email') }}"
                                class="w-full p-3 rounded border border-gray-300 focus:ring focus:ring-blue-200"
                                required
                            >
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-gray-700 font-semibold mb-2">
                                Phone Number
                            </label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="phone"
                                value="{{ old('phone') }}"
                                class="w-full p-3 rounded border border-gray-300 focus:ring focus:ring-blue-200"
                                required
                            >
                        </div>

                        <button 
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded transition"
                        >
                            Send OTP
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <a 
                            href="{{ route('search.index') }}"
                            class="text-blue-600 hover:underline font-medium"
                        >
                            ← Back to Search
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

