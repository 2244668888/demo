@extends('layouts.app')

@section('content')
    <!-- Row start -->
    <div class="row gx-3 mt-3">
        <div class="col-md-4 col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3">Weekly Sales</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="me-2">2,860</h2><span class="badge bg-success">02.5%</span>
                    </div>
                    <div class="graph-body-sm">
                        <div id="sparklineLine1"></div>
                    </div>
                    <small class="lh-sm">Total conversion value divided by the number of eligible
                        clicks.</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3">Weekly Income</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="me-2">2,971</h2><span class="badge bg-danger">0.55%</span>
                    </div>
                    <div class="graph-body-sm">
                        <div id="sparklineLine2"></div>
                    </div>
                    <small class="lh-sm">A good conversion rate is between 2 percent and 5 percent
                        views.</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3">Weekly Revenue</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="me-2">1,896</h2><span class="badge bg-info">1.8%</span>
                    </div>
                    <div class="graph-body-sm">
                        <div id="sparklineLine3"></div>
                    </div>
                    <small class="lh-sm">The number of conversions divided by the total number of
                        visits.</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Row end -->
    <!-- Row start -->
    <div class="row gx-3">
        <div class="col-xxl-12">
            <div class="card mb-3">
                <div class="card-body">

                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs" id="customTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-one" data-bs-toggle="tab" href="#one"
                                    role="tab" aria-controls="one" aria-selected="true"><i
                                        class="bi bi-pie-chart me-2"></i>Overview</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-two" data-bs-toggle="tab" href="#two" role="tab"
                                    aria-controls="two" aria-selected="false" tabindex="-1"><i
                                        class="bi bi-funnel me-2"></i>Sales</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-three" data-bs-toggle="tab" href="#three" role="tab"
                                    aria-controls="three" aria-selected="false" tabindex="-1"><i
                                        class="bi bi-bar-chart me-2"></i>Profit</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-four" data-bs-toggle="tab" href="#four" role="tab"
                                    aria-controls="four" aria-selected="false" tabindex="-1"><i
                                        class="bi bi-people me-2"></i>Users</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane fade show active" id="one" role="tabpanel"
                                aria-labelledby="tab-one">
                                <!-- Row start -->
                                <div class="row gx-3">
                                    <div class="col-lg-5 col-sm-12 col-12">
                                        <h6 class="text-center mb-3">Visitors</h6>
                                        <div id="visitors"></div>
                                        <div class="my-3 text-center">
                                            <div class="badge bg-danger bg-opacity-10 text-danger">
                                                10% higher than last month
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-12 col-12">
                                        <div class="border rounded-4 px-2 py-4 h-100 text-center x-grid">
                                            <h6 class="mt-3 mb-5">Monthly Average</h6>
                                            <div class="mb-5">
                                                <h2 class="text-primary">9600</h2>
                                                <h6>Visitors</h6>
                                            </div>
                                            <div class="mb-4">
                                                <h2 class="text-success">$450<sup>k</sup></h2>
                                                <h6>Sales</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-sm-12 col-12">
                                        <h6 class="text-center mb-3">Sales</h6>
                                        <div id="sales"></div>
                                        <div class="my-3 text-center">
                                            <div class="badge bg-primary bg-opacity-10 text-primary">
                                                12% higher than last month
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->
                            </div>
                            <div class="tab-pane fade" id="two" role="tabpanel" aria-labelledby="tab-two">

                                <!-- Row start -->
                                <div class="row gx-3">
                                    <div class="col-lg-5 col-sm-12 col-12">
                                        <h6 class="text-center mb-3">Q1 Sales</h6>
                                        <div id="qone"></div>
                                        <div class="my-3 text-center">
                                            <div class="badge bg-primary bg-opacity-10 text-primary">
                                                30% higher than last month
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-12 col-12">
                                        <div class="border rounded-4 px-2 py-4 h-100 text-center x-grid">
                                            <h6 class="mt-3 mb-5">Monthly Average</h6>
                                            <div class="mb-5">
                                                <h2 class="text-primary">$8900</h2>
                                                <h6>Income</h6>
                                            </div>
                                            <div class="mb-4">
                                                <h2 class="text-danger">$8600</h2>
                                                <h6>Income</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-sm-12 col-12">
                                        <h6 class="text-center mb-3">Q2 Sales</h6>
                                        <div id="qtwo"></div>
                                        <div class="my-3 text-center">
                                            <div class="badge bg-danger bg-opacity-10 text-danger">
                                                20% higher than last quarter
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->

                            </div>
                            <div class="tab-pane fade" id="three" role="tabpanel" aria-labelledby="tab-three">

                                <!-- Row start -->
                                <div class="row gx-3">
                                    <div class="col-sm-12 col-12">
                                        <h6 class="text-center mb-3">Yearly Profits</h6>
                                        <div id="profit"></div>
                                        <div class="my-3 text-center">
                                            <div class="badge bg-primary bg-opacity-10 text-primary">
                                                30% higher than last month
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- Row ends -->

                            </div>
                            <div class="tab-pane fade" id="four" role="tabpanel" aria-labelledby="tab-four">

                                <!-- Row start -->
                                <div class="row gx-3">
                                    <div class="col-sm-12 col-12">
                                        <h6 class="text-center mb-3">Users Yearly</h6>
                                        <div id="users"></div>
                                        <div class="my-3 text-center">
                                            <div class="badge bg-success bg-opacity-10 text-success">
                                                80% higher than last month
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- Row ends -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->

    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Overall Sales</h5>
                </div>
                <div class="card-body">

                    <!-- Stats starts -->
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <div class="position-relative">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('assets/images/flags/1x1/us.svg') }}" class="img-4x rounded-circle"
                                    alt="United States">
                                <div class="ms-3">
                                    <h2 class="mb-1">200M</h2>
                                    <h6 class="mb-2">United States</h6>
                                    <span class="badge bg-dark me-1">+33% high than last week</span>
                                </div>
                            </div>
                        </div>
                        <div class="position-relative">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('assets/images/flags/1x1/br.svg') }}" class="img-4x rounded-circle"
                                    alt="Brazil">
                                <div class="ms-3">
                                    <h2 class="mb-1">300M</h2>
                                    <h6 class="mb-2">Brazil</h6>
                                    <span class="badge bg-dark me-1">+28% high than last week</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ asset('assets/images/flags/1x1/in.svg') }}" class="img-4x rounded-circle"
                                alt="India">
                            <div class="ms-3">
                                <h2 class="mb-1">800M</h2>
                                <h6 class="mb-2">India</h6>
                                <span class="badge bg-danger me-1">+48% high than last week</span>
                            </div>
                        </div>
                    </div>
                    <!-- Stats ends -->

                    <!-- Map starts -->
                    <div class="map-body-xxl">
                        <div class="card-loader">
                            <div class="spinner-border text-warning"></div>
                        </div>
                        <div class="population">
                            <div class="map">
                                <span>Alternative content for the map</span>
                            </div>
                            <div class="areaLegend">
                                <span>Alternative content for the legend</span>
                            </div>
                            <div class="plotLegend">
                                <span>Alternative content for the legend</span>
                            </div>
                        </div>
                    </div>
                    <!-- Map ends -->

                    <div class="grid gap-3 mt-4">
                        <div class="g-col-lg-3 g-col-12">
                            <div class="p-3 flex-column border border-light rounded-2">
                                <p class="mb-1">Total Sales</p>
                                <h4 class="fw-bold mb-2">2,600</h4>
                                <div class="progress small">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 60%"
                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="g-col-lg-3 g-col-12">
                            <div class="p-3 flex-column border border-light rounded-2">
                                <p class="mb-1">Active Users</p>
                                <h4 class="fw-bold mb-2">3,800</h4>
                                <div class="progress small">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 70%"
                                        aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="g-col-lg-3 g-col-12">
                            <div class="p-3 flex-column border border-light rounded-2">
                                <p class="mb-1">Daily Users</p>
                                <h4 class="fw-bold mb-2">6,900</h4>
                                <div class="progress small">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 80%"
                                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="g-col-lg-3 g-col-12">
                            <div class="p-3 flex-column border border-light rounded-2">
                                <p class="mb-1">Income</p>
                                <h4 class="fw-bold mb-2">$8,600</h4>
                                <div class="progress small">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 90%"
                                        aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->

    <!-- Row start -->
    <div class="row gx-3">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Row start -->
                    <div class="row g-4">
                        <div class="px-0 border-end col-xl-3 col-sm-6">
                            <div class="text-center">
                                <p class="m-0 small">Gross Profit</p>
                                <h3 class="my-2">75%</h3>
                                <p class="m-0 small">
                                    <span class="text-danger me-1">
                                        <i class="bi bi-arrow-down-left-square"></i>
                                        1.99%</span>
                                    for Last month
                                </p>
                            </div>
                        </div>
                        <div class="px-0 border-end col-xl-3 col-sm-6">
                            <div class="text-center">
                                <p class="m-0 small">Opex Ratio</p>
                                <h3 class="my-2">62%</h3>
                                <p class="m-0 small">
                                    <span class="text-success me-1">
                                        <i class="bi bi-arrow-up-right-square"></i>
                                        1.69%</span>
                                    for Last month
                                </p>
                            </div>
                        </div>
                        <div class="px-0 border-end col-xl-3 col-sm-6">
                            <div class="text-center">
                                <p class="m-0 small">
                                    Operating Profit
                                </p>
                                <h3 class="my-2">48%</h3>
                                <p class="m-0 small">
                                    <span class="text-success me-1">
                                        <i class="bi bi-arrow-up-right-square"></i>
                                        2.9%</span>
                                    for Last month
                                </p>
                            </div>
                        </div>
                        <div class="px-0 col-xl-3 col-sm-6">
                            <div class="text-center">
                                <p class="m-0 small">Net Profit</p>
                                <h3 class="my-2">32%</h3>
                                <p class="m-0 small">
                                    <span class="text-success me-1">
                                        <i class="bi bi-arrow-up-right-square"></i>
                                        18.5%</span>
                                    for Last month
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Row end -->
                </div>
            </div>
        </div>
    </div>
    <!-- Row end -->

    <!-- Row start -->
    <div class="row gx-3">
        <div class="col-xl-8 col-lg-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Team Activity</h5>
                </div>
                <div class="card-body">
                    <ul class="m-0 p-0">
                        <li class="team-activity d-flex flex-wrap">
                            <div class="activity-time py-2 me-3">
                                <p class="m-0">10:30AM</p>
                                <span class="badge bg-primary">New</span>
                            </div>
                            <div class="d-flex flex-column py-2">
                                <h6>Earth - Admin Dashboard</h6>
                                <p class="m-0">by Elnathan Lois</p>
                            </div>
                            <div class="ms-auto mt-4">
                                <p class="mb-1">(225 of 700gb)</p>
                                <div class="progress small">
                                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </li>
                        <li class="team-activity d-flex flex-wrap">
                            <div class="activity-time py-2 me-3">
                                <p class="m-0">11:30AM</p>
                                <span class="badge bg-primary">Task</span>
                            </div>
                            <div class="d-flex flex-column py-2">
                                <h6>Bootstrap Gallery Admin Templates</h6>
                                <p class="m-0">by Patrobus Nicole</p>
                            </div>
                            <div class="ms-auto mt-4">
                                <p class="mb-1">90% completed</p>
                                <div class="progress small mb-1">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 80%"
                                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </li>
                        <li class="team-activity d-flex flex-wrap">
                            <div class="activity-time py-2 me-3">
                                <p class="m-0">12:50PM</p>
                                <span class="badge bg-danger">Closed</span>
                            </div>
                            <div class="d-flex flex-column py-2">
                                <h6>Bootstrap Admin Themes</h6>
                                <p class="m-0">by Abilene Omega</p>
                            </div>
                            <div class="ms-auto mt-3">
                                <div id="sparkline1"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Tasks</h5>
                </div>
                <div class="card-body">
                    <div class="auto-align-graph">
                        <div id="tasks"></div>
                    </div>
                    <div class="grid text-center">
                        <div class="g-col-4">
                            <i class="bi bi-triangle text-danger"></i>
                            <h3 class="m-0 mt-1">6</h3>
                            <p class="m-0">New</p>
                        </div>
                        <div class="g-col-4">
                            <i class="bi bi-triangle text-primary"></i>
                            <h3 class="m-0 mt-1 fw-bolder">9</h3>
                            <p class="m-0">Pending</p>
                        </div>
                        <div class="g-col-4">
                            <i class="bi bi-triangle text-success"></i>
                            <h3 class="m-0 mt-1">12</h3>
                            <p class="m-0">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row end -->

    <!-- Row start -->
    <div class="row gx-3">
        <div class="col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Stats</h5>
                </div>
                <div class="card-body">
                    <div class="scroll350">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-record-fill text-primary me-2"></i>
                            <div class="d-flex p-2 bg-primary rounded-circle me-3">
                                <i class="bi bi-bag fs-4 text-white lh-1"></i>
                            </div>
                            <p class="m-0 me-2">
                                You have spent about <b>65%</b> of your annual budget.
                            </p>
                            <div class="ms-auto badge bg-primary bg-opacity-10 text-primary small">
                                24/12/2023
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-record-fill text-primary me-2"></i>
                            <div class="d-flex p-2 bg-primary rounded-circle me-3">
                                <i class="bi bi-check-circle fs-4 text-white lh-1"></i>
                            </div>
                            <p class="m-0 me-2">
                                New admin dashboard purchased, and payment paid
                                through online.
                            </p>
                            <div class="ms-auto badge bg-primary bg-opacity-10 text-primary small">
                                23/12/2023
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-record-fill text-primary me-2"></i>
                            <div class="d-flex p-2 bg-primary rounded-circle me-3">
                                <i class="bi bi-clipboard-check fs-4 text-white lh-1"></i>
                            </div>
                            <p class="m-0 me-2">
                                A new ticket opened and assigned to <b>Zion</b>.
                            </p>
                            <div class="ms-auto badge bg-primary bg-opacity-10 text-primary small">
                                22/12/2023
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-record-fill me-2"></i>
                            <div class="d-flex p-2 bg-primary rounded-circle me-3">
                                <i class="bi bi-slash-circle fs-4 text-white lh-1"></i>
                            </div>
                            <p class="m-0 me-2">
                                Thanks <b>Sarah</b>, I want you to share Jim's
                                profile.
                            </p>
                            <div class="ms-auto badge bg-primary bg-opacity-10 text-primary small">
                                21/12/2023
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-record-fill me-2"></i>
                            <div class="d-flex p-2 bg-primary rounded-circle me-3">
                                <i class="bi bi-envelope-open fs-4 text-white lh-1"></i>
                            </div>
                            <p class="m-0 me-2">
                                <b>Ora Mahoney,</b> has completed the design of the
                                CRM admin application.
                            </p>
                            <div class="ms-auto badge bg-danger bg-opacity-10 text-danger small">
                                20/12/2023
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-record-fill me-2"></i>
                            <div class="d-flex p-2 bg-primary rounded-circle me-3">
                                <i class="bi bi-envelope-open fs-4 text-white lh-1"></i>
                            </div>
                            <p class="m-0 me-2">
                                <b>Daren Boyd,</b> received the order.
                            </p>
                            <div class="ms-auto badge bg-danger bg-opacity-10 text-danger small">
                                18/12/2023
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Orders</h5>
                </div>
                <div class="card-body">
                    <div class="row gx-3">
                        <div class="col-sm-6 col-12">
                            <div class="d-flex flex-column align-items-start">
                                <div class="icon-box lg border rounded-circle p-3 my-3">
                                    <span class="bi bi-bar-chart-line fs-2 text-primary lh-1"></span>
                                </div>
                                <h3 class="mt-4">$780<sup>k</sup></h3>
                                <p class="mb-4">
                                    Highest sales growth in last two years.
                                </p>
                                <div class="d-flex p-3 flex-column border border-primary rounded-2 mb-4">
                                    <h6>Target - <span>75/100</span></h6>
                                    <div class="progress small">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <small class="badge bg-primary bg-opacity-10 text-primary rounded-1">47% High
                                    growth</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div id="orders"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
@endsection
