<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $intern = auth()->user()->intern;
        $tasks = Task::where('intern_id', $intern->id)->with('mentor')->latest()->get();

        return view('pages.intern.tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        $intern = auth()->user()->intern;
        abort_if($task->intern_id !== $intern->id, 403);
        
        return view('pages.intern.tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $intern = auth()->user()->intern;
        abort_if($task->intern_id !== $intern->id, 403);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $request->status]);

        // Notifikasi ke Mentor
        if ($task->mentor_id) {
            NotificationService::send(
                userId: $task->mentor_id,
                title: 'Status Tugas Diperbarui',
                body: 'Intern ' . $intern->name . ' mengubah status tugas ' . $task->title . ' menjadi ' . str_replace('_', ' ', $request->status),
                type: 'info',
                icon: 'ri-task-line'
            );
        }

        return redirect()->route('intern.tasks.show', $task)->with('status', 'Status tugas berhasil diperbarui!');
    }
}
