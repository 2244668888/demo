<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ZENIG AUTO</title>

    <!-- Meta -->
    <link rel="shortcut icon" href="{{ asset('assets/images/zenig.png') }}" />

 <!-- Add Toaster CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <!-- *************
   ************ CSS Files *************
  ************* -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/bootstrap/bootstrap-icons.min.css') }}" />

    <!-- *************
   ************ Vendor Css Files *************
  ************ -->

    <!-- Scrollbar CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/overlay-scroll/OverlayScrollbars.min.css') }}" />

    <!-- NO UI Slider CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/nouislider/css/nouislider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/jquery.dataTables.min.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/daterange/daterange.css') }}" /> --}}

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/modernizr.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/moment.min.js') }}"></script> --}}

    <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
    <!-- Date Range JS -->
    {{-- <script  src="{{ url('assets/vendor/daterange/daterange.js') }}"></script>
            <script  src="{{ url('assets/vendor/daterange/custom-daterange.js') }}"></script> --}}

    <style>
        .description {
            width: 300px;
            position: relative;
        }

        .short-desc {
            margin: 0;
        }

        .full-desc {
            display: none;
            margin: 0;
        }

        .read-more {
            display: block;
            color: rgb(99, 99, 243);
            cursor: pointer;
            text-decoration: underline;
        }

        .loader {
            position: fixed;
            left: 50%;
            top: 50%;
            width: 50px;
            height: 50px;
            margin: -25px 0 0 -25px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #3498db;
            width: 40px;
            height: 40px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            z-index: 9999;
            display: none;
            /* Initially hidden */
        }

        tfoot {
            display: table-row-group;
        }

        th,
        td {
            text-align: left !important;
        }

        .dt-layout-cell.dt-start {
            position: sticky;
            left: 0px;
        }

        .select2-results__options {
            background: white !important;
        }

        .select2-results__options>li {
            color: black !important;
        }

        .select2-container {
            box-sizing: border-box;
            max-width: 100% !important;
            width: -webkit-fill-available !important;
        }
        .form-select {
            max-width: fit-content;
            box-sizing: border-box;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>




    
    <div id="loader" class="loader"></div>

    <!-- Page wrapper start -->
    <div class="page-wrapper" id="page-wrapper">

        <script>
            document.getElementById('loader').style.display = 'block'; // Show loader while loading CSS
            document.getElementById('page-wrapper').style.opacity = 0;
            let theme_mode = localStorage.getItem('theme_mode');
            let linkElement = document.createElement('link');
            linkElement.rel = 'stylesheet';
            linkElement.id = 'css_link';
            linkElement.onload = function() {
                // document.getElementById('loader').style.display = 'none'; // Hide loader once CSS is loaded
                document.getElementById('page-wrapper').style.opacity = '';
            };
            if (theme_mode == 'light') {
                linkElement.href = '{{ asset('assets/css/main.light.css') }}';
            } else {
                linkElement.href = '{{ asset('assets/css/main.css') }}';
            }
            document.head.appendChild(linkElement);

            function switchTheme(theme) {
                document.body.setAttribute('data-bs-theme', theme);
                let link = document.getElementById('css_link');
                let loader = document.getElementById('loader');

                if (theme == 'light') {
                    link.href = '{{ asset('assets/css/main.light.css') }}';
                } else {
                    link.href = '{{ asset('assets/css/main.css') }}';
                }

                localStorage.setItem('theme_mode', theme);
            }

            window.onload = function() {
                document.getElementById('loader').style.display = 'none'; // Hide loader once CSS is loaded
            };
        </script>

        <!-- Main container start -->
        <div class="main-container">

            <!-- Sidebar wrapper start -->
            <nav id="sidebar" class="sidebar-wrapper">

                <!-- App brand starts -->
                <!-- <div class="app-brand p-2 d-flex align-items-center">
                    <a href="{{ route('shopfloor') }}"
                        style="margin-left: 0.3rem; display: flex; align-items: center; padding-top: 10px;">
                        <img src="{{ asset('assets/images/zenig.png') }}" class="logo" alt="ZENIG AUTO" />
                        <h4 class="ms-3 mt-2 text-danger">ZENIG AUTO</h4>
                    </a>
                </div> -->
                <!-- App brand ends -->

                <!-- Sidebar menu starts -->
                <div class="sidebarMenuScroll">
                    <ul class="sidebar-menu">
                        <li class="menu-label"><a href="">ERP</a></li>
                        </li>
                        <li class="treeview database-bar">
                        <a href="#!">
    <i class="bi bi-people"></i>
    <span class="menu-text">Membership</span>
</a>
<ul class="treeview-menu">
                                <li>
                                    <a href="{{route ('memberships.create')}}">Create Membership</a>
                                </li>
                                <li>
                                    <a href="{{route ('memberships.index')}}">View Membership</a>
                                </li>
                                <!-- <li>
                                    <a href="{{ route('invoice.index') }}">Invoice</a>
                                </li> -->
</ul>
                        </li>
                        <!-- <li class="treeview pvd-bar">
                            <a href="#!">
                                <i class="bi bi-clipboard-check"></i>
                                <span class="menu-text">PVD</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('purchase_price.index') }}">Purchase Price</a>
                                </li>
                                <li>
                                    <a href="{{ route('purchase_planning.index') }}">Purchase Planning</a>
                                </li>
                                <li>
                                    <a href="{{ route('purchase_requisition.index') }}">Purchase Requisition</a>
                                </li>
                                <li>
                                    <a href="{{ route('purchase_order.index') }}">Purchase Order</a>
                                </li>
                                <li>
                                    <a href="{{ route('supplier_ranking.index') }}">Supplier Ranking List</a>
                                </li>
                            </ul>
                        </li> -->
                        <!-- <li class="menu-label"><a href="">MES</a></li>
                        <li class="treeview dashboard-bar">
                            <a href="#!">
                                <i class="bi bi-house-up"></i>
                                <span class="menu-text">Dashboard</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('machine_status') }}">Machine Status</a>
                                </li>
                                <li>
                                    <a href="{{ route('shopfloor') }}">Shopfloor</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview engineering-bar">
                            <a href="#!">
                                <i class="bi bi-explicit"></i>
                                <span class="menu-text">Engineering</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('bom') }}">BOM</a>
                                </li>
                                <li>
                                    <a href="{{ route('bom.report') }}">BOM Report</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview ppc-bar">
                            <a href="#!">
                                <i class="bi bi-calendar2"></i>
                                <span class="menu-text">PPC</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('ppc.monthly_production_planning') }}">Monthly Production
                                        Planning</a>
                                </li>
                                <li>
                                    <a href="{{ route('daily-production-planning') }}">Daily Production Planning</a>
                                </li>
                                <li>
                                    <a href="{{ route('production-scheduling.index') }}">Production Scheduling</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview production-bar">
                            <a href="#!">
                                <i class="bi bi-ui-checks-grid"></i>
                                <span class="menu-text">Production</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('production_output_traceability.index') }}">Production Output
                                        Traceability</a>
                                </li>
                                <li>
                                    <a href="{{ route('summary_report') }}">Summary Report</a>
                                </li>
                                <li>
                                    <a href="{{ route('call_for_assistance.index') }}">Call for Assistance</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview oee-bar">
                            <a href="#!">
                                <i class="bi bi-laptop"></i>
                                <span class="menu-text">OEE</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('oee') }}">OEE Report</a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-label"><a href="">WMS</a></li>
                        <li class="treeview wms-dashboard-bar">
                            <a href="#!">
                                <i class="bi bi-house-up"></i>
                                <span class="menu-text">Dashboard</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('inventory_dashboard') }}">Inventory Dashboard</a>
                                </li>
                                <li>
                                    <a href="{{ route('inventory_shopfloor') }}">Inventory Shopfloor</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview operations-bar">
                            <a href="#!">
                                <i class="bi bi-box-seam"></i>
                                <span class="menu-text">Operations</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('delivery_instruction.index') }}">Delivery Instruction</a>
                                </li>
                                <li>
                                    <a href="{{ route('good_receiving.index') }}">Good Receiving</a>
                                </li>
                                <li>
                                    <a href="{{ route('material_requisition.index') }}">Material Requisition</a>
                                </li>
                                <li>
                                    <a href="{{ route('transfer_request.index') }}">Transfer Request</a>
                                </li>
                                <li>
                                    <a href="{{ route('discrepancy.index') }}">Discrepancy</a>
                                </li>
                                <li>
                                    <a href="{{ route('stock_adjustment.index') }}">Stock Adjustment</a>
                                </li>
                                <li>
                                    <a href="{{ route('stock_relocation.index') }}">Stock Relocation</a>
                                </li>
                                <li>
                                    <a href="{{ route('product_reordering.index') }}">Product Reordering</a>
                                </li>
                                <li>
                                    <a href="{{ route('outgoing.index') }}">Outgoing</a>
                                </li>
                                <li>
                                    <a href="{{ route('sales_return.index') }}">Sales Return</a>
                                </li>
                                <li>
                                    <a href="{{ route('purchase_return.index') }}">Purchase Return</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview report-bar">
                            <a href="#!">
                                <i class="bi bi-speedometer"></i>
                                <span class="menu-text">Report</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('inventory_report') }}">Inventory Report</a>
                                </li>
                                <li>
                                    <a href="{{ route('stock_card_report') }}">Stock Card</a>
                                </li>
                                <li>
                                    <a href="{{ route('summary_do_report') }}">Summary DO Report</a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="menu-label"><a href="">HR</a></li>
                        <li class="leave-bar">
                            <a href="{{ route('leave.index') }}">
                                <i class=" bi bi-people"></i>
                                <span class="menu-text">Leave</span>
                            </a>
                        </li>

                        <li class="attendance-bar">
                            <a href="{{ route('attendance.index') }}">
                                <i class=" bi bi-person-bounding-box"></i>
                                <span class="menu-text">Attendance</span>
                            </a>
                        </li>
                        <li class="attendance-bar">
                            <a href="{{ route('summary_attendance.index') }}">
                                <i class=" bi bi-person-bounding-box"></i>
                                <span class="menu-text">Summary Attendance</span>
                            </a>
                        </li>
                        <li class="payroll-bar">
                            <a href="{{ route('payroll.index') }}">
                                <i class=" bi bi-people"></i>
                                <span class="menu-text">Payroll</span>
                            </a>
                        </li>
                        <li class="menu-label"><a href="">SETTINGS</a></li>
                        <li class="treeview administration-bar">
                            <a href="#!">
                                <i class="bi bi-person-square"></i>
                                <span class="menu-text">Administration</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('user.index') }}">Staff Registration</a>
                                </li>
                                <li>
                                    <a href="{{ route('role.index') }}">Role & Permissions</a>
                                </li>
                                <li>
                                    <a href="{{ route('department.index') }}">Department</a>
                                </li>
                                <li>
                                    <a href="{{ route('designation.index') }}">Designation</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview database-bar">
                            <a href="#!">
                                <i class="bi bi-database-gear"></i>
                                <span class="menu-text">Database</span>
                            </a>
                            <ul class="treeview-menu">
                            <li>
                                    <a href="{{ route('services.index') }}">Services</a>
                                </li>
                                <!-- <li>
                                    <a href="{{ route('product.index') }}">Product</a>
                                </li>
                                <li>
                                    <a href="{{ route('category.index') }}">Category</a>
                                </li>
                                <li>
                                    <a href="{{ route('supplier.index') }}">Supplier</a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.index') }}">Customer</a>
                                </li>
                                <li>
                                    <a href="{{ route('process.index') }}">Process</a>
                                </li>
                                <li>
                                    <a href="{{ route('unit.index') }}">Unit</a>
                                </li>
                                <li>
                                    <a href="{{ route('area_level.index') }}">Area - Level</a>
                                </li>
                                <li>
                                    <a href="{{ route('area_rack.index') }}">Area - Rack</a>
                                </li>
                                <li>
                                    <a href="{{ route('area.index') }}">Area</a>
                                </li>
                                <li>
                                    <a href="{{ route('machine.index') }}">Machine</a>
                                </li>
                                <li>
                                    <a href="{{ route('machine_tonage.index') }}">Machine Tonnage</a>
                                </li>
                                <li>
                                    <a href="{{ route('type_of_product.index') }}">Type of Product</a>
                                </li>
                                <li>
                                    <a href="{{ route('type_of_rejection.index') }}">Type of Rejection</a>
                                </li> -->
                            </ul>
                        </li>
<!-- 
                        <li class="general-bar">
                            <a href="{{ route('general_setting.index', 0) }}">
                                <i class="bi bi-gear"></i>
                                <span class="menu-text">General Settings</span>
                            </a>
                        </li> -->
                        <li class="menu-label"><a href="">ACCOUNTING</a></li>
                        <li class="account-dashboard">
                            <a href="{{ route('account-home') }}">
                                <i class="bi bi-speedometer2"></i>
                                Accounts Dashboard
                            </a>
                        </li>
                        <li class="treeview accounts-bar">
                            <a href="#!">
                                <i class="bi bi-wallet2"></i>
                                <span class="menu-text">Accounts</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('account_categories.index') }}">Account Category</a>
                                </li>
                                <li>
                                    <a href="{{ route('accounts.index') }}">Account Details</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview accounts-reports-bar">
                            <a href="#!">
                                <i class="bi bi-bar-chart-line"></i>
                                <span class="menu-text">Accounts Reports</span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="{{ route('ledger.index') }}">Ledger Summary</a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.trial_balance') }}">Trial Balance</a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.profit_loss') }}">Profit and Loss</a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.balance_sheet') }}">Balance Sheet</a>
                                </li>
                                <li>
                                    <a href="{{ route('carryforward.index') }}">Carryforward</a>
                                </li>
                                <li>
                                    <a href="{{ route('aging_report.index') }}">Aging Report</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- Sidebar menu ends -->

            </nav>
            <!-- Sidebar wrapper end -->

            <!-- App container starts -->
            <div class="app-container">

                <!-- App header starts -->
                <div class="app-header">

                    <!-- Toggle buttons start -->
                    <div class="d-flex">
                        <button class="btn btn-primary btn-sm me-3 toggle-sidebar" id="toggle-sidebar">
                            <i class="bi bi-list fs-5"></i>
                        </button>
                        <button class="btn btn-light btn-sm me-3 pin-sidebar" id="pin-sidebar">
                            <i class="bi bi-list fs-5"></i>
                        </button>
                    </div>
                    <!-- Toggle buttons end -->

                    <!-- App brand start -->
                    {{-- <div class="app-brand-sm">
                        <a href="index.html" class="d-lg-none d-md-block">
                            <img src="{{ asset('assets/images/logo-sm.svg') }}" class="logo"
                                alt="Bootstrap Gallery">
                        </a>
                    </div> --}}
                    <!-- App brand end -->

                    <!-- App header actions start -->
                    <div class="header-actions gap-3">

                        <!-- Mode container starts -->
                        <div>
                            <select class="form-control" style="cursor: pointer;" id="mode-selector">
                                <option value="dark">Dark Mode</option>
                                <option value="light">Light Mode</option>
                            </select>
                        </div>
                        <!-- Mode container ends -->

                        <!-- Header action starts -->
                        <div class="header-actions-block rounded-5 p-2 gap-2 d-sm-flex d-none">
                            <div class="dropdown">
                                <a class="action-icon" href="{{ route('notifications') }}" role="button"
                                    aria-expanded="false">
                                    <i class="bi bi-bell"></i>
                                    <span class="count-label danger"></span>
                                </a>

                            </div>
                        </div>
                        <!-- Header action ends -->
<!--  -->
                        <!-- Header settings starts -->

                    </div>
                    <!-- App header actions end -->

                </div>
                <!-- App header ends -->

                <!-- App hero header starts -->

                <!-- App Hero header ends -->

                <!-- App body starts -->
                <div class="app-body">
                    <div class="app-hero-header d-flex justify-content-between">
                        <h5 class="d-flex align-items-center">@yield('title')</h5>
                        <h5>@yield('button')</h5>
                    </div>
                    @include('includes.errors')
                    @include('includes.success')
                    @yield('content')
                </div>
                <!-- App body ends -->

                <!-- App footer start -->
                <div class="app-footer text-center" style="position: relative; bottom: -62px;">
                    <span class="text-white">Copyright © {{ date('Y') }} . All rights reserved IIOT
                        FACTORY.</span>
                </div>
                <!-- App footer end -->

            </div>
            <!-- App container ends -->

        </div>
        <!-- Main container end -->

    </div>
    <!-- Page wrapper end -->

    <!-- *************
   ************ JavaScript Files *************
  ************* -->
    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- *************
   ************ Vendor Js Files *************
  ************* -->

    <!-- Overlay Scroll JS -->
    <script src="{{ asset('assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/overlay-scroll/custom-scrollbar.js') }}"></script>


   <!-- Apex Charts -->
   <script src="{{ asset('assets/vendor/apex/apexcharts.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ url('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/vendor/datatables/js/dataTables.fixedColumns.min.js') }}"></script>

    <!-- Moment JS files -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment-timezone-with-data.min.js') }}"></script>

    <!-- Custom JS files -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/side-bar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <!-- Add Toaster JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        // Initialize Toastr notifications
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @elseif (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $('input[type="number"]').attr('step', '0.01');
            $('[title]').tooltip();

            $(document).on('click', 'a.read-more', function(e) {
                e.preventDefault();
                var descriptionDiv = $(this).prev();
                var descriptionDiv1 = $(this).prev().prev();
                if (descriptionDiv.css('display') === 'none') {
                    descriptionDiv.css('display', 'block');
                    descriptionDiv1.css('display', 'none');
                    $(this).text('Read Less');
                } else {
                    descriptionDiv.css('display', 'none');
                    descriptionDiv1.css('display', 'block');
                    $(this).text('Read More');
                }
            });
            $(document).on('mouseenter', '.select2-selection__rendered', function () {
                $('.select2-selection__rendered').removeAttr('data-bs-original-title');
            });
            fetchNotificationCount();
        });

        document.getElementById('mode-selector').value = theme_mode;
        document.getElementById('mode-selector').addEventListener('change', function() {
            switchTheme(this.value);
        });

        function fetchNotificationCount() {
            $.ajax({
                url: "{{ route('notifications.count') }}",
                type: 'GET',
                success: function(response) {
                    $('.count-label').text(response.count);
                    if (response.count > 0) {
                        $('.count-label').addClass('danger');
                    } else {
                        $('.count-label').removeClass('danger');
                    }
                },
                error: function(xhr) {
                    console.log("Error fetching notification count");
                }
            });
        }

        // Call the function every X seconds (e.g., 10 seconds)
        setInterval(fetchNotificationCount, 10000);
    </script>
</body>

</html>
