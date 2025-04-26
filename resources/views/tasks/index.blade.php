@extends('layouts.master')

@section('content')
<div class="row ">
    <div class="col-6 bg-light border p-4">
    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
            <input type="text" class="form-control  mb-2" name="name" placeholder="New Project Name" required>
            <button class="btn btn-primary " type="submit">Create Project</button>
        </form>

    </div>

        <div class="col-6 bg-light border p-4">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <input type="text" class="form-control mb-2" name="name" placeholder="Task Name" required>
                <select name="project_id" class="form-select mb-2" required>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success">Create Task</button>
            </form>
        </div>


        <div class="col-12 p-4">
            <form method="GET" action="{{ route('tasks.index') }}">
                <select name="project_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Projects</option>
                    @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ $selectedProject == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>



    <ul id="task-list" class="list-unstyled">
        @foreach ($tasks as $task)
            <li class="task-item bg-light m-2 p-2 border rounded" data-id="{{ $task->id }}">
                {{ $task->name }}
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-info btn-sm">Edit</a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn-sm btn btn-danger" type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        let el = document.getElementById('task-list');
        let sortable = new Sortable(el, {
            onEnd: function() {
                let order = [];
                document.querySelectorAll('.task-item').forEach(item => {
                    order.push(item.dataset.id);
                });

                fetch('{{ route('tasks.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        order: order
                    })
                });
            }
        });
    </script>
@endsection
