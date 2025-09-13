<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LaravelBrevoMailService
{
    /**
     * Send interview invitation email using Laravel Mail with Brevo SMTP
     */
    public function sendInterviewInvitation($candidateEmail, $candidateName, $interviewTitle, $interviewLink, $companyName = 'Hireflix')
    {
        try {
            Mail::send('emails.interview-invitation', [
                'candidateName' => $candidateName,
                'interviewTitle' => $interviewTitle,
                'interviewLink' => $interviewLink,
                'companyName' => $companyName
            ], function ($mailMessage) use ($candidateEmail, $candidateName, $interviewTitle, $companyName) {
                $mailMessage->to($candidateEmail, $candidateName)
                        ->subject("Interview Invitation: {$interviewTitle}")
                        ->from(config('mail.from.address'), $companyName);
            });

            Log::info('Interview invitation sent successfully via Laravel Mail', [
                'candidate_email' => $candidateEmail,
                'interview_title' => $interviewTitle,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send interview invitation via Laravel Mail', [
                'candidate_email' => $candidateEmail,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send reviewer invitation email using Laravel Mail with Brevo SMTP
     */
    public function sendReviewerInvitation($reviewerEmail, $interviewTitle, $submissionsLink, $message = '', $companyName = 'Hireflix')
    {
        try {
            Mail::send('emails.reviewer-invitation', [
                'interviewTitle' => $interviewTitle,
                'submissionsLink' => $submissionsLink,
                'customMessage' => $message,
                'companyName' => $companyName
            ], function ($mailMessage) use ($reviewerEmail, $interviewTitle, $companyName) {
                $mailMessage->to($reviewerEmail)
                        ->subject("Reviewer Invitation: {$interviewTitle}")
                        ->from(config('mail.from.address'), $companyName);
            });

            Log::info('Reviewer invitation sent successfully via Laravel Mail', [
                'reviewer_email' => $reviewerEmail,
                'interview_title' => $interviewTitle,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send reviewer invitation via Laravel Mail', [
                'reviewer_email' => $reviewerEmail,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
