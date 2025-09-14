<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Submission;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['submission.interview', 'reviewer']);

        // Apply access control based on user role
        if (Auth::user()->role === 'admin') {
            // Admin can see all reviews
        } elseif (Auth::user()->role === 'reviewer') {
            // Reviewers can only see their own reviews
            $query->where('reviewer_id', Auth::id());
        } else {
            // Interview creators can see reviews for their interviews
            $query->whereHas('submission.interview', function($q) {
                $q->where('created_by', Auth::id());
            });
        }

        // Filter by reviewer
        if ($request->has('reviewer_id')) {
            $query->where('reviewer_id', $request->reviewer_id);
        }

        // Filter by interview
        if ($request->has('interview_id')) {
            $query->whereHas('submission', function($q) use ($request) {
                $q->where('interview_id', $request->interview_id);
            });
        }

        // Filter by score range
        if ($request->has('min_score')) {
            $query->where('score', '>=', $request->min_score);
        }
        if ($request->has('max_score')) {
            $query->where('score', '<=', $request->max_score);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

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

        return view('reviews.index', compact('reviews', 'interviews'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create(Submission $submission)
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
            'responses.question'
        ]);

        // Check if user has already reviewed this submission
        $existingReview = Review::where('submission_id', $submission->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview)
                ->with('info', 'You have already reviewed this submission. You can edit your review below.');
        }

        return view('reviews.create', compact('submission'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, Submission $submission)
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

        $request->validate([
            'score' => 'required|integer|min:1|max:10',
            'comments' => 'nullable|string|max:2000',
        ]);

        // Check if user has already reviewed this submission
        $existingReview = Review::where('submission_id', $submission->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview)
                ->with('error', 'You have already reviewed this submission.');
        }

        $review = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => Auth::id(),
            'score' => $request->score,
            'comments' => $request->comments,
        ]);

        return redirect()->route('submissions.show', $submission)
            ->with('success', 'Review submitted successfully!');
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        // Check if user has access to this review
        if ($review->submission->interview->created_by !== Auth::id() && 
            $review->reviewer_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403, 'You do not have access to this review.');
        }

        $review->load([
            'submission.interview.questions',
            'submission.responses.question',
            'reviewer'
        ]);

        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(Review $review)
    {
        // Check if user can edit this review
        if ($review->reviewer_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'You can only edit your own reviews.');
        }

        $review->load([
            'submission.interview.questions',
            'submission.responses.question'
        ]);

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Check if user can edit this review
        if ($review->reviewer_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'You can only edit your own reviews.');
        }

        $request->validate([
            'score' => 'required|integer|min:1|max:10',
            'comments' => 'nullable|string|max:2000',
        ]);

        $review->update([
            'score' => $request->score,
            'comments' => $request->comments,
        ]);

        return redirect()->route('submissions.show', $review->submission)
            ->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Check if user can delete this review
        if ($review->reviewer_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'You can only delete your own reviews.');
        }

        $submission = $review->submission;
        $review->delete();

        return redirect()->route('submissions.show', $submission)
            ->with('success', 'Review deleted successfully!');
    }

    /**
     * Get reviews for a specific submission
     */
    public function forSubmission(Submission $submission)
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

        $reviews = $submission->reviews()->with('reviewer')->get();

        return response()->json($reviews);
    }

    /**
     * Get review statistics
     */
    public function statistics(Request $request)
    {
        $query = Review::query();

        // Filter by interview if specified
        if ($request->has('interview_id')) {
            $query->whereHas('submission', function($q) use ($request) {
                $q->where('interview_id', $request->interview_id);
            });
        }

        $reviews = $query->get();

        $stats = [
            'total_reviews' => $reviews->count(),
            'average_score' => $reviews->avg('score'),
            'score_distribution' => $reviews->groupBy('score')->map->count(),
            'reviews_by_reviewer' => $reviews->groupBy('reviewer_id')->map->count(),
        ];

        return response()->json($stats);
    }
}