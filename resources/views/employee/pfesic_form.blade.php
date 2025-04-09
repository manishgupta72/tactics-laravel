<div class="container">
    <form id="PFESICForm">
        <input type="hidden" name="emp_id" value="{{ $emp_id ?? '' }}">

        <div class="row">
            <!-- PF Number -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_PF_no">PF Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emp_PF_no" name="emp_PF_no" placeholder="Enter PF Number" value="{{ $emp_pfesic_details->emp_PF_no ?? '' }}">
            </div>

            <!-- ESIC Number -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_ESIC_no">ESIC Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="emp_ESIC_no" name="emp_ESIC_no" placeholder="Enter ESIC Number" value="{{ $emp_pfesic_details->emp_ESIC_no ?? '' }}">
            </div>
        </div>

        <div class="row">
            <!-- ESIC State -->
            <div class="form-group col-md-6 mb-3">
                <label for="comp_status" class="form-label">ESIC State <span class="text-danger">*</span></label><br>
                <select class="form-control select2 w-100" id="emp_esic_State" name="emp_esic_State" style="width: 100%;">
                    <option value="">Select State</option>
                    @foreach (config('constant.STATES') as $key => $state)
                    <option value="{{ $key }}" {{ isset($emp_pfesic_details->emp_esic_State) && $emp_pfesic_details->emp_esic_State == $key ? 'selected' : '' }}>
                        {{ $state }}
                    </option>
                    @endforeach
                </select>
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