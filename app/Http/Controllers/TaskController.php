<?php

namespace App\Http\Controllers;

use App\Models\{Task, Project};
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('tasks')->get();
        return view('tasks.index', compact('projects'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        $task = Task::create([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'priority' => Task::where('project_id', $request->project_id)->count() + 1,
        ]);

        return response()->json($task);
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
