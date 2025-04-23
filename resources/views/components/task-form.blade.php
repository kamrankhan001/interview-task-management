<div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <label for="task-name" class="block text-sm font-medium text-gray-700 mb-2">Add New Task</label>
    <div class="flex gap-3">
        <input type="text" id="task-name"
            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="What needs to be done?">
        <button id="add-task"
            class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 shadow-sm">
            Add Task
        </button>
    </div>
    <strong id="name-error" class="text-red-500 error-message"></strong>
</div>
