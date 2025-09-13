@extends('layouts.app')

@section('title', 'Interview Details')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $interview->title }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('submissions.for-interview', $interview) }}" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">
                📊 View Submissions
            </a>
            <a href="{{ route('interviews.edit', $interview) }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Edit Interview
            </a>
            <a href="{{ route('interviews.index') }}" class="bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300">
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Description</h2>
        <p class="text-gray-600 leading-relaxed">{{ $interview->description }}</p>
    </div>

    <div>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Interview Questions ({{ $interview->questions->count() }})</h2>
        
        @if($interview->questions->count() > 0)
            <div class="space-y-4">
                @foreach($interview->questions as $index => $question)
                    <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500">
                        <div class="flex items-start">
                            <span class="bg-blue-600 text-white text-sm font-bold rounded-full w-6 h-6 flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-grow">
                                <p class="text-gray-800 font-medium">{{ $question->question_text }}</p>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <span class="bg-gray-200 px-2 py-1 rounded">{{ ucfirst($question->type) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 p-6 rounded-lg text-center">
                <p class="text-gray-600">No questions have been added to this interview yet.</p>
            </div>
        @endif
    </div>

    <div class="mt-8 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Interview Actions</h3>
        
        <!-- Invite Candidate Form -->
        <div class="bg-blue-50 p-6 rounded-lg mb-6">
            <h4 class="text-lg font-semibold text-blue-800 mb-4">📧 Invite Candidate</h4>
            <form method="POST" action="{{ route('interviews.invite', $interview) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="candidate_name" class="block text-sm font-medium text-gray-700 mb-1">Candidate Name</label>
                        <input type="text" id="candidate_name" name="candidate_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter candidate's full name">
                    </div>
                    <div>
                        <label for="candidate_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="candidate_email" name="candidate_email" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter candidate's email">
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                    Send Invitation
                </button>
            </form>
        </div>

        <!-- Share with Reviewers Form -->
        <div class="bg-green-50 p-6 rounded-lg mb-6">
            <h4 class="text-lg font-semibold text-green-800 mb-4">👥 Share with Reviewers</h4>
            <form method="POST" action="{{ route('interviews.share-reviewers', $interview) }}" class="space-y-4" onsubmit="return validateAndSubmit(event)">
                @csrf
                <div>
                    <label for="reviewer_emails" class="block text-sm font-medium text-gray-700 mb-1">Reviewer Email Addresses</label>
                    <input type="text" id="reviewer_emails" name="reviewer_emails" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter reviewer emails separated by commas (e.g., reviewer1@company.com, reviewer2@company.com)">
                    <p class="text-sm text-gray-500 mt-1">Separate multiple email addresses with commas</p>
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Optional Message</label>
                    <textarea id="message" name="message" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Add a personal message for the reviewers..."></textarea>
                </div>
                <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">
                    Send Reviewer Invitations
                </button>
            </form>
        </div>

        <!-- Other Actions -->
        <div class="flex flex-wrap gap-3">
            <button onclick="copyInterviewLink()" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">
                📋 Copy Interview Link
            </button>
            <a href="{{ route('submissions.for-interview', $interview) }}" class="bg-purple-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300">
                📊 View Submissions ({{ $interview->submissions->count() }})
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function validateAndSubmit(event) {
        const reviewerEmails = document.getElementById('reviewer_emails').value;
        
        if (!reviewerEmails.trim()) {
            Swal.fire({
                title: 'Error',
                text: 'Please enter at least one reviewer email address',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            return false;
        }
        
        // Basic email validation
        const emails = reviewerEmails.split(',').map(email => email.trim()).filter(email => email);
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const invalidEmails = emails.filter(email => !emailRegex.test(email));
        
        if (invalidEmails.length > 0) {
            Swal.fire({
                title: 'Invalid Email Addresses',
                text: `Please check these email addresses: ${invalidEmails.join(', ')}`,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            return false;
        }
        
        // Show loading
        Swal.fire({
            title: 'Sending Invitations...',
            text: 'Please wait while we send the reviewer invitations',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Let the form submit normally
        return true;
    }

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
        button.classList.remove('bg-green-600', 'hover:bg-green-700');
        button.classList.add('bg-green-500');
        
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-500');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
        }, 2000);
    }
    
    function showCopyError(text, button, originalText) {
        button.textContent = '❌ Copy Failed';
        button.classList.remove('bg-green-600', 'hover:bg-green-700');
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
            button.classList.add('bg-green-600', 'hover:bg-green-700');
        }, 3000);
    }
</script>
@endpush
