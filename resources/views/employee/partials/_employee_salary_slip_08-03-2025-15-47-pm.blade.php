<html>

<body>
    <style>
        table,
        th,
        td {
            border: 2px solid #000;
            text-align: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 5px;
        }

        th {
            color: #042B48;
        }

        .high-light {
            color: #042B48;
        }
        .bg-light-gray{
            background-color: #ECECF1;
        }

        .no-border {
            border: 0px solid #000;
        }
    </style>
    @php
        $pf_number = '';
        $eslc_number = '';
        $employee_name = '';
        $employee_code = '';
        $employee_location = '';
        $employee_id = 0;
        $salary_slip_id = $contact_detail->salary_slip_id ?: 0;
        $employee_detail = $contact_detail->employee_detail;
        if ($employee_detail) {
            $employee_name          = $employee_detail->full_name ?? '';
            $employee_code          = $employee_detail->emp_code ?? '';
            $employee_id            = $employee_detail->emp_id  ?? '';
            $company_detail         = $employee_detail->company_detail;
            $employee_basic_details = $employee_detail->employee_basic_details ?? "";
            $employee_pf_details    = $employee_detail->employee_pf_details;
            $employee_pescldetails  = $employee_detail->employee_pescldetails;
            if ($company_detail) {
                $location_detail = $company_detail->master_location_detail;
                if ($location_detail) {
                    $employee_location = $location_detail->master_data_name ?? '';
                }
            }
            if ($employee_pf_details) {
                $pf_number = $employee_pf_details->emp_PF_no ?? '';
            }
            if ($employee_pf_details) {
                $eslc_number = $employee_pf_details->emp_ESIC_no ?? '';
            }
        }

        // $monthname =
        //     $contact_detail->month_p > 0
        //         ? \Carbon\Carbon::createFromFormat('m', $contact_detail->month_p)->format('F')
        //         : '';
               // $monthname = $contact_detail->month_p > 0 ? \Carbon\Carbon::createFromFormat('Y-m-d', $contact_detail->year_p . '-' . str_pad($contact_detail->month_p, 2, '0', STR_PAD_LEFT) . '-01')->format('F') : '';

               $year  = !empty($detail->year_p) && is_numeric($detail->year_p) ? $detail->year_p : null;
               $month = !empty($detail->month_p) && is_numeric($detail->month_p) ? str_pad($detail->month_p, 2, '0', STR_PAD_LEFT) : null;
                $monthname = '';

                if ($year && $month) {
                        try {
                            $monthname = \Carbon\Carbon::createFromFormat('Y-m-d', "$year-$month-01")->format('F');
                        } catch (\Exception $e) {
                            \Log::error("Date parsing error: " . $e->getMessage());
                            $monthname = 'Invalid Date';
                        }
                }
        $totalEarnings = 0;
        $totalDeductions = 0;

        $totalEarnings += $contact_detail->basic_amt ?: 0;
        $totalEarnings += $contact_detail->hra ?: 0;
        $totalEarnings += $contact_detail->conveyance ?: 0;
        $totalEarnings += $contact_detail->state_bonus ?: 0;
        $totalEarnings += $contact_detail->cca ?: 0;
        $totalEarnings += $contact_detail->stat_allowance ?: 0;
        $totalEarnings += $contact_detail->leave_encash ?: 0;
        $totalEarnings += $contact_detail->washingall ?: 0;
        $totalEarnings += $contact_detail->night_allowance ?: 0;
        $totalEarnings += $contact_detail->uniform_allowance ?: 0;
        $totalEarnings += $contact_detail->food_allowance ?: 0;
        $totalEarnings += $contact_detail->ppi ?: 0;
        $totalEarnings += $contact_detail->attendance_bonus ?: 0;

        $totalDeductions += $contact_detail->pf ?: 0;
        $totalDeductions += $contact_detail->esci ?: 0;
        $totalDeductions += $contact_detail->professional_tax ?: 0;
        $totalDeductions += $contact_detail->lwf ?: 0;
        $totalDeductions += $contact_detail->gratutity_amt ?: 0;
        $totalDeductions += $contact_detail->recovery ?: 0;
        $totalDeductions += $contact_detail->tds ?: 0;

        $roundedTotalEarnings = round($totalEarnings);
        $roundedTotalDeductions = round($totalDeductions);

        $netPay =
            $roundedTotalEarnings >= $roundedTotalDeductions ? $roundedTotalEarnings - $roundedTotalDeductions : 0;

        $formattedNetPay = numberFormation($netPay, 2, '.', ',');

    @endphp
    <table class="no-border">
        <tr>
            <td class="no-border"><img width="250px"
                    src="{{ get_settings('system_logo', '') }}" /></td>
            <td class="no-border">
                <center>
                    <h2 class="high-light">PaySlip Of {{ $monthname }}- {{ $contact_detail->year_p }}
                    </h2>
                </center>
            </td>
        </tr>

    </table>
    <hr style="margin:5px;">
    <table class="no-border">
        <tr class="no-border">
            <td class="no-border"><b class="high-light">Employee ID:</b> {{ $employee_code }}</td>
            <td class="no-border"><b class="high-light">Name:</b> {{ $employee_name }}</td>
        </tr>
        <tr>
            {{-- <td class="no-border"><b class="high-light">Location:</b> {{ $employee_location }}</td> --}}
            <td class="no-border"><b class="high-light">Location:</b> {{ $employee_basic_details->location_branch ?? "" }}</td>
            <td class="no-border"><b class="high-light">Designation:</b> {{ $employee_basic_details->designation ?? "" }}</td>
        </tr>
    </table>


    <table>
        <tr class="bg-light-gray">
            <th>PF No/UAN No:</th>
            <td>{{ $pf_number }}</td>
            <th>ESIC No:</th>
            <td>{{ $eslc_number }}</td>
        </tr>
        <tr>
            <td>Worked Days</td>
            <td>{{ $contact_detail->total_days ?: 0 }}</td>
            <td>Paid Days</td>
            <td>{{ $contact_detail->total_work_days ?: 0 }}</td>
        </tr>
        <tr>
            <td>Arrear Days</td>
            <td>{{ $contact_detail->arrear_days ?: 0 }}</td>
            <td>Arrears</td>
            <td>{{ $contact_detail->arrear_amt ?: 0 }}</td>
        </tr>
        <tr>
            <td>Incentives</td>
            <td>{{ $contact_detail->incentive ?: 0 }}</td>
            <td>GPRS</td>
            <td>{{ $contact_detail->gprs ?: 0 }}</td>
        </tr>
        <tr>
            <td>Advance Deduction</td>
            <td>{{ $contact_detail->advance_deduction ?: 0 }}</td>
            <td>Other Deductions</td>
            <td>{{ $contact_detail->other_deduction ?: 0 }}</td>
        </tr>
        <tr>
            <td>OT Hours</td>
            <td>{{ $contact_detail->ot_hour ?: 0 }}</td>
            <td>OT Amount</td>
            <td>{{ $contact_detail->ot_amt ?: 0 }}</td>
        </tr>
        <tr>
            <td>Extra Days</td>
            <td>{{ $contact_detail->extra_days ?: 0 }}</td>
            <td>Extra Days Amount</td>
            <td>{{ $contact_detail->extra_days_amt ?: 0 }}</td>
        </tr>
        <tr>
            <td>Conveyance1</td>
            <td>{{ $contact_detail->conveyance_1 ?: 0 }}</td>
            <td>Other</td>
            <td>{{ $contact_detail->other ?: 0 }}</td>
        </tr>
        <tr class="bg-light-gray">
            <th>Earnings</th>
            <th>Amount (Rs)</th>
            <th>Deductions</th>
            <th>Amount (Rs)</th>
        </tr>
        <tr>
            <td>Basic</td>
            <td>{{ $contact_detail->basic_amt ?: 0 }}</td>
            <td>PF</td>
            <td>{{ $contact_detail->pf ?: 0 }}</td>
        </tr>
        <tr>
            <td>HRA</td>
            <td>{{ $contact_detail->hra ?: 0 }}</td>
            <td>ESIC</td>
            <td>{{ $contact_detail->esci ?: 0 }}</td>
        </tr>
        <tr>
            <td>Conveyance</td>
            <td>{{ $contact_detail->conveyance ?: 0 }}</td>
            <td>P.Tax</td>
            <td>{{ $contact_detail->professional_tax ?: 0 }}</td>
        </tr>
        <tr>
            <td>Stat Bonus</td>
            <td>{{ $contact_detail->state_bonus ?: 0 }}</td>
            <td>MLWF</td>
            <td>{{ $contact_detail->lwf ?: 0 }}</td>
        </tr>
        <tr>
            <td>CCA</td>
            <td>{{ $contact_detail->cca ?: 0 }}</td>
            <td>Gratuity Amount</td>
            <td>{{ $contact_detail->gratutity_amt ?: 0 }}</td>
        </tr>
        <tr>
            <td>Stat Allowance</td>
            <td>{{ $contact_detail->stat_allowance ?: 0 }}</td>
            <td>Recovery</td>
            <td>{{ $contact_detail->recovery ?: 0 }}</td>
        </tr>
        <tr>
            <td>Leave Encashment</td>
            <td>{{ $contact_detail->leave_encash ?: 0 }}</td>
            <td>TDS</td>
            <td>{{ $contact_detail->tds ?: 0 }}</td>
        </tr>
        <tr>
            <td>Washing Allowance</td>
            <td>{{ $contact_detail->washingall ?: 0 }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Night Allowance</td>
            <td>{{ $contact_detail->night_allowance ?: 0 }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Uniform Allowance</td>
            <td>{{ $contact_detail->uniform_allowance ?: 0 }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Food Allowance</td>
            <td>{{ $contact_detail->food_allowance ?: 0 }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>PPI</td>
            <td>{{ $contact_detail->ppi ?: 0 }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Attendance Bonus</td>
            <td>{{ $contact_detail->attendance_bonus ?: 0 }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr class="bg-light-gray">
            <th><strong>Total Earnings</strong></th>
            <th> &#8377; {{ $contact_detail->gross_salary ?: 0 }}</th>
            <!-- <th> &#8377; {{ numberFormation($roundedTotalEarnings, 2, '.', ',') }}</th> -->
            <th><strong>Total Deductions</strong></th>
            <!-- <th> &#8377; {{ numberFormation($roundedTotalDeductions, 2, '.', ',') }}</th> -->
            <th> &#8377; {{ $contact_detail->total_deduction ?: 0 }}</th>
        </tr>


    </table>
    <p class="high-light" style="margin: 5px;">Net Pay after Round Off:<b> &#8377; {{ $contact_detail->net_salary ?: 0 }}</b></p>
    <p class="high-light" style="margin: 5px;">In Words:<b> {{ convertNumberToWords($contact_detail->net_salary) }} Only</b></p>
    <footer>
        <hr style="margin:5px;">
        <center>
            <p style="margin: 5px;">* This is computer generated statement, requires no signature</p>
        </center>
        <b>Tactics Management Services Pvt. Ltd,<br>
            <!-- CIN:</b> U74999MH2013PTC240653 | -->
        <b>Regd Add:</b> 201, 2rd Floor, Gulmohar Complex, Station Road, Goregaon (East), Mumbai
        Maharashtra
        400063 | <b>www.tacticsservices.com</b>

    </footer>

</body>

</html>
