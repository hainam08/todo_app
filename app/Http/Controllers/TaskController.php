<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function dash()
    {

        $notifications = auth()->user()->notifications()->latest()->take(5)->get();
        
        return view('user.dashboard', compact('notifications'));
       

    }

    public function index(Request $request)
    {
        $query = Task::where('user_id', Auth::id())->whereNull('deleted_at');

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('due_date') && $request->due_date) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('user.index', compact('tasks'));
    }

    public function create()
    {
        return view('user.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:New,Inprogress,Completed,Pending',
        ]);
        $dueDate = Carbon::parse($request->due_date, 'Asia/Ho_Chi_Minh');

        $remindAt = $request->filled('remind_at')
            ? Carbon::parse($request->remind_at, 'Asia/Ho_Chi_Minh')
            : $dueDate->copy()->subMinutes(15);



        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $dueDate,
            'remind_at' => $remindAt,
            'status' => $request->status,
        ]);
        //  dd($remindAt,$dueDate);

        return redirect()->route('user.index')->with('success', 'Thêm công việc thành công.');
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền sửa công việc này.');
        }

        return view('user.index', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền sửa công việc này.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:New,Inprogress,Completed,Pending',
        ]);
        $dueDate = $request->filled('due_date')
            ? Carbon::parse($request->due_date, 'Asia/Ho_Chi_Minh')
            : null;

        $remindAt = $request->filled('remind_at')
            ? Carbon::parse($request->remind_at, 'Asia/Ho_Chi_Minh')
            : ($dueDate ? $dueDate->copy()->subMinutes(15) : null);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $dueDate,
            'remind_at' => $remindAt,
            'status' => $request->status,
        ]);

        return redirect()->route('user.index')->with('success', 'Cập nhật công việc thành công.');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền xóa công việc này.');
        }

        $task->delete();

        return redirect()->route('user.index')->with('success', 'Xóa công việc thành công.');
    }

    // public function toggleStatus(Task $task)
    // {
    //     if ($task->user_id !== Auth::id()) {
    //         return redirect()->route('user.dashboard')->with('error', 'Bạn không có quyền thay đổi trạng thái công việc này.');
    //     }

    //     // Kiểm tra trạng thái hiện tại và chỉ toggle giữa Completed và Inprogress
    //     $newStatus = in_array($task->status, ['New', 'Inprogress', 'Completed', 'Pending'])
    //         ? ($task->status === 'Completed' ? 'Inprogress' : 'Completed')
    //         : 'Inprogress';

    //     $task->update([
    //         'status' => $newStatus,
    //     ]);

    //     return redirect()->route('user.dashboard')->with('success', 'Cập nhật trạng thái công việc thành công.');
    // }
    public function toggleReminder(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        $task->is_reminder_enabled = !$task->is_reminder_enabled;
        $task->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái nhắc nhở thành công.');
    }

    public function bulkComplete(Request $request)
    {
        $taskIds = $request->input('task_ids', []);

        if (empty($taskIds)) {
            return redirect()->route('user.index')->with('error', 'Vui lòng chọn ít nhất một công việc.');
        }

        Task::whereIn('id', $taskIds)
            ->where('user_id', Auth::id())
            ->update(['status' => 'Completed']);

        return redirect()->route('user.index')->with('success', 'Đã cập nhật trạng thái hoàn thành cho các công việc đã chọn.');
    }
}
