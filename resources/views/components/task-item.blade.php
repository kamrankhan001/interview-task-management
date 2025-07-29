<li class="group p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move"
    data-id="{{ $task->id }}">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <span class="text-gray-800 dark:text-gray-200 task-name">{{ $task->name }}</span>
        </div>
        <div class="flex gap-2">
            <button class="edit-task opacity-0 group-hover:opacity-100 transition-opacity">
                <x-heroicon-o-pencil class="h-5 w-5 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300" />
            </button>
            <button class="delete-task opacity-0 group-hover:opacity-100 transition-opacity">
                <x-heroicon-o-trash class="h-5 w-5 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300" />
            </button>
        </div>
    </div>
    @if ($project->name)
        <div class="mt-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                {{ $project->name }}
            </span>
        </div>
    @endif
</li>