<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin']);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'Completed')->count();
        return view('admin.dashboard', compact('totalUsers', 'totalTasks', 'completedTasks'));
    }

    public function statistics()
    {
        $stats = User::withCount([
            'tasks',
            'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'Completed');
            },
            'tasks as pending_tasks_count' => function ($query) {
                $query->whereIn('status', ['New', 'Inprogress', 'Pending']);
            }
        ])->get();

        return view('admin.statistics', compact('stats'));
    }

  
}
?>