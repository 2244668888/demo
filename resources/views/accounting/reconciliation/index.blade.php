@extends('layouts.app')

@section('content')
<div class="container">
    @section('title')
    <span class="text-uppercase">
        Reconciliation for {{ $account->name }}
    </span>
    @endsection

    @section('button')
        <a type="button" class="btn btn-info" href="#" id="scrollToForm">
            <i class="bi bi-plus-square"></i> Add External Statement
        </a>
    @endsection

    @if(session('success'))
        <div class="alert alert-success" style="color: black">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning" style="color: black">
            {!! session('warning') !!}
        </div>
    @endif

    <form action="{{ route('reconciliation.reconcile', $account->id) }}" method="POST">
        @csrf
        <h4 class="mb-3">General Ledger Transactions</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ledgerTransactions as $transaction)
                <tr>
                    <td><input type="checkbox" name="transactions[]" value="{{ $transaction->id }}" class="form-check-input"></td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->type == 'debit' ? $transaction->amount : '' }}</td>
                    <td>{{ $transaction->type == 'credit' ? $transaction->amount : '' }}</td>
                    <td>{{ $transaction->created_at->format('d/m/y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h2 class="mt-5 mb-3">External Statements</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($externalStatements as $statement)
                <tr>
                    <td><input type="checkbox" name="statements[]" value="{{ $statement->id }}" class="form-check-input"></td>
                    <td>{{ $statement->description }}</td>
                    <td>{{ $statement->type == 'debit' ? $statement->amount : '' }}</td>
                    <td>{{ $statement->type == 'credit' ? $statement->amount : '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($statement->transaction_date)->format('d/m/y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success mt-3">Reconcile Selected</button>
    </form>
    <div id="externalStatementForm" class="card mt-4">
        <form action="{{ route('reconciliation.storeExternalStatement', $account->id) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5 col-sm-4 col-12">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control" required>
                    </div>
                    <div class="col-lg-5 col-sm-4 col-12">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" required>
                    </div>
                    <div class="col-lg-5 col-sm-4 col-12">
                        <label for="type" class="form-label">Transaction Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="debit">Debit</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>                        
                    <div class="col-lg-5 col-sm-4 col-12">
                        <label for="transaction_date" class="form-label">Transaction Date</label>
                        <input type="date" name="transaction_date" id="transaction_date" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary mb-4">Submit</button>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    document.getElementById('scrollToForm').addEventListener('click', function (event) {
        event.preventDefault();
        const targetElement = document.getElementById('externalStatementForm');
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>
@endsection
