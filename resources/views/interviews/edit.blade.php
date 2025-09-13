@extends('layouts.app')

@section('title', 'Edit Interview')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-center">Edit Interview</h2>
        <a href="{{ route('interviews.show', $interview) }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300">
            Cancel
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('interviews.update', $interview) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-semibold mb-2">Interview Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $interview->title) }}" required autofocus
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-gray-700 text-sm font-semibold mb-2">Description</label>
            <textarea id="description" name="description" rows="4" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $interview->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="questions-container" class="space-y-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Interview Questions</label>
            <!-- Question input fields will be populated by JavaScript -->
        </div>

        <button type="button" id="add-question-btn"
            class="mt-4 w-full bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300">
            + Add Another Question
        </button>

        <div class="flex space-x-4 mt-6">
            <button type="submit"
                class="flex-1 bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Update Interview
            </button>
            <a href="{{ route('interviews.show', $interview) }}"
                class="flex-1 bg-gray-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300 text-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('questions-container');
        const addButton = document.getElementById('add-question-btn');
        let questionIndex = 0;

        // Load existing questions
        const existingQuestions = @json($interview->questions->sortBy('order'));
        existingQuestions.forEach(function(question) {
            addQuestionField(question.question_text);
        });

        // If no existing questions, add one empty field
        if (existingQuestions.length === 0) {
            addQuestionField();
        }

        function addQuestionField(value = '') {
            const div = document.createElement('div');
            div.classList.add('flex', 'space-x-2', 'items-center');
            div.innerHTML = `
                <div class="flex-grow">
                    <input type="text" name="questions[${questionIndex}][text]" placeholder="e.g., Tell us about yourself." required
                        value="${value}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="button" class="remove-question-btn text-red-500 hover:text-red-700 font-bold text-lg">
                    &times;
                </button>
            `;
            container.appendChild(div);
            questionIndex++;
        }

        addButton.addEventListener('click', function() {
            addQuestionField();
        });

        container.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-question-btn')) {
                event.target.closest('div').remove();
            }
        });
    });
</script>
@endpush
