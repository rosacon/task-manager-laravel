<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request)
    {
        $q = trim($request->input('search', ''));

        $query = Task::query()
            ->when($q, fn($qBuilder, $term) => $qBuilder->where('title', 'like', "%{$term}%"))
            ->latest();

        $tasks = $query->paginate(2)->appends($request->except('page'));

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
        // Forma segura: crear mediante la relación del usuario autenticado
        $request->user()->tasks()->create($request->validated());        

        return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        // $data = ;
        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }
}
