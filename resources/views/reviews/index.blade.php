@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">⭐ Reviews</h1>
        <div class="flex space-x-4">
            <a href="{{ route('submissions.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                📊 View Submissions
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">🔍 Filters</h2>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="interview_id" class="block text-sm font-medium text-gray-700 mb-2">Interview</label>
                <select name="interview_id" id="interview_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Interviews</option>
                    @foreach($interviews as $interview)
                        <option value="{{ $interview->id }}" {{ request('interview_id') == $interview->id ? 'selected' : '' }}>
                            {{ $interview->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="reviewer_id" class="block text-sm font-medium text-gray-700 mb-2">Reviewer</label>
                <select name="reviewer_id" id="reviewer_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Reviewers</option>
                    <option value="{{ Auth::id() }}" {{ request('reviewer_id') == Auth::id() ? 'selected' : '' }}>My Reviews</option>
                </select>
            </div>
            <div>
                <label for="min_score" class="block text-sm font-medium text-gray-700 mb-2">Min Score</label>
                <select name="min_score" id="min_score" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Any</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ request('min_score') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="max_score" class="block text-sm font-medium text-gray-700 mb-2">Max Score</label>
                <select name="max_score" id="max_score" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Any</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ request('max_score') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    Apply Filters
                </button>
                <a href="{{ route('reviews.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-300 ml-2">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">All Reviews ({{ $reviews->total() }})</h2>
        </div>
        
        @if($reviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interview</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $review->submission->candidate_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->submission->candidate_email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $review->submission->interview->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $review->reviewer->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold 
                                            @if($review->score >= 8) text-green-600
                                            @elseif($review->score >= 6) text-yellow-600
                                            @else text-red-600 @endif">
                                            {{ $review->score }}
                                        </span>
                                        <span class="text-sm text-gray-500 ml-1">/10</span>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $review->score_description }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $review->comments ? Str::limit($review->comments, 50) : 'No comments' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $review->created_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('reviews.show', $review) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    @if($review->reviewer_id === Auth::id() || Auth::user()->role === 'admin')
                                        <a href="{{ route('reviews.edit', $review) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <div class="text-gray-500 text-lg">No reviews found.</div>
                <div class="text-gray-400 text-sm mt-2">Try adjusting your filters or complete some interviews first.</div>
            </div>
        @endif
    </div>
</div>
@endsection
