@extends('layouts.master')

@section('content')
    <div class="row ">
        <h1>Edit Task</h1>

        <form method="POST" action="{{ route('tasks.update', $task) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-4">
                    <input type="text" class="form-control  name="name" value="{{ $task->name }}" required>
                </div>

                <div class="col-4">
                    <select name="project_id" class="form-control" required>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4">
                    <button class="btn btn-primary w-100" type="submit">Update Task</button>
                </div>
            </div>
        </form>
    @endsection
