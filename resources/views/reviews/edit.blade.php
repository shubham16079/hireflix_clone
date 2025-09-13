@extends('layouts.app')

@section('title', 'Edit Review - ' . $review->submission->candidate_name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Review</h1>
            <p class="text-gray-600 mt-2">{{ $review->submission->candidate_name }} - {{ $review->submission->interview->title }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('submissions.show', $review->submission) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                ← Back to Submission
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Review Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">📝 Edit Review</h2>
                
                <form method="POST" action="{{ route('reviews.update', $review) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Score -->
                    <div class="mb-6">
                        <label for="score" class="block text-sm font-medium text-gray-700 mb-3">Overall Score</label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 10; $i++)
                                <label class="flex items-center">
                                    <input type="radio" name="score" value="{{ $i }}" 
                                        class="sr-only peer" {{ old('score', $review->score) == $i ? 'checked' : '' }}>
                                    <div class="w-12 h-12 border-2 border-gray-300 rounded-lg flex items-center justify-center cursor-pointer
                                        peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white
                                        hover:bg-gray-100 transition duration-200">
                                        {{ $i }}
                                    </div>
                                </label>
                            @endfor
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="text-red-600">1-3: Poor</span> | 
                            <span class="text-yellow-600">4-6: Average</span> | 
                            <span class="text-green-600">7-10: Good to Excellent</span>
                        </div>
                        @error('score')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comments -->
                    <div class="mb-6">
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                        <textarea name="comments" id="comments" rows="6" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('comments') border-red-500 @enderror"
                            placeholder="Provide detailed feedback about the candidate's performance...">{{ old('comments', $review->comments) }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="charCount">0</span>/2000 characters
                        </div>
                        @error('comments')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('submissions.show', $review->submission) }}" 
                            class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            Update Review
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Submission Summary -->
        <div class="space-y-6">
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

            <!-- Current Review Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">⭐ Current Review</h3>
                <div class="space-y-2">
                    <p><strong>Reviewer:</strong> {{ $review->reviewer->name }}</p>
                    <p><strong>Current Score:</strong> 
                        <span class="text-2xl font-bold text-blue-600">{{ $review->score }}/10</span>
                    </p>
                    <p><strong>Created:</strong> {{ $review->created_at->format('M j, Y g:i A') }}</p>
                    <p><strong>Last Updated:</strong> {{ $review->updated_at->format('M j, Y g:i A') }}</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Quick Stats</h3>
                <div class="space-y-2">
                    <p><strong>Started:</strong> {{ $review->submission->started_at ? $review->submission->started_at->format('M j, Y g:i A') : 'N/A' }}</p>
                    <p><strong>Submitted:</strong> {{ $review->submission->submitted_at ? $review->submission->submitted_at->format('M j, Y g:i A') : 'Not submitted' }}</p>
                    @if($review->submission->started_at && $review->submission->submitted_at)
                        <p><strong>Duration:</strong> {{ $review->submission->submitted_at->diffForHumans($review->submission->started_at, true) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentsTextarea = document.getElementById('comments');
    const charCount = document.getElementById('charCount');
    
    // Character count
    commentsTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // Initialize character count
    charCount.textContent = commentsTextarea.value.length;
    
    // Score selection feedback
    const scoreInputs = document.querySelectorAll('input[name="score"]');
    scoreInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Remove previous feedback
            document.querySelectorAll('.score-feedback').forEach(el => el.remove());
            
            // Add feedback
            const feedback = document.createElement('div');
            feedback.className = 'score-feedback mt-2 text-sm font-medium';
            
            const score = parseInt(this.value);
            if (score <= 3) {
                feedback.className += ' text-red-600';
                feedback.textContent = 'Poor - Significant improvement needed';
            } else if (score <= 6) {
                feedback.className += ' text-yellow-600';
                feedback.textContent = 'Average - Room for improvement';
            } else {
                feedback.className += ' text-green-600';
                feedback.textContent = 'Good to Excellent - Strong performance';
            }
            
            this.closest('.mb-6').appendChild(feedback);
        });
    });
});
</script>
@endpush
@endsection
