@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h1 class="text-3xl font-playfair font-bold text-primary-dark mb-6">Register </h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-custom">
            {!! session('success') !!}
        </div>
    @endif
    @if ($activeLink)
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6 shadow-custom">
            Welcome back! Your active link: <a href="{{ route('link.show', ['uuid' => $activeLink->uuid]) }}" class="underline text-primary-accent hover:text-highlight">{{ route('link.show', ['uuid' => $activeLink->uuid]) }}</a>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-custom">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('register.store') }}" class="max-w-md mx-auto">
        @csrf
        <div class="mb-6">
            <label for="username" class="block text-sm font-medium text-text-dark mb-2">Username</label>
            <input type="text" name="username" id="username" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent shadow-custom" required value="{{ old('username') }}">
            @error('username')
                <span class="text-highlight text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-6">
            <label for="phonenumber" class="block text-sm font-medium text-text-dark mb-2">Phone Number</label>
            <input type="text" name="phonenumber" id="phonenumber" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-accent shadow-custom" required value="{{ old('phonenumber') }}">
            @error('phonenumber')
                <span class="text-highlight text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="w-full bg-primary-accent text-white px-4 py-2 rounded-md hover:bg-primary-dark transition shadow-custom">Submit</button>
    </form>
@endsection
