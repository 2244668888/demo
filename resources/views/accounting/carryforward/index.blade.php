@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Carryforward</h2>

    <div class="card">
        <div class="card-header">Calculate Carryforward</div>
        <div class="card-body">
            <form id="carryforward-form">
                <div class="form-group">
                    <label for="financial_year">Select Financial Year:</label>
                    <select class="form-control" id="financial_year" name="financial_year">
                        <option value="">Select Year</option>
                        @foreach(range(date('Y') - 1, date('Y') - 17) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Save Carryforward</button>
                </div>
            </form>

            <div id="calculated-carryforward" class="mt-3">
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('financial_year').addEventListener('change', function() {
        const year = this.value;
        if (year) {
            fetch(`calculate-carryforward/${year}`)
                .then(response => response.json())
                .then(data => {
                    let formattedBalance;
                    if (data.balance >= 0) {
                        formattedBalance = parseFloat(data.balance).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    } else {
                        formattedBalance = `-${Math.abs(parseFloat(data.balance)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`; // Fixed this line
                    }

                    const statusMessage = data.status === 'Profit' ? 'Profit' : 'Loss';
                    document.getElementById('calculated-carryforward').innerHTML =
                        '<strong>Calculated Carryforward:</strong> ' + formattedBalance + ' (' +
                        statusMessage + ')';
                })
                .catch(error => {
                    console.error('Error calculating carryforward:', error);
                });
        }
    });

    document.getElementById('carryforward-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const year = document.getElementById('financial_year').value;
        const balance = document.getElementById('calculated-carryforward').innerText.split(': ')[1].split(' ')[0];

        fetch("carryforward.store", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'rL9CrYecyXTck7nfQmOFtP69REXDdoXmzT8ZPH6P'
                },
                body: JSON.stringify({
                    year: year,
                    balance: balance
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.message);
                } else {
                    alert('Carryforward saved successfully!');
                }
            })
            .catch(error => {
                console.error('Error saving carryforward:', error);
            });
    });
</script>


@endsection
