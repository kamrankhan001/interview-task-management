@extends('layouts.app')

@section('content')
    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Form and Controls -->
            <div>
                <!-- Header -->
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Task Manager</h1>
                    <p class="text-gray-600 dark:text-gray-400">Organize your work efficiently</p>
                </div>

                <!-- Project Selector -->
                <div class="mb-8">
                    <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by Project</label>
                    <select id="project_id"
                        class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="" selected disabled>All Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <strong id="project_id-error" class="text-red-500 dark:text-red-400 error-message"></strong>
                </div>

                <x-task-form />

                @if (!$showTasks)
                    <p class="bg-gray-50 dark:bg-gray-700 p-5 text-center text-xl text-gray-800 dark:text-gray-200" id="select-project-text">Please select the project.</p>
                @endif
            </div>

            <!-- Right Column - Task List -->
            <div>
                <x-task-list :projects="$projects" :showTasks="$showTasks"/>
            </div>
        </div>
    </div>
@endsection