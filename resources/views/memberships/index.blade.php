@extends('layouts.app')

@section('title')
    MEMBERSHIPS VIEW
@endsection

@section('button')
    <a type="button" class="btn btn-info" href="{{ route('memberships.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection

@section('content')
    <style>
        .table thead tr input {
            background: transparent;
            color: white;
        }

        .services,
        .total-price {
            word-wrap: break-word;
            max-width: 150px;
        }
    </style>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0" id="member">
                    <thead>
                        <tr>
                            <th>Ref No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Services</th>
                            <th>Total Price (PKR)</th>
                            <th>Issue Date</th>
                            <th>Expiry Date</th>
                            <th>Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberships as $membership)
                            <tr>
                                <td>{{ $membership->ref_no }}</td>
                                <td>{{ $membership->member_name }}</td>
                                <td>{{ $membership->phone }}</td>
                                <td class="services">
    @foreach ($membership->services as $service)
        {{ $service->name }} ({{ $service->pivot->price }} PKR) <br>
    @endforeach
</td>
<td class="total-price">
    {{ $membership->services->sum('pivot.price') }} PKR
</td>


                                <td>{{ $membership->issue_date }}</td>
                                <td>{{ $membership->expiry_date }}</td>
                                <td>
                                <a class="btn btn-danger btn-sm mx-1" href="{{ route('memberships.preview', $membership->id) }}" target="_blank" title="Preview PDF">
    <i class="bi bi-file-pdf"></i>  
</a>

                                    <a href="{{ route('memberships.edit', $membership->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                    <a href="{{ route('stripe.payment', $membership->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-credit-card"></i> 
                                    </a>
                                    <form action="{{ route('memberships.destroy', $membership->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this membership?');">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#member').DataTable({
                responsive: true,  e
                paging: true,       
                searching: true,    
                ordering: true,     
                order: [[0, 'asc']],
                lengthMenu: [5, 10, 15, 25],  
                columnDefs: [
                    { targets: [2, 3], orderable: false } 
                ]
            });
        });
    </script>
@endsection
