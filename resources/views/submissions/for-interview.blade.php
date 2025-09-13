@extends('layouts.app')

@section('title', 'Submissions - ' . $interview->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📊 Submissions</h1>
            <p class="text-gray-600 mt-2">{{ $interview->title }}</p>
        </div>
        <div class="flex space-x-4">
            @if($statistics['completed'] > 0)
                <button onclick="openReviewerModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                    👥 Send to Reviewers
                </button>
            @endif
            <button onclick="copyInterviewLink()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                📋 Copy Interview Link
            </button>
            <a href="{{ route('submissions.export', $interview) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                📥 Export CSV
            </a>
            <a href="{{ route('interviews.show', $interview) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                ← Back to Interview
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Submissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['completed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Progress</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['in_progress'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Score</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ round($statistics['average_score'], 1) }}/10
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <h2 class="text-xl font-semibold text-gray-800">🔍 Search & Filter Submissions</h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                <button onclick="clearFilters()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 text-sm">
                    🗑️ Clear Filters
                </button>
                <button onclick="exportFiltered()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 text-sm">
                    📥 Export Filtered
                </button>
            </div>
        </div>
        
        <form id="filterForm" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Search by name or email...">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="abandoned" {{ request('status') == 'abandoned' ? 'selected' : '' }}>Abandoned</option>
                </select>
            </div>
            
            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Date To -->
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </form>
        
        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="text-sm text-gray-600">Active filters:</span>
                @if(request('search'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Search: "{{ request('search') }}"
                        <button onclick="removeFilter('search')" class="ml-1 text-blue-600 hover:text-blue-800">×</button>
                    </span>
                @endif
                @if(request('status'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                        <button onclick="removeFilter('status')" class="ml-1 text-green-600 hover:text-green-800">×</button>
                    </span>
                @endif
                @if(request('date_from'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        From: {{ request('date_from') }}
                        <button onclick="removeFilter('date_from')" class="ml-1 text-purple-600 hover:text-purple-800">×</button>
                    </span>
                @endif
                @if(request('date_to'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        To: {{ request('date_to') }}
                        <button onclick="removeFilter('date_to')" class="ml-1 text-purple-600 hover:text-purple-800">×</button>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">All Submissions ({{ $submissions->total() }})</h2>
        </div>
        
        <div id="submissionsTable">
            @include('submissions.partials.submissions-table', ['submissions' => $submissions, 'interview' => $interview])
        </div>
    </div>
</div>

<!-- Reviewer Invitation Modal -->
<div id="reviewerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">👥 Send to Reviewers</h3>
                <button onclick="closeReviewerModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('interviews.share-reviewers', $interview) }}" class="space-y-4">
                @csrf
                <div>
                    <label for="reviewer_emails" class="block text-sm font-medium text-gray-700 mb-1">Reviewer Email Addresses</label>
                    <input type="text" id="reviewer_emails" name="reviewer_emails" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="reviewer1@company.com, reviewer2@company.com">
                    <p class="text-sm text-gray-500 mt-1">Separate multiple emails with commas</p>
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea id="message" name="message" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Add a message for the reviewers...">{{ $statistics['completed'] }} completed submission(s) ready for review.</textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReviewerModal()" 
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                        Send Invitations
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Global variables
let filterTimeout;
const interviewId = {{ $interview->id }};

// Modal functions
function openReviewerModal() {
    document.getElementById('reviewerModal').classList.remove('hidden');
}

function closeReviewerModal() {
    document.getElementById('reviewerModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('reviewerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewerModal();
    }
});

// Filter and search functionality
function initializeFilters() {
    const form = document.getElementById('filterForm');
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        // Add event listeners for real-time filtering
        if (input.type === 'text') {
            input.addEventListener('input', debounceFilter);
        } else {
            input.addEventListener('change', applyFilters);
        }
    });
}

function debounceFilter() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(applyFilters, 500); // 500ms delay for search
}

function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Add all form data to params with comprehensive validation
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            // Validate search input
            if (key === 'search') {
                // Remove potentially dangerous characters
                value = value.replace(/[<>'"&]/g, '');
                if (value.length > 100) {
                    Swal.fire({
                        title: 'Search Too Long',
                        text: 'Search term must be less than 100 characters',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
            }
            
            // Validate status input
            if (key === 'status') {
                const allowedStatuses = ['completed', 'in_progress', 'abandoned'];
                if (!allowedStatuses.includes(value)) {
                    Swal.fire({
                        title: 'Invalid Status',
                        text: 'Please select a valid status',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
            }
            
            // Validate date inputs
            if ((key === 'date_from' || key === 'date_to') && value) {
                // Check if it matches YYYY-MM-DD format
                const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
                if (!dateRegex.test(value)) {
                    Swal.fire({
                        title: 'Invalid Date Format',
                        text: `Please enter a valid date in YYYY-MM-DD format for ${key.replace('_', ' ')}`,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
                
                const date = new Date(value);
                if (isNaN(date.getTime())) {
                    Swal.fire({
                        title: 'Invalid Date',
                        text: `Please enter a valid date for ${key.replace('_', ' ')}`,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
            }
            
            params.append(key, value);
        }
    }
    
    // Show loading state
    showLoadingState();
    
    // Make AJAX request
    fetch(`{{ route('submissions.for-interview', $interview) }}?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Check if there's an error in the response
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Update the table
        document.getElementById('submissionsTable').innerHTML = data.html;
        
        // Update the count in the header
        const countElement = document.querySelector('.text-xl.font-semibold.text-gray-800');
        if (countElement && data.submissions) {
            countElement.textContent = `All Submissions (${data.submissions.total})`;
        }
        
        // Update URL without page reload
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
        
        hideLoadingState();
        
        // Show success message
        Swal.fire({
            title: 'Filters Applied!',
            text: `Found ${data.submissions ? data.submissions.total : 0} submission(s)`,
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    })
    .catch(error => {
        console.error('Error applying filters:', error);
        hideLoadingState();
        
        // Show error message in the table
        document.getElementById('submissionsTable').innerHTML = `
            <div class="px-6 py-8 text-center">
                <div class="text-red-500 text-lg">Error loading submissions</div>
                <div class="text-gray-500 text-sm mt-2">${error.message || 'Please try again or refresh the page'}</div>
                <button onclick="applyFilters()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                    Try Again
                </button>
            </div>
        `;
        
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Failed to apply filters. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444'
        });
    });
}

function clearFilters() {
    Swal.fire({
        title: 'Clear All Filters?',
        text: 'This will remove all search and filter criteria',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Clear All',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            // Clear all form inputs
            const form = document.getElementById('filterForm');
            form.reset();
            
            // Reload page without any filters
            window.location.href = `{{ route('submissions.for-interview', $interview) }}`;
        }
    });
}

function removeFilter(filterName) {
    const input = document.querySelector(`[name="${filterName}"]`);
    if (input) {
        input.value = '';
        applyFilters();
    }
}

function exportFiltered() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Add all form data to params
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            params.append(key, value);
        }
    }
    
    // Show loading
    Swal.fire({
        title: 'Preparing Export...',
        text: 'Please wait while we prepare your filtered data',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Create download link
    const exportUrl = `{{ route('submissions.export', $interview) }}?${params.toString()}`;
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'filtered_submissions.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading and show success
    setTimeout(() => {
        Swal.fire({
            title: 'Export Started!',
            text: 'Your filtered submissions are being downloaded',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }, 1000);
}

function showLoadingState() {
    const tableContainer = document.getElementById('submissionsTable');
    tableContainer.innerHTML = `
        <div class="px-6 py-8 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <div class="text-gray-500 text-lg mt-2">Loading submissions...</div>
        </div>
    `;
}

function hideLoadingState() {
    // Loading state will be replaced by the AJAX response
}

// Copy interview link function
function copyInterviewLink() {
    const interviewLink = '{{ \App\Http\Controllers\CandidateInterviewController::generateInterviewLink($interview) }}';
    const button = event.target;
    const originalText = button.textContent;
    
    // Try modern clipboard API first
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(interviewLink).then(function() {
            showCopySuccess(button, originalText);
        }).catch(function(err) {
            console.error('Clipboard API failed:', err);
            fallbackCopy(interviewLink, button, originalText);
        });
    } else {
        // Fallback for older browsers or non-secure contexts
        fallbackCopy(interviewLink, button, originalText);
    }
}

function fallbackCopy(text, button, originalText) {
    // Create a temporary textarea element
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    
    try {
        textArea.focus();
        textArea.select();
        const successful = document.execCommand('copy');
        
        if (successful) {
            showCopySuccess(button, originalText);
        } else {
            showCopyError(text, button, originalText);
        }
    } catch (err) {
        console.error('Fallback copy failed:', err);
        showCopyError(text, button, originalText);
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccess(button, originalText) {
    button.textContent = '✅ Copied!';
    button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
    button.classList.add('bg-green-500');
    
    // Show SweetAlert success
    Swal.fire({
        title: 'Success!',
        text: 'Interview link copied to clipboard',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
    
    setTimeout(function() {
        button.textContent = originalText;
        button.classList.remove('bg-green-500');
        button.classList.add('bg-purple-600', 'hover:bg-purple-700');
    }, 2000);
}

function showCopyError(text, button, originalText) {
    button.textContent = '❌ Copy Failed';
    button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
    button.classList.add('bg-red-600');
    
    // Show SweetAlert with the link
    Swal.fire({
        title: '📋 Interview Link',
        html: `
            <p class="text-gray-600 mb-3">Please copy this link manually:</p>
            <div class="bg-gray-100 p-3 rounded-lg">
                <input type="text" value="${text}" readonly class="w-full bg-transparent text-sm border-none outline-none" onclick="this.select()" style="font-family: monospace;">
            </div>
        `,
        icon: 'info',
        showCancelButton: false,
        confirmButtonText: 'Got it!',
        confirmButtonColor: '#2563eb',
        width: '500px'
    });
    
    setTimeout(function() {
        button.textContent = originalText;
        button.classList.remove('bg-red-600');
        button.classList.add('bg-purple-600', 'hover:bg-purple-700');
    }, 3000);
}

// Initialize filters when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
});
</script>
@endpush
@endsection
