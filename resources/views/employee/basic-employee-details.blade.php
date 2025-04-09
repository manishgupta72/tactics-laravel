<div class="container">
    <form id="BasicEmployeeForm" enctype="multipart/form-data">

        <input type="hidden" name="emp_id" value="{{ request()->segment(3) }}">
        <input type="hidden" name="id" value="{{ $emp_basic_details->id ?? '' }}">


        <div class="row">
            <!-- Father/Husband Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="father_husband_name">Father/Husband Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="father_husband_name" name="father_husband_name" placeholder="Enter Name" value="{{ $emp_basic_details->father_husband_name ?? '' }}">
            </div>

            <!-- Father/Husband DOB -->
            <div class="form-group col-md-6 mb-3">
                <label for="father_husband_dob">Father/Husband DOB<span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="father_husband_dob" name="father_husband_dob" value="{{ $emp_basic_details->father_husband_dob ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Mother Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="mother_name">Mother Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Enter Name" value="{{ $emp_basic_details->mother_name ?? '' }}">
            </div>

            <!-- Mother DOB -->
            <div class="form-group col-md-6 mb-3">
                <label for="mother_dob">Mother DOB<span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="mother_dob" name="mother_dob" value="{{ $emp_basic_details->mother_dob ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Emergency Contact -->
            <div class="form-group col-md-6 mb-3">
                <label for="emergency_contact_no">Emergency Contact Number<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emergency_contact_no" name="emergency_contact_no" placeholder="Enter Emergency Contact" value="{{ $emp_basic_details->emergency_contact_no ?? '' }}">
            </div>

            <!-- Emergency Contact Relation -->
            <div class="form-group col-md-6 mb-3">
                <label for="emergency_contact_relation">Emergency Contact Relation<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation" placeholder="Enter Relation" value="{{ $emp_basic_details->emergency_contact_relation ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Gender -->
            <div class="form-group col-md-6 mb-3">
                <label for="gender">Gender <span class="text-danger">*</span></label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ (isset($emp_basic_details->gender) && $emp_basic_details->gender == 'Male') ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ (isset($emp_basic_details->gender) && $emp_basic_details->gender == 'Female') ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ (isset($emp_basic_details->gender) && $emp_basic_details->gender == 'Other') ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Date of Birth -->
            <div class="form-group col-md-6 mb-3">
                <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="dob" name="dob" value="{{ $emp_basic_details->dob ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Email -->
            <div class="form-group col-md-6 mb-3">
                <label for="email">Email<span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $emp_basic_details->email ?? '' }}">
            </div>

            <!-- PAN Card Number -->
            <div class="form-group col-md-6 mb-3">
                <label for="pan_card_no">PAN Card Number<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pan_card_no" name="pan_card_no" placeholder="Enter PAN Card Number" value="{{ $emp_basic_details->pan_card_no ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Date of Joining -->
            <div class="form-group col-md-6 mb-3">
                <label for="date_of_joining">Date of Joining<span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date_of_joining" name="date_of_joining" value="{{ $emp_basic_details->date_of_joining ?? '' }}">
            </div>

            <!-- Designation -->
            <div class="form-group col-md-6 mb-3">
                <label for="designation">Designation<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter Designation" value="{{ $emp_basic_details->designation ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Address as per Aadhar -->
            <div class="form-group col-md-6 mb-3">
                <label for="address_as_per_aadhar">Address as per Aadhar</label>
                <textarea class="form-control" id="address_as_per_aadhar" name="address_as_per_aadhar" rows="2" placeholder="Enter Address">{{ $emp_basic_details->address_as_per_aadhar ?? '' }}</textarea>
            </div>

            <!-- Present Address -->
            <div class="form-group col-md-6 mb-3">
                <label for="present_address">Present Address</label>
                <textarea class="form-control" id="present_address" name="present_address" rows="2" placeholder="Enter Present Address">{{ $emp_basic_details->present_address ?? '' }}</textarea>
            </div>
        </div>

        <div class="row">
            <!-- Nominee Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="nominee_name">Nominee Name</label>
                <input type="text" class="form-control" id="nominee_name" name="nominee_name" placeholder="Enter Nominee Name" value="{{ $emp_basic_details->nominee_name ?? '' }}">
            </div>

            <!-- Religion -->
            <div class="form-group col-md-6 mb-3">
                <label for="religion">Religion</label>
                <input type="text" class="form-control" id="religion" name="religion" placeholder="Enter Religion" value="{{ $emp_basic_details->religion ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Spouse Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="spouse_name">Spouse Name</label>
                <input type="text" class="form-control" id="spouse_name" name="spouse_name" placeholder="Enter Spouse Name" value="{{ $emp_basic_details->spouse_name ?? '' }}">
            </div>

            <!-- Spouse DOB -->
            <div class="form-group col-md-6 mb-3">
                <label for="spouse_dob">Spouse DOB</label>
                <input type="date" class="form-control" id="spouse_dob" name="spouse_dob" value="{{ $emp_basic_details->spouse_dob ?? '' }}">
            </div>
        </div>
        <div class="row">
            <!-- marital_status Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="marital_status">Marital Status</label>
                <input type="text" class="form-control" id="marital_status" name="marital_status" placeholder="Enter Marital Status" value="{{ $emp_basic_details->marital_status ?? '' }}">
            </div>

            <!-- Spouse DOB -->
            <div class="form-group col-md-6 mb-3">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="{{ $emp_basic_details->age ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- First Child Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="first_child_name">First Child Name</label>
                <input type="text" class="form-control" id="first_child_name" name="first_child_name" placeholder="Enter First Child Name" value="{{ $emp_basic_details->first_child_name ?? '' }}">
            </div>

            <!-- First Child DOB -->
            <div class="form-group col-md-6 mb-3">
                <label for="first_child_dob">First Child DOB</label>
                <input type="date" class="form-control" id="first_child_dob" name="first_child_dob" value="{{ $emp_basic_details->first_child_dob ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- Second Child Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="second_child_name">Second Child Name</label>
                <input type="text" class="form-control" id="second_child_name" name="second_child_name" placeholder="Enter Second Child Name" value="{{ $emp_basic_details->second_child_name ?? '' }}">
            </div>

            <!-- Second Child DOB -->
            <div class="form-group col-md-6 mb-3">
                <label for="second_child_dob">Second Child DOB</label>
                <input type="date" class="form-control" id="second_child_dob" name="second_child_dob" value="{{ $emp_basic_details->second_child_dob ?? '' }}">
            </div>
        </div>
        <div class="row">
            <!-- Second Child Name -->
            <div class="form-group col-md-6 mb-3">
                <label for="nth_pm">Nth Pm Name</label>
                <input type="text" class="form-control" id="nth_pm" name="nth_pm" placeholder="Enter Nth Pm Name" value="{{ $emp_basic_details->nth_pm ?? '' }}">
            </div>


        </div>
        <!-- Save Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button type="button" class="btn btn-primary BasicEmployeeForm">
                    {{ isset($emp_basic_details->emp_id) ? "Update" : "Save" }}
                </button>
            </div>
        </div>

    </form>
</div>