<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title style="text-transform: uppercase;">ZENIG AUTO SALARY SLIP {{ strtoupper($payroll->month) }} , {{ $payroll->year }}</title>
    <link rel="shortcut icon" href="https://zenig.iiotmachine.com/assets/images/zenig.png" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }




        .header img {
            width: 175px;
        }

        .header h3 {
            margin: 10px 0;
        }

        .company-details,
        .supplier-details {
            width: 100%;
            margin-bottom: 20px;
        }

        .company-details td,
        .supplier-details td {
            border: none;
            padding: 5px;
        }

        .details-table,
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td,
        .item-table th,
        .item-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 13px;
        }

        .item-table th,
        .item-table td {
            text-align: center;
        }

        .details-tables {
            width: 100%;
            border-collapse: collapse;
        }

        .details-tables th,
        td {
            border: 1px solid #000;
            text-align: left;
            padding: 5px;
        }

        .pagenum:before {
            content: counter(page);
        }

        .no-border-table td {
            border: none;
        }

        .mt-none {
            margin-top: none;
        }

        .mb-none {
            margin-bottom: none;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .page-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .a4-safe {
            max-width: 100%;
            /* Adjust according to your layout */
            width: 21cm;
            /* A4 page width */
            word-wrap: break-word;
            overflow-wrap: break-word;
            margin: 0 auto;
            /* Center the content if needed */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <table class="no-border-table">
                <tr>
                    <td>
                        <img src="https://zenig.iiotmachine.com/assets/images/zenig1.png" alt="Zenig Auto Logo">
                    </td>
                    <td>
                        <h3>ZENIG AUTO SDN BHD</h3>
                        <p class="mt-none mb-none">(1015897-M)</p>
                        <p class="mt-none mb-none">Lot 9414, Jalan Jasmine 1, Seksyen BB 10, Bukit Beruntung, 48300
                            Rawang, Selangor Darul Ehsan.<br>
                            Tel: 03-6028 1712/ 03-6028 4421<br>
                            Fax: 03-6028 2844
                        </p>
                    </td>
                </tr>
                <tr class="text-center">
                    <td colspan="2" class="text-center">
                        <h3>SALARY SLIP {{ strtoupper($payroll->month) }} , {{ $payroll->year }}</h3>
                    </td>
                </tr>


            </table>
        </div>

        <table class="company-details text-center">
            {{-- @dd($payroll_detail) --}}
            <tr>
                <td><b>NAME</b> : {{ $payroll_detail->user->user_name ?? '' }} </td>
                <td style="padding-left:250px;"><b>REF NO</b> : {{ $payroll_detail->ref_no ?? '' }} </td>
            </tr>
            <tr>
                <td><b>NRIC</b> :
                    {{ isset($payroll_detail->user->personalUser) ? $payroll_detail->user->personalUser->nric : '' }}
                </td>
                <td style="padding-left:250px;"><b>DATE</b> : {{ $payroll_detail->date ?? '' }} </td>
            </tr>
            <tr>
                <td><b>EMPLOYEE ID</b> : {{ $payroll_detail->user->code ?? '' }} </td>
                <td style="padding-left:250px;"><b>EPF NO.</b> :
                    {{ isset($payroll_detail->user->personalUser) ? $payroll_detail->user->personalUser->epf_no : '' }}
                </td>
            </tr>
            <tr>
                <td><b>DESIGNATION</b> :
                    {{ isset($payroll_detail->user->designation) ? $payroll_detail->user->designation->name : '' }}
                </td>
                <td style="padding-left:250px;"><b>SOCSO NO.</b> :
                    {{ isset($payroll_detail->user->personalUser) ? $payroll_detail->user->personalUser->sosco_no : '' }}
                </td>
            </tr>
            <tr>
                <td><b>DEPARTMENT</b> : {{ $payroll_detail->user->user_name ?? '' }} </td>
                <td style="padding-left:250px;"><b>INCOME TAX NO.</b> :
                    {{ isset($payroll_detail->user->personalUser) ? $payroll_detail->user->personalUser->tin : '' }}
                </td>
            </tr>
            <tr>
                <td><b>BANK</b> :
                    {{ isset($payroll_detail->user->user_bank_detail) ? $payroll_detail->user->user_bank_detail->bank : '' }}
                </td>
                <td style="padding-left:250px;"><b>BANK ACCOUNT NO.</b> :
                    {{ isset($payroll_detail->user->user_bank_detail) ? $payroll_detail->user->user_bank_detail->account_no : '' }}
                </td>
            </tr>

        </table>


        <br>

        <table class=" text-center item-table">
            {{-- @dd($payroll_detail) --}}
            <thead>
                <tr>
                    <th>PAYMENT</th>
                    <th>AMOUNT (RM)</th>
                    <th style="width:150px;">DEDUCTION</th>
                    <th>AMOUNT (RM)</th>
                    <th style="width:150px;">COMPANY CONTRIBUTION</th>
                    <th>AMOUNT (RM)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $column_1 = 0.0;
                $column_2 = 0.0;
                $column_3 = 0.0;
                @endphp
                <tr>
                    <td>BASIC SALARY</td>
                    <td>{{ isset($payroll_detail->user->personalUser->base_salary) ? number_format($payroll_detail->user->personalUser->base_salary, 1) : '' }}</td>
                    @php
                    $column_1 += isset($payroll_detail->user->personalUser->base_salary)
                    ? round($payroll_detail->user->personalUser->base_salary, 1)
                    : 0;
                    @endphp
                    @if (isset($payroll_detail->user->personalUser->base_salary) && $payroll_detail->user->personalUser->base_salary < 5000)
    @if ($payroll_setup->kwsp == 1 && $payroll_detail->kwsp == 1)
        @php
            $base_salary = $payroll_detail->user->personalUser->base_salary;
            $employee_epf = 0;
            $employer_epf = 0;

            // Check if age is available
            if (isset($payroll_detail->user->personalUser->age)) {
                $ageParts = explode('-', $payroll_detail->user->personalUser->age);
                $years = (int) filter_var($ageParts[0], FILTER_SANITIZE_NUMBER_INT);
                $months = (int) filter_var($ageParts[1], FILTER_SANITIZE_NUMBER_INT);
                $totalMonths = ($years * 12) + $months;

                // Determine the percentage based on age
                if ($totalMonths < (60 * 12)) {
                    $employee_percentage = $payroll_setup->kwsp_category_1_employee_per;
                    $employer_percentage = $payroll_setup->kwsp_category_1_employer_per;
                } elseif ($totalMonths > (60 * 12)) {
                    $employee_percentage = $payroll_setup->kwsp_category_3_employee_per;
                    $employer_percentage = $payroll_setup->kwsp_category_3_employer_per;
                } else {
                    $employee_percentage = 0;
                    $employer_percentage = 0;
                }
            } else {
                // Default percentages if age is not available
                $employee_percentage = $payroll_setup->kwsp_category_1_employee_per;
                $employer_percentage = $payroll_setup->kwsp_category_1_employer_per;
            }

            // Calculate the EPF contributions
            $employee_epf = round(($employee_percentage / 100) * $base_salary, 1);
            $employer_epf = round(($employer_percentage / 100) * $base_salary, 1);

            // Update column totals
            $column_2 += $employee_epf;
            $column_3 += $employer_epf; // Adding to the total for employer EPF
        @endphp

        <!-- Employee Contribution -->
        <td>KWSP/EPF (Employee)</td>
        <td>{{ number_format($employee_epf, 1) }}</td>

        <!-- Employer Contribution -->
        <td>KWSP/EPF (Employer)</td>
        <td>{{ number_format($employer_epf, 1) }}</td>
    @else
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    @endif
@else
    @if ($payroll_setup->kwsp == 1 && $payroll_detail->kwsp == 1)
        @php
            $base_salary = $payroll_detail->user->personalUser->base_salary;
            $employee_epf = 0;
            $employer_epf = 0;

            // Default percentages if no specific conditions are applied
            $employee_percentage = $payroll_setup->kwsp_category_1_employee_per;
            $employer_percentage = $payroll_setup->kwsp_category_1_employer_per;

            // Calculate the EPF contributions
            $employee_epf = round(($employee_percentage / 100) * $base_salary, 1);
            $employer_epf = round(($employer_percentage / 100) * $base_salary, 1);

            // Update column totals
            $column_2 += $employee_epf;
            $column_3 += $employer_epf; // Adding to the total for employer EPF
        @endphp

        <!-- Employee Contribution -->
        <td>KWSP/EPF (Employee)</td>
        <td>{{ number_format($employee_epf, 1) }}</td>

        <!-- Employer Contribution -->
        <td>KWSP/EPF (Employer)</td>
        <td>{{ number_format($employer_epf, 1) }}</td>
    @else
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    @endif
@endif

                </tr>

                @foreach ($payroll_detail_childs as $key => $payroll_detail_child)
                <tr>
                    @if (isset($payroll_detail_child['checkbox']) && $payroll_detail_child['checkbox'] == 1)
                    <td>{{ $payroll_detail_child['particular'] }}</td>
                    @else
                    <td> - </td>
                    @endif

                    @if (isset($payroll_detail_child['checkbox']) && $payroll_detail_child['checkbox'] == 1)
                    <td>{{ number_format($payroll_detail_child['value'], 1) }}</td>
                    @else
                    <td> - </td>
                    @endif

                    @php
                    $column_1 += isset($payroll_detail_child['checkbox']) && $payroll_detail_child['checkbox'] == 1
                    ? round($payroll_detail_child['value'], 1)
                    : 0;
                    @endphp

                    @if ($loop->iteration == 1)
                    @if ($payroll_setup->socso == 1 && $payroll_detail->socso == 1)
                    <td>SOCSO (Employee)</td>
                    <td>{{ number_format(($payroll_setup->socso_employee_per / 100) * $payroll_detail->user->personalUser->base_salary, 1) }}</td>
                    @php
                    $column_2 += round(($payroll_setup->socso_employee_per / 100) * $payroll_detail->user->personalUser->base_salary, 1);
                    @endphp
                    @else
                    <td></td>
                    <td></td>
                    @endif

                    @if ($payroll_setup->socso == 1 && $payroll_detail->socso == 1)
                    <td>SOCSO (Employer)</td>
                    <td>{{ number_format(($payroll_setup->socso_employer_per / 100) * $payroll_detail->user->personalUser->base_salary, 1) }}</td>
                    @php
                    $column_3 += round(($payroll_setup->socso_employer_per / 100) * $payroll_detail->user->personalUser->base_salary, 1);
                    @endphp
                    @else
                    <td></td>
                    <td></td>
                    @endif
                    @elseif($loop->iteration == 2)
                    @if ($payroll_setup->eis == 1 && $payroll_detail->eis == 1)
                    <td>SIP (Employee)</td>
                    <td>{{ number_format(($payroll_setup->eis_employee_per / 100) * $payroll_detail->user->personalUser->base_salary, 1) }}</td>
                    @php
                    $column_2 += round(($payroll_setup->eis_employee_per / 100) * $payroll_detail->user->personalUser->base_salary, 1);
                    @endphp
                    @else
                    <td></td>
                    <td></td>
                    @endif

                    @if ($payroll_setup->eis == 1 && $payroll_detail->eis == 1)
                    <td>SIP (Employer)</td>
                    <td>{{ number_format(($payroll_setup->eis_employer_per / 100) * $payroll_detail->user->personalUser->base_salary, 1) }}</td>
                    @php
                    $column_3 += round(($payroll_setup->eis_employer_per / 100) * $payroll_detail->user->personalUser->base_salary, 1);
                    @endphp
                    @else
                    <td></td>
                    <td></td>
                    @endif
                    @elseif($loop->iteration == 3)
                    <td></td>
                    <td></td>
                    @if ($payroll_setup->hrdf == 1 && $payroll_detail->hrdf == 1)
                    <td>HRDF</td>
                    <td>{{ number_format(($payroll_setup->hrdf_per / 100) * $payroll_detail->user->personalUser->base_salary, 1) }}</td>
                    @php
                    $column_3 += round(($payroll_setup->hrdf_per / 100) * $payroll_detail->user->personalUser->base_salary, 1);
                    @endphp
                    @else
                    <td></td>
                    <td></td>
                    @endif
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                @endforeach
            </tbody>


            <tfoot>
                <tr>
                    <td>TOTAL GROSS SALARY</td>
                    <td>{{ $column_1 }}</td>
                    <td>TOTAL DEDUCTION</td>
                    <td>{{ $column_2 }}</td>
                    <td>TOTAL</td>
                    <td>{{ $column_3 }}</td>
                </tr>
            </tfoot>
            <tfoot>
                <tr>
                    <td class="text-end" colspan="5">NET AMOUNT (RM)</td>
                    <td>{{ $column_1 - $column_2 }}</td>
                </tr>
            </tfoot>

        </table>
        @if ($payroll_setup->paysilp == 1)
        <h5>Payslip Remarks:</h5>
        <p class="a4-safe">{!! nl2br(e($payroll_setup->paysilp_remarks)) !!}</p>
        @endif




    </div>
</body>

</html>