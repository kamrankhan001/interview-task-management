<li class="group p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move"
    data-id="{{ $task->id }}">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            {{-- <x-heroicon-o-dots-vertical class="h-5 w-5 text-gray-400 mr-3" /> --}}
            <span class="text-gray-800 task-name">{{ $task->name }}</span>
        </div>
        <div class="flex gap-2">
            <button class="edit-task opacity-0 group-hover:opacity-100 transition-opacity">
                <x-heroicon-o-pencil class="h-5 w-5 text-blue-500 hover:text-blue-600" />
            </button>
            <button class="delete-task opacity-0 group-hover:opacity-100 transition-opacity">
                <x-heroicon-o-trash class="h-5 w-5 text-red-500 hover:text-red-600" />
            </button>
        </div>
    </div>
    @if ($project->name)
        <div class="mt-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                {{ $project->name }}
            </span>
        </div>
    @endif
</li>
