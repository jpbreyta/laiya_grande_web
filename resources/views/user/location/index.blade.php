@extends('user.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-16">

  <div class="max-w-6xl mx-auto px-6 space-y-16">

    <!-- Hero Title -->
    <div class="text-center space-y-4 animate-fade-in-up">
      <h2 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text 
                 bg-gradient-to-r from-teal-600 via-cyan-500 to-sky-600 font-[Poppins] tracking-tight">
        About Laiya Grande
      </h2>
      <p class="text-lg md:text-xl text-gray-600 font-[Poppins] max-w-2xl mx-auto">
        Our story, vision, and how to find us.
      </p>
      <div class="flex justify-center mt-6">
        <div class="h-1 w-32 bg-gradient-to-r from-teal-400 via-cyan-400 to-sky-400 rounded-full animate-pulse"></div>
      </div>
    </div>

    <!-- About Us / History -->
    <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl px-10 py-12 space-y-6 
                animate-fade-in-up hover:scale-[1.01] hover:shadow-3xl transition-transform duration-500 ease-out">
      <h3 class="text-3xl font-semibold text-teal-700 font-[Poppins] mb-6">Our Story</h3>
      <p class="text-base md:text-lg text-gray-700 leading-relaxed font-[Poppins]">
        Nestled along the pristine shores of Laiya Aplaya in San Juan, Batangas, 
        <span class="font-semibold text-teal-600">Laiya Grande Beach Resort</span> has been a beloved destination since the early 2000s. 
        What began as a modest beachfront retreat has grown into one of Laiya’s most recognized names in coastal hospitality.
      </p>
      <p class="text-base md:text-lg text-gray-700 leading-relaxed font-[Poppins]">
        Inspired by the natural beauty of Batangas — from its white sand beaches to its lush mountain views — 
        Laiya Grande was built with the vision of blending relaxation, recreation, and Filipino warmth. 
        Over the years, thousands of guests have enjoyed not just accommodations, but memorable experiences by the sea.
      </p>
      <p class="text-base md:text-lg text-gray-700 leading-relaxed font-[Poppins]">
        Today, Laiya Grande continues to evolve, offering upgraded amenities, curated activities, and personalized service — 
        all while preserving the charm and serenity that made it a favorite getaway.
      </p>
    </div>

    <!-- Mission & Vision -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="bg-gradient-to-br from-white to-teal-50 rounded-2xl shadow-lg px-8 py-10 
                  animate-fade-in-up hover:scale-[1.02] transition-transform duration-500 ease-out">
        <h3 class="text-2xl font-semibold text-teal-700 font-[Poppins] mb-4">Our Mission</h3>
        <p class="text-gray-700 font-[Poppins] leading-relaxed">
          To provide a serene and welcoming beachfront experience that celebrates Filipino hospitality, nature, and relaxation.
        </p>
      </div>
      <div class="bg-gradient-to-br from-white to-cyan-50 rounded-2xl shadow-lg px-8 py-10 
                  animate-fade-in-up motion-safe:delay-200 hover:scale-[1.02] transition-transform duration-500 ease-out">
        <h3 class="text-2xl font-semibold text-teal-700 font-[Poppins] mb-4">Our Vision</h3>
        <p class="text-gray-700 font-[Poppins] leading-relaxed">
          To be the leading beach resort in Laiya, known for exceptional service, sustainable practices, and unforgettable guest experiences.
        </p>
      </div>
    </div>

    <!-- What Makes Us Different -->
    <div class="bg-gradient-to-br from-white to-teal-50 rounded-3xl shadow-2xl px-10 py-12 space-y-6 
                animate-fade-in-up hover:scale-[1.01] hover:shadow-3xl transition-transform duration-500 ease-out">
      <h3 class="text-3xl font-semibold text-teal-700 font-[Poppins] mb-6 text-center">What Makes Us Different</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 font-[Poppins] text-base md:text-lg">
        <div class="flex items-start gap-3 animate-fade-in-up motion-safe:delay-100">
          <span class="text-teal-600 font-bold">•</span> Personalized guest service with a warm Filipino touch
        </div>
        <div class="flex items-start gap-3 animate-fade-in-up motion-safe:delay-200">
          <span class="text-teal-600 font-bold">•</span> Direct beachfront access to Laiya’s pristine shores
        </div>
        <div class="flex items-start gap-3 animate-fade-in-up motion-safe:delay-300">
          <span class="text-teal-600 font-bold">•</span> Blend of tradition and modern comfort
        </div>
        <div class="flex items-start gap-3 animate-fade-in-up motion-safe:delay-400">
          <span class="text-teal-600 font-bold">•</span> Commitment to sustainability and respect for nature
        </div>
        <div class="flex items-start gap-3 animate-fade-in-up motion-safe:delay-500">
          <span class="text-teal-600 font-bold">•</span> Curated experiences designed to create lasting memories
        </div>
      </div>
    </div>

        <!-- Closing Section -->
    <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl px-10 py-14 space-y-8 
                animate-fade-in-up hover:shadow-3xl transition-transform duration-500 ease-out text-center">
      <h3 class="text-3xl font-semibold text-teal-700 font-[Poppins]">Experience Laiya Grande</h3>
      <p class="text-base md:text-lg text-gray-700 font-[Poppins] leading-relaxed max-w-3xl mx-auto">
        Whether you’re seeking a peaceful retreat, a family getaway, or a place to celebrate life’s milestones, 
        Laiya Grande Beach Resort offers the perfect blend of nature, comfort, and Filipino hospitality. 
        Come and be part of our story — your unforgettable seaside escape awaits.
      </p>
      <div class="flex justify-center mt-6">
        <a href="/booking" 
           class="inline-block bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold px-8 py-3 rounded-full shadow-md hover:shadow-lg hover:scale-[1.03] transition-transform duration-300 ease-out">
          Book Your Stay
        </a>
      </div>
    </div>

    <!-- Accent Divider -->
    <div class="mt-16 flex justify-center animate-fade-in-up motion-safe:delay-600">
      <div class="h-1 w-40 bg-gradient-to-r from-teal-400 via-cyan-400 to-sky-400 rounded-full animate-pulse"></div>
    </div>

    <!-- Location Section -->
<div class="space-y-8">

  <!-- Map -->
  <div class="bg-white rounded-3xl shadow-xl px-8 py-10 flex flex-col justify-between animate-fade-in-up">
    <h3 class="text-2xl font-semibold text-center text-teal-700 font-[Poppins] mb-6">Find Us on the Map</h3>
    <div class="flex-grow">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.7464965381914!2d121.39534827490587!3d13.673172786710557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd244ce27742e5%3A0xa4c7df8e965a6e99!2sLaiya%20Grande%20Beach%20Resort!5e0!3m2!1sen!2sph!4v1761321246805!5m2!1sen!2sph"
        width="100%"
        height="360"
        style="border:0;"
        allowfullscreen
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>

  <!-- Location Information -->
  <div class="bg-white rounded-3xl shadow-xl px-8 py-10 space-y-8 animate-fade-in-up">
    <h3 class="text-2xl font-semibold text-center text-teal-700 font-[Poppins]">Location Information</h3>

    @foreach([
      ['fa-map-marker-alt', 'Address', 'Laiya Grande Beach Resort<br>Laiya Aplaya, San Juan, Batangas, 4226<br>Philippines'],
      ['fa-phone-alt', 'Phone', 'Globe: (0977) 222 4792<br>Smart: (0963) 003 7629'],
      ['fa-envelope', 'Email', 'laiyagrandebr22@gmail.com'],
      ['fa-clock', 'Estimated Travel Time', 'Approximately 3 hours (136 km) from Metro Manila to Laiya Grande Beach Resort.'],
      ['fa-car', 'Via Private Transportation', 'Exit from South Luzon Expressway (SLEX)... follow the road going to Laiya Aplaya.'],
    ] as [$icon, $label, $text])
      <div class="flex items-start gap-3">
        <i class="fas {{ $icon }} text-teal-600 text-lg mt-1"></i>
        <div>
          <p class="text-sm font-semibold text-gray-500">{{ $label }}</p>
          <p class="text-sm text-gray-700 leading-relaxed">{!! $text !!}</p>
        </div>
      </div>
    @endforeach

    <!-- Public Transportation -->
    <div class="flex items-start gap-3">
      <i class="fas fa-bus text-teal-600 text-lg mt-1"></i>
      <div>
        <p class="text-sm font-semibold text-gray-500">Via Public Transportation</p>
        <p class="text-sm text-gray-700 leading-relaxed">
          Laiya Grande Beach Resort can be reached through the following bus terminals:
        </p>
        <ul class="list-disc list-inside text-sm text-gray-700 ml-4 mt-2 space-y-1">
          <li>Cubao Bus Terminal</li>
          <li>LRT Station Bus Terminal</li>
          <li>Alabang South Station</li>
        </ul>
        <p class="text-sm text-gray-700 mt-3 leading-relaxed">
          Take a bus bound for San Juan, Batangas or Lipa City... ride a tricycle directly to Laiya Grande Beach Resort in Laiya Aplaya.
        </p>
      </div>
    </div>
  </div>
</div>


  </div>
</div>
@endsection
