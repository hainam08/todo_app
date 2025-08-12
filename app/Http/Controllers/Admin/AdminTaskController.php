<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpadateTaskRequest;
use Illuminate\Support\Facades\Log;

class AdminTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('due_date') && $request->due_date) {
            $query->whereDate('due_date', $request->due_date);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $tasks = $query->with('user')->orderBy('created_at', 'desc')->paginate(10);
        $users = User::select('id', 'name')->get();

        return view('admin.tasks', compact('tasks', 'users'));
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('admin.tasks-edit', compact('task'));
    }

    public function update(UpadateTaskRequest $request, $id)
    {
        

            $task = Task::findOrFail($id);
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'status' => $request->status,
            ]);

            return redirect()
            ->route('admin.tasks.index',$request->only('search','status','due_date','user_id'))
            ->with('success', 'Cập nhật công việc thành công.');
       
    }

    public function destroy(Request $request, $id)
    {
       
            $task = Task::findOrFail($id);
            $task->delete();
            return redirect()
            ->route('admin.tasks.index', $request->only('search','status','due_date','user_id'))
            ->with('success', 'Xóa công việc thành công.');
    }
}
