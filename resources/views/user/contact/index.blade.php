@extends('user.layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }

        textarea::-webkit-scrollbar { width: 8px; }
        textarea::-webkit-scrollbar-track { background: #f0fdfa; }
        textarea::-webkit-scrollbar-thumb { background: #ccfbf1; border-radius: 4px; }
        textarea::-webkit-scrollbar-thumb:hover { background: #99f6e4; }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-teal-50/50 via-white to-teal-50/50 font-poppins relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute top-[-5%] left-[-5%] w-[500px] h-[500px] bg-teal-100/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[400px] h-[400px] bg-teal-200/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute top-[20%] right-[10%] w-[300px] h-[300px] bg-teal-50/60 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">

            <div class="text-center mb-16 space-y-4">
                <span class="inline-block py-1 px-4 rounded-full bg-teal-100 text-teal-800 text-xs font-bold tracking-widest uppercase border border-teal-200">
                    Contact Us
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-playfair font-bold text-teal-950 leading-tight">
                    Let's Start Your <span class="text-teal-600 italic">Journey</span>
                </h1>
                <p class="text-lg text-teal-700/70 max-w-2xl mx-auto font-light leading-relaxed">
                    We are here to help you plan the perfect escape. Reach out to us for reservations, inquiries, or just to say hello.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

                <div class="lg:col-span-5 space-y-8">
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-[0_8px_30px_rgb(20,184,166,0.1)] border border-teal-100">
                        <h2 class="text-2xl font-playfair font-semibold text-teal-900 mb-8 border-b border-teal-50 pb-4">Get in Touch</h2>
                        
                        <div class="space-y-8">
                            <div class="group flex items-start gap-5">
                                <div class="flex-shrink-0 w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center border border-teal-100 group-hover:bg-teal-600 transition-colors duration-300 shadow-sm">
                                    <svg class="w-5 h-5 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-teal-900 mb-1">Our Location</h3>
                                    <p class="text-teal-700/80 text-sm leading-relaxed">Laiya, San Juan,<br>Batangas, Philippines 4226</p>
                                </div>
                            </div>

                            <div class="group flex items-start gap-5">
                                <div class="flex-shrink-0 w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center border border-teal-100 group-hover:bg-teal-600 transition-colors duration-300 shadow-sm">
                                    <svg class="w-5 h-5 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-teal-900 mb-1">Phone Number</h3>
                                    <p class="text-teal-700/80 text-sm">0963 033 7629</p>
                                    <p class="text-teal-600/60 text-xs mt-1">Mon-Sun 8am to 6pm</p>
                                </div>
                            </div>

                            <div class="group flex items-start gap-5">
                                <div class="flex-shrink-0 w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center border border-teal-100 group-hover:bg-teal-600 transition-colors duration-300 shadow-sm">
                                    <svg class="w-5 h-5 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-teal-900 mb-1">Email Support</h3>
                                    <p class="text-teal-700/80 text-sm">laiyagrandebr22@gmail.com</p>
                                </div>
                            </div>

                            <div class="group flex items-start gap-5">
                                <div class="flex-shrink-0 w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center border border-teal-100 group-hover:bg-teal-600 transition-colors duration-300 shadow-sm">
                                    <svg class="w-5 h-5 text-teal-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-teal-900 mb-1">Social Media</h3>
                                    <a href="https://web.facebook.com/laiyagrande" target="_blank" class="text-teal-700/80 text-sm hover:text-teal-600 transition-colors inline-flex items-center">
                                        Laiya Grande Beach Resort
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="bg-teal-900 rounded-3xl p-8 shadow-xl text-white relative overflow-hidden ring-1 ring-teal-800">
                        <div class="relative z-10">
                            <h3 class="font-playfair text-xl font-semibold mb-2">Urgent Inquiries?</h3>
                            <p class="text-teal-200 text-sm mb-5 font-light">For immediate booking assistance or emergencies.</p>
                            <a href="tel:09630337629" class="inline-flex items-center text-white font-medium bg-teal-800/50 px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors border border-teal-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                Call 0963 033 7629
                            </a>
                        </div>
                        <div class="absolute right-0 bottom-0 w-40 h-40 bg-teal-500/10 rounded-full -mr-10 -mb-10 blur-xl"></div>
                        <div class="absolute left-0 top-0 w-20 h-20 bg-teal-400/10 rounded-full -ml-5 -mt-5 blur-lg"></div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgb(20,184,166,0.15)] p-8 md:p-10 border border-teal-100/50">
                        <div class="mb-8">
                            <h2 class="text-3xl font-playfair font-semibold text-teal-900 mb-2">Send us a Message</h2>
                            <p class="text-teal-700/60 font-light">Fill out the form below and we'll get back to you shortly.</p>
                        </div>

                        @if (session('success'))
                            <div class="mb-8 p-4 bg-teal-50 border border-teal-200 rounded-xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-teal-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                <p class="text-teal-800 font-medium text-sm">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                    <div>
                                        <p class="text-red-800 font-medium text-sm mb-1">Please correct the errors below:</p>
                                        <ul class="text-xs text-red-600 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="text-sm font-medium text-teal-900">Full Name</label>
                                    <input type="text" id="name" name="name" required
                                        class="w-full px-5 py-3.5 bg-teal-50/30 border border-teal-100 rounded-xl text-teal-900 focus:bg-white focus:ring-2 focus:ring-teal-200 focus:border-teal-500 transition-all duration-300 placeholder:text-gray-400"
                                        placeholder="John Doe">
                                </div>

                                <div class="space-y-2">
                                    <label for="email" class="text-sm font-medium text-teal-900">Email Address</label>
                                    <input type="email" id="email" name="email" required
                                        class="w-full px-5 py-3.5 bg-teal-50/30 border border-teal-100 rounded-xl text-teal-900 focus:bg-white focus:ring-2 focus:ring-teal-200 focus:border-teal-500 transition-all duration-300 placeholder:text-gray-400"
                                        placeholder="john@example.com">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="phone" class="text-sm font-medium text-teal-900">Phone Number</label>
                                    <input type="tel" id="phone" name="phone"
                                        class="w-full px-5 py-3.5 bg-teal-50/30 border border-teal-100 rounded-xl text-teal-900 focus:bg-white focus:ring-2 focus:ring-teal-200 focus:border-teal-500 transition-all duration-300 placeholder:text-gray-400"
                                        placeholder="+63 9XX XXX XXXX">
                                </div>

                                <div class="space-y-2">
                                    <label for="subject" class="text-sm font-medium text-teal-900">Subject</label>
                                    <div class="relative">
                                        <select id="subject" name="subject" required
                                            class="appearance-none w-full px-5 py-3.5 bg-teal-50/30 border border-teal-100 rounded-xl text-teal-900 focus:bg-white focus:ring-2 focus:ring-teal-200 focus:border-teal-500 transition-all duration-300 cursor-pointer">
                                            <option value="" class="text-gray-400">Select a topic</option>
                                            <option value="reservation">Reservation Inquiry</option>
                                            <option value="booking">Booking Assistance</option>
                                            <option value="general">General Question</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="complaint">Complaint</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="message" class="text-sm font-medium text-teal-900">Message</label>
                                <textarea id="message" name="message" rows="5" required
                                    class="w-full px-5 py-3.5 bg-teal-50/30 border border-teal-100 rounded-xl text-teal-900 focus:bg-white focus:ring-2 focus:ring-teal-200 focus:border-teal-500 transition-all duration-300 placeholder:text-gray-400 resize-none"
                                    placeholder="How can we help you today?"></textarea>
                            </div>

                            <button type="submit" class="group w-full bg-teal-600 text-white font-medium py-4 px-6 rounded-xl hover:bg-teal-700 transition-all duration-300 shadow-lg shadow-teal-600/20 hover:shadow-teal-600/40 flex items-center justify-center gap-2">
                                Send Message
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection