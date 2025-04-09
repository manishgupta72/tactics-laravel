@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row mt-4 gy-4">

            <!-- LEFT: Individual Form -->
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h5 class="mb-0">{{ isset($detail->emp_id) ? 'Edit Employee' : 'Add Employee' }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="AdminFrms" enctype="multipart/form-data">
                            <div class="row g-3">

                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="emp_first_name"
                                        placeholder="Enter First Name"
                                        value="{{ isset($detail->full_name) ? explode(' ', $detail->full_name)[0] : '' }}">
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="emp_last_name"
                                        placeholder="Enter Last Name"
                                        value="{{ isset($detail->full_name) ? explode(' ', $detail->full_name)[1] ?? '' : '' }}">
                                </div>

                                <!-- Mobile -->
                                <div class="col-md-6">
                                    <label class="form-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="emp_mobile"
                                        placeholder="Enter Mobile"
                                        value="{{ $detail->emp_mobile ?? '' }}">
                                </div>

                                <!-- Aadhaar -->
                                <div class="col-md-6">
                                    <label class="form-label">Aadhaar <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="emp_aadhar"
                                        placeholder="Enter Aadhaar"
                                        value="{{ $detail->emp_aadhar ?? '' }}"
                                        {{ isset($detail->emp_aadhar) ? 'disabled' : '' }}>
                                </div>

                                <!-- Company -->
                                <div class="col-md-6">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="emp_company_id">
                                        <option value="">Select Company</option>
                                        @foreach ($company_list as $company)
                                        <option value="{{ $company->comp_id }}"
                                            {{ isset($detail->emp_company_id) && $detail->emp_company_id == $company->comp_id ? 'selected' : '' }}>
                                            {{ $company->comp_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="emp_status">
                                        <option value="">Select Status</option>
                                        @foreach (config('constant.STATUS') as $key => $status)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->emp_status) && $detail->emp_status == $key ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Hidden ID -->
                                <input type="hidden" name="id" value="{{ $detail->emp_id ?? 0 }}">

                                <!-- Submit Button -->
                                <div class="col-12 text-end mt-3">
                                    <button type="button" class="btn btn-primary create_employee_page_data px-4">
                                        {{ isset($detail->emp_id) ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Bulk Upload -->
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Bulk Upload Employees</h5>
                        <a href="{{ asset('assets/employee.xlsx') }}" class="btn btn-danger btn-sm">Download Format</a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('employee.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Select Company <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="emp_company_id" required>
                                    <option value="">Select Company</option>
                                    @foreach ($company_list as $company)
                                    <option value="{{ $company->comp_id }}">{{ $company->comp_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                                <input type="file" name="employee_excel" class="form-control" required accept=".xlsx,.xls">
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">Upload & Download</button>
                            </div>
                        </form>

                        @if(session('upload_inserted') !== null)
                        <div class="alert alert-info mt-4">
                            ✅ <strong>{{ session('upload_inserted') }}</strong> inserted<br>
                            ⚠️ <strong>{{ session('upload_skipped') }}</strong> Aadhaar duplicates skipped
                        </div>

                        @if(session('download_filename'))
                        <script>
                            window.onload = function() {
                                const filename = "{{ session('download_filename') }}";
                                const url = "{{ url('backend/employee/download-excel') }}?file=" + encodeURIComponent(filename);
                                window.location.href = url;
                            };
                        </script>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal for duplicate Aadhaar -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-sm">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Duplicate Aadhaar Detected</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The Aadhaar number already exists. Do you want to deactivate the existing employee and continue?</p>
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td id="existingName"></td>
                    </tr>
                    <tr>
                        <th>Emp Code</th>
                        <td id="existingEmpCode"></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td id="existingMobile"></td>
                    </tr>
                    <tr>
                        <th>Aadhaar</th>
                        <td id="existingAadhaar"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="existingStatus"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="proceedButton">Proceed</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/employee/employee.js') }}"></script>
@endsection

@push('footer_scripts')
<script>
    const save_route = '{{ $save_route ?? '
    ' }}';

    $("#proceedButton").on("click", function() {
        let formData = $("#AdminFrms").serialize() + "&proceed=1";

        $.post(save_route, formData, function(response) {
            $("#warningModal").modal("hide");
            Swal.fire("Success", response.msg, "success").then(() => {
                if (response.TargetURL) window.location.href = response.TargetURL;
            });
        }).fail(function(xhr) {
            Swal.fire("Error", xhr.responseJSON?.msg || "Something went wrong.", "error");
        });
    });
</script>
@endpush