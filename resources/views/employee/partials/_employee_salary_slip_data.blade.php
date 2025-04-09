<div class="container mt-3">
    <div class="table-responsive scrollable-table">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    @if ($action)
                    <th>Action</th>
                    @endif
                    <th>Employee Name</th>
                    <th>Company</th>
                    <th>Month-Year</th>

                </tr>
            </thead>
            <tbody>
                @forelse($contact_detail as $detail)
                    @php
                        $employee_name   = '';
                        $company_name    = '';
                        $employee_code   = '';
                        $employee_id     = 0;
                        $salary_slip_id  = $detail->salary_slip_id ?: 0;
                        $employee_detail = $detail->employee_detail;
                        if ($employee_detail) {
                            $employee_name = $employee_detail->full_name ?? '';
                            $employee_code = $employee_detail->emp_code ?? '';
                            $employee_id   = $employee_detail->emp_id  ?? '';
                            $company_detail  = $employee_detail->company_detail;
                            //dd(   $employee_detail->company_detail );
                            if ($company_detail) {
                                $company_name = $company_detail->comp_name ?? "";
                            }
                        }
                       // $monthname = $detail->month_p > 0 ? \Carbon\Carbon::createFromFormat('m', $detail->month_p)->format('F')  : '';
                      //  $monthname = $detail->month_p;
                    //  $monthname = $detail->month_p > 0 ? \Carbon\Carbon::createFromFormat('Y-m-d', $detail->year_p . '-' . str_pad($detail->month_p, 2, '0', STR_PAD_LEFT) . '-01')->format('F') : '';
                    //   $monthname = $detail->month_p > 0 ? \Carbon\Carbon::createFromFormat('Y-m-d', $detail->year_p . '-' . str_pad($detail->month_p, 2, '0', STR_PAD_LEFT) . '-01')->format('F') : '';
                    $year  = !empty($detail->year_p) && is_numeric($detail->year_p) ? $detail->year_p : null;
                    $month = !empty($detail->month_p) && is_numeric($detail->month_p) ? str_pad($detail->month_p, 2, '0', STR_PAD_LEFT) : null;
                     // $monthname = "";
                     $monthname = '';

                    if ($year && $month) {
                        try {
                            $monthname = \Carbon\Carbon::createFromFormat('Y-m-d', "$year-$month-01")->format('F');
                        } catch (\Exception $e) {
                            \Log::error("Date parsing error: " . $e->getMessage());
                            $monthname = 'Invalid Date';
                        }
                    }
                    @endphp
                    <tr>
                        @if ($action && HasPermission('A93') == 'true')
                            <td><button class="btn btn-primary btn-sm salary-slip-donwload" data-employee_id="{{$employee_id}}" data-salary_slip_id="{{$salary_slip_id}}" >View Salary Slip</button></td> 
                        @endif
                        <td>{{ $employee_name }} ({{$employee_code}})</td>
                        <td>{{ $company_name  }}</td>
                        <td>{{ $monthname }}- {{$detail->year_p}}</td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
