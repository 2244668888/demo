@extends('layouts.app')
@section('title')
    OEE DASHBOARD
@endsection
@section('content')
    <style>
        .card1 {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .card-header1 {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .bar-container {
            display: flex;
            justify-content: space-between;
            font-size: 17px;
            text-align: left;
            margin-bottom: 8px;
            width: 100%;
        }

        .bar-label {
            display: inline-block;
            width: 100px;
        }

        .bar {
            width: 150px;
            height: 10px;
            background-color: #e6e6e6;
            border-radius: 5px;
            display: inline-block;
            vertical-align: middle;
            margin-top: 9px;
        }

        .bar-fill {
            height: 100%;
            border-radius: 5px;
            transition: width 1s;
        }

        .details-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #3f51b5;
            cursor: pointer;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 16px;
            width: 100%;
        }

        .border-red {
            border: 2px solid red;
        }

        .blink {
            animation: blinker 1s linear infinite;
        }

        .blink-border {
            animation: border-blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        @keyframes border-blinker {
            50% {
                border-color: transparent;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterange/daterange.css') }}" />
    <div class="row gx-5">
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card mb-3">
                <div class="card-body pb-3">
                    <div class="m-0">
                        <div class="form-group">
                            <label class="form-label" for="abc12">Date Range</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar4"></i>
                                </span>
                                <input type="text" id="abc12" class="form-control datepicker-range-iso-week-numbers">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="machine">Machine Category</label>
                        <select id="machine_category" class="form-select">
                            <option value="All">All</option>
                            <option value="Small">Small</option>
                            <option value="Big">Big</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="machine">Machine Name</label>
                        <select id="machine" class="form-select" multiple>
                            @foreach ($machines as $machine)
                                <option value="{{ $machine->id }}" data-id="{{ $machine->code }}">
                                    {{ $machine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3 mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-info me-2" id="generate">
                <i class="bi bi-bar-chart"></i> <span id="generate_text">GENERATE OEE</span>
            </button>
        </div>
    </div>
    <div class="row gx-3" id="charts">
    </div>
    {{-- OEE DETAILS MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalTitle" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalTitle">

                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" id="popUp">
                        <table class="table table-bordered m-0 w-100" id="modalTable">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Part No</th>
                                    <th>Cycle Time(s)</th>
                                    <th>No of Cavity</th>
                                    <th>Ideal Run Rate</th>
                                    <th>Total Produced</th>
                                    <th>Rejected Qty</th>
                                    <th>Down Time(hr)</th>
                                    <th>Production Time(hr)</th>
                                    <th>Setup Time(hr)</th>
                                    <th>Performance(%)</th>
                                    <th>Quality(%)</th>
                                    <th>Availability(%)</th>
                                    <th>OEE(%)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="exportToExcel()">Export</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Datatables -->
    <script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <!-- Date Range JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/daterange/daterange.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script>
        let modalTable = $('#modalTable').DataTable();

        $(document).ready(function() {
            $(".datepicker-range-iso-week-numbers").daterangepicker({
                drops: "up",
                opens: "left",
                showWeekNumbers: true,
                showISOWeekNumbers: true,
                startDate: moment().startOf("hour"),
                endDate: moment().startOf("hour").add(32, "hour"),
                locale: {
                    format: "YYYY-MM-DD",
                },
            });
            $('#generate').trigger('click');
        });

        function getDateRange() {
            var datepicker = $(".datepicker-range-iso-week-numbers").data('daterangepicker');
            return {
                start_date: datepicker.startDate.format('YYYY-MM-DD'),
                end_date: datepicker.endDate.format('YYYY-MM-DD')
            };
        }

        $('#generate').on('click', function() {
            $('#generate_text').text('GENERATING...');
            $('#generate').attr('disabled', 'disabled');
            let machines = $('#machine').val();
            let dates = getDateRange(); // Get start and end dates from the picker
            let start_date = dates.start_date;
            let end_date = dates.end_date;
            $.ajax({
                url: '{{ route('oee.generate') }}',
                type: 'GET',
                data: {
                    machines: machines,
                    start_date: start_date,
                    end_date: end_date
                },
                success: function(response) {
                    responseData = response;
                    $('#charts').html('');

                    Object.keys(response).forEach(machineName => {
                        const machineData = response[machineName];

                        const machineStatus = machineData.status;
                        delete machineData.status;

                        const chartDiv = document.createElement('div');
                        chartDiv.classList.add('col-md-4', 'mb-4');
                        const chartId = `${machineName.replace(/\s+/g, '')}Chart`;
                        $color = '';
                        if (machineData.production == null) {
                            $color = 'grey';
                        } else if (machineData.production != null) {
                            if (machineData.production.status == 'Not-initiated') {
                                $color = 'grey';
                            }
                        } else if (machineData.preperation) {
                            $color = 'yellow';
                        } else if (machineData.preperation == null && machineData
                            .machine_production == null) {
                            $color = 'red';
                        } else if (machineData.machine_production != null) {
                            $color = 'green';
                        }
                        chartDiv.innerHTML = `
                            <div class="card1 ${machineData.call_for_assistance != null ? 'border-red blink-border' : ''}">
                                <div class="card-header1 text-center p-0 pt-2" style="background: ${$color};">${machineName}</div>
                                <div class="machine-status">Machine Status: <span class="${machineStatus == 'ON' ? 'text-success' : 'text-danger'}">${machineStatus}</span></div>
                                <canvas id="${chartId}" width="200" height="200"></canvas>
                                <div class="bar-container availability">
                                    <span class="bar-label">Availability</span>
                                    <div class="bar">
                                        <div class="bar-fill" id="${chartId}_availabilityBar"></div>
                                    </div>
                                    <span id="${chartId}_availabilityValue"></span>
                                </div>
                                <div class="bar-container performance">
                                    <span class="bar-label">Performance</span>
                                    <div class="bar">
                                        <div class="bar-fill" id="${chartId}_performanceBar"></div>
                                    </div>
                                    <span id="${chartId}_performanceValue"></span>
                                </div>
                                <div class="bar-container quality">
                                    <span class="bar-label">Quality</span>
                                    <div class="bar">
                                        <div class="bar-fill" id="${chartId}_qualityBar"></div>
                                    </div>
                                    <span id="${chartId}_qualityValue"></span>
                                </div>
                                <a class="details-button" onclick="generateDetail('${machineName}', '${start_date}', '${end_date}')">View OEE Detail</a>
                            </div>`;
                        document.getElementById('charts').appendChild(chartDiv);

                        // Generate a random color
                        function getRandomColor() {
                            const letters = '0123456789ABCDEF';
                            let color = '#';
                            for (let i = 0; i < 6; i++) {
                                color += letters[Math.floor(Math.random() * 16)];
                            }
                            return color;
                        }

                        // Assign random colors to each section
                        const oeeColor = getRandomColor();
                        const availabilityColor = getRandomColor();
                        const performanceColor = getRandomColor();
                        const qualityColor = getRandomColor();

                        const ctx = document.getElementById(chartId).getContext(
                            '2d');
                        const oeeChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    data: [machineData.oee],
                                    backgroundColor: [oeeColor,
                                        '#e6e6e6'
                                    ],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                rotation: -90,
                                circumference: 180,
                                cutout: '70%',
                                plugins: {
                                    tooltip: {
                                        enabled: false
                                    },
                                    legend: {
                                        display: false
                                    },
                                    centerText: {
                                        text: `${machineData.oee}%`,
                                        color: '#000',
                                        fontSize: 24
                                    }
                                }
                            }
                        });

                        Chart.register({
                            id: 'centerText',
                            afterDatasetsDraw(chart) {
                                const {
                                    ctx,
                                    options,
                                    data
                                } = chart;
                                ctx.save();
                                const fontSize = options.plugins.centerText
                                    .fontSize || 24;
                                ctx.font = `bold ${fontSize}px Arial`;
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillStyle = options.plugins.centerText
                                    .color ||
                                    '#000';
                                ctx.fillText(options.plugins.centerText
                                    .text, chart
                                    .width / 2, chart.height / 1.35);
                                ctx.restore();
                            }
                        });

                        // Update the bars with random colors
                        document.getElementById(`${chartId}_availabilityBar`).style
                            .width =
                            `${machineData.availability}%`;
                        document.getElementById(`${chartId}_availabilityBar`).style
                            .backgroundColor = availabilityColor;
                        document.getElementById(`${chartId}_performanceBar`).style
                            .width =
                            `${machineData.performance}%`;
                        document.getElementById(`${chartId}_performanceBar`).style
                            .backgroundColor = performanceColor;
                        document.getElementById(`${chartId}_qualityBar`).style
                            .width =
                            `${machineData.quality}%`;
                        document.getElementById(`${chartId}_qualityBar`).style
                            .backgroundColor = qualityColor;

                        document.getElementById(`${chartId}_availabilityValue`)
                            .innerText =
                            `${machineData.availability}%`;
                        document.getElementById(`${chartId}_performanceValue`)
                            .innerText =
                            `${machineData.performance}%`;
                        document.getElementById(`${chartId}_qualityValue`)
                            .innerText =
                            `${machineData.quality}%`;
                    });
                    $('#generate_text').text('GENERATE OEE');
                    $('#generate').removeAttr('disabled');
                },
            });
        });

        function generateDetail(machineName, start_date, end_date) {
            $.ajax({
                url: '{{ route('oee.details') }}',
                method: "GET",
                data: {
                    machine: machineName,
                    start_date: start_date,
                    end_date: end_date
                },
                success: function(response) {
                    modalTable.clear(); // Clear existing data

                    response.forEach(function(detail) {
                        modalTable.row.add([
                            detail.order_no,
                            detail.part_no,
                            detail.cycle_time,
                            detail.no_cavity,
                            detail.ideal_run_rate,
                            detail.total_produced,
                            detail.rejected_qty,
                            detail.down_time,
                            detail.production_time,
                            detail.setup_time,
                            detail.performance,
                            detail.quality,
                            detail.availability,
                            detail.oee
                        ]);
                    });

                    modalTable.draw(); // Redraw table with new data
                    $('#exampleModalTitle').text(machineName);
                    $('#exampleModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function exportToExcel() {
            const table = document.getElementById("modalTable");
            const rows = table.querySelectorAll("tr");
            let csv = [];

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll("td, th");
                let row = [];
                for (let j = 0; j < cells.length; j++) {
                    row.push('"' + cells[j].innerText.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }

            const csvContent = "\uFEFF" + csv.join("\n");
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });

            // if we're using IE/Edge, then use different download call
            if (typeof navigator.msSaveOrOpenBlob === 'function') {
                navigator.msSaveOrOpenBlob(blob, "oee.csv");
            } else {
                // the next code will generate a temp <a /> tag that you can trigger a hidden click for it to start downloading
                const link = document.createElement('a');
                const csvUrl = URL.createObjectURL(blob);

                link.href = csvUrl;
                link.setAttribute('download', "oee.csv");

                // set the visibility hidden so that there is no effect on your web-layout
                link.style.visibility = 'hidden';

                // finally we will append the anchor tag and remove it after clicking it
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        $machinesMap = @json($machines);

        $('#machine_category').change(function() {
            $('#machine').html('');
            if ($(this).val() == 'All') {
                $.each($machinesMap, function(index, machine) {
                    $('#machine').append($('<option>', {
                        value: machine.id,
                        text: machine.name
                    }));
                });
            } else if ($(this).val() == 'Small') {
                $.each($machinesMap, function(index, machine) {
                    if (machine.category === 'Small') {
                        $('#machine').append($('<option>', {
                            value: machine.id,
                            text: machine.name
                        }));
                    }
                });
            } else if ($(this).val() == 'Big') {
                $.each($machinesMap, function(index, machine) {
                    if (machine.category == 'Big') {
                        $('#machine').append($('<option>', {
                            value: machine.id,
                            text: machine.name
                        }));
                    }
                });
            }
            $('#machine').trigger('change');
        });
    </script>
@endsection
