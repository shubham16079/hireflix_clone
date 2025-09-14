<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Question;
use App\Services\LaravelBrevoMailService;
use App\Http\Controllers\CandidateInterviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    
    /**
     * Show the form for creating a new interview.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('interviews.create');
    }
    public function index()
    {
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'reviewer') {
            $interviews = Interview::where('created_by', Auth::id())->with('questions')->get();
            return view('interviews.index', compact('interviews'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have access to this page.');
    }

    /**
     * Display the specified interview.
     *
     * @param  \App\Models\Interview  $interview
     * @return \Illuminate\View\View
     */
    public function show(Interview $interview)
    {
        // Check if user owns this interview
        if ($interview->created_by !== Auth::id()) {
            return redirect()->route('interviews.index')->with('error', 'You do not have access to this interview.');
        }

        $interview->load('questions');
        return view('interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified interview.
     *
     * @param  \App\Models\Interview  $interview
     * @return \Illuminate\View\View
     */
    public function edit(Interview $interview)
    {
        // Check if user owns this interview
        if ($interview->created_by !== Auth::id()) {
            return redirect()->route('interviews.index')->with('error', 'You do not have access to this interview.');
        }

        $interview->load('questions');
        return view('interviews.edit', compact('interview'));
    }

    /**
     * Update the specified interview in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Interview  $interview
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Interview $interview)
    {
        // Check if user owns this interview
        if ($interview->created_by !== Auth::id()) {
            return redirect()->route('interviews.index')->with('error', 'You do not have access to this interview.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request, $interview) {
                // Update the interview
                $interview->update([
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                ]);

                // Delete existing questions
                $interview->questions()->delete();

                // Create new questions
                foreach ($request->input('questions') as $index => $questionData) {
                    $interview->questions()->create([
                        'question_text' => $questionData['text'],
                        'type' => 'video',
                        'order' => $index + 1
                    ]);
                }
            });

            return redirect()->route('interviews.show', $interview)->with('success', 'Interview updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update interview. Please try again.');
        }
    }

    /**
     * Send interview invitation to candidate
     */
    public function sendInvitation(Request $request, Interview $interview)
    {
        // Check if user owns this interview
        if ($interview->created_by !== Auth::id()) {
            return redirect()->route('interviews.index')->with('error', 'You do not have access to this interview.');
        }

        $request->validate([
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email|max:255',
        ]);

        try {
            $brevoService = new LaravelBrevoMailService();
            $interviewLink = CandidateInterviewController::generateInterviewLink($interview);
            
            $brevoService->sendInterviewInvitation(
                $request->input('candidate_email'),
                $request->input('candidate_name'),
                $interview->title,
                $interviewLink
            );

            return redirect()->route('interviews.show', $interview)
                ->with('success', 'Interview invitation sent successfully to ' . $request->input('candidate_email'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to send interview invitation', [
                'error' => $e->getMessage(),
                'candidate_email' => $request->input('candidate_email'),
                'interview_id' => $interview->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('interviews.show', $interview)
                ->with('error', 'Failed to send invitation: ' . $e->getMessage());
        }
    }

    /**
     * Share interview with reviewers
     */
    public function shareWithReviewers(Request $request, Interview $interview)
    {
        // Check if user owns this interview
        if ($interview->created_by !== Auth::id()) {
            return redirect()->route('interviews.index')->with('error', 'You do not have access to this interview.');
        }

        $request->validate([
            'reviewer_emails' => 'required|string',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            $brevoService = new LaravelBrevoMailService();
            $reviewerEmails = array_filter(array_map('trim', explode(',', $request->input('reviewer_emails'))));
            
            $submissionsLink = route('submissions.for-interview', $interview);
            $assignedCount = 0;
            
            foreach ($reviewerEmails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Find or create user with this email
                    $reviewer = \App\Models\User::where('email', $email)->first();
                    
                    if (!$reviewer) {
                        // Create a new user account for the reviewer
                        $reviewer = \App\Models\User::create([
                            'name' => explode('@', $email)[0], // Use email prefix as name
                            'email' => $email,
                            'password' => \Hash::make(\Str::random(12)), // Random password
                            'role' => 'reviewer',
                        ]);
                    }
                    
                    // Check if reviewer is already assigned to this interview
                    $existingAssignment = \App\Models\ReviewAssignment::where('interview_id', $interview->id)
                        ->where('reviewer_id', $reviewer->id)
                        ->first();
                    
                    if (!$existingAssignment) {
                        // Create review assignment
                        \App\Models\ReviewAssignment::create([
                            'interview_id' => $interview->id,
                            'reviewer_id' => $reviewer->id,
                            'assigned_by' => Auth::id(),
                            'status' => 'assigned',
                            'message' => $request->input('message', ''),
                            'assigned_at' => now(),
                        ]);
                        $assignedCount++;
                    }
                    
                    // Send email invitation
                    $brevoService->sendReviewerInvitation(
                        $email,
                        $interview->title,
                        $submissionsLink,
                        $request->input('message', '')
                    );
                }
            }

            return redirect()->route('interviews.show', $interview)
                ->with('success', 'Reviewer invitations sent successfully to ' . count($reviewerEmails) . ' reviewer(s). ' . $assignedCount . ' new assignments created.');
        } catch (\Exception $e) {
            \Log::error('Failed to send reviewer invitations', [
                'error' => $e->getMessage(),
                'interview_id' => $interview->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('interviews.show', $interview)
                ->with('error', 'Failed to send reviewer invitations: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created interview in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:255',
            'allow_retakes' => 'boolean',
            'max_retakes_per_question' => 'integer|min:0|max:10',
            'max_video_duration' => 'integer|min:30|max:1800',
            'preparation_time' => 'integer|min:0|max:300',
            'show_timer' => 'boolean',
            'allow_pause' => 'boolean',
            'sequential_questions' => 'boolean',
            'show_progress' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create the interview
                $interview = Interview::create([
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'created_by' => auth()->id(),
                    'allow_retakes' => $request->has('allow_retakes'),
                    'max_retakes_per_question' => $request->input('max_retakes_per_question', 3),
                    'max_video_duration' => $request->input('max_video_duration', 300),
                    'preparation_time' => $request->input('preparation_time', 30),
                    'show_timer' => $request->has('show_timer'),
                    'allow_pause' => $request->has('allow_pause'),
                    'sequential_questions' => $request->has('sequential_questions'),
                    'show_progress' => $request->has('show_progress'),
                    'allowed_video_formats' => ['mp4', 'webm'],
                    'max_video_size' => 100, // MB
                    'video_quality' => '720p',
                ]);

                // Create questions for the interview
                foreach ($request->input('questions') as $index => $questionData) {
                    $interview->questions()->create([
                        'question_text' => $questionData['text'],
                        'type' => 'video', 
                        'order' => $index + 1
                    ]);
                }
            });

            return redirect()->route('dashboard')->with('success', 'Interview created successfully!');
        } catch (\Exception $e) {   
            dd($e);
            return back()->withInput()->with('error', 'Failed to create interview. Please try again.');
        }
    }
}
