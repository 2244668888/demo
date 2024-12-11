@extends('layouts.app')
@section('title')
    DELIVERY INSTRUCTION EDIT
@endsection
@section('content')
    <style>
        #dayGrid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            padding: 10px;
        }

        .day,
        .header {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .day input {
            width: 100%;
            margin-top: 5px;
        }

        .header {
            font-weight: bold;
            background-color: transparent;
        }
    </style>
    <div class="card">
        <form method="post" action="{{ route('delivery_instruction.update', $delivery_instruction->id) }}"
            enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Order No <a href="#" target="_blank"
                                    id="so_view" type="button">
                                    <i class="bi bi-eye"></i></a></label>
                            <select name="order_id" onchange="order_change()" id="order_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}" @selected($delivery_instruction->order_id == $order->id)>{{ $order->order_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" id="customer_name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_month" class="form-label">Order Month</label>
                            <input type="text" id="order_month" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
    <label for="date" class="form-label">Created Date</label>
    <input type="date" name="date" id="date" class="form-control" 
        value="{{ \Carbon\Carbon::parse($delivery_instruction->date)->format('Y-m-d') }}">
</div>


                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="mainTable">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Type of Product</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Calendar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="hidden" id="products" name="products">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('delivery_instruction.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="button" class="btn btn-primary submit">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- CALENDAR MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalTitle" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="product_id">
                    <h5 class="modal-title" id="exampleModalTitle">
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label for="part_no" class="form-label">Part No: </label>
                                <input type="text" id="part_no" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label for="part_name" class="form-label">Part Name: </label>
                                <input type="text" id="part_name" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label for="model" class="form-label">Model: </label>
                                <input type="text" id="model" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label for="type_of_product" class="form-label">Type of Product: </label>
                                <input type="text" id="type_of_product" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label for="variance" class="form-label">Variance: </label>
                                <input type="text" id="variance" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row text-center">
                        <h5 class="modal-title-2"></h5>
                    </div>
                    <hr>
                    <br>
                    <div class="table-responsive">
                        <div id="dayGrid"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondry" data-bs-dismiss="modal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-primary" onclick="add_product()" data-bs-dismiss="modal">
                        DONE
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var orderViewUrlTemplate = '{{ route('order.view', ':orderId') }}';
        let mainTable;
        $(document).ready(function() {
            mainTable = $('#mainTable').DataTable();
            order_change();

            details = @json($delivery_instruction_details);
            details.forEach(element => {
                let products = JSON.parse(sessionStorage.getItem('products')) || {};

                // Add or update the product in the products object
                products[element.product_id] = JSON.parse(element.calendar);

                // Save the updated products object back to session storage
                sessionStorage.setItem('products', JSON.stringify(products));
            });
        });
        function formatDate(dateString) {
            var date = new Date(dateString);
            if (isNaN(date.getTime())) {
                console.error("Invalid date format: " + dateString);
                return "";
            }
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2); // Months are zero-based
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

        var orders = {!! json_encode($orders) !!};

        function order_change() {
    var orderId = $("#order_id").val(); // Get the selected order ID.
    var order = orders.find(p => p.id == orderId); // Find the order details from the `orders` array.

    if (order) {
        // Extract and format the order's date
        var orderDate = moment(order.date, 'DD-MM-YYYY').format('YYYY-MM-DD');
        // $('#date').val(orderDate); // Set the formatted date in the input field.
        
        // Additional logic (setting customer name, modal titles, etc.)
        $('#customer_name').val(order.customers.name);
        $('.modal-title').text(order.order_no);

        // Format and display the order month
        const orderMonth = order.order_month;
        let [year1, month1] = orderMonth.split('-');
        let formattedOrderMonth = `${month1}-${year1}`;
        $('#order_month').val(formattedOrderMonth);

        const date = new Date(orderMonth + "-01");
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        const year = date.getFullYear();
        const month = monthNames[date.getMonth()];
        $('.modal-title-2').text(`${month} ${year}`);

        appendOrderDetails(order.order_detail);

        // Set the "View Order" URL
        var orderViewUrl = orderViewUrlTemplate.replace(':orderId', orderId);
        $('#so_view').attr('href', orderViewUrl);
    } else {
        // Reset fields if no order is selected
        $('#customer_name').val('');
        $('#order_month').val('');
        // $('#date').val('');
        mainTable.clear().draw();
        $('#so_view').attr('href', '#');
    }

    sessionStorage.clear('products'); // Clear session storage.
}


        function appendOrderDetails(orderDetails) {
            mainTable.clear(); // Clear existing details
            orderDetails.forEach(detail => {
                mainTable.row.add([
                    mainTable.rows().count() + 1,
                    detail.products.part_no,
                    detail.products.part_name,
                    detail.products.type_of_products.type ?? '',
                    detail.products.model,
                    detail.products.variance,
                    `<button type="button" class="btn btn-info calendar" onclick="openCalendar(this, ${detail.products.id})"><i class="bi bi-calendar2 me-2"></i>Calendar</button>`
                ]).draw(false); // Append new details
            });
        }

        function openCalendar(row, product_id) {
            let order_month = $('#order_month').val();
            var [month, year] = order_month.split('-').map(Number);
            initializeCalendar(month - 1, year);
            let part_no = $(row).closest('tr').find('td:eq(1)').text();
            let part_name = $(row).closest('tr').find('td:eq(2)').text();
            let type_of_product = $(row).closest('tr').find('td:eq(3)').text();
            let model = $(row).closest('tr').find('td:eq(4)').text();
            let variance = $(row).closest('tr').find('td:eq(5)').text();
            $('#product_id').val(product_id);
            $('#part_no').val(part_no);
            $('#part_name').val(part_name);
            $('#type_of_product').val(type_of_product);
            $('#model').val(model);
            $('#variance').val(variance);
            render_product(product_id);
            $('#exampleModal').modal('show');
        }

        function initializeCalendar(month, year) {
            var calendarEl = document.getElementById("dayGrid");
            calendarEl.innerHTML = ''; // Clear previous calendar content

            var daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

            // Create day labels
            daysOfWeek.forEach(function(day) {
                const headerCell = document.createElement("div");
                headerCell.classList.add("header");
                headerCell.textContent = day;
                calendarEl.appendChild(headerCell);
            });

            // Get the first day of the month
            var firstDay = new Date(year, month, 1).getDay();
            // Get the number of days in the month
            var daysInMonth = new Date(year, month + 1, 0).getDate();

            // Create empty cells for days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement("div");
                emptyCell.classList.add("day");
                calendarEl.appendChild(emptyCell);
            }

            // Create cells for each day of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement("div");
                dayCell.classList.add("day");
                dayCell.innerHTML = `<span>${day}</span><input type="number" min="0" class="form-control"/>`;
                calendarEl.appendChild(dayCell);
            }
        }

        function add_product() {
            let product_id = $('#product_id').val();
            let dayValues = [];
            $('#dayGrid').find('input').each(function() {
                let day = $(this).parent().find('span').text();
                let value = $(this).val();
                dayValues.push({
                    day: day,
                    value: value
                });
            });

            // Store products with their corresponding day values in an object
            let products = JSON.parse(sessionStorage.getItem('products')) || {};

            // Add or update the product in the products object
            products[product_id] = dayValues;

            // Save the updated products object back to session storage
            sessionStorage.setItem('products', JSON.stringify(products));
        }

        function render_product(product_id) {
            // Retrieve the products object from session storage
            let products = JSON.parse(sessionStorage.getItem('products')) || {};

            // Check if the product_id exists in the products object
            if (products[product_id]) {
                let dayValues = products[product_id];

                // Loop through each day value and set the corresponding input field
                $('#dayGrid').find('input').each(function() {
                    let day = $(this).parent().find('span').text();

                    // Find the corresponding day value from the dayValues array
                    let dayValue = dayValues.find(dv => dv.day === day);

                    // If a dayValue is found, set the input field's value
                    if (dayValue) {
                        $(this).val(dayValue.value);
                    }
                });
            } else {
                console.log('Product ID not found in session storage');
            }
        }

        $('.submit').click(function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
            let products = sessionStorage.getItem('products') || {};
            $('#products').val(products);
            console.log(products)
            $(this).closest('form').submit();
        });
    </script>
@endsection
