<div class="card border-0">
    <div class="card-body">
        <form id="AdminFrms" enctype="multipart/form-data">
            <div class="row g-3">
                <!-- Company Name -->
                <div class="col-md-6">
                    <label for="comp_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="comp_name" placeholder="Enter Company Name"
                        name="comp_name" value="{{ isset($detail) ? $detail->comp_name : '' }}">

                </div>

                <!-- Company Location -->
                <div class="col-md-6">
                    <label for="comp_location" class="form-label">Company Location <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="comp_location" name="comp_location"
                        placeholder="Enter Company Location" value="{{ isset($detail) ? $detail->comp_location : '' }}">
                </div>


                <!-- Company Status -->
                <div class="row form-group pb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="comp_status" class="form-label">Company Status <span
                                    class="text-danger">*</span></label>
                            <select name="comp_status" id="comp_status" class="form-control select2">
                                <option value="">Select Status</option>
                                @foreach (config('constant.STATUS') as $key => $status)
                                    <option value="{{ $key }}" {{ isset($detail->comp_status) && $detail->comp_status == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <!-- Hidden ID Field -->
                <input type="hidden" name="comp_id" value="{{isset($detail) ? $detail->comp_id : 0 }}">
            </div>

            <div class="row mt-4">
                <div class="col text-center">
                    <button type="button" class="btn btn-primary create_company_page_data">
                        {{ isset($detail) && $detail->comp_id ? 'Update' : 'Save' }}
                    </button>

                </div>
            </div>
        </form>
    </div>
</div>
