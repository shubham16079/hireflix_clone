<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InterviewController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth', 'can:access-admin-reviewer-features'])->group(function () {
    // Interview routes
    Route::get('/interviews/create', [InterviewController::class, 'create'])->name('interviews.create');
    Route::post('/interviews', [InterviewController::class, 'store'])->name('interviews.store');
    Route::get('/interviews', [InterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/{interview}', [InterviewController::class, 'show'])->name('interviews.show');
    Route::get('/interviews/{interview}/edit', [InterviewController::class, 'edit'])->name('interviews.edit');
    Route::put('/interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
    Route::post('/interviews/{interview}/invite', [InterviewController::class, 'sendInvitation'])->name('interviews.invite');
    Route::post('/interviews/{interview}/share-reviewers', [InterviewController::class, 'shareWithReviewers'])->name('interviews.share-reviewers');
    
    // Submission routes
    Route::get('/submissions', [App\Http\Controllers\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [App\Http\Controllers\SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/interviews/{interview}/submissions', [App\Http\Controllers\SubmissionController::class, 'forInterview'])->name('submissions.for-interview');
    Route::get('/interviews/{interview}/export', [App\Http\Controllers\SubmissionController::class, 'export'])->name('submissions.export');
    Route::get('/interviews/{interview}/statistics', [App\Http\Controllers\SubmissionController::class, 'statistics'])->name('submissions.statistics');
    
    // Review routes
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/submissions/{submission}/reviews/create', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/submissions/{submission}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/submissions/{submission}/reviews', [App\Http\Controllers\ReviewController::class, 'forSubmission'])->name('reviews.for-submission');
    Route::get('/reviews/statistics', [App\Http\Controllers\ReviewController::class, 'statistics'])->name('reviews.statistics');
    
    // Review Assignment routes
    Route::get('/review-assignments', [App\Http\Controllers\ReviewAssignmentController::class, 'index'])->name('review-assignments.index');
    Route::get('/review-assignments/{assignment}', [App\Http\Controllers\ReviewAssignmentController::class, 'show'])->name('review-assignments.show');
    Route::post('/review-assignments/{assignment}/accept', [App\Http\Controllers\ReviewAssignmentController::class, 'accept'])->name('review-assignments.accept');
    Route::post('/review-assignments/{assignment}/decline', [App\Http\Controllers\ReviewAssignmentController::class, 'decline'])->name('review-assignments.decline');
});

// Candidate interview routes (no authentication required)
Route::get('/interview/{token}', [App\Http\Controllers\CandidateInterviewController::class, 'show'])->name('candidate.interview');
Route::post('/interview/{token}/submit', [App\Http\Controllers\CandidateInterviewController::class, 'store'])->name('candidate.submit');
Route::get('/interview/{token}/thank-you', [App\Http\Controllers\CandidateInterviewController::class, 'thankYou'])->name('candidate.thank-you');

// Video interview API routes
Route::post('/interview/{token}/start', [App\Http\Controllers\CandidateInterviewController::class, 'startInterview'])->name('candidate.start');
Route::post('/interview/{token}/save-video', [App\Http\Controllers\CandidateInterviewController::class, 'saveVideoResponse'])->name('candidate.save-video');
Route::post('/interview/{token}/retake', [App\Http\Controllers\CandidateInterviewController::class, 'requestRetake'])->name('candidate.retake');
Route::get('/interview/{token}/progress', [App\Http\Controllers\CandidateInterviewController::class, 'getProgress'])->name('candidate.progress');
Route::post('/interview/{token}/complete', [App\Http\Controllers\CandidateInterviewController::class, 'completeInterview'])->name('candidate.complete');
