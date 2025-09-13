@extends('layouts.app')

@section('title', 'Create Interview')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full mx-auto">
    <h2 class="text-2xl font-bold text-center mb-6">Create New Interview</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('interviews.store') }}">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-semibold mb-2">Interview Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-gray-700 text-sm font-semibold mb-2">Description</label>
            <textarea id="description" name="description" rows="4" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Video Interview Settings -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🎥 Video Interview Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="allow_retakes" value="1" checked class="mr-2">
                        <span class="text-gray-700">Allow retakes</span>
                    </label>
                </div>
                <div>
                    <label for="max_retakes_per_question" class="block text-gray-700 text-sm font-semibold mb-2">
                        Max retakes per question
                    </label>
                    <input type="number" id="max_retakes_per_question" name="max_retakes_per_question" value="3" min="0" max="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="max_video_duration" class="block text-gray-700 text-sm font-semibold mb-2">
                        Max video duration (seconds)
                    </label>
                    <input type="number" id="max_video_duration" name="max_video_duration" value="300" min="30" max="1800"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="preparation_time" class="block text-gray-700 text-sm font-semibold mb-2">
                        Preparation time (seconds)
                    </label>
                    <input type="number" id="preparation_time" name="preparation_time" value="30" min="0" max="300"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="show_timer" value="1" checked class="mr-2">
                        <span class="text-gray-700">Show timer during recording</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="allow_pause" value="1" class="mr-2">
                        <span class="text-gray-700">Allow pause during recording</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="sequential_questions" value="1" checked class="mr-2">
                        <span class="text-gray-700">Sequential questions (must answer in order)</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="show_progress" value="1" checked class="mr-2">
                        <span class="text-gray-700">Show progress bar</span>
                    </label>
                </div>
            </div>
        </div>

        <div id="questions-container" class="space-y-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Interview Questions</label>
            <!-- Question input fields will be added here by JavaScript -->
        </div>

        <button type="button" id="add-question-btn"
            class="mt-4 w-full bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300">
            + Add Another Question
        </button>

        <button type="submit"
            class="mt-6 w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
            Create Interview
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('questions-container');
        const addButton = document.getElementById('add-question-btn');
        let questionIndex = 0;

        function addQuestionField() {
            const div = document.createElement('div');
            div.classList.add('flex', 'space-x-2', 'items-center');
            div.innerHTML = `
                <div class="flex-grow">
                    <input type="text" name="questions[${questionIndex}][text]" placeholder="e.g., Tell us about yourself." required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="button" class="remove-question-btn text-red-500 hover:text-red-700 font-bold text-lg">
                    &times;
                </button>
            `;
            container.appendChild(div);
            questionIndex++;
        }

        addButton.addEventListener('click', addQuestionField);

        container.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-question-btn')) {
                event.target.closest('div').remove();
            }
        });

        // Add the first question field by default
        addQuestionField();
    });
</script>
@endpush
