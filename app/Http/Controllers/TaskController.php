<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // Hiển thị danh sách task của user
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return view('user.user', compact('tasks'));
    }

    // Hiển thị form tạo task
    public function create()
    {
        return view('todo_client'); // Modal trong todo_client xử lý tạo
    }

    // Lưu task mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:high,medium,low',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }

    // Hiển thị form sửa task
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        return view('todo_client', compact('task')); // Modal trong todo_client xử lý sửa
    }

    // Cập nhật task
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:high,medium,low',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
    }

    // Xóa task
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $task->delete();
        return redirect()->route('dashboard')->with('success', 'Task deleted successfully.');
    }
}