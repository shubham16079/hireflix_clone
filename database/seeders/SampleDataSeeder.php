<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Interview;
use App\Models\Question;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user
        $admin = User::where('email', 'admin@hireflixclone.com')->first();
        
        if (!$admin) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        // Create sample interviews
        $interviews = [
            [
                'title' => 'Software Developer Interview',
                'description' => 'Technical interview for senior software developer position',
                'max_video_duration' => 300, // 5 minutes
                'allow_retakes' => true,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Marketing Manager Interview',
                'description' => 'Interview for marketing manager role',
                'max_video_duration' => 240, // 4 minutes
                'allow_retakes' => true,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Customer Support Interview',
                'description' => 'Interview for customer support representative',
                'max_video_duration' => 180, // 3 minutes
                'allow_retakes' => false,
                'created_by' => $admin->id,
            ]
        ];

        foreach ($interviews as $interviewData) {
            $interview = Interview::create($interviewData);
            
            // Add questions based on interview type
            if (str_contains($interview->title, 'Software Developer')) {
                $this->addDeveloperQuestions($interview);
            } elseif (str_contains($interview->title, 'Marketing Manager')) {
                $this->addMarketingQuestions($interview);
            } else {
                $this->addSupportQuestions($interview);
            }
        }

        $this->command->info('✅ Sample interviews and questions created successfully!');
        $this->command->info('📋 Created 3 sample interviews with questions');
    }

    private function addDeveloperQuestions(Interview $interview)
    {
        $questions = [
            [
                'question_text' => 'Tell us about yourself and your experience in software development.',
                'type' => 'video',
                'order' => 1,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'Describe a challenging technical problem you solved recently.',
                'type' => 'video',
                'order' => 2,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'How do you stay updated with the latest technologies?',
                'type' => 'video',
                'order' => 3,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'Explain your approach to code review and quality assurance.',
                'type' => 'video',
                'order' => 4,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'What are your career goals in the next 5 years?',
                'type' => 'video',
                'order' => 5,
                'interview_id' => $interview->id,
            ]
        ];

        foreach ($questions as $questionData) {
            Question::create($questionData);
        }
    }

    private function addMarketingQuestions(Interview $interview)
    {
        $questions = [
            [
                'question_text' => 'Tell us about your marketing experience and achievements.',
                'type' => 'video',
                'order' => 1,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'How would you develop a marketing strategy for a new product launch?',
                'type' => 'video',
                'order' => 2,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'Describe your experience with digital marketing tools and platforms.',
                'type' => 'video',
                'order' => 3,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'How do you measure the success of marketing campaigns?',
                'type' => 'video',
                'order' => 4,
                'interview_id' => $interview->id,
            ]
        ];

        foreach ($questions as $questionData) {
            Question::create($questionData);
        }
    }

    private function addSupportQuestions(Interview $interview)
    {
        $questions = [
            [
                'question_text' => 'Tell us about yourself and why you want to work in customer support.',
                'type' => 'video',
                'order' => 1,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'How would you handle an angry customer who is not satisfied with our service?',
                'type' => 'video',
                'order' => 2,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'Describe a time when you went above and beyond to help a customer.',
                'type' => 'video',
                'order' => 3,
                'interview_id' => $interview->id,
            ],
            [
                'question_text' => 'How do you prioritize multiple customer requests?',
                'type' => 'video',
                'order' => 4,
                'interview_id' => $interview->id,
            ]
        ];

        foreach ($questions as $questionData) {
            Question::create($questionData);
        }
    }
}
