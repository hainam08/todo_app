<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Hiển thị danh sách tất cả task
    public function index()
    {
        $tasks = Task::with('user')->get(); // Lấy tất cả task kèm thông tin user
        $users = User::all(); // Lấy danh sách user để gán khi tạo/sửa task
        return view('admin.admin', compact('tasks', 'users'));
    }

    // Hiển thị form tạo task
    public function create()
    {
        $users = User::all(); // Lấy danh sách user để chọn trong form
        return view('admin.tasks.create', compact('users'));
    }

    // Lưu task mới
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        Task::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    // Hiển thị form sửa task
    public function edit(Task $task)
    {
        $users = User::all();
        return view('admin.tasks.edit', compact('task', 'users'));
    }

    // Cập nhật task
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }

    // Xóa task
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }
}