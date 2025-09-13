@extends('layouts.candidate')

@section('title', 'Thank You')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-8 text-center">
        @if(session('success'))
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">🎉 Thank You!</h1>
                <p class="text-lg text-gray-600 mb-6">{{ session('success') }}</p>
            </div>
        @else
            <div class="mb-6">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">✅ Interview Completed</h1>
                <p class="text-lg text-gray-600 mb-6">Your interview has been successfully submitted!</p>
            </div>
        @endif

        <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg mb-6">
            <h2 class="text-xl font-semibold text-blue-800 mb-3">📋 Interview: {{ $interview->title }}</h2>
            <p class="text-blue-700">We have received your video responses and will review them carefully.</p>
            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $interview->questions->count() }}</div>
                    <p class="text-blue-700">Questions Answered</p>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">100%</div>
                    <p class="text-blue-700">Completion Rate</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">📧 What's Next?</h3>
            <ul class="text-left text-gray-600 space-y-2">
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                    Our team will review your interview responses
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                    We'll contact you within 3-5 business days
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                    Keep an eye on your email for updates
                </li>
            </ul>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">💡 Tips for Future Interviews</h3>
            <ul class="text-left text-yellow-700 space-y-1 text-sm">
                <li>• Prepare specific examples from your experience</li>
                <li>• Research the company and role beforehand</li>
                <li>• Practice common interview questions</li>
                <li>• Ask thoughtful questions about the role</li>
            </ul>
        </div>

        <div class="text-center">
            <p class="text-gray-600 mb-4">Thank you for taking the time to complete this interview.</p>
            <p class="text-sm text-gray-500">
                If you have any questions, please don't hesitate to contact us.
            </p>
        </div>
    </div>
</div>
@endsection
