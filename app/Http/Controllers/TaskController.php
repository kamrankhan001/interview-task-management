<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest, ReorderTaskRequest};
use App\Models\Project;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        try {
            $projects = Project::all();

            if ($request->ajax()) {
                $tasks = $this->taskService->getFilteredTasks($request->project_id);
                return $this->taskService->successResponse(['tasks' => $tasks]);
            }

            return view('tasks.index', [
                'projects' => $projects,
                'showTasks' => false,
            ]);

        } catch (\Exception $e) {
            return $this->taskService->handleError($e, $request);
        }
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            return $this->taskService->successResponse([
                'task' => $task,
                'project_name' => $task->project->name
            ]);
        } catch (\Exception $e) {
            return $this->taskService->handleJsonError($e);
        }
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        try {
            $task = $this->taskService->updateTask($id, $request->validated());
            return $this->taskService->successResponse(['task' => $task]);
        } catch (\Exception $e) {
            return $this->taskService->handleJsonError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $this->taskService->deleteTask($id);
            return $this->taskService->successResponse([], 'Task deleted successfully.');
        } catch (\Exception $e) {
            return $this->taskService->handleJsonError($e);
        }
    }

    public function reorder(ReorderTaskRequest $request)
    {
        try {
            $this->taskService->reorderTasks($request->order);
            return $this->taskService->successResponse([], 'Tasks reordered successfully.');
        } catch (\Exception $e) {
            return $this->taskService->handleJsonError($e);
        }
    }
}