@extends('layouts.candidate')

@section('title', 'Video Interview - ' . $interview->title)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Interview Header -->
    <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-8 mb-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">🎥 {{ $interview->title }}</h1>
            <p class="text-xl text-gray-600 mb-6">{{ $interview->description }}</p>
            
            <!-- Progress Bar -->
            @if($interview->show_progress)
            <div class="bg-gray-200 rounded-full h-3 mb-4">
                <div id="progressBar" class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600">0 of {{ $interview->questions->count() }} questions completed</p>
            @endif
        </div>
    </div>

    <!-- Candidate Information Form (Initial) -->
    <div id="candidateInfoForm" class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">👤 Your Information</h2>
        <form id="infoForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="candidate_name" class="block text-gray-700 text-sm font-semibold mb-2">Full Name *</label>
                <input type="text" id="candidate_name" name="candidate_name" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your full name">
            </div>
            <div>
                <label for="candidate_email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address *</label>
                <input type="email" id="candidate_email" name="candidate_email" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your email address">
            </div>
            <div class="md:col-span-2 text-center">
                <button type="submit" id="startInterviewBtn"
                    class="bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition duration-300">
                    🚀 Start Interview
                </button>
            </div>
        </form>
    </div>

    <!-- Interview Questions Container -->
    <div id="questionsContainer" class="hidden">
        <!-- Question Navigation -->
        <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Question <span id="currentQuestionNumber">1</span> of {{ $interview->questions->count() }}</h2>
                <div class="flex space-x-2">
                    <button id="prevQuestionBtn" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        ← Previous
                    </button>
                    <button id="nextQuestionBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next →
                    </button>
                </div>
            </div>
        </div>

        <!-- Question Display -->
        <div id="questionContainer" class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-8 mb-8">
            <!-- Question content will be loaded here -->
        </div>

        <!-- Interview Controls -->
        <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-6">
            <div class="text-center">
                <button id="completeInterviewBtn" class="bg-green-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-green-700 transition duration-300 hidden">
                    ✅ Complete Interview
                </button>
            </div>
        </div>
    </div>

    <!-- Video Recording Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-4xl w-full mx-4">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Record Your Answer</h3>
                <p id="questionText" class="text-gray-600"></p>
            </div>

            <!-- Video Preview -->
            <div class="bg-gray-900 rounded-lg mb-6 relative">
                <video id="videoPreview" class="w-full h-64 object-cover rounded-lg" autoplay muted></video>
                <div id="recordingIndicator" class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm hidden">
                    🔴 RECORDING
                </div>
                <div id="timerDisplay" class="absolute top-4 right-4 bg-black bg-opacity-75 text-white px-3 py-1 rounded-full text-sm">
                    <span id="timer">00:00</span>
                </div>
            </div>

            <!-- Recording Controls -->
            <div class="flex justify-center space-x-4 mb-6">
                <button id="startRecordingBtn" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300">
                    🎥 Start Recording
                </button>
                <button id="stopRecordingBtn" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-300 hidden">
                    ⏹️ Stop Recording
                </button>
                <button id="pauseRecordingBtn" class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition duration-300 hidden">
                    ⏸️ Pause
                </button>
                <button id="resumeRecordingBtn" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300 hidden">
                    ▶️ Resume
                </button>
            </div>

            <!-- Video Playback -->
            <div id="videoPlayback" class="hidden mb-6">
                <video id="recordedVideo" class="w-full h-64 object-cover rounded-lg bg-gray-900" controls></video>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <button id="retakeVideoBtn" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition duration-300 hidden">
                    🔄 Retake Video
                </button>
                <button id="saveVideoBtn" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300 hidden">
                    💾 Save Answer
                </button>
                <button id="cancelVideoBtn" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-300">
                    ❌ Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Preparation Timer Modal -->
    <div id="preparationModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Get Ready!</h3>
            <div class="text-6xl font-bold text-blue-600 mb-4" id="preparationTimer">30</div>
            <p class="text-gray-600">Prepare your answer. Recording will start automatically.</p>
        </div>
    </div>
</div>

<!-- Hidden form for video upload -->
<form id="videoUploadForm" enctype="multipart/form-data" style="display: none;">
    <input type="file" id="videoFileInput" accept="video/*">
</form>

@endsection

@push('scripts')
<script>
class VideoInterview {
    constructor() {
        this.token = '{{ $token }}';
        this.interview = @json($interview);
        this.submissionId = null;
        this.currentQuestionIndex = 0;
        this.questions = this.interview.questions;
        this.mediaRecorder = null;
        this.recordedChunks = [];
        this.recordingStartTime = null;
        this.timerInterval = null;
        this.preparationTimer = null;
        
        this.initializeElements();
        this.bindEvents();
        this.loadProgress();
    }

    initializeElements() {
        // Forms and containers
        this.candidateInfoForm = document.getElementById('candidateInfoForm');
        this.questionsContainer = document.getElementById('questionsContainer');
        this.questionContainer = document.getElementById('questionContainer');
        this.videoModal = document.getElementById('videoModal');
        this.preparationModal = document.getElementById('preparationModal');
        
        // Buttons
        this.startInterviewBtn = document.getElementById('startInterviewBtn');
        this.prevQuestionBtn = document.getElementById('prevQuestionBtn');
        this.nextQuestionBtn = document.getElementById('nextQuestionBtn');
        this.completeInterviewBtn = document.getElementById('completeInterviewBtn');
        
        // Video elements
        this.videoPreview = document.getElementById('videoPreview');
        this.recordedVideo = document.getElementById('recordedVideo');
        this.startRecordingBtn = document.getElementById('startRecordingBtn');
        this.stopRecordingBtn = document.getElementById('stopRecordingBtn');
        this.pauseRecordingBtn = document.getElementById('pauseRecordingBtn');
        this.resumeRecordingBtn = document.getElementById('resumeRecordingBtn');
        this.retakeVideoBtn = document.getElementById('retakeVideoBtn');
        this.saveVideoBtn = document.getElementById('saveVideoBtn');
        this.cancelVideoBtn = document.getElementById('cancelVideoBtn');
        
        // Progress elements
        this.progressBar = document.getElementById('progressBar');
        this.progressText = document.getElementById('progressText');
        this.currentQuestionNumber = document.getElementById('currentQuestionNumber');
        
        // Timer elements
        this.timerDisplay = document.getElementById('timerDisplay');
        this.timer = document.getElementById('timer');
        this.preparationTimer = document.getElementById('preparationTimer');
        this.recordingIndicator = document.getElementById('recordingIndicator');
    }

    bindEvents() {
        // Start interview
        document.getElementById('infoForm').addEventListener('submit', (e) => this.startInterview(e));
        
        // Navigation
        this.prevQuestionBtn.addEventListener('click', () => this.previousQuestion());
        this.nextQuestionBtn.addEventListener('click', () => this.nextQuestion());
        this.completeInterviewBtn.addEventListener('click', () => this.completeInterview());
        
        // Video recording
        this.startRecordingBtn.addEventListener('click', () => this.startRecording());
        this.stopRecordingBtn.addEventListener('click', () => this.stopRecording());
        this.pauseRecordingBtn.addEventListener('click', () => this.pauseRecording());
        this.resumeRecordingBtn.addEventListener('click', () => this.resumeRecording());
        this.retakeVideoBtn.addEventListener('click', () => this.retakeVideo());
        this.saveVideoBtn.addEventListener('click', () => this.saveVideo());
        this.cancelVideoBtn.addEventListener('click', () => this.cancelVideo());
    }

    async startInterview(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const candidateData = {
            candidate_name: formData.get('candidate_name'),
            candidate_email: formData.get('candidate_email')
        };

        try {
            const response = await fetch(`/interview/${this.token}/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(candidateData)
            });

            const result = await response.json();
            
            if (result.success) {
                this.submissionId = result.submission_id;
                this.candidateInfoForm.classList.add('hidden');
                this.questionsContainer.classList.remove('hidden');
                this.loadQuestion(0);
                this.updateProgress();
            } else {
                alert('Failed to start interview: ' + result.message);
            }
        } catch (error) {
            console.error('Error starting interview:', error);
            alert('Failed to start interview. Please try again.');
        }
    }

    loadQuestion(index) {
        if (index < 0 || index >= this.questions.length) return;
        
        this.currentQuestionIndex = index;
        const question = this.questions[index];
        
        this.currentQuestionNumber.textContent = index + 1;
        
        // Update navigation buttons
        this.prevQuestionBtn.disabled = index === 0;
        this.nextQuestionBtn.disabled = index === this.questions.length - 1;
        
        // Show complete button on last question
        if (index === this.questions.length - 1) {
            this.completeInterviewBtn.classList.remove('hidden');
            this.nextQuestionBtn.classList.add('hidden');
        } else {
            this.completeInterviewBtn.classList.add('hidden');
            this.nextQuestionBtn.classList.remove('hidden');
        }
        
        // Load question content
        this.questionContainer.innerHTML = `
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">${question.question_text}</h3>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                    <p class="text-blue-800">
                        <strong>Instructions:</strong> Click the button below to record your video response. 
                        You have up to ${Math.floor(this.interview.max_video_duration / 60)} minutes to answer.
                        ${this.interview.allow_retakes ? `You can retake your answer up to ${this.interview.max_retakes_per_question} times.` : ''}
                    </p>
                </div>
                <button onclick="videoInterview.openVideoModal(${question.id})" 
                    class="bg-red-600 text-white font-bold py-4 px-8 rounded-lg hover:bg-red-700 transition duration-300 text-lg">
                    🎥 Record Video Answer
                </button>
            </div>
        `;
    }

    openVideoModal(questionId) {
        this.currentQuestionId = questionId;
        const question = this.questions.find(q => q.id === questionId);
        document.getElementById('questionText').textContent = question.question_text;
        
        this.videoModal.classList.remove('hidden');
        this.initializeCamera();
    }

    async initializeCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }, 
                audio: true 
            });
            
            this.videoPreview.srcObject = stream;
            this.mediaStream = stream;
        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Unable to access camera. Please check your permissions.');
        }
    }

    startRecording() {
        if (!this.mediaStream) return;
        
        this.recordedChunks = [];
        this.mediaRecorder = new MediaRecorder(this.mediaStream);
        
        this.mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                this.recordedChunks.push(event.data);
            }
        };
        
        this.mediaRecorder.onstop = () => {
            const blob = new Blob(this.recordedChunks, { type: 'video/webm' });
            const url = URL.createObjectURL(blob);
            this.recordedVideo.src = url;
            document.getElementById('videoPlayback').classList.remove('hidden');
            this.saveVideoBtn.classList.remove('hidden');
            this.retakeVideoBtn.classList.remove('hidden');
        };
        
        this.mediaRecorder.start();
        this.recordingStartTime = Date.now();
        
        // Update UI
        this.startRecordingBtn.classList.add('hidden');
        this.stopRecordingBtn.classList.remove('hidden');
        this.pauseRecordingBtn.classList.remove('hidden');
        this.recordingIndicator.classList.remove('hidden');
        
        // Start timer
        this.startTimer();
    }

    stopRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.stop();
        }
        
        // Update UI
        this.stopRecordingBtn.classList.add('hidden');
        this.pauseRecordingBtn.classList.add('hidden');
        this.resumeRecordingBtn.classList.add('hidden');
        this.recordingIndicator.classList.add('hidden');
        
        // Stop timer
        this.stopTimer();
    }

    pauseRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.pause();
            this.pauseRecordingBtn.classList.add('hidden');
            this.resumeRecordingBtn.classList.remove('hidden');
            this.stopTimer();
        }
    }

    resumeRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'paused') {
            this.mediaRecorder.resume();
            this.resumeRecordingBtn.classList.add('hidden');
            this.pauseRecordingBtn.classList.remove('hidden');
            this.startTimer();
        }
    }

    startTimer() {
        this.timerInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - this.recordingStartTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            this.timer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Check if max duration reached
            if (elapsed >= this.interview.max_video_duration) {
                this.stopRecording();
            }
        }, 1000);
    }

    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }

    async saveVideo() {
        if (!this.recordedChunks.length) return;
        
        const blob = new Blob(this.recordedChunks, { type: 'video/webm' });
        const formData = new FormData();
        formData.append('video', blob, 'response.webm');
        formData.append('submission_id', this.submissionId);
        formData.append('question_id', this.currentQuestionId);
        
        try {
            const response = await fetch(`/interview/${this.token}/save-video`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.closeVideoModal();
                this.updateProgress();
                this.showSuccessMessage('Video response saved successfully!');
            } else {
                alert('Failed to save video: ' + result.message);
            }
        } catch (error) {
            console.error('Error saving video:', error);
            alert('Failed to save video. Please try again.');
        }
    }

    retakeVideo() {
        document.getElementById('videoPlayback').classList.add('hidden');
        this.saveVideoBtn.classList.add('hidden');
        this.retakeVideoBtn.classList.add('hidden');
        this.startRecordingBtn.classList.remove('hidden');
        this.recordedChunks = [];
    }

    cancelVideo() {
        this.closeVideoModal();
    }

    closeVideoModal() {
        this.videoModal.classList.add('hidden');
        this.stopRecording();
        
        // Stop camera
        if (this.mediaStream) {
            this.mediaStream.getTracks().forEach(track => track.stop());
        }
        
        // Reset UI
        this.startRecordingBtn.classList.remove('hidden');
        this.stopRecordingBtn.classList.add('hidden');
        this.pauseRecordingBtn.classList.add('hidden');
        this.resumeRecordingBtn.classList.add('hidden');
        this.saveVideoBtn.classList.add('hidden');
        this.retakeVideoBtn.classList.add('hidden');
        document.getElementById('videoPlayback').classList.add('hidden');
        this.recordingIndicator.classList.add('hidden');
    }

    previousQuestion() {
        if (this.currentQuestionIndex > 0) {
            this.loadQuestion(this.currentQuestionIndex - 1);
        }
    }

    nextQuestion() {
        if (this.currentQuestionIndex < this.questions.length - 1) {
            this.loadQuestion(this.currentQuestionIndex + 1);
        }
    }

    async updateProgress() {
        try {
            const candidateEmail = document.getElementById('candidate_email')?.value;
            if (!candidateEmail) {
                return; // Don't update progress if no email is available yet
            }
            
            const response = await fetch(`/interview/${this.token}/progress?candidate_email=${encodeURIComponent(candidateEmail)}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                const progress = result.progress;
                this.progressBar.style.width = progress.percentage + '%';
                this.progressText.textContent = `${progress.completed_questions} of ${progress.total_questions} questions completed`;
            }
        } catch (error) {
            console.error('Error updating progress:', error);
        }
    }

    async loadProgress() {
        // Load existing progress if any - only if candidate email is available
        const candidateEmail = document.getElementById('candidate_email')?.value;
        if (candidateEmail) {
            await this.updateProgress();
        }
    }

    async completeInterview() {
        const result = await Swal.fire({
            title: 'Complete Interview?',
            text: "Are you sure you want to complete the interview? You won't be able to make changes after submission.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, complete it!',
            cancelButtonText: 'Cancel'
        });
        
        if (!result.isConfirmed) {
            return;
        }
        
        try {
            const response = await fetch(`/interview/${this.token}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    submission_id: this.submissionId
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Interview completed successfully!',
                    icon: 'success',
                    confirmButtonText: 'Continue',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.href = `/interview/${this.token}/thank-you`;
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to complete interview: ' + result.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            }
        } catch (error) {
            console.error('Error completing interview:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to complete interview. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        }
    }

    showSuccessMessage(message) {
        Swal.fire({
            title: 'Success!',
            text: message,
            icon: 'success',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }
}

// Initialize the video interview when the page loads
document.addEventListener('DOMContentLoaded', function() {
    window.videoInterview = new VideoInterview();
});
</script>
@endpush
