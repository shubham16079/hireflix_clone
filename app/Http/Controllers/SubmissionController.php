<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Interview;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    /**
     * Display a listing of submissions for an interview
     */
    public function index(Request $request)
    {
        $query = Submission::with(['interview', 'responses.question', 'reviews.reviewer']);

        // Apply access control based on user role
        if (Auth::user()->role === 'admin') {
            // Admin can see all submissions
        } elseif (Auth::user()->role === 'reviewer') {
            // Reviewers can only see submissions from interviews they are assigned to
            $query->whereHas('interview.reviewAssignments', function($q) {
                $q->where('reviewer_id', Auth::id())
                  ->where('status', 'accepted');
            });
        } else {
            // Interview creators can see submissions for their interviews
            $query->whereHas('interview', function($q) {
                $q->where('created_by', Auth::id());
            });
        }

        if ($request->filled('interview_id')) {
            $query->where('interview_id', $request->interview_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get interviews for filter dropdown based on user role
        if (Auth::user()->role === 'admin') {
            $interviews = Interview::all();
        } elseif (Auth::user()->role === 'reviewer') {
            // Get interviews where user is assigned as reviewer
            $interviews = Interview::whereHas('reviewAssignments', function($q) {
                $q->where('reviewer_id', Auth::id());
            })->get();
        } else {
            $interviews = Interview::where('created_by', Auth::id())->get();
        }

        return view('submissions.index', compact('submissions', 'interviews'));
    }

    /**
     * Display the specified submission
     */
    public function show(Submission $submission)
    {
        // Check if user has access to this submission
        $hasAccess = false;
        
        // Admin can access all submissions
        if (Auth::user()->role === 'admin') {
            $hasAccess = true;
        }
        // Interview creator can access their submissions
        elseif ($submission->interview->created_by === Auth::id()) {
            $hasAccess = true;
        }
        // Reviewer can access if they are assigned to review this interview
        elseif (Auth::user()->role === 'reviewer') {
            $assignment = \App\Models\ReviewAssignment::where('interview_id', $submission->interview_id)
                ->where('reviewer_id', Auth::id())
                ->where('status', 'accepted')
                ->first();
            $hasAccess = $assignment !== null;
        }
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this submission.');
        }

        $submission->load([
            'interview.questions',
            'responses.question',
            'reviews.reviewer'
        ]);

        return view('submissions.show', compact('submission'));
    }

    /**
     * Get submissions for a specific interview
     */
    public function forInterview(Request $request, Interview $interview)
    {
        // Check if user has access to this interview
        $hasAccess = false;
        
        // Admin can access all interviews
        if (Auth::user()->role === 'admin') {
            $hasAccess = true;
        }
        // Interview creator can access their interviews
        elseif ($interview->created_by === Auth::id()) {
            $hasAccess = true;
        }
        // Reviewer can access if they are assigned to review this interview
        elseif (Auth::user()->role === 'reviewer') {
            $assignment = \App\Models\ReviewAssignment::where('interview_id', $interview->id)
                ->where('reviewer_id', Auth::id())
                ->where('status', 'accepted')
                ->first();
            $hasAccess = $assignment !== null;
        }
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this interview.');
        }

        $query = $interview->submissions()
            ->with(['responses.question', 'reviews.reviewer']);

        // Search functionality with comprehensive validation
        if ($request->has('search')) {
            $search = $request->input('search');
            if (is_string($search) && trim($search) !== '') {
                $search = trim($search);
                // Only allow alphanumeric, spaces, @, ., -, and _ characters
                $search = preg_replace('/[^a-zA-Z0-9\s@.\-_]/', '', $search);
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('candidate_name', 'like', "%{$search}%")
                          ->orWhere('candidate_email', 'like', "%{$search}%");
                    });
                }
            }
        }

        // Status filter with validation
        if ($request->has('status') && $request->status !== '') {
            $status = $request->input('status');
            if (is_string($status) && in_array($status, ['completed', 'in_progress', 'abandoned'])) {
                $query->where('status', $status);
            }
        }

        // Date range filters with comprehensive validation
        if ($request->has('date_from') && $request->date_from !== '') {
            $dateFrom = $request->input('date_from');
            if (is_string($dateFrom) && !empty(trim($dateFrom))) {
                try {
                    // Validate date format (YYYY-MM-DD)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
                        $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom);
                        if ($parsedDate && $parsedDate->isValid()) {
                            $query->where('created_at', '>=', $parsedDate->startOfDay());
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Invalid date_from format: ' . $dateFrom . ' - ' . $e->getMessage());
                }
            }
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $dateTo = $request->input('date_to');
            if (is_string($dateTo) && !empty(trim($dateTo))) {
                try {
                    // Validate date format (YYYY-MM-DD)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
                        $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateTo);
                        if ($parsedDate && $parsedDate->isValid()) {
                            $query->where('created_at', '<=', $parsedDate->endOfDay());
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Invalid date_to format: ' . $dateTo . ' - ' . $e->getMessage());
                }
            }
        }

        // Sort options with comprehensive validation
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields - ensure they are strings and in allowed list
        $allowedSortFields = ['created_at', 'candidate_name', 'status', 'submitted_at'];
        if (!is_string($sortBy) || !in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        if (!is_string($sortOrder) || !in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        // Apply sorting with additional safety
        try {
            $query->orderBy($sortBy, $sortOrder);
        } catch (\Exception $e) {
            Log::warning('Error applying sort: ' . $e->getMessage() . ' - sortBy: ' . $sortBy . ', sortOrder: ' . $sortOrder);
            // Fallback to default sorting
            $query->orderBy('created_at', 'desc');
        }

        // Execute query with comprehensive error handling
        try {
            $submissions = $query->paginate(20)->appends($request->query());
        } catch (\Exception $e) {
            Log::error('Error executing submissions query', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all()
            ]);
            
            // Fallback to basic query without filters
            $submissions = $interview->submissions()
                ->with(['responses.question', 'reviews.reviewer'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        // Get statistics for all submissions (not just paginated ones)
        $allSubmissions = $interview->submissions()->with('reviews')->get();
        $statistics = [
            'total' => $allSubmissions->count(),
            'completed' => $allSubmissions->where('status', 'completed')->count(),
            'in_progress' => $allSubmissions->where('status', 'in_progress')->count(),
            'abandoned' => $allSubmissions->where('status', 'abandoned')->count(),
            'average_score' => $allSubmissions->flatMap->reviews->avg('score') ?? 0,
        ];

        // If this is an AJAX request, return JSON
        if ($request->ajax()) {
            try {
                return response()->json([
                    'submissions' => $submissions,
                    'statistics' => $statistics,
                    'html' => view('submissions.partials.submissions-table', compact('submissions', 'interview'))->render()
                ]);
            } catch (\Exception $e) {
                Log::error('Error in AJAX submissions response', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_params' => $request->all()
                ]);
                
                return response()->json([
                    'error' => 'Failed to load submissions. Please try again.',
                    'submissions' => collect(),
                    'statistics' => $statistics,
                    'html' => '<div class="px-6 py-8 text-center"><div class="text-red-500 text-lg">Error loading submissions. Please refresh the page.</div></div>'
                ], 500);
            }
        }

        return view('submissions.for-interview', compact('submissions', 'interview', 'statistics'));
    }

    /**
     * Download submission data as CSV
     */
    public function export(Request $request, Interview $interview)
    {
        // Check if user has access to this interview
        $hasAccess = false;
        
        // Admin can access all interviews
        if (Auth::user()->role === 'admin') {
            $hasAccess = true;
        }
        // Interview creator can access their interviews
        elseif ($interview->created_by === Auth::id()) {
            $hasAccess = true;
        }
        // Reviewer can access if they are assigned to review this interview
        elseif (Auth::user()->role === 'reviewer') {
            $assignment = \App\Models\ReviewAssignment::where('interview_id', $interview->id)
                ->where('reviewer_id', Auth::id())
                ->where('status', 'accepted')
                ->first();
            $hasAccess = $assignment !== null;
        }
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this interview.');
        }

        $query = $interview->submissions()
            ->with(['responses.question', 'reviews.reviewer']);

        // Apply the same filters as the main view with comprehensive validation
        if ($request->has('search')) {
            $search = $request->input('search');
            if (is_string($search) && trim($search) !== '') {
                $search = trim($search);
                // Only allow alphanumeric, spaces, @, ., -, and _ characters
                $search = preg_replace('/[^a-zA-Z0-9\s@.\-_]/', '', $search);
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('candidate_name', 'like', "%{$search}%")
                          ->orWhere('candidate_email', 'like', "%{$search}%");
                    });
                }
            }
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            if (is_string($status) && in_array($status, ['completed', 'in_progress', 'abandoned'])) {
                $query->where('status', $status);
            }
        }

        if ($request->has('date_from')) {
            $dateFrom = $request->input('date_from');
            if (is_string($dateFrom) && !empty(trim($dateFrom))) {
                try {
                    // Validate date format (YYYY-MM-DD)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
                        $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom);
                        if ($parsedDate && $parsedDate->isValid()) {
                            $query->where('created_at', '>=', $parsedDate->startOfDay());
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Invalid date_from format in export: ' . $dateFrom . ' - ' . $e->getMessage());
                }
            }
        }

        if ($request->has('date_to')) {
            $dateTo = $request->input('date_to');
            if (is_string($dateTo) && !empty(trim($dateTo))) {
                try {
                    // Validate date format (YYYY-MM-DD)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
                        $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateTo);
                        if ($parsedDate && $parsedDate->isValid()) {
                            $query->where('created_at', '<=', $parsedDate->endOfDay());
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Invalid date_to format in export: ' . $dateTo . ' - ' . $e->getMessage());
                }
            }
        }

        // Execute query with comprehensive error handling
        try {
            $submissions = $query->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error executing export query', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all()
            ]);
            
            // Fallback to basic query without filters
            $submissions = $interview->submissions()
                ->with(['responses.question', 'reviews.reviewer'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Create filename with filter info
        $filterInfo = [];
        if ($request->filled('search')) $filterInfo[] = 'search';
        if ($request->filled('status')) $filterInfo[] = $request->status;
        if ($request->filled('date_from')) $filterInfo[] = 'from_' . $request->date_from;
        if ($request->filled('date_to')) $filterInfo[] = 'to_' . $request->date_to;
        
        $filterSuffix = !empty($filterInfo) ? '_filtered_' . implode('_', $filterInfo) : '';
        $filename = 'interview_' . $interview->id . '_submissions' . $filterSuffix . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($submissions, $interview) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Submission ID',
                'Candidate Name',
                'Candidate Email',
                'Status',
                'Submitted At',
                'Total Questions',
                'Completed Questions',
                'Completion %'
            ]);

            // Add question headers
            foreach ($interview->questions as $question) {
                fputcsv($file, ['Q' . $question->id . ': ' . $question->question_text]);
            }

            // Add review headers
            fputcsv($file, ['Reviewer', 'Score', 'Comments', 'Reviewed At']);

            // Data rows
            foreach ($submissions as $submission) {
                $row = [
                    $submission->id,
                    $submission->candidate_name,
                    $submission->candidate_email,
                    $submission->status,
                    $submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i:s') : 'Not submitted',
                    $submission->total_questions,
                    $submission->completed_questions,
                    $submission->completion_percentage . '%'
                ];

                // Add response data
                foreach ($interview->questions as $question) {
                    $response = $submission->responses->where('question_id', $question->id)->first();
                    $row[] = $response ? ($response->response_type === 'video' ? 'Video Response' : $response->response_text) : 'No response';
                }

                // Add review data
                $review = $submission->reviews->first();
                $row[] = $review ? $review->reviewer->name : 'Not reviewed';
                $row[] = $review ? $review->score : 'N/A';
                $row[] = $review ? $review->comments : 'N/A';
                $row[] = $review ? $review->created_at->format('Y-m-d H:i:s') : 'N/A';

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get submission statistics
     */
    public function statistics(Interview $interview)
    {
        // Check if user has access to this interview
        $hasAccess = false;
        
        // Admin can access all interviews
        if (Auth::user()->role === 'admin') {
            $hasAccess = true;
        }
        // Interview creator can access their interviews
        elseif ($interview->created_by === Auth::id()) {
            $hasAccess = true;
        }
        // Reviewer can access if they are assigned to review this interview
        elseif (Auth::user()->role === 'reviewer') {
            $assignment = \App\Models\ReviewAssignment::where('interview_id', $interview->id)
                ->where('reviewer_id', Auth::id())
                ->where('status', 'accepted')
                ->first();
            $hasAccess = $assignment !== null;
        }
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this interview.');
        }

        $submissions = $interview->submissions;

        $stats = [
            'total_submissions' => $submissions->count(),
            'completed_submissions' => $submissions->where('status', 'completed')->count(),
            'in_progress_submissions' => $submissions->where('status', 'in_progress')->count(),
            'abandoned_submissions' => $submissions->where('status', 'abandoned')->count(),
            'average_completion_time' => $submissions->where('status', 'completed')
                ->whereNotNull('submitted_at')
                ->whereNotNull('started_at')
                ->avg(function($submission) {
                    return $submission->submitted_at->diffInMinutes($submission->started_at);
                }),
            'completion_rate' => $submissions->count() > 0 
                ? round(($submissions->where('status', 'completed')->count() / $submissions->count()) * 100, 2)
                : 0,
        ];

        return response()->json($stats);
    }
}