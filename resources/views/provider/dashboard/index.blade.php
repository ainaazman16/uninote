@extends('layouts.app')

@section('contents')
    <h2 class="text-2xl font-bold mb-6">Provider Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- Total Notes -->
        <div class="p-4 bg-white shadow rounded">
            <h3 class="text-gray-600 text-sm">Total Notes</h3>
            <p class="text-3xl font-semibold">{{ $totalNotes }}</p>
        </div>

        <!-- Approved -->
        <div class="p-4 bg-green-100 border border-green-300 shadow rounded">
            <h3 class="text-green-700 text-sm">Approved Notes</h3>
            <p class="text-3xl font-semibold text-green-800">{{ $approvedNotes }}</p>
        </div>

        <!-- Pending -->
        <div class="p-4 bg-yellow-100 border border-yellow-300 shadow rounded">
            <h3 class="text-yellow-700 text-sm">Pending Approval</h3>
            <p class="text-3xl font-semibold text-yellow-800">{{ $pendingNotes }}</p>
        </div>

        <!-- Rejected -->
        <div class="p-4 bg-red-100 border border-red-300 shadow rounded">
            <h3 class="text-red-700 text-sm">Rejected Notes</h3>
            <p class="text-3xl font-semibold text-red-800">{{ $rejectedNotes }}</p>
        </div>

    </div>

    <!-- Upload Button -->
    <div class="mt-6">
        <a href="{{ route('notes.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Upload New Note
        </a>
    </div>

@endsection
