<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TaskService
{
    public function getFilteredTasks($projectId = null)
    {
        $query = Task::with('project');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        return $query->orderBy('priority')->get();
    }

    public function createTask(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Task::create([
                'name' => $data['name'],
                'project_id' => $data['project_id'],
                'priority' => Task::where('project_id', $data['project_id'])->count() + 1,
            ]);
        });
    }

    public function updateTask($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $task = Task::findOrFail($id);
            $task->update($data);
            return $task;
        });
    }

    public function deleteTask($id)
    {
        return DB::transaction(function () use ($id) {
            $task = Task::findOrFail($id);
            $projectId = $task->project_id;
            $priority = $task->priority;

            $task->delete();

            // Update priorities of remaining tasks
            Task::where('project_id', $projectId)
                ->where('priority', '>', $priority)
                ->decrement('priority');
        });
    }

    public function reorderTasks(array $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order as $item) {
                Task::where('id', $item['id'])->update(['priority' => $item['position']]);
            }
        });
    }

    public function handleError(Exception $e, Request $request = null)
    {
        if ($request && $request->ajax()) {
            return $this->handleJsonError($e);
        }

        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }

    public function handleJsonError(Exception $e, string $message = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message ?? 'An error occurred.',
            'error' => $e->getMessage()
        ], 500);
    }

    public function successResponse(array $data = [], string $message = null)
    {
        $response = ['success' => true];
        
        if (!empty($data)) {
            $response = array_merge($response, $data);
        }
        
        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response);
    }
}