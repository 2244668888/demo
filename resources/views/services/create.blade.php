@extends('layouts.app')

@section('title', 'Create Service')

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4">Create New Service</h3>
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Service Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="price">Price (PKR)</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" step="0.01" required>
                </div>

                <button type="submit" class="btn btn-primary">Create Service</button>
            </form>
        </div>
    </div>
@endsection
