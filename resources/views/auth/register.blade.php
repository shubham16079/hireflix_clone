@extends('layouts.auth')

@section('title', 'Sign Up')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
    <h2 class="text-2xl font-bold text-center mb-6">Create Your Account</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-semibold mb-2">I am a...</label>
            <div class="flex space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="role" value="admin" class="form-radio text-blue-600" required>
                    <span class="ml-2 text-sm text-gray-700">Admin</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="role" value="reviewer" class="form-radio text-blue-600" required>
                    <span class="ml-2 text-sm text-gray-700">Reviewer</span>
                </label>
            </div>
            @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input type="password" id="password" name="password" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
            Sign Up
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-4">
        Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign In</a>
    </p>
</div>
@endsection
