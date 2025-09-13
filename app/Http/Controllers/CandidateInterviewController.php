<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Question;
use App\Models\Submission;
use App\Models\SubmissionResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidateInterviewController extends Controller
{
    /**
     * Display the interview for candidates
     */
    public function show($token)
    {
        try {
            // Decode the token to get interview ID
            $interviewId = base64_decode($token);
            $interview = Interview::with('questions')->findOrFail($interviewId);
            
            // Check if there's an existing submission
            $submission = Submission::where('interview_id', $interview->id)
                ->where('status', '!=', 'completed')
                ->first();
            
            return view('candidate.video-interview', compact('interview', 'token', 'submission'));
        } catch (\Exception $e) {
            return view('candidate.error', ['message' => 'Invalid interview link.']);
        }
    }

    /**
     * Store candidate's interview submission
     */
    public function store(Request $request, $token)
    {
        try {
            $interviewId = base64_decode($token);
            $interview = Interview::findOrFail($interviewId);

            $request->validate([
                'candidate_name' => 'required|string|max:255',
                'candidate_email' => 'required|email|max:255',
                'responses' => 'required|array',
                'responses.*' => 'required|string',
            ]);

            DB::transaction(function () use ($request, $interview) {
                // Create submission record
                $submission = Submission::create([
                    'interview_id' => $interview->id,
                    'candidate_name' => $request->input('candidate_name'),
                    'candidate_email' => $request->input('candidate_email'),
                    'status' => 'completed',
                    'submitted_at' => now(),
                ]);

                // Store responses
                foreach ($request->input('responses') as $questionId => $response) {
                    $submission->responses()->create([
                        'question_id' => $questionId,
                        'response_text' => $response,
                        'response_type' => 'text', // For now, we'll support text responses
                    ]);
                }
            });

            return redirect()->route('candidate.thank-you', $token)
                ->with('success', 'Your interview has been submitted successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit interview. Please try again.');
        }
    }

    /**
     * Show thank you page after submission
     */
    public function thankYou($token)
    {
        try {
            $interviewId = base64_decode($token);
            $interview = Interview::findOrFail($interviewId);
            
            return view('candidate.thank-you', compact('interview'));
        } catch (\Exception $e) {
            return view('candidate.error', ['message' => 'Invalid interview link.']);
        }
    }

    /**
     * Start interview session
     */
    public function startInterview(Request $request, $token)
    {
        try {
            $interviewId = base64_decode($token);
            $interview = Interview::with('questions')->findOrFail($interviewId);

            $request->validate([
                'candidate_name' => 'required|string|max:255',
                'candidate_email' => 'required|email|max:255',
            ]);

            // Create or update submission
            $submission = Submission::updateOrCreate(
                [
                    'interview_id' => $interview->id,
                    'candidate_email' => $request->input('candidate_email'),
                ],
                [
                    'candidate_name' => $request->input('candidate_name'),
                    'status' => 'in_progress',
                    'total_questions' => $interview->questions->count(),
                    'started_at' => now(),
                    'last_activity_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'submission_id' => $submission->id,
                'message' => 'Interview session started successfully'
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to start interview session', [
                'error' => $e->getMessage(),
                'token' => $token,
                'candidate_email' => $request->input('candidate_email'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to start interview session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save video response for a question
     */
    public function saveVideoResponse(Request $request, $token)
    {
        try {
            $interviewId = base64_decode($token);
            $interview = Interview::findOrFail($interviewId);

            $request->validate([
                'submission_id' => 'required|exists:submissions,id',
                'question_id' => 'required|exists:questions,id',
                'video' => 'required|file|mimes:mp4,webm,avi|max:' . ($interview->max_video_size * 1024), // Convert MB to KB
            ]);

            $submission = Submission::findOrFail($request->input('submission_id'));
            
            // Check if submission belongs to this interview
            if ($submission->interview_id !== $interview->id) {
                return response()->json(['success' => false, 'message' => 'Invalid submission'], 400);
            }

            $question = Question::findOrFail($request->input('question_id'));
            
            // Check if question belongs to this interview
            if ($question->interview_id !== $interview->id) {
                return response()->json(['success' => false, 'message' => 'Invalid question'], 400);
            }

            // Handle video upload
            $videoFile = $request->file('video');
            $filename = 'interview_' . $interview->id . '_question_' . $question->id . '_' . time() . '.' . $videoFile->getClientOriginalExtension();
            $path = $videoFile->storeAs('interview_videos', $filename, 'public');

            // Get video metadata
            $videoPath = storage_path('app/public/' . $path);
            $videoSize = filesize($videoPath);
            
            // Create or update response
            $response = SubmissionResponse::updateOrCreate(
                [
                    'submission_id' => $submission->id,
                    'question_id' => $question->id,
                ],
                [
                    'response_text' => null, // No text response for video
                    'response_type' => 'video',
                    'video_url' => Storage::url($path),
                    'video_filename' => $filename,
                    'video_size' => $videoSize,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'is_final' => true,
                ]
            );

            // Update submission progress
            $submission->updateProgress();

            return response()->json([
                'success' => true,
                'response_id' => $response->id,
                'video_url' => $response->video_url,
                'message' => 'Video response saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save video response: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request retake for a question
     */
    public function requestRetake(Request $request, $token)
    {
        try {
            $interviewId = base64_decode($token);
            $interview = Interview::findOrFail($interviewId);

            $request->validate([
                'submission_id' => 'required|exists:submissions,id',
                'question_id' => 'required|exists:questions,id',
            ]);

            $submission = Submission::findOrFail($request->input('submission_id'));
            $question = Question::findOrFail($request->input('question_id'));

            // Check if retakes are allowed
            if (!$interview->allow_retakes) {
                return response()->json(['success' => false, 'message' => 'Retakes are not allowed for this interview'], 400);
            }

            $response = SubmissionResponse::where('submission_id', $submission->id)
                ->where('question_id', $question->id)
                ->first();

            if (!$response) {
                return response()->json(['success' => false, 'message' => 'No response found for this question'], 400);
            }

            // Check retake limit
            if ($response->retake_count >= $interview->max_retakes_per_question) {
                return response()->json(['success' => false, 'message' => 'Maximum retakes exceeded for this question'], 400);
            }

            // Update response for retake
            $response->update([
                'retake_count' => $response->retake_count + 1,
                'is_final' => false,
                'status' => 'retake_requested',
                'video_url' => null,
                'video_filename' => null,
                'video_duration' => null,
                'video_size' => null,
                'video_metadata' => null,
            ]);

            return response()->json([
                'success' => true,
                'retake_count' => $response->retake_count,
                'remaining_retakes' => $interview->max_retakes_per_question - $response->retake_count,
                'message' => 'Retake approved. You can now record a new response.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process retake request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get interview progress
     */
    public function getProgress(Request $request, $token)
    {
        try {
            $interviewId = base64_decode($token);
            $interview = Interview::with('questions')->findOrFail($interviewId);

            $candidateEmail = $request->input('candidate_email');
            if (!$candidateEmail) {
                return response()->json([
                    'success' => true,
                    'progress' => [
                        'total_questions' => $interview->questions->count(),
                        'completed_questions' => 0,
                        'percentage' => 0,
                        'status' => 'not_started'
                    ]
                ]);
            }

            $submission = Submission::where('interview_id', $interview->id)
                ->where('candidate_email', $candidateEmail)
                ->first();

            if (!$submission) {
                return response()->json([
                    'success' => true,
                    'progress' => [
                        'total_questions' => $interview->questions->count(),
                        'completed_questions' => 0,
                        'percentage' => 0,
                        'status' => 'not_started'
                    ]
                ]);
            }

            $completedResponses = $submission->responses()
                ->where('status', 'completed')
                ->where('is_final', true)
                ->count();

            $totalQuestions = $interview->questions->count();
            $percentage = $totalQuestions > 0 ? round(($completedResponses / $totalQuestions) * 100) : 0;

            return response()->json([
                'success' => true,
                'progress' => [
                    'total_questions' => $totalQuestions,
                    'completed_questions' => $completedResponses,
                    'percentage' => $percentage,
                    'status' => $submission->status,
                    'submission_id' => $submission->id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete interview
     */
    public function completeInterview(Request $request, $token)
    {
        try {
            $interviewId = base64_decode($token);
            $interview = Interview::findOrFail($interviewId);

            $request->validate([
                'submission_id' => 'required|exists:submissions,id',
            ]);

            $submission = Submission::findOrFail($request->input('submission_id'));
            
            // Check if all questions are answered
            $totalQuestions = $interview->questions->count();
            $completedResponses = $submission->responses()
                ->where('status', 'completed')
                ->where('is_final', true)
                ->count();

            if ($completedResponses < $totalQuestions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete all questions before submitting the interview'
                ], 400);
            }

            // Mark submission as completed
            $submission->markAsCompleted();

            return response()->json([
                'success' => true,
                'message' => 'Interview completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete interview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate interview link for sharing
     */
    public static function generateInterviewLink(Interview $interview)
    {
        $token = base64_encode($interview->id);
        return route('candidate.interview', $token);
    }
}
