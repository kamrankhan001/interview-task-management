@extends('layouts.app')

@section('content')
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Task Manager</h1>
                <p class="text-gray-600">Organize your work efficiently</p>
            </div>

            <!-- Project Selector -->
            <div class="mb-8">
                <label for="project-select" class="block text-sm font-medium text-gray-700 mb-2">Filter by Project</label>
                <select id="project-select" class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Add Task Form -->
            <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <label for="task-name" class="block text-sm font-medium text-gray-700 mb-2">Add New Task</label>
                <div class="flex gap-3">
                    <input type="text" id="task-name" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="What needs to be done?">
                    <button id="add-task" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 shadow-sm">
                        Add Task
                    </button>
                </div>
            </div>

            <!-- Task List -->
            <div class="space-y-3">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Your Tasks</h2>

                <ul id="task-list" class="space-y-3">
                    @foreach($projects as $project)
                        @foreach($project->tasks as $task)
                            <li class="group p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move" data-id="{{ $task->id }}">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                        </svg>
                                        <span class="text-gray-800">{{ $task->name }}</span>
                                    </div>
                                    <button class="opacity-0 group-hover:opacity-100 px-3 py-1 bg-red-100 text-red-600 text-sm font-medium rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 delete-task">
                                        Delete
                                    </button>
                                </div>
                                @if($project->name)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $project->name }}
                                        </span>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    @endforeach
                </ul>

                <!-- Empty State -->
                <div id="empty-state" class="text-center py-12 hidden">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth drag and drop styling */
        .sortable-chosen {
            opacity: 0.8;
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .sortable-ghost {
            opacity: 0.4;
            background: #e5e7eb;
            border: 2px dashed #9ca3af;
        }
    </style>
@endsection

