@extends('layouts.app')

@section('content')
    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Task Manager</h1>
                <p class="text-gray-600">Organize your work efficiently</p>
            </div>

            <!-- Project Selector -->
            <div class="mb-8">
                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Project</label>
                <select id="project_id"
                    class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm">
                    <option value="" selected disabled>All Projects</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                <strong id="project_id-error" class="text-red-500 error-message"></strong>
            </div>

            <x-task-form />

            <x-task-list :projects="$projects" :showTasks="$showTasks"/>

            @if (!$showTasks)
                <p class="bg-gray-50 p-5 text-center text-xl" id="select-project-text">Please select the project.</p>
            @endif
        </div>
    </div>
@endsection
