<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Task;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('intern')->latest()->get();
        return view('pages.admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $interns = Intern::active()->get();
        return view('pages.admin.tasks.create', compact('interns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create([
            'intern_id' => $request->intern_id,
            'mentor_id' => auth()->id(), // Admin as the assigner
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        // Notifikasi ke intern
        $intern = Intern::find($request->intern_id);
        if ($intern->user_id) {
            NotificationService::send(
                userId: $intern->user_id,
                title: 'Tugas Baru Diberikan',
                body: 'Admin ' . auth()->user()->name . ' memberikan tugas: ' . $task->title,
                type: 'info',
                icon: 'ri-task-line'
            );
        }

        return redirect()->route('admin.tasks.index')->with('status', 'Tugas berhasil diberikan!');
    }

    public function show(Task $task)
    {
        return view('pages.admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('pages.admin.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only('title', 'description', 'status', 'due_date'));

        return redirect()->route('admin.tasks.index')->with('status', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('status', 'Tugas berhasil dihapus!');
    }
}
