@extends('layouts.app')

@section('title', 'Submission - ' . $submission->candidate_name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📋 Submission Details</h1>
            <p class="text-gray-600 mt-2">{{ $submission->candidate_name }} - {{ $submission->interview->title }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('submissions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                ← Back to Submissions
            </a>
            @if($submission->status === 'completed')
                <a href="{{ route('reviews.create', $submission) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                    📝 Add Review
                </a>
            @endif
        </div>
    </div>

    <!-- Submission Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Candidate Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">👤 Candidate Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-gray-900">{{ $submission->candidate_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-gray-900">{{ $submission->candidate_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($submission->status === 'completed') bg-green-100 text-green-800
                            @elseif($submission->status === 'in_progress') bg-yellow-100 text-yellow-800
                            @elseif($submission->status === 'abandoned') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Completion</label>
                        <div class="flex items-center">
                            <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $submission->completion_percentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">{{ $submission->completion_percentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interview Responses -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">🎥 Interview Responses</h2>
                <div class="space-y-6">
                    @foreach($submission->interview->questions as $index => $question)
                        @php
                            $response = $submission->responses->where('question_id', $question->id)->first();
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                        Question {{ $index + 1 }}: {{ $question->question_text }}
                                    </h3>
                                    @if($response)
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                                {{ ucfirst($response->response_type) }} Response
                                            </span>
                                            @if($response->response_type === 'video')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                                    {{ round($response->video_size / 1024 / 1024, 2) }} MB
                                                </span>
                                            @endif
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                                {{ $response->completed_at ? $response->completed_at->format('M j, Y g:i A') : 'Not completed' }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm">No Response</span>
                                    @endif
                                </div>
                            </div>

                            @if($response)
                                @if($response->response_type === 'video')
                                    <div class="bg-gray-900 rounded-lg overflow-hidden">
                                        <video controls class="w-full h-64 object-cover">
                                            <source src="{{ $response->video_url }}" type="video/webm">
                                            <source src="{{ $response->video_url }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @else
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-gray-800 whitespace-pre-wrap">{{ $response->response_text }}</p>
                                    </div>
                                @endif
                            @else
                                <div class="bg-gray-100 p-4 rounded-lg text-center text-gray-500">
                                    No response provided for this question.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Interview Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Interview Details</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <p class="text-gray-900">{{ $submission->interview->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="text-gray-900">{{ $submission->interview->description }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Questions</label>
                        <p class="text-gray-900">{{ $submission->interview->questions->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">⏰ Timeline</h2>
                <div class="space-y-3">
                    @if($submission->started_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Started</p>
                                <p class="text-xs text-gray-500">{{ $submission->started_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($submission->last_activity_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Activity</p>
                                <p class="text-xs text-gray-500">{{ $submission->last_activity_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($submission->submitted_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Submitted</p>
                                <p class="text-xs text-gray-500">{{ $submission->submitted_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">⭐ Reviews</h2>
                @if($submission->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($submission->reviews as $review)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $review->reviewer->name }}</h4>
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-yellow-500">{{ $review->score }}</span>
                                        <span class="text-sm text-gray-500 ml-1">/10</span>
                                    </div>
                                </div>
                                @if($review->comments)
                                    <p class="text-sm text-gray-700">{{ $review->comments }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-4">
                        <p class="text-sm">No reviews yet.</p>
                        @if($submission->status === 'completed')
                            <a href="{{ route('reviews.create', $submission) }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                                Add the first review
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
