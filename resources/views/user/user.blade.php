@extends('layouts.master-user')

@section('title', 'User Tasks')

@section('css')
<link href="{{ URL::asset('build/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="file-manager-content w-100 p-4 pb-0">
    <div class="row mb-4">
        <div class="col-sm">
            <h5 class="fw-semibold mb-0">My Tasks</h5>
        </div>
        <div class="col-auto ms-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTask">
                <i class="ri-add-fill align-bottom"></i> Add Task
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
        <div class="todo-task" id="todo-task">
            <div class="table-responsive">
                <table class="table align-middle position-relative table-nowrap">
                    <thead class="table-active">
                        <tr>
                            <th scope="col">Task Name</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="task-list">
                        @forelse ($tasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                               <td>
                                    @if ($task->due_date instanceof \Carbon\Carbon)
                                        {{ $task->due_date->format('Y-m-d') }}
                                    @else
                                        {{ $task->due_date ?: 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        {{ $task->status == 'completed' ? 'bg-success' : 
                                           ($task->status == 'in_progress' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        {{ $task->priority == 'high' ? 'bg-danger' : 
                                           ($task->priority == 'medium' ? 'bg-warning' : 'bg-success') }}">
                                        {{ ucfirst($task->priority ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2">
                                        <button class="btn btn-sm btn-soft-primary edit-task" data-bs-toggle="modal" data-bs-target="#createTask" data-task-id="{{ $task->id }}">Edit</button>
                                        <button class="btn btn-sm btn-soft-danger remove-task" data-bs-toggle="modal" data-bs-target="#removeTaskItemModal" data-task-id="{{ $task->id }}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px"></lord-icon>
                                    <h5 class="mt-4">No Tasks Found</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Task Modal -->
<div class="modal fade" id="createTask" tabindex="-1" aria-labelledby="createTaskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-success-subtle">
                <h5 class="modal-title" id="createTaskLabel">Create Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="createTaskBtn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="task-error-msg" class="alert alert-danger py-2" style="display: none;"></div>
                <form autocomplete="off" id="creattask-form" method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    <input type="hidden" id="taskid-input" name="task_id">
                    @method('POST')
                    <div class="mb-3">
                        <label for="task-title-input" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" id="task-title-input" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Enter task title" value="{{ old('title') }}">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="task-description-input" class="form-label">Description</label>
                        <textarea id="task-description-input" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Enter description">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="task-duedate-input" class="form-label">Due Date</label>
                        <input type="date" id="task-duedate-input" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                        @error('due_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="task-status-input" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices data-choices-search-false id="task-status-input" name="status">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="priority-field" class="form-label">Priority <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices data-choices-search-false id="priority-field" name="priority">
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                        @error('priority')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-ghost-success" data-bs-dismiss="modal"><i class="ri-close-fill align-bottom"></i> Close</button>
                        <button type="submit" class="btn btn-primary" id="addNewTodo">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Task Modal -->
<div id="removeTaskItemModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-removetodomodal"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this task?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <form id="delete-task-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn w-sm btn-danger" id="remove-todoitem">Yes, Delete It!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Choices.js for select elements
        new Choices('#task-status-input');
        new Choices('#priority-field');

        // Handle Edit Task
        document.querySelectorAll('.edit-task').forEach(button => {
            button.addEventListener('click', function () {
                const taskId = this.getAttribute('data-task-id');
                const task = @json($tasks).find(t => t.id == taskId);
                if (task) {
                    document.getElementById('createTaskLabel').innerText = 'Edit Task';
                    document.getElementById('creattask-form').action = `/tasks/${taskId}`;
                    document.getElementById('creattask-form').innerHTML += `<input type="hidden" name="_method" value="PUT">`;
                    document.getElementById('taskid-input').value = task.id;
                    document.getElementById('task-title-input').value = task.title;
                    document.getElementById('task-description-input').value = task.description || '';
                    document.getElementById('task-duedate-input').value = task.due_date || '';
                    document.getElementById('task-status-input').value = task.status;
                    document.getElementById('priority-field').value = task.priority || 'low';
                    new Choices('#task-status-input').setChoiceByValue(task.status);
                    new Choices('#priority-field').setChoiceByValue(task.priority || 'low');
                }
            });
        });

        // Handle Delete Task
        document.querySelectorAll('.remove-task').forEach(button => {
            button.addEventListener('click', function () {
                const taskId = this.getAttribute('data-task-id');
                const form = document.getElementById('delete-task-form');
                form.action = `/tasks/${taskId}`;
            });
        });
    });
</script>
@endsection