<?php

namespace App\Http\Controllers;

use App\Models\{Task, Project};
use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest, ReorderTaskRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $projects = Project::all();

            if ($request->ajax()) {
                $query = Task::with('project');

                if ($request->has('project_id') && $request->project_id) {
                    $query->where('project_id', $request->project_id);
                }

                $tasks = $query->orderBy('priority')->get();

                return response()->json([
                    'success' => true,
                    'tasks' => $tasks,
                ]);
            }

            return view('tasks.index', [
                'projects' => $projects,
                'showTasks' => false,
            ]);

        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $task = Task::create([
                    'name' => $request->name,
                    'project_id' => $request->project_id,
                    'priority' => Task::where('project_id', $request->project_id)->count() + 1,
                ]);

                return response()->json([
                    'success' => true,
                    'task' => $task,
                    'project_name' => $task->project->name,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            DB::transaction(function () use ($request, $task) {
                $task->update(['name' => $request->name]);
            });

            return response()->json([
                'success' => true,
                'task' => $task
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            return DB::transaction(function () use ($task) {
                // Update priorities of remaining tasks
                Task::where('project_id', $task->project_id)
                    ->where('priority', '>', $task->priority)
                    ->decrement('priority');

                $task->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Task deleted successfully.'
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reorder(ReorderTaskRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->order as $item) {
                    Task::where('id', $item['id'])->update(['priority' => $item['position']]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Tasks reordered successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder tasks.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function handleError(\Exception $e, Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }

        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
