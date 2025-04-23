class TaskManager {
    constructor() {
        this.csrfToken = $('meta[name="csrf-token"]').attr('content');
        this.initEvents();
        this.loadTasks();
    }

    initEvents() {
        this.handleShowTask();
        this.handleAddTask();
        this.handleEditTask();
        this.handleDeleteTask();
        this.initSortable();
    }

    handleShowTask() {
        $("#project_id").change(() => {
            const project_id = $("#project_id").val();
            this.loadTasks(project_id);
        });
    }

    loadTasks(project_id = null) {
        const params = project_id ? { project_id } : {};

        $.get({
            url: "/tasks",
            data: params,
            dataType: 'json'
        })
        .done((response) => {
            this.renderTasks(response.tasks);
            this.checkEmptyState();
        })
        .fail(this.handleError);
    }

    renderTasks(tasks) {
        const $taskList = $("#task-list");
        $taskList.empty();

        if (tasks.length === 0) {
            $('#empty-state').removeClass('hidden');
            return;
        }

        tasks.forEach(task => {
            $taskList.append(this.createTaskElement(task));
        });

        $('#empty-state').addClass('hidden');
    }

    createTaskElement(task) {
        const projectName = task.project?.name || task.project_name;
        return `
            <li class="group p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move" data-id="${task.id}">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="h-5 w-5 text-gray-400 mr-3">⋮</span>
                        <span class="text-gray-800 task-name">${task.name}</span>
                    </div>
                    <div class="flex gap-2">
                        <button class="edit-task opacity-0 group-hover:opacity-100 transition-opacity">
                            ${this.getEditIcon()}
                        </button>
                        <button class="delete-task opacity-0 group-hover:opacity-100 transition-opacity">
                            ${this.getDeleteIcon()}
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        ${projectName}
                    </span>
                </div>
            </li>
        `;
    }

    getEditIcon() {
        return `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-500 hover:text-blue-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
            </svg>
        `;
    }

    getDeleteIcon() {
        return `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-red-500 hover:text-red-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        `;
    }

    handleAddTask() {
        $("#add-task").click(() => {
            const name = $("#task-name").val().trim();
            const project_id = $("#project_id").val();

            this.clearErrors();

            if (!this.validateTask(name, project_id)) return;

            $.post("/tasks", {
                name: name,
                project_id: project_id,
                _token: this.csrfToken
            })
            .done((response) => {
                this.addTaskToDOM(response);
            })
            .fail(this.handleError);
        });
    }

    addTaskToDOM(response) {
        $("#task-list").prepend(this.createTaskElement({
            ...response.task,
            project_name: response.project_name
        }));
        $("#task-name").val('');
        $("#empty-state").addClass('hidden');
    }

    handleDeleteTask() {
        $(document).on("click", ".delete-task", (e) => {
            const taskElement = $(e.currentTarget).closest('li');
            const taskId = taskElement.data('id');

            if (!confirm('Are you sure you want to delete this task?')) return;

            $.ajax({
                url: `/tasks/${taskId}`,
                type: 'DELETE',
                data: { _token: this.csrfToken },
                success: () => {
                    taskElement.remove();
                    this.checkEmptyState();
                },
                error: this.handleError
            });
        });
    }

    handleEditTask() {
        $(document).on("click", ".edit-task", (e) => {
            const $li = $(e.currentTarget).closest('li');
            const currentName = $li.find('.task-name').text();

            $li.find('.task-name').replaceWith(`
                <input type="text" class="edit-task-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="${currentName}">
            `);

            $li.find('.edit-task-input').focus().select();
            $li.find('.edit-task, .delete-task').hide();
        });

        $(document).on('blur keypress', '.edit-task-input', (e) => {
            if (e.type === 'keypress' && e.which !== 13) return;

            const $input = $(e.target);
            const newName = $input.val().trim();
            const $li = $input.closest('li');
            const taskId = $li.data('id');

            if (!newName) {
                this.revertEdit($input, $li);
                return;
            }

            $.ajax({
                url: `/tasks/${taskId}`,
                type: 'PUT',
                data: { name: newName, _token: this.csrfToken },
                success: () => {
                    $input.replaceWith(`<span class="text-gray-800 task-name">${newName}</span>`);
                    $li.find('.edit-task, .delete-task').show();
                },
                error: (xhr) => {
                    this.handleError(xhr);
                    this.revertEdit($input, $li);
                }
            });
        });
    }

    initSortable() {
        $("#task-list").sortable({
            opacity: 0.7,
            update: (event, ui) => {
                const order = [];
                $("#task-list li").each((index, el) => {
                    order.push({
                        id: $(el).data('id'),
                        position: index + 1
                    });
                });

                $.post("/tasks/reorder", {
                    order: order,
                    _token: this.csrfToken
                }).fail(this.handleError);
            }
        });
    }

    // Helper Methods
    validateTask(name, project_id) {
        let isValid = true;

        if (!name) {
            $('#name-error').text('Please provide the name of task');
            isValid = false;
        }

        if (!project_id) {
            $('#project_id-error').text('Please select the project');
            isValid = false;
        }

        return isValid;
    }

    clearErrors() {
        $('.error-message').text('');
    }

    revertEdit($input, $li) {
        const originalName = $li.find('.task-name').data('original-name') || $input.val();
        $input.replaceWith(`<span class="text-gray-800 task-name">${originalName}</span>`);
        $li.find('.edit-task, .delete-task').show();
    }

    checkEmptyState() {
        const isEmpty = $('#task-list li').length === 0;
        $('#empty-state').toggleClass('hidden', !isEmpty);
    }

    handleError(xhr) {
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            for (let field in errors) {
                $(`#${field}-error`).text(errors[field][0]);
            }
        } else {
            console.error('Error:', xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    }
}

// Initialize when document is ready
$(document).ready(() => {
    new TaskManager();
});
