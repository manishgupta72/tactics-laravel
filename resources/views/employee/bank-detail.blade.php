<div class="container">
    <form id="BankEmployeeForm">
        <input type="hidden" name="emp_id" value="{{ request()->segment(3) }}">

        <div class="row">
            <!-- Full Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_bank_fullname">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emp_bank_fullname" name="emp_bank_fullname" placeholder="Enter Full Name" value="{{ $emp_bank_details->emp_bank_fullname ?? '' }}">
            </div>

            <!-- Bank Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_bank_name">Bank Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emp_bank_name" name="emp_bank_name" placeholder="Enter Bank Name" value="{{ $emp_bank_details->emp_bank_name ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Account Number -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_account_no">Account Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emp_account_no" name="emp_account_no" placeholder="Enter Account Number" value="{{ $emp_bank_details->emp_account_no ?? '' }}">
            </div>

            <!-- IFSC Code -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_ifsc_code">IFSC Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emp_ifsc_code" name="emp_ifsc_code" placeholder="Enter IFSC Code" value="{{ $emp_bank_details->emp_ifsc_code ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Branch -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_branch">Branch </label>
                <input type="text" class="form-control" id="emp_branch" name="emp_branch" placeholder="Enter Branch" value="{{ $emp_bank_details->emp_branch ?? '' }}">
            </div>
        </div>

        <!-- Save Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button type="button" class="btn btn-primary SaveBankDetails">
                    {{ isset($emp_bank_details->emp_id) ? "Update" : "Save" }}
                </button>
            </div>
        </div>
    </form>
</div>