@extends('layouts.app')

@section('content')
@section('title')
    <span class="text-uppercase">
        Ledger for {{ $account->name }}
    </span>
@endsection
@section('button')
    <a href="{{ route('ledger.export-single', ['accountId' => $account->id, 'format' => 'excel'] + request()->all()) }}" class="btn btn-success mr-2">
        <i class="bi bi-download"></i> Export
    </a>
    <a href="{{ route('ledger.export-single', ['accountId' => $account->id, 'format' => 'pdf'] + request()->all()) }}" class="btn btn-danger">
        Export PDF
    </a>
    <a href="{{ route('accounts.index') }}" class="btn btn-info">
        <i class="bi bi-arrow-left"></i> Back
    </a>
@endsection
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filter Options</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('ledger.ledgerAccount', $account->id) }}" id="search-form">
            <div class="row justify-content-center m-auto">
                <div class="col-lg-5 col-sm-4 col-12">
                    <label for="start_date">Start Date</label>
                    <input type="text" name="start_date" id="start_date" class="form-control" placeholder="Select Start Date" value="{{ request('start_date') }}">
                </div>

                <div class="col-lg-5 col-sm-4 col-12">
                    <label for="end_date">End Date</label>
                    <input type="text" name="end_date" id="end_date" class="form-control" placeholder="Select End Date" value="{{ request('end_date') }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Transaction Date</th>
            <th>Description</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @php
            $runningBalance = $account->opening_balance;
        @endphp
        @foreach ($transactions as $transaction)
        <tr>
            <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
            <td>{{ $transaction->description }}</td>
            <td>
                @if ($transaction->type == 'debit')
                    {{ number_format($transaction->amount, 2) }}
                @endif
            </td>
            <td>
                @if ($transaction->type == 'credit')
                    {{ number_format($transaction->amount, 2) }}
                @endif
            </td>
            <td>
                @php
                    if ($transaction->type == 'debit') {
                        $runningBalance += ($account->type == 'asset' || $account->type == 'expense') ? $transaction->amount : -$transaction->amount;
                    } else {
                        $runningBalance += ($account->type == 'liability' || $account->type == 'equity' || $account->type == 'income') ? $transaction->amount : -$transaction->amount;
                    }
                @endphp
                {{ number_format($runningBalance, 2) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        flatpickr("#start_date", {
            dateFormat: "d-m-Y",
        });
        flatpickr("#end_date", {
            dateFormat: "d-m-Y",
        });
        document.getElementById('clear-filters').addEventListener('click', function() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('search-form').submit();
        });
    });
</script>
@endsection

