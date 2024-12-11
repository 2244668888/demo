@extends('layouts.app')
@section('title')
    ACCOUNT CATEGORIES EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('account_categories.update', $accountCategories->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $accountCategories->name }}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Please Select</option>
                                <option value="asset" @selected($accountCategories->type == 'asset')>Asset</option>
                                <option value="liability" @selected($accountCategories->type == 'liability')>Liability</option>
                                <option value="equity" @selected($accountCategories->type == 'equity')>Equity</option>
                                <option value="expense" @selected($accountCategories->type == 'expense')>Expense</option>
                                <option value="income" @selected($accountCategories->type == 'income')>Income</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('account_categories.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection