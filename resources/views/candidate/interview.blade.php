@extends('layouts.candidate')

@section('title', 'Interview - ' . $interview->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Interview Header -->
    <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-8 mb-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">🎥 {{ $interview->title }}</h1>
            <p class="text-xl text-gray-600 mb-6">{{ $interview->description }}</p>
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <p class="text-blue-800">
                    <strong>Welcome!</strong> Please fill out your details below and answer all the questions. 
                    Take your time and provide thoughtful responses.
                </p>
            </div>
        </div>
    </div>

    <!-- Interview Form -->
    <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-8">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('candidate.submit', $token) }}" id="interviewForm">
            @csrf

            <!-- Candidate Information -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">👤 Your Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="candidate_name" class="block text-gray-700 text-sm font-semibold mb-2">
                            Full Name *
                        </label>
                        <input type="text" id="candidate_name" name="candidate_name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('candidate_name') border-red-500 @enderror"
                            placeholder="Enter your full name">
                        @error('candidate_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="candidate_email" class="block text-gray-700 text-sm font-semibold mb-2">
                            Email Address *
                        </label>
                        <input type="email" id="candidate_email" name="candidate_email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('candidate_email') border-red-500 @enderror"
                            placeholder="Enter your email address">
                        @error('candidate_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Interview Questions -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">❓ Interview Questions</h2>
                <div class="space-y-8">
                    @foreach($interview->questions as $index => $question)
                        <div class="bg-gray-50 p-6 rounded-lg border-l-4 border-blue-500">
                            <div class="flex items-start mb-4">
                                <span class="bg-blue-600 text-white text-lg font-bold rounded-full w-8 h-8 flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-grow">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                        {{ $question->question_text }}
                                    </h3>
                                    <div class="text-sm text-gray-500 mb-4">
                                        <span class="bg-gray-200 px-2 py-1 rounded">{{ ucfirst($question->type) }} Response</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="responses_{{ $question->id }}" class="block text-gray-700 text-sm font-semibold mb-2">
                                    Your Answer *
                                </label>
                                <textarea 
                                    id="responses_{{ $question->id }}" 
                                    name="responses[{{ $question->id }}]" 
                                    rows="6" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-vertical @error('responses.' . $question->id) border-red-500 @enderror"
                                    placeholder="Please provide a detailed answer to this question..."
                                >{{ old('responses.' . $question->id) }}</textarea>
                                @error('responses.' . $question->id)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submission -->
            <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-green-800 mb-2">✅ Ready to Submit?</h3>
                <p class="text-green-700 mb-4">
                    Please review your answers carefully. Once submitted, you won't be able to make changes.
                </p>
                <div class="flex items-center">
                    <input type="checkbox" id="confirm_submission" required class="mr-2">
                    <label for="confirm_submission" class="text-green-800 font-medium">
                        I confirm that I have reviewed my answers and am ready to submit
                    </label>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" id="submitBtn"
                    class="bg-green-600 text-white font-bold py-4 px-8 rounded-lg hover:bg-green-700 transition duration-300 text-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    🚀 Submit Interview
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('interviewForm');
        const submitBtn = document.getElementById('submitBtn');
        const confirmCheckbox = document.getElementById('confirm_submission');

        // Auto-save functionality (optional)
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                localStorage.setItem('interview_' + '{{ $token }}' + '_' + this.name, this.value);
            });
        });

        // Load saved data
        inputs.forEach(input => {
            const saved = localStorage.getItem('interview_' + '{{ $token }}' + '_' + input.name);
            if (saved && !input.value) {
                input.value = saved;
            }
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            if (!confirmCheckbox.checked) {
                e.preventDefault();
                alert('Please confirm that you have reviewed your answers before submitting.');
                return;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            // Clear saved data
            inputs.forEach(input => {
                localStorage.removeItem('interview_' + '{{ $token }}' + '_' + input.name);
            });
        });

        // Character count for textareas
        const textareas = form.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            const maxLength = 2000;
            const counter = document.createElement('div');
            counter.className = 'text-sm text-gray-500 mt-1';
            textarea.parentNode.appendChild(counter);

            function updateCounter() {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${textarea.value.length}/${maxLength} characters`;
                counter.className = remaining < 100 ? 'text-sm text-red-500 mt-1' : 'text-sm text-gray-500 mt-1';
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        });
    });
</script>
@endpush
