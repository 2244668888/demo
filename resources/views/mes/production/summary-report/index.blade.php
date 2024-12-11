@extends('layouts.app')
@section('title')
    SUMMARY REPORT
@endsection
@section('content')
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
                <div class="card-body pb-3">
                    <div class="m-0 d-flex justify-content-center">
                        <label class="form-label me-2" for="machine_product">Machine</label>
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="machine_product" name="machine_product">
                            </div>
                        </div>
                        <label class="form-label ms-2" for="machine_product">Product</label>
                    </div>
                    <br>
                    <div class="m-0 d-flex justify-content-center">
                        <i class="bi bi-arrow-up"></i>
                        <label class="form-label me-2" for="asc_desc">Ascending</label>
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="asc_desc" name="asc_desc">
                            </div>
                        </div>
                        <label class="form-label ms-2" for="asc_desc">Descending</label>
                        <i class="bi bi-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0 machine">
                        <label class="form-label" for="machine">Machine</label>
                        <select id="machine" class="form-select" multiple>
                            @foreach ($machines as $machine)
                                <option value="{{ $machine->id }}" data-id="{{ $machine->code }}">
                                    {{ $machine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="m-0 product d-none">
                        <label class="form-label" for="product">Product</label>
                        <select id="product" class="form-select" multiple>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-id="{{ $product->part_name }}">
                                    {{ $product->part_no }}</option>
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
                <i class="bi bi-search"></i> <span id="generate_text">SEARCH</span>
            </button>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">PRODUCTION TIME (HRS)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="production_time_hr">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">PRODUCTION TIME (%)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="production_time_percentage">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">DOWN TIME (HRS)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="down_time_hr">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">DOWN TIME (%)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="down_time_percentage">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">SETUP TIME (HRS)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="setup_time_hr">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">SETUP TIME (%)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="setup_time_percentage">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">SETUP FREQUENCY</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="setup_frequency">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">REJECTED (PCS)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="rejected_pcs">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">REJECTED (%)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="rejected_percentage">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">REJECTED (KG)</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="rejected_kg">
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-0 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">PLANNED VS ACTUAL QTY</h5>
            </div>
            <hr>
            <div class="card-body">
                <div id="planned_actual">
                </div>
            </div>
        </div>
    </div>
 
    <!-- Date Range JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/daterange/daterange.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".datepicker-range-iso-week-numbers").daterangepicker({
                drops: "up",
                opens: "left",
                showWeekNumbers: true,
                showISOWeekNumbers: true,
                startDate: moment().subtract(1, 'year').startOf('day'),
                endDate: moment().endOf('day'),
                locale: {
                    format: "YYYY-MM-DD",
                },
            });
            $('#generate').trigger('click');
        });

        $('#machine_product').on('change', function() {
            if ($(this).is(':checked')) {
                $('.product').removeClass('d-none');
                $('.product').find('.select2-container').addClass('w-100');
                $('.machine').addClass('d-none');
            } else {
                $('.machine').removeClass('d-none');
                $('.product').addClass('d-none');
            }
        });

        function getDateRange() {
            var datepicker = $(".datepicker-range-iso-week-numbers").data('daterangepicker');
            return {
                start_date: datepicker.startDate.format('YYYY-MM-DD'),
                end_date: datepicker.endDate.format('YYYY-MM-DD')
            };
        }

        $('#generate').on('click', function() {
            $('#generate_text').text('SEARCHING...');
            $('#generate').attr('disabled', 'disabled');
            let machine_product = ($('#machine_product').is(':checked')) ? 1 : 0;
            let asc_desc = ($('#asc_desc').is(':checked')) ? 'ASC' : 'DESC';
            let machines = $('#machine').val();
            let products = $('#product').val();
            let dates = getDateRange(); // Get start and end dates from the picker
            let start_date = dates.start_date;
            let end_date = dates.end_date;

            $.ajax({
                url: '{{ route('summary_report.generate') }}',
                type: 'GET',
                data: {
                    machine_product: machine_product,
                    start_date: start_date,
                    asc_desc: asc_desc,
                    machines: machines,
                    products: products,
                    end_date: end_date
                },
                success: function(response) {
                    console.log(response);
                    // Clear previous charts
                    ['#production_time_hr', '#production_time_percentage', '#down_time_hr',
                        '#down_time_percentage', '#setup_time_hr', '#setup_time_percentage'
                    ].forEach(id => {
                        $(id).empty();
                    });

                    // Arrays to hold data for each category
                    let productionTimeHrData = [];
                    let productionTimePercentageData = [];
                    let downTimeHrData = [];
                    let downTimePercentageData = [];
                    let setupTimeHrData = [];
                    let setupTimePercentageData = [];
                    let setupFrequencyData = [];
                    let RejectedPcsData = [];
                    let RejectedPercentageData = [];
                    let RejectedKgData = [];
                    let PlannedData = [];
                    let ActualData = [];
                    let categories = [];

                    // Iterate over the response object and collect data
                    Object.keys(response).forEach(machineCode => {
                        let data = response[machineCode];
                        categories.push(machineCode);
                        productionTimeHrData.push(data.production_time_hr);
                        productionTimePercentageData.push(data.production_time_percentage);
                        downTimeHrData.push(data.down_time_hr);
                        downTimePercentageData.push(data.down_time_percentage);
                        setupTimeHrData.push(data.setup_time_hr);
                        setupTimePercentageData.push(data.setup_time_percentage);
                        setupFrequencyData.push(data.setup_frequency);
                        RejectedPcsData.push(data.rejected_pcs);
                        RejectedPercentageData.push(data.rejected_percentage);
                        RejectedKgData.push(data.rejected_kg);
                        PlannedData.push(data.planned_qty);
                        ActualData.push(data.actual_qty);
                    });

                    // Render Production Time (HRS) chart
                    let productionTimeHrOptions = {
                        series: [{
                            name: 'Production Time (HRS)',
                            data: productionTimeHrData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let productionTimeHrChart = new ApexCharts(document.querySelector(
                        "#production_time_hr"), productionTimeHrOptions);
                    productionTimeHrChart.render();

                    // Render Production Time (%) chart
                    let productionTimePercentageOptions = {
                        series: [{
                            name: 'Production Time (%)',
                            data: productionTimePercentageData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let productionTimePercentageChart = new ApexCharts(document.querySelector(
                        "#production_time_percentage"), productionTimePercentageOptions);
                    productionTimePercentageChart.render();

                    // Render Down Time (HRS) chart
                    let downTimeHrOptions = {
                        series: [{
                            name: 'Down Time (HRS)',
                            data: downTimeHrData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let downTimeHrChart = new ApexCharts(document.querySelector("#down_time_hr"),
                        downTimeHrOptions);
                    downTimeHrChart.render();

                    // Render Down Time (%) chart
                    let downTimePercentageOptions = {
                        series: [{
                            name: 'Down Time (%)',
                            data: downTimePercentageData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let downTimePercentageChart = new ApexCharts(document.querySelector(
                        "#down_time_percentage"), downTimePercentageOptions);
                    downTimePercentageChart.render();

                    // Render Setup Time (HRS) chart
                    let setupTimeHrOptions = {
                        series: [{
                            name: 'Setup Time (HRS)',
                            data: setupTimeHrData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let setupTimeHrChart = new ApexCharts(document.querySelector("#setup_time_hr"),
                        setupTimeHrOptions);
                    setupTimeHrChart.render();

                    // Render Setup Time (%) chart
                    let setupTimePercentageOptions = {
                        series: [{
                            name: 'Setup Time (%)',
                            data: setupTimePercentageData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let setupTimePercentageChart = new ApexCharts(document.querySelector(
                        "#setup_time_percentage"), setupTimePercentageOptions);
                    setupTimePercentageChart.render();

                    // Render Setup Frequency
                    let setupFrequencyOptions = {
                        series: [{
                            name: 'Setup Frequency',
                            data: setupFrequencyData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let setupFrequencyChart = new ApexCharts(document.querySelector(
                        "#setup_frequency"), setupFrequencyOptions);
                    setupFrequencyChart.render();

                    // Render Rejected (pcs) chart
                    let rejectedPcsOptions = {
                        series: [{
                            name: 'Rejected (pcs)',
                            data: RejectedPcsData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let rejectedPcsChart = new ApexCharts(document.querySelector(
                        "#rejected_pcs"), rejectedPcsOptions);
                    rejectedPcsChart.render();

                    // Render Rejected (%) chart
                    let rejectedPercentageOptions = {
                        series: [{
                            name: 'Rejected (%)',
                            data: RejectedPercentageData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let rejectedPercentageChart = new ApexCharts(document.querySelector(
                        "#rejected_percentage"), rejectedPercentageOptions);
                    rejectedPercentageChart.render();

                    // Render Rejected (kg) chart
                    let rejectedKgOptions = {
                        series: [{
                            name: 'Rejected (kg)',
                            data: RejectedKgData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let rejectedKgChart = new ApexCharts(document.querySelector(
                        "#rejected_kg"), rejectedKgOptions);
                    rejectedKgChart.render();

                    // Render Planned Vs Actual chart
                    let plannedActualOptions = {
                        series: [{
                            name: 'Planned Qty',
                            data: PlannedData
                        }, {
                            name: 'Actual Qty',
                            data: ActualData
                        }],
                        chart: {
                            type: 'bar',
                            height: 400,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories,
                        }
                    };
                    let plannedActualChart = new ApexCharts(document.querySelector(
                        "#planned_actual"), plannedActualOptions);
                    plannedActualChart.render();

                    $('#generate_text').text('SEARCH');
                    $('#generate').removeAttr('disabled');
                },
            });
        });
    </script>
@endsection
