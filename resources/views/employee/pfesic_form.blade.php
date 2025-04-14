<div class="container">
    <form id="PFESICForm">
        <input type="hidden" name="emp_id" value="{{ $emp_id ?? '' }}">

        <div class="row">
            <!-- PF Number -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_PF_no">PF Number </label>
                <input type="text" class="form-control" id="emp_PF_no" name="emp_PF_no" placeholder="Enter PF Number" value="{{ $emp_pfesic_details->emp_PF_no ?? '' }}">
            </div>

            <!-- ESIC Number -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_ESIC_no">ESIC Number </label>
                <input type="text" class="form-control" id="emp_ESIC_no" name="emp_ESIC_no" placeholder="Enter ESIC Number" value="{{ $emp_pfesic_details->emp_ESIC_no ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- ESIC State as Text Input -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_esic_State" class="form-label">ESIC State </label>
                <input type="text" class="form-control" id="emp_esic_State" name="emp_esic_State"
                    placeholder="Enter ESIC State"
                    value="{{ isset($emp_pfesic_details->emp_esic_State) ? $emp_pfesic_details->emp_esic_State : '' }}">
            </div>
        </div>



        <!-- Save Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button type="button" class="btn btn-primary SavePFESICDetails">
                    {{ isset($emp_pfesic_details->emp_id) ? "Update" : "Save" }}
                </button>
            </div>
        </div>
    </form>
</div>