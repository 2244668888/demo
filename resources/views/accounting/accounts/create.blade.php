@extends('layouts.app')

@section('content')
@section('title')
    CREATE NEW ACCOUNTS
    @endsection
    @section('button')
        <a type="button" class="btn btn-info" href="{{ route('accounts.index') }}">
            <i class="bi bi-arrow-left"></i> Back
        </a>
@endsection
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card">
    <form action="{{ route('accounts.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group mb-3">
                    <label for="type" class="form-label">Account Type</label>
                    <select class="form-control" name="type" id="type" required onchange="showCategories(this.value)">
                        <option value="">Select Type</option>
                        <option value="asset">Asset</option>
                        <option value="liability">Liability</option>
                        <option value="equity">Equity</option>
                        <option value="expense">Expense</option>
                        <option value="income">Income</option>
                    </select>
                </div>
            
                <div class="col-md-4 form-group" id="categoryTypeDiv" style="display: none;">
                    <label for="categoryType">Category Type</label>
                    <select class="form-control" name="categoryType" id="categoryType">
                        <option value="">Select Type</option>
                        <option value="current">Current</option>
                        <option value="non-current">Non-Current</option>
                    </select>
                </div>
            
                <div class="col-md-4 form-group mb-3" id="category-section" style="display:none;">
                    <label for="category_id" class="form-label">Category (optional)</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">No Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" data-type="{{ $category->type }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="code" class="form-label">Account Code</label>
                    <input type="text" class="form-control" name="code" id="code" required>
                </div>
            
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Account Name</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
            
                <div class="col-md-4 mb-3">
                    <label for="opening_balance" class="form-label">Opening Balance</label>
                    <input type="number" class="form-control" name="opening_balance" id="opening_balance" required>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script>
    function showCategories(type) {
        const categorySection = document.getElementById('category-section');
        const categorySelect = document.getElementById('category_id');
        let hasCategories = false;
        categorySelect.querySelectorAll('option').forEach(option => {
            const optionType = option.getAttribute('data-type');
            if (optionType === type) {
                option.style.display = 'block';
                hasCategories = true;
            } else {
                option.style.display = 'none';
            }
        });
        categorySection.style.display = hasCategories ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const type = document.getElementById('type').value;
        if (type) {
            showCategories(type);
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const categoryTypeDiv = document.getElementById('categoryTypeDiv');

        typeSelect.addEventListener('change', function() {
            const selectedType = typeSelect.value;
            
            if (selectedType === 'asset' || selectedType === 'liability') {
                categoryTypeDiv.style.display = 'block';
            } else {
                categoryTypeDiv.style.display = 'none';
                document.getElementById('categoryType').value = '';
            }
        });
    });
</script>

@endsection
