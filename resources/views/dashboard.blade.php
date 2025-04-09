@extends('IncludesFiles.Master')

@section('container')
<div class="container-fluid mt-5 pt-5">
    @if (HasPermission('DS1') == 'true')
    <!-- Active Employee Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header text-white text-center" style="background-color: #042B48;">
                    <h5 class="mb-0">Active Users</h5>
                </div>
                <div class="card-body text-center shadow">
                    <h1 class="display-3 fw-bold text-dark">{{ count($activeUsers ?? []) }}</h1>
                    <p class="h5 text-dark">Active Users</p>
                </div>
            </div>
        </div>
        @endif
        @if (HasPermission('DS4') == 'true')
        <!-- Active Employee Section -->

        <div class="col-md-3">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header text-white text-center" style="background-color: #042B48;">
                    <h5 class="mb-0">Re-Visited Users</h5>
                </div>
                <div class="card-body text-center shadow">
                    <h1 class="display-3 fw-bold text-dark">{{ count($revisitedUsers ?? []) }}</h1>
                    <p class="h5 text-dark">Re-Visited Users</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Employee Performance List Section -->
    @if (HasPermission('DS2') == 'true')
    <div class="row mb-4 mx-1">
        <div class="col-md-5">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header text-white text-center" style="background-color: #042B48;">
                    <h5 class="mb-0">Employee Performance List</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table text-center mb-0">
                        <thead>
                            <tr style="background-color: #0d1b2a; color: #fff;">
                                <th class="text-uppercase">Employee Name</th>
                                <th class="text-uppercase">Current Month</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($employeePerformance) && count($employeePerformance) > 0)
                            @foreach($employeePerformance as $performance)
                            <tr class="hoverable-row">
                                <td>{{ $performance->user_name }}</td>
                                <td>{{ $performance->current_count }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="2" class="text-muted">No data available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endif


        <!-- Company Data Section -->
        @if (HasPermission('DS3') == 'true')

        <div class="col-md-7">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header text-white text-center" style="background-color: #042B48;">
                    <h5 class="mb-0">Company Data</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table text-center mb-0">
                        <thead>
                            <tr style="background-color: #0d1b2a; color: #fff;">
                                <th class="text-uppercase">Company</th>
                                <th class="text-uppercase">Current Month</th>
                                <th class="text-uppercase">Till Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($companyData) && count($companyData) > 0)
                            @foreach($companyData as $company)
                            <tr class="hoverable-row">
                                <td>{{ $company->comp_name }}</td>
                                <td>{{ $company->current_month }}</td>
                                <td>{{ $company->till_date }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3" class="text-muted">No data available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- Summary Report Section -->
<div class="container-fluid mt-5">
    <!-- Summary Report Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header text-white text-center" style="background-color: #042B48;">
                    <h5 class="mb-0">Summary Report Approved Data by Team Member</h5>
                </div>
                <div class="card-body p-0">
                    <div class="row mb-3 p-3">
                        <div class="col-md-6">
                            <select id="employeeDropdown" class="form-select select2 w-auto"
                                aria-label="Select Employee List">
                                <option selected value="">Select Your Employee</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->emp_id }}">{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <table class="table text-center mb-0">
                        <thead style="background-color: #042B48; color: #fff;">
                            <tr>
                                <th class="text-uppercase">Status</th>
                                <th class="text-uppercase">Bank Details</th>
                                <th class="text-uppercase">Basic Details</th>
                                <th class="text-uppercase">PF-ESIC Details</th>
                                <th class="text-uppercase">Document Details</th>

                        </thead>
                        <tbody id="summaryReportBody">
                            <tr>
                                <td>Last Payroll</td>
                                <td colspan="5">Select an employee to view details</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('file_script')
<script>
    $(document).ready(function() {
        $('#employeeDropdown').change(function() {
            let empId = $(this).val();
            if (empId) {
                $.ajax({
                    url: '{{ url("/get-employee-data") }}',
                    method: 'POST',
                    data: {
                        recruiter_id: empId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        let rows = `
                            <tr style="background-color: #039be5; color: #fff;">
                                <td>Previous Month</td>
                                <td>${response.previous_month.bank}</td>
                                <td>${response.previous_month.basic}</td>
                                <td>${response.previous_month.pf_esic}</td>
                                <td>${response.previous_month.documents}</td>
                               
                            </tr>
                            <tr style="background-color: #e53935; color: #fff;">
                                <td>Current Month</td>
                                <td>${response.current_month.bank}</td>
                                <td>${response.current_month.basic}</td>
                                <td>${response.current_month.pf_esic}</td>
                                <td>${response.current_month.documents}</td>
                               
                            </tr>
                            <tr>
                                <td>All-Time</td>
                                <td>${response.all_time.bank}</td>
                                <td>${response.all_time.basic}</td>
                                <td>${response.all_time.pf_esic}</td>
                                <td>${response.all_time.documents}</td>
                               
                            </tr>
                        `;
                        $('#summaryReportBody').html(rows);
                    }
                });
            }
        });
    });
</script>
<style>
    /* Hover effect for table rows */
    .hoverable-row:hover {
        background-color: #042B48;
        color: #fff;
    }
</style>
@endsection