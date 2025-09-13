@extends('layouts.candidate')

@section('title', 'Error')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-xl p-8 text-center">
        <div class="mb-6">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">⚠️ Oops!</h1>
            <p class="text-lg text-gray-600 mb-6">{{ $message ?? 'Something went wrong.' }}</p>
        </div>

        <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-lg mb-6">
            <h2 class="text-xl font-semibold text-red-800 mb-3">Possible Reasons:</h2>
            <ul class="text-left text-red-700 space-y-2">
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                    The interview link has expired
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                    The interview has been removed
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                    The link was copied incorrectly
                </li>
            </ul>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">What You Can Do:</h3>
            <ul class="text-left text-blue-700 space-y-2">
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                    Contact the person who sent you this link
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                    Request a new interview link
                </li>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                    Check your email for the correct link
                </li>
            </ul>
        </div>

        <div class="text-center">
            <p class="text-gray-600 mb-4">We apologize for any inconvenience.</p>
            <p class="text-sm text-gray-500">
                If you continue to experience issues, please contact support.
            </p>
        </div>
    </div>
</div>
@endsection
