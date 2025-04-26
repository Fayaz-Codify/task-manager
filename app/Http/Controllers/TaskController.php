<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::all();
        $selectedProject = $request->project_id;

        $tasks = $selectedProject ? Task::where('project_id', $selectedProject)->orderBy('priority')->get()
                                  : Task::orderBy('priority')->get();

        return view('tasks.index', compact('tasks', 'projects', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|exists:projects,id',
        ]);

        $maxPriority = Task::where('project_id', $request->project_id)->max('priority') ?? 0;

        Task::create([
            'name' => $request->name,
            'priority' => $maxPriority + 1,
            'project_id' => $request->project_id,
        ]);

        return redirect()->back();
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task->update($request->only('name', 'project_id'));

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back();
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
