@extends('layouts.app')

@section('title', 'Home | Fesmera Inc.')

@section('content')

<!-- Main Section -->
<section class="py-12 text-center bg-white">
    <div class="mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="Fesmera Inc. Logo" class="mx-auto h-20 w-20 rounded-full">
    </div>
    <h1 class="text-4xl md:text-5xl font-bold mb-3">Fesmera Inc.</h1>
    <p class="text-lg text-gray-600 mb-6">Hardware</p>

    <!-- Buttons -->
    <div class="flex flex-wrap justify-center gap-4">
        <a href="#" class="px-6 py-3 bg-amber-500 text-white font-medium rounded-md hover:bg-amber-600 transition">REQUEST</a>
        <a href="#" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-md hover:bg-emerald-600 transition">PURCHASE</a>
        <a href="#" class="px-6 py-3 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 transition">INVENTORY</a>
    </div>
</section>

<!-- Truck Banner -->
<div class="my-8">
    <img src="{{ asset('images/truck.jpg') }}" alt="Fesmera Inc. Truck" class="w-full h-80 object-cover rounded-lg">
</div>

<!-- About Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4 max-w-3xl">
        <h2 class="text-2xl font-bold text-center mb-6">ABOUT</h2>
        <div class="text-center text-gray-700 italic space-y-4">
            <p>
                Fesmera Inc. is a trusted hardware solutions provider dedicated to delivering high-quality tools, 
                equipment, and building materials to contractors, builders, and DIY enthusiasts across the region. 
                Since our inception, we’ve built a reputation for reliability, competitive pricing, and exceptional 
                customer service—ensuring every project, big or small, gets the right support from start to finish.
            </p>
            <p>
                Founded with a vision to empower local construction and home improvement efforts, Fesmera Inc. combines 
                industry expertise with a commitment to integrity and innovation. Our team is passionate about helping customers 
                find exactly what they need, when they need it—because your success is the foundation of ours.
            </p>
        </div>
    </div>
</section>

<!-- Owner Section -->
<section class="py-12 text-center">
    <div class="inline-block p-6 bg-white rounded-xl shadow-md">
        <i class="bi bi-person-circle text-5xl text-gray-500 mb-3"></i>
        <h5 class="text-lg font-semibold">Owner name</h5>
    </div>
</section>

@endsection