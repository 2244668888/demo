@extends('layouts.app')

@section('title')
EDIT MEMBERSHIP
@endsection

@section('content')
<div class="card">
    <div class="container">
        <h2>Edit Membership</h2>
        <form action="{{ route('memberships.update', $membership->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ref_no" class="form-label">Ref No</label>
                    <input type="text" name="ref_no" class="form-control" value="{{ old('ref_no', $membership->ref_no) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="member_name" class="form-label">Member Name</label>
                    <input type="text" name="member_name" class="form-control" value="{{ old('member_name', $membership->member_name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="number" name="phone" class="form-control" value="{{ old('phone', $membership->phone) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="issue_date" class="form-label">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', $membership->issue_date) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $membership->expiry_date) }}" required>
                </div>
            </div>

            <div class="row" id="service-fields">
    @foreach ($membership->services as $service)
        <div class="col-md-6 mb-3 d-flex align-items-center" id="service-row-{{ $service->id }}">
            <input type="hidden" name="added_services[]" value="{{ $service->id }}">
            <input type="hidden" name="added_prices[{{ $service->id }}]" value="{{ $service->pivot->price }}">
            <input type="text" value="{{ $service->name }} ({{ $service->pivot->price }} PKR)" 
                   class="form-control me-2" readonly>
            <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endforeach
</div>

<select id="services" class="form-select">
    <option value="" disabled selected>Select a service</option>
    @foreach ($services as $service)
        @if (!in_array($service->id, $membership->services->pluck('id')->toArray()))
            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                {{ $service->name }} ({{ $service->price }} PKR)
            </option>
        @endif
    @endforeach
</select>


<!-- Button to Add Service -->
<div class="d-flex justify-content-end mb-3">
    <button type="button" id="add-row-btn" class="btn btn-primary mt-3">Add Service</button>
</div>


            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="{{ route('memberships.index') }}" class="btn btn-secondary btn-md">Back</a>
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-primary btn-md">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
 document.addEventListener('DOMContentLoaded', function () {
    const serviceSelect = document.getElementById('services');
    const serviceFields = document.getElementById('service-fields');
    const addRowBtn = document.getElementById('add-row-btn');

    addRowBtn.addEventListener('click', function () {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];

        if (!selectedOption || selectedOption.value === "" || selectedOption.disabled) {
            alert('Please select a valid service first!');
            return;
        }

        const serviceId = selectedOption.value;
        const serviceName = selectedOption.text;
        const servicePrice = selectedOption.dataset.price;

        if (document.getElementById(`service-row-${serviceId}`)) {
            alert('This service is already added!');
            return;
        }

        const newRow = document.createElement('div');
        newRow.classList.add('col-md-6', 'mb-3', 'd-flex', 'align-items-center');
        newRow.id = `service-row-${serviceId}`;

        newRow.innerHTML = `
            <input type="hidden" name="added_services[]" value="${serviceId}">
            <input type="hidden" name="added_prices[${serviceId}]" value="${servicePrice}">
            <input type="text" value="${serviceName} (${servicePrice} PKR)" class="form-control me-2" readonly>
            <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                <i class="bi bi-x"></i>
            </button>
        `;

        serviceFields.appendChild(newRow);

        selectedOption.disabled = true;

        serviceSelect.value = "";  
    });

    serviceFields.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row-btn') || e.target.closest('.remove-row-btn')) {
            const rowToRemove = e.target.closest('.col-md-6');
            const serviceId = rowToRemove.querySelector('input[name="added_services[]"]').value;

            serviceFields.removeChild(rowToRemove);

            const optionToEnable = serviceSelect.querySelector(`option[value="${serviceId}"]`);
            if (optionToEnable) {
                optionToEnable.disabled = false;
            }
        }
    });
});

</script>