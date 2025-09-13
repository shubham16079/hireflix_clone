@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🎥 Welcome to Hireflix</h1>
                <p class="text-gray-600 mt-2">Hello {{ Auth::user()->name }}, you are logged in as a <span class="font-bold capitalize text-blue-600">{{ Auth::user()->role }}</span></p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Last login: {{ Auth::user()->updated_at->format('M j, Y g:i A') }}</p>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'reviewer')
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Interviews -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Interviews</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ Auth::user()->interviews()->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Submissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Submissions</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            @php
                                $totalSubmissions = \App\Models\Submission::whereHas('interview', function($q) {
                                    $q->where('created_by', Auth::id());
                                })->count();
                            @endphp
                            {{ $totalSubmissions }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Completed Reviews -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">My Reviews</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            @php
                                $myReviews = \App\Models\Review::where('reviewer_id', Auth::id())->count();
                            @endphp
                            {{ $myReviews }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Reviews -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            @php
                                $pendingReviews = \App\Models\Submission::whereHas('interview', function($q) {
                                    $q->where('created_by', Auth::id());
                                })->where('status', 'completed')
                                ->whereDoesntHave('reviews', function($q) {
                                    $q->where('reviewer_id', Auth::id());
                                })->count();
                            @endphp
                            {{ $pendingReviews }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Interviews -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Recent Interviews</h2>
                @php
                    $recentInterviews = Auth::user()->interviews()->latest()->take(5)->get();
                @endphp
                @if($recentInterviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentInterviews as $interview)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $interview->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $interview->submissions->count() }} submissions</p>
                                </div>
                                <a href="{{ route('interviews.show', $interview) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    View →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No interviews created yet.</p>
                        <a href="{{ route('interviews.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            Create your first interview →
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Submissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Recent Submissions</h2>
                @php
                    $recentSubmissions = \App\Models\Submission::whereHas('interview', function($q) {
                        $q->where('created_by', Auth::id());
                    })->with('interview')->latest()->take(5)->get();
                @endphp
                @if($recentSubmissions->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentSubmissions as $submission)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $submission->candidate_name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $submission->interview->title }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($submission->status === 'completed') bg-green-100 text-green-800
                                        @elseif($submission->status === 'in_progress') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No submissions yet.</p>
                        <p class="text-sm mt-1">Share interview links with candidates to start receiving submissions.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">🚀 Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('interviews.create') }}" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 transition duration-300 text-center">
                    <div class="text-2xl mb-2">➕</div>
                    <div class="font-semibold">Create Interview</div>
                </a>
                <a href="{{ route('interviews.index') }}" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 transition duration-300 text-center">
                    <div class="text-2xl mb-2">📋</div>
                    <div class="font-semibold">View Interviews</div>
                </a>
                <a href="{{ route('submissions.index') }}" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 transition duration-300 text-center">
                    <div class="text-2xl mb-2">📊</div>
                    <div class="font-semibold">View Submissions</div>
                </a>
                <a href="{{ route('reviews.index') }}" class="bg-yellow-600 text-white p-4 rounded-lg hover:bg-yellow-700 transition duration-300 text-center">
                    <div class="text-2xl mb-2">⭐</div>
                    <div class="font-semibold">View Reviews</div>
                </a>
            </div>
        </div>
    @else
        <!-- Candidate Dashboard -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">👤 Candidate Dashboard</h2>
            <p class="text-gray-600 mb-6">You are logged in as a candidate. Use the interview links provided by recruiters to take video interviews.</p>
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-blue-800 font-medium">💡 Tip: Make sure you have a working camera and microphone before starting an interview.</p>
            </div>
        </div>
    @endif
</div>
@endsection
