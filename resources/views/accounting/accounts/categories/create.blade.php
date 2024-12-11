@extends('layouts.app')

@section('content')
<div class="container">
    @section('title')
        CATEGORY CREATE
    @endsection
    @section('button')
        <a type="button" class="btn btn-info" href="{{ route('account_categories.index') }}">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    @endsection
    <div class="card">
        <form action="{{ route('account_categories.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="name">Category Name:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="form-group mb-3">
                            <label for="type">Category Type:</label>
                            <select class="form-control" name="type" required>
                                <option value="">Select Type</option>
                                <option value="asset">Asset</option>
                                <option value="liability">Liability</option>
                                <option value="equity">Equity</option>
                                <option value="expense">Expense</option>
                                <option value="income">Income</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>  
</div>
@endsection
