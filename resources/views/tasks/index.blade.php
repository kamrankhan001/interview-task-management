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
                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Project</label>
                <select id="project_id"
                    class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm">
                    <option value="">All Projects</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                <strong id="project_id-error" class="text-red-500 error-message"></strong>
            </div>

            <!-- Add Task Form -->
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

            <!-- Task List -->
            <div class="space-y-3">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Your Tasks</h2>

                <ul id="task-list" class="space-y-3">
                    @foreach ($projects as $project)
                        @foreach ($project->tasks as $task)
                            <li class="group p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move"
                                data-id="{{ $task->id }}">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                        </svg>
                                        <span class="text-gray-800">{{ $task->name }}</span>
                                    </div>
                                    <button
                                        class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 edit-task opacity-0 group-hover:opacity-100 transition-opacity">Edit</button>
                                    <button
                                        class="opacity-0 group-hover:opacity-100 px-3 py-1 bg-red-100 text-red-600 text-sm font-medium rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 delete-task">
                                        Delete
                                    </button>
                                </div>
                                @if ($project->name)
                                    <div class="mt-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Add New Task
        $("#add-task").click(function() {
            let name = $("#task-name").val().trim();
            let project_id = $("#project_id").val();
            let isValid = true;

            // Clear previous errors
            $('.error-message').text('');

            // Validate name
            if (name === '') {
                $('#name-error').text('Please provide the name of task');
                isValid = false;
            }

            // Validate project selection
            if (!project_id) {
                $('#project_id-error').text('Please select the project');
                isValid = false;
            }

            if (!isValid) return;

            $.post("/tasks", {
                    name: name,
                    project_id: project_id,
                    _token: '{{ csrf_token() }}'
                })
                .done(onSuccess)
                .fail(onError);
        });

        function onSuccess(response) {
            // Add the new task to the list
            $("#task-list").prepend(`
            <li class="group p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move" data-id="${response.task.id}">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        <span class="text-gray-800">${response.task.name}</span>
                    </div>
                    <button class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 edit-task opacity-0 group-hover:opacity-100 transition-opacity">Edit</button>
                    <button class="opacity-0 group-hover:opacity-100 px-3 py-1 bg-red-100 text-red-600 text-sm font-medium rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 delete-task">
                        Delete
                    </button>
                </div>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        ${response.project_name}
                    </span>
                </div>
            </li>
        `);

            // Clear the input field
            $("#task-name").val('');

            // Hide empty state if shown
            $("#empty-state").addClass('hidden');
        }

        function onError(xhr) {
            if (xhr.status === 422) {
                // Handle validation errors
                let errors = xhr.responseJSON.errors;
                for (let field in errors) {
                    $(`#${field}-error`).text(errors[field][0]);
                }
            } else {
                console.error('Error:', xhr.responseText);
                alert('An error occurred while creating the task');
            }
        }

        // Delete Task
        $(document).on("click", ".delete-task", function() {
            let taskElement = $(this).closest('li');
            let taskId = taskElement.data('id');

            if (!confirm('Are you sure you want to delete this task?')) {
                return;
            }

            $.ajax({
                url: `/tasks/${taskId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    taskElement.remove();

                    // Show empty state if no tasks left
                    if ($('#task-list li').length === 0) {
                        $('#empty-state').removeClass('hidden');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    alert('Failed to delete the task. Please try again.');
                }
            });
        });

        // Drag & Drop Reordering
        $("#task-list").sortable({
            opacity: 0.7,
            update: function(event, ui) {
                let order = [];
                $("#task-list li").each(function(index) {
                    order.push({
                        id: $(this).data('id'),
                        position: index + 1
                    });
                });

                $.post("{{ route('tasks.reorder') }}", {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function(response) {
                        if (response.success) {
                            alert('Order updated successfully');
                        }
                    })
                    .fail(function(xhr) {
                        console.error('Error updating order:', xhr.responseJSON);

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            alert(xhr.responseJSON.message);
                        } else {
                            alert('Failed to update task order');
                        }

                        // Revert the sortable if needed
                        $("#task-list").sortable("cancel");
                    });
            }
        });

        // Edit Task
        $(document).on("click", ".edit-task", function() {
            let $li = $(this).closest('li');
            let taskId = $li.data('id');
            let $nameContainer = $li.find('.flex.items-center').find('span:first'); // Target the name span

            // Store current name
            let currentName = $nameContainer.text();

            // Replace text with input field
            $nameContainer.replaceWith(`
        <input type="text" class="edit-task-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
               value="${currentName}">
    `);

            // Focus and select all text
            $li.find('.edit-task-input').focus().select();

            // Hide edit/delete buttons while editing
            $li.find('.edit-task, .delete-task').hide();
        });

        // Save edited task (on enter key or blur)
        $(document).on('blur keypress', '.edit-task-input', function(e) {
            if (e.type === 'keypress' && e.which !== 13) return;

            let $input = $(this);
            let newName = $input.val().trim();
            let $li = $input.closest('li');
            let taskId = $li.data('id');

            if (!newName) {
                let originalName = $input.val(); // Get the original value before trimming
                $input.replaceWith(`<span class="text-gray-800">${originalName}</span>`);
                $li.find('.edit-task, .delete-task').show();
                return;
            }

            $.ajax({
                url: `/tasks/${taskId}`,
                type: 'PUT',
                data: {
                    name: newName,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    // Replace input with the new name
                    $input.replaceWith(`
                <span class="text-gray-800">${newName}</span>
            `);
                    $li.find('.edit-task, .delete-task').show();
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    alert('Failed to update the task. Please try again.');

                    // Revert to original display
                    let originalName = $input.val();
                    $input.replaceWith(`<span class="text-gray-800">${originalName}</span>`);
                    $li.find('.edit-task, .delete-task').show();
                }
            });
        });
    </script>
@endpush
