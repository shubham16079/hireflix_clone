<?php

namespace App\Http\Controllers;

use App\Models\ReviewAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewAssignmentController extends Controller
{
    /**
     * Display a listing of review assignments for the current user
     */
    public function index()
    {
        $assignments = ReviewAssignment::with(['interview', 'assignedBy'])
            ->where('reviewer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('review-assignments.index', compact('assignments'));
    }

    /**
     * Accept a review assignment
     */
    public function accept(ReviewAssignment $assignment)
    {
        // Check if user is the assigned reviewer
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You can only accept your own assignments.');
        }

        // Check if assignment is still pending
        if ($assignment->status !== 'assigned') {
            return redirect()->back()->with('error', 'This assignment has already been responded to.');
        }

        $assignment->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Review assignment accepted successfully!');
    }

    /**
     * Decline a review assignment
     */
    public function decline(ReviewAssignment $assignment)
    {
        // Check if user is the assigned reviewer
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You can only decline your own assignments.');
        }

        // Check if assignment is still pending
        if ($assignment->status !== 'assigned') {
            return redirect()->back()->with('error', 'This assignment has already been responded to.');
        }

        $assignment->update([
            'status' => 'declined',
            'responded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Review assignment declined.');
    }

    /**
     * Show the specified review assignment
     */
    public function show(ReviewAssignment $assignment)
    {
        // Check if user is the assigned reviewer or admin
        if ($assignment->reviewer_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'You do not have access to this assignment.');
        }

        $assignment->load(['interview', 'assignedBy']);

        return view('review-assignments.show', compact('assignment'));
    }
}