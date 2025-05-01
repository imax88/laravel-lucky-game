@extends('layouts.app')

@section('title', 'Your Link')

@section('content')
    <h1 class="text-3xl font-playfair font-bold text-primary-dark mb-6">Your Unique Link</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-custom">
            {!! session('success') !!}
        </div>
    @endif
    @if (isset($game_result) && isset($game_result['random_number']) && isset($game_result['winnings']))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6 shadow-custom">
            You got {{ $game_result['random_number'] }}! Winnings: {{ $game_result['winnings'] }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-custom">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="max-w-md mx-auto">
        <form method="POST" action="{{ route('game.play', ['uuid' => $uuid]) }}" class="mb-4">
            @csrf
            <button type="submit" class="w-full bg-primary-accent text-white px-4 py-2 rounded-md hover:bg-primary-dark transition shadow-custom">I'm Feeling Lucky</button>
        </form>
        <form method="POST" action="{{ route('link.generate', ['uuid' => $uuid]) }}" class="mb-4">
            @csrf
            <button type="submit" class="w-full bg-secondary-accent text-white px-4 py-2 rounded-md hover:bg-highlight transition shadow-custom">Generate New Link</button>
        </form>
        <form method="POST" action="{{ route('link.deactivate', ['uuid' => $uuid]) }}" class="mb-4">
            @csrf
            <button type="submit" class="w-full bg-highlight text-white px-4 py-2 rounded-md hover:bg-primary-dark transition shadow-custom">Deactivate Link</button>
        </form>
        <a href="{{ route('game.history', ['uuid' => $uuid]) }}" class="block w-full text-center bg-primary-dark text-white px-4 py-2 rounded-md hover:bg-primary-accent transition shadow-custom">History</a>
    </div>
@endsection
