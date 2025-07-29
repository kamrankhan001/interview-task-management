<div class="space-y-3">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Your Tasks</h2>

    <ul id="task-list" class="space-y-3 max-h-96 overflow-auto 
        scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100
        dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-800
        scrollbar-thumb-rounded-full scrollbar-track-rounded-full">
        @if ($showTasks)
            @foreach ($projects as $project)
                @foreach ($project->tasks as $task)
                    <x-task-item :task="$task" :project="$project" />
                @endforeach
            @endforeach
        @endif
    </ul>

    <x-empty-state :projects="$projects" :show="$showTasks ?? false" />
</div>