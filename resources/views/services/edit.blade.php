@extends('layouts.app')

@section('title', 'Edit Service')

@section('content')
    <div class="card">
        <div class="card-body">
            <h3>Edit Service</h3>

            <form action="{{ route('services.update', $service->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Service Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Service Nrice</label>
                    <input type="text" class="form-control" id="price" name="price" value="{{ old('price', $service->price) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Service</button>
            </form>
        </div>
    </div>
@endsection
