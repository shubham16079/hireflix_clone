@extends('layouts.app')

@section('title', 'My Interviews')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Interviews</h1>

    @if ($interviews->isEmpty())
        <div class="bg-gray-50 p-6 rounded-lg text-center">
            <p class="text-lg text-gray-600">You haven't created any interviews yet. </p>
            <a href="{{ route('interviews.create') }}" class="inline-block mt-4 bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Create Your First Interview
            </a>
        </div>
    @else
        <div class="overflow-x-auto">   
            <table class="min-w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Title</th>
                        <th class="py-3 px-6 text-left">Description</th>
                        <th class="py-3 px-6 text-center">Questions</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($interviews as $interview)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $interview->title }}</td>
                        <td class="py-3 px-6 text-left">{{ \Illuminate\Support\Str::limit($interview->description, 50) }}</td>
                        <td class="py-3 px-6 text-center">{{ $interview->questions->count() }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('interviews.show', $interview) }}" 
                                   class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition duration-300"
                                   title="View Interview">
                                    View
                                </a>
                                <a href="{{ route('interviews.edit', $interview) }}" 
                                   class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition duration-300"
                                   title="Edit Interview">
                                    Edit
                                </a>
                                <button class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition duration-300"
                                        title="Share Interview">
                                    Share
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
