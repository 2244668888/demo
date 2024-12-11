@extends('layouts.app')

@section('title')
CREATE MEMBERSHIP
@endsection

@section('content')
<div class="card">
    <div class="container">
        <h2>Create Membership</h2>
        <form action="{{ route('memberships.store') }}" method="POST">
            @csrf
            <div class="row">
            <div class="col-md-6 mb-3">
    <label for="ref_no" class="form-label">Ref No</label>
    <input type="text" name="ref_no" class="form-control" value="{{ old('ref_no', $refNo) }}" readonly required>
</div>

                <div class="col-md-6 mb-3">
                    <label for="member_name" class="form-label">Member Name</label>
                    <input type="text" name="member_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="number" name="phone" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="issue_date" class="form-label">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="services" class="form-label">Select Services</label>
                    <select name="dropdown_services[]" id="services" class="form-select" multiple>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}" 
                                {{ in_array($service->id, old('services', [])) ? 'selected' : '' }}>
                                {{ $service->name }} ({{ $service->price }} PKR)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row" id="service-fields"></div>

            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-success btn-sm" id="add-row-btn">
                    <i class="bi bi-plus"></i> Add Row
                </button>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="{{ route('memberships.index') }}" class="btn btn-secondary btn-md">Back</a>
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
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
        const availableServices = Array.from(serviceSelect.options);

        const nextService = availableServices.find(option => 
            !document.getElementById(`service-row-${option.value}`)
        );

        if (!nextService) {
            alert('No more services to add!');
            return;
        }

        const serviceId = nextService.value;
        const serviceName = nextService.text;
        const servicePrice = nextService.dataset.price;

        const newRow = document.createElement('div');
        newRow.classList.add('col-md-6', 'mb-3', 'd-flex', 'align-items-center');
        newRow.id = `service-row-${serviceId}`;

        newRow.innerHTML = `
            <input type="hidden" name="added_services[]" value="${serviceId}">
            <input type="hidden" name="added_prices[${serviceId}]" value="${servicePrice}">
            <input type="text" value="${serviceName} (${servicePrice} PKR)" 
                   class="form-control me-2" readonly>
            <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                <i class="bi bi-x"></i>
            </button>
        `;

        serviceFields.appendChild(newRow);
    });

    serviceFields.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row-btn') || 
            e.target.closest('.remove-row-btn')) {
            const rowToRemove = e.target.closest('.col-md-6');
            if (rowToRemove) {
                serviceFields.removeChild(rowToRemove);
            }
        }
    });
});
</script>
