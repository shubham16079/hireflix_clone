@if($submissions->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'candidate_name', 'sort_order' => request('sort_by') == 'candidate_name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                           class="hover:text-gray-700 flex items-center">
                            Candidate
                            @if(request('sort_by') == 'candidate_name')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                           class="hover:text-gray-700 flex items-center">
                            Status
                            @if(request('sort_by') == 'status')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'submitted_at', 'sort_order' => request('sort_by') == 'submitted_at' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                           class="hover:text-gray-700 flex items-center">
                            Submitted
                            @if(request('sort_by') == 'submitted_at')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviews</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($submissions as $submission)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $submission->candidate_name }}</div>
                                <div class="text-sm text-gray-500">{{ $submission->candidate_email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($submission->status === 'completed') bg-green-100 text-green-800
                                @elseif($submission->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($submission->status === 'abandoned') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $submission->completion_percentage }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $submission->completion_percentage }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $submission->submitted_at ? $submission->submitted_at->format('M j, Y g:i A') : 'Not submitted' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $submission->reviews->count() }} review(s)
                            @if($submission->reviews->count() > 0)
                                <div class="text-xs text-gray-400">
                                    Avg: {{ round($submission->reviews->avg('score'), 1) }}/10
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('submissions.show', $submission) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            @if($submission->status === 'completed')
                                <a href="{{ route('reviews.create', $submission) }}" class="text-green-600 hover:text-green-900">Review</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $submissions->links() }}
    </div>
@else
    <div class="px-6 py-8 text-center">
        <div class="text-gray-500 text-lg">No submissions found matching your criteria.</div>
        <div class="text-gray-400 text-sm mt-2">Try adjusting your search or filter settings.</div>
    </div>
@endif
