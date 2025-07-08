<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin']);
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status && $request->status != 'all') {
            $query->where('is_locked', $request->status == 'locked' ? 1 : 0);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.user-management', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('tasks')->findOrFail($id);
        return view('admin.show-users', compact('user'));
    }

    public function toggleLock(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_locked = !$user->is_locked;
            $user->save();

            $message = $user->is_locked ? 'Khóa tài khoản thành công.' : 'Mở khóa tài khoản thành công.';
            return redirect()->route('admin.users.index', [
                'search' => $request->search,
                'status' => $request->status,
            ])->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error in toggleLock: ' . $e->getMessage());
            return redirect()->route('admin.users.index', [
                'search' => $request->search,
                'status' => $request->status,
            ])->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.users.index', [
                'search' => $request->search,
                'status' => $request->status,
            ])->with('success', 'Xóa tài khoản thành công.');
        } catch (\Exception $e) {
            Log::error('Error in destroy: ' . $e->getMessage());
            return redirect()->route('admin.users.index', [
                'search' => $request->search,
                'status' => $request->status,
            ])->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
?>