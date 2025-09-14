@extends('layouts.app')

@section('title', 'Review Details - ' . $review->submission->candidate_name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">⭐ Review Details</h1>
            <p class="text-gray-600 mt-2">{{ $review->submission->candidate_name }} - {{ $review->submission->interview->title }}</p>
        </div>
        <div class="flex space-x-4">
            @if($review->reviewer_id === Auth::id() || Auth::user()->role === 'admin')
                <a href="{{ route('reviews.edit', $review) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    ✏️ Edit Review
                </a>
            @endif
            <a href="{{ route('submissions.show', $review->submission) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                ← Back to Submission
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Review Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Score -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Review Score</h2>
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <div class="text-6xl font-bold text-blue-600">{{ $review->score }}</div>
                        <div class="text-lg text-gray-600">out of 10</div>
                    </div>
                    <div class="flex-1">
                        <div class="mb-2">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Score</span>
                                <span>{{ $review->score }}/10</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ ($review->score / 10) * 100 }}%"></div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">
                            <strong>Rating:</strong> {{ $review->score_description }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Review Comments -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">💬 Review Comments</h2>
                @if($review->comments)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $review->comments }}</p>
                    </div>
                @else
                    <div class="bg-gray-100 p-4 rounded-lg text-center text-gray-500">
                        No comments provided for this review.
                    </div>
                @endif
            </div>

            <!-- Interview Responses -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">🎥 Interview Responses</h2>
                <div class="space-y-6">
                    @foreach($review->submission->interview->questions as $index => $question)
                        @php
                            $response = $review->submission->responses->where('question_id', $question->id)->first();
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
            <!-- Review Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Review Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reviewer</label>
                        <p class="text-gray-900">{{ $review->reviewer->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Score</label>
                        <p class="text-gray-900">{{ $review->score }}/10 ({{ $review->score_description }})</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created</label>
                        <p class="text-gray-900">{{ $review->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                        <p class="text-gray-900">{{ $review->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Candidate Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">👤 Candidate</h3>
                <div class="space-y-2">
                    <p><strong>Name:</strong> {{ $review->submission->candidate_name }}</p>
                    <p><strong>Email:</strong> {{ $review->submission->candidate_email }}</p>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($review->submission->status === 'completed') bg-green-100 text-green-800
                            @elseif($review->submission->status === 'in_progress') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $review->submission->status)) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Interview Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Interview</h3>
                <div class="space-y-2">
                    <p><strong>Title:</strong> {{ $review->submission->interview->title }}</p>
                    <p><strong>Questions:</strong> {{ $review->submission->interview->questions->count() }}</p>
                    <p><strong>Completed:</strong> {{ $review->submission->completed_questions }}/{{ $review->submission->total_questions }}</p>
                    <p><strong>Progress:</strong> {{ $review->submission->completion_percentage }}%</p>
                </div>
            </div>

            <!-- Actions -->
            @if($review->reviewer_id === Auth::id() || Auth::user()->role === 'admin')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">⚙️ Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('reviews.edit', $review) }}" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-center block">
                            ✏️ Edit Review
                        </a>
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" 
                            onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
                                🗑️ Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    return Swal.fire({
        title: 'Delete Review?',
        text: "Are you sure you want to delete this review? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form
            event.target.closest('form').submit();
        }
        return false; // Prevent default form submission
    });
}
</script>
@endpush
@endsection
