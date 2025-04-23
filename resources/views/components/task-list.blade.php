<div class="space-y-3">
    <h2 class="text-lg font-semibold text-gray-800 mb-3">Your Tasks</h2>

    <ul id="task-list" class="space-y-3">
        @foreach ($projects as $project)
            @foreach ($project->tasks as $task)
                <x-task-item :task="$task" :project="$project" />
            @endforeach
        @endforeach
    </ul>

    <x-empty-state :projects="$projects"/>
</div>
