@extends('layouts.app')

@section('content')
<div class="container">
    @section('title')
        <span class="text-uppercase">
            Add Transaction for {{ $account->name }}
        </span>
    @endsection
    @section('button')
        <a href="{{ route('accounts.index') }}" class="btn btn-info">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    @endsection
    <div class="card">
        <form action="{{ route('transactions.store', $account->id) }}" method="POST" class="mt-4">
            @csrf
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="account_id" value="{{ $account->id }}">
                    <div class="col-lg-5 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="type" class="form-label">Transaction Type</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-4 col-12">
                        <label for="secondary_account_id" class="form-label">Select Corresponding Account</label>
                        <select name="secondary_account_id" id="secondary_account_id" class="form-control" required>
                            <option value="">Select Account</option>
                            @foreach($accounts as $accountType => $accountGroup)
                                <optgroup label="{{ ucfirst($accountType) }}">
                                    @foreach($accountGroup as $secondaryAccount)
                                        <option value="{{ $secondaryAccount->id }}">{{ $secondaryAccount->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Transaction Date</label>
                            <input type="date" name="created_at" id="created_at" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (optional)</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary">Add Transaction</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
