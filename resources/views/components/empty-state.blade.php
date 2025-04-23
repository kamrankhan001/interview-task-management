<div id="empty-state" class="text-center py-12 {{ ($show ?? true) && $projects->flatMap->tasks->isEmpty() ? '' : 'hidden' }}">
    <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-gray-400" />
    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
    <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
</div>
