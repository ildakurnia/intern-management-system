<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Task;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Mentor hanya bisa melihat tugas yang diberikan kepada anak bimbingannya
        $tasks = Task::whereHas('intern', function($q) use ($user) {
            $q->where('mentor_id', $user->id);
        })->with('intern')->latest()->get();

        return view('pages.mentor.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $user = auth()->user();
        // Hanya ambil intern yang ditugaskan ke mentor ini
        $interns = Intern::where('mentor_id', $user->id)->active()->get();
        return view('pages.mentor.tasks.create', compact('interns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'intern_id' => [
                'required',
                'exists:interns,id',
                // Validasi tambahan: Intern harus anak bimbingan mentor ini
                function ($attribute, $value, $fail) {
                    $exists = Intern::where('id', $value)
                        ->where('mentor_id', auth()->id())
                        ->exists();
                    if (!$exists) {
                        $fail('Anda hanya dapat memberikan tugas kepada anak bimbingan Anda sendiri.');
                    }
                },
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create([
            'intern_id' => $request->intern_id,
            'mentor_id' => auth()->id(),
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
                body: 'Mentor ' . auth()->user()->name . ' memberikan tugas: ' . $task->title,
                type: 'info',
                icon: 'ri-task-line'
            );
        }

        return redirect()->route('mentor.tasks.index')->with('status', 'Tugas berhasil diberikan!');
    }

    public function show(Task $task)
    {
        abort_if(optional($task->intern)->mentor_id !== auth()->id(), 403, 'Anda tidak memiliki akses ke tugas ini.');
        return view('pages.mentor.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        abort_if(optional($task->intern)->mentor_id !== auth()->id(), 403);
        return view('pages.mentor.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        abort_if(optional($task->intern)->mentor_id !== auth()->id(), 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only('title', 'description', 'status', 'due_date'));

        return redirect()->route('mentor.tasks.index')->with('status', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        abort_if(optional($task->intern)->mentor_id !== auth()->id(), 403);
        $task->delete();

        return redirect()->route('mentor.tasks.index')->with('status', 'Tugas berhasil dihapus!');
    }
}
