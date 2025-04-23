<?php

namespace App\Http\Controllers;

use App\Models\{Task, Project};
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::all();

        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            $query = Task::with('project');

            if ($request->has('project_id') && $request->project_id) {
                $query->where('project_id', $request->project_id);
            }

            $tasks = $query->orderBy('priority')->get();

            return response()->json([
                'tasks' => $tasks
            ]);
        }

        // Regular request returns view
        return view('tasks.index', compact('projects'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:150',
                'project_id' => 'required|exists:projects,id',
            ],
            [
                'name.required' => 'The task name is required',
                'project_id.required' => 'Please select a project',
                'project_id.exists' => 'The selected project is invalid',
            ],
        );

        $task = Task::create([
            'name' => $validated['name'],
            'project_id' => $validated['project_id'],
            'priority' => Task::where('project_id', $validated['project_id'])->count() + 1,
        ]);

        return response()->json([
            'task' => $task,
            'project_name' => $task->project->name,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $task->update(['name' => $request->name]);
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            Task::where('id', $item['id'])->update(['priority' => $item['position']]);
        }
        return response()->json(['success' => true]);
    }
}
