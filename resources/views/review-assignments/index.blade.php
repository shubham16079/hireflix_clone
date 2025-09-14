@extends('layouts.app')

@section('title', 'Review Assignments')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📋 My Review Assignments</h1>
        <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
            ← Back to Dashboard
        </a>
    </div>

    @if($assignments->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interview</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assignments as $assignment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $assignment->interview->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $assignment->interview->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignment->assignedBy->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($assignment->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($assignment->status === 'declined') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignment->assigned_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($assignment->status === 'assigned')
                                        <form method="POST" action="{{ route('review-assignments.accept', $assignment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Accept</button>
                                        </form>
                                        <form method="POST" action="{{ route('review-assignments.decline', $assignment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">Decline</button>
                                        </form>
                                    @elseif($assignment->status === 'accepted')
                                        <a href="{{ route('submissions.for-interview', $assignment->interview) }}" class="text-blue-600 hover:text-blue-900">
                                            View Submissions
                                        </a>
                                    @else
                                        <span class="text-gray-400">No actions available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $assignments->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-6xl mb-4">📋</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">No Review Assignments</h2>
            <p class="text-gray-600 mb-6">You don't have any review assignments yet.</p>
            <p class="text-sm text-gray-500">Interview creators will invite you to review submissions, and you'll see those assignments here.</p>
        </div>
    @endif
</div>
@endsection
