@extends('user.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-12">
  <div class="max-w-6xl mx-auto px-4 py-10 space-y-8">

    <!-- Title -->
    <h2 class="text-3xl font-semibold text-center text-teal-700">How to Locate Us</h2>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Left Column: Map + Contact Form -->
      <div class="space-y-6">

        <!-- Map Panel -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.7464965381914!2d121.39534827490587!3d13.673172786710557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd244ce27742e5%3A0xa4c7df8e965a6e99!2sLaiya%20Grande%20Beach%20Resort!5e0!3m2!1sen!2sph!4v1761321246805!5m2!1sen!2sph" 
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>

        <!-- Contact Form -->
        <div class="bg-white rounded-xl shadow-md px-6 py-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Send us a Message</h3>
          <form method="POST" action="#" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Subject</label>
              <input type="text" name="subject" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Message</label>
              <textarea name="message" rows="5" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
            </div>
            <button type="submit" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 rounded-md transition">
              Send Message
            </button>
          </form>
        </div>
      </div>

      <!-- Right Column -->
      <div class="bg-white rounded-xl shadow-md px-6 py-6 space-y-8">

        <h3 class="text-1xl font-semibold text-center text-teal-700">Other Information</h3>

        <!-- Address -->
        <div class="flex items-start gap-4">
          <svg class="h-5 w-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 1011.314-11.314l-4.243 4.243z" />
          </svg>
          <div>
            <p class="text-xs font-semibold text-gray-500 font-serif mb-1">Address</p>
            <p class="text-xs text-gray-700 leading-relaxed">
              Laiya Grande Beach Resort<br>
              Laiya Aplaya, San Juan, Batangas, 4226<br>
              Philippines
            </p>
          </div>
        </div>

        <!-- Phone -->
        <div class="flex items-start gap-4">
          <svg class="h-5 w-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 5h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13a1 1 0 001-1v-1H6.5M7 13l-4-8m0 0H1" />
          </svg>
          <div>
            <p class="text-xs font-semibold text-gray-500 font-serif mb-1">Phone</p>
            <p class="text-xs text-gray-700 leading-relaxed">
              Globe: (0977) 222 4792<br>
              Smart: (0963) 003 7629
            </p>
          </div>
        </div>

        <!-- Email -->
        <div class="flex items-start gap-4">
          <svg class="h-5 w-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 12H8m0 0l4-4m-4 4l4 4m8-4a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-xs font-semibold text-gray-500 font-serif mb-1">Email</p>
            <p class="text-xs text-gray-700 leading-relaxed">laiyagrandebr22@gmail.com</p>
          </div>
        </div>

        <!-- Travel Time -->
        <div class="flex items-start gap-4">
          <svg class="h-5 w-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 1.343-3 3v1h6v-1c0-1.657-1.343-3-3-3zM5 20h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
          </svg>
          <div>
            <p class="text-xs font-semibold text-gray-500 font-serif mb-1">Estimated Travel Time</p>
            <p class="text-xs text-gray-700 leading-relaxed">
              Approximately 3 hours (136 km) from Metro Manila to Laiya Grande Beach Resort.
            </p>
          </div>
        </div>

        <!-- Private Transportation -->
        <div class="flex items-start gap-4">
          <svg class="h-5 w-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 13l2-2m0 0l7-7 7 7M13 5v6h6M5 13v6h14v-6" />
          </svg>
          <div>
            <p class="text-xs font-semibold text-gray-500 font-serif mb-1">Via Private Transportation</p>
            <p class="text-xs text-gray-700 leading-relaxed">
              Exit from South Luzon Expressway (SLEX) and take the exit toward Calabarzon Expressway/STAR Tollway going to Lipa City. From STAR Tollway, take the Ibaan Exit and follow the road going to Rosario, Batangas. From Rosario, follow the road going to San Juan, Batangas. Upon reaching San Juan town proper, turn right at the Municipal Hall (San Juan-Laiya Road) and follow the road going to Laiya Aplaya. Laiya Grande Beach Resort is located along the beach.
            </p>
          </div>
        </div>

        <!-- Public Transportation -->
        <div class="flex items-start gap-4">
          <svg class="h-5 w-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 17l-4 4m0 0l4-4m-4 4h16M4 4h16v12H4z" />
          </svg>
          <div>
            <p class="text-xs font-semibold text-gray-500 font-serif mb-1">Via Public Transportation</p>
            <p class="text-xs text-gray-700 leading-relaxed">
              Laiya Grande Beach Resort can be reached through the following bus terminals:
            </p>
            <ul class="list-disc list-inside text-xs text-gray-700 ml-4 mt-2 space-y-1">
              <li>Cubao Bus Terminal</li>
              <li>LRT Station Bus Terminal</li>
              <li>Alabang South Station</li>
            </ul>
            <p class="text-xs text-gray-700 mt-3 leading-relaxed">
              Take a bus bound for San Juan, Batangas or Lipa City. If bound for Lipa City, get off at SM Lipa City Grand Terminal and take a jeepney or van to San Juan, Batangas. From San Juan town proper, ride a tricycle directly to Laiya Grande Beach Resort in Laiya Aplaya.
            </p>
          </div>
        </div>

@endsection
