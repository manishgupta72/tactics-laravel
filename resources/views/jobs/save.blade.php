@extends('IncludesFiles.Master2')

<div class="offcanvas-body">
    <div class="card border-0">
        <div class="card-body">
            <form id="JobOpeningForm" enctype="multipart/form-data">
                <div class="row g-3">
                    <!-- Job Title -->
                    <div class="col-md-6">
                        <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="job_title" name="job_title" placeholder="Enter Job Title"
                            value="{{ isset($detail->job_title) ? $detail->job_title : '' }}">
                    </div>

                    <!-- Job Language -->
                    <div class="col-md-6">
                        <label for="job_language" class="form-label">Job Language <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="job_language" name="job_language" placeholder="Enter Job Language"
                            value="{{ isset($detail->job_language) ? $detail->job_language : '' }}">
                    </div>
                    <!-- Job Location -->
                    <div class="col-md-6">
                        <label for="job_location" class="form-label">Job Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="job_location" name="job_location" placeholder="Enter Job Location"
                            value="{{ isset($detail->job_location) ? $detail->job_location : '' }}">
                    </div>

                    <!-- Experience -->
                    <div class="col-md-6">
                        <label for="job_experience" class="form-label">Experience <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="job_experience" name="job_experience" placeholder="Enter Experience"
                            value="{{ isset($detail->job_experience) ? $detail->job_experience : '' }}">
                    </div>

                    <!-- Salary -->
                    <div class="col-md-6">
                        <label for="job_salary" class="form-label">Salary <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="job_salary" name="job_salary" placeholder="Enter Salary"
                            value="{{ isset($detail->job_salary) ? $detail->job_salary : '' }}">
                    </div>

                    <!-- job opening -->
                    <div class="col-md-6">
                        <label for="job_opening" class="form-label">Job Openings <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="job_opening" name="job_opening" placeholder="Enter job openings"
                            value="{{ isset($detail->job_opening) ? $detail->job_opening : '' }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label" for="job_status_switch">Status <span class="text-danger">*</span></label>
                        <div class="form-group mt-2">
                            <div class="d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    class="modern-toggle-switch"
                                    id="job_status_switch"
                                    name="job_status"
                                    value="1"
                                    {{ isset($detail->job_status) && $detail->job_status == 1 ? 'checked' : '' }}>
                                <label class="modern-toggle-label" for="job_status_switch"></label>
                                <span class="ms-3 status-text">
                                    {{ isset($detail->job_status) && $detail->job_status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>



                    <!-- Hidden ID Field -->
                    <input type="hidden" name="jobs_id" value="{{ isset($detail->jobs_id) ? $detail->jobs_id : 0 }}">
                </div>

                <!-- Save Button -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" class="btn btn-primary create_jobopening_page_data">
                            {{ isset($detail->jobs_id) ? "Update" : "Save" }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    /* Switch Container */
    .modern-toggle-switch {
        display: none;
    }

    /* Toggle Slider */
    .modern-toggle-label {
        width: 50px;
        height: 25px;
        background-color: #ddd;
        border-radius: 50px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
    }

    .modern-toggle-label::before {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: #fff;
        border-radius: 50%;
        top: 50%;
        left: 3px;
        transform: translateY(-50%);
        transition: all 0.3s ease-in-out;
    }

    /* Checked State */
    .modern-toggle-switch:checked+.modern-toggle-label {
        background-color: #4caf50;
    }

    .modern-toggle-switch:checked+.modern-toggle-label::before {
        transform: translate(26px, -50%);
    }

    /* Status Text Styling */
    .status-text {
        font-size: 14px;
        font-weight: bold;
        color: #4caf50;
        transition: color 0.3s ease-in-out;
    }

    .modern-toggle-switch:not(:checked)~.status-text {
        color: #888;
        font-weight: normal;
    }
</style>
<script>
    $(document).on("change", "#job_status_switch", function() {
        const statusText = $(this).siblings(".status-text");
        if ($(this).is(":checked")) {
            statusText.text("Active").css("color", "#4caf50");
        } else {
            statusText.text("Inactive").css("color", "#888");
        }
    });
</script>