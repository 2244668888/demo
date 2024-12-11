@extends('layouts.app')

@section('title', 'Services List')

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4">Services List</h3>
            <a href="{{ route('services.create') }}" class="btn btn-primary mb-3">Create New Service</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary btn-sm"> <i class="bi bi-pencil-square"></i> </a>
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
