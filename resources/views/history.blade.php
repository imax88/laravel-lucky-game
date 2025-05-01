@extends('layouts.app')

@section('title', 'Game History')

@section('content')
    <h1 class="text-3xl font-playfair font-bold text-primary-dark mb-6">Game History</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-custom">
            {!! session('success') !!}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-custom">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    @if ($history->isEmpty())
        <p class="text-text-light">No game history available.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-primary-dark text-white">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Random Number</th>
                        <th class="px-4 py-2 text-left">Winnings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $record)
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-2">{{ $record->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-2">{{ $record->random_number }}</td>
                            <td class="px-4 py-2">{{ $record->winnings }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <a href="{{ route('link.show', ['uuid' => $uuid]) }}" class="mt-4 inline-block bg-primary-accent text-white px-4 py-2 rounded-md hover:bg-primary-dark transition shadow-custom">Back to Link</a>
@endsection
