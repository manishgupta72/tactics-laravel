<div class="offcanvas offcanvas-end" tabindex="-1" id="customformaddModal" aria-labelledby="customformaddModalLabel"
    style="width:700px" data-bs-backdrop="static">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="customformaddModalLabel">Add Feature Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <hr class="border border-dark">
    <div class="offcanvas-body py-0">
        <form id="CompanyCustomFromFrm">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mt-4">
                            <label class="col-form-label" for="formGroupExampleInput">Select Location</label>
                            <select class="form-control mb-3 select2" data-plugin-selectTwo name="location_id"
                                id="location_id">
                                <option value="">Select Location</option>
                                @if ($loc_list->count() > 0)
                                    @foreach ($loc_list as $key => $item)
                                        <option value="{{ $item->location_id }}">
                                            {{ $item->location_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mt-4">
                            <label class="col-form-label" for="formGroupExampleInput">Select Form Type</label>
                            <select class="form-control mb-3 select2" data-plugin-selectTwo name="form_type"
                                id="form_type">
                                <option value="">Select Form Type</option>
                                @if (check_valid_array($form_type_list))
                                    @foreach ($form_type_list as $key => $item)
                                        <option value="{{ $key }}">
                                            {{ $item }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mt-4">
                            <label class="col-form-label" for="formGroupExampleInput">Form Name</label>
                            <input type="text" class="form-control" name="form_name" id="form_name" placeholder="Form Name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mt-4">
                            <label class="col-form-label" for="formGroupExampleInput">Number of Form Entry</label>
                            <input type="text" class="form-control" name="no_of_form" id="no_of_form" placeholder="Form Name" onkeypress="return isNumberKey(event);">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="company_id" id="company_id" value="0" />
                <input type="hidden" name="customization_form_id" id="customization_form_id" value="0" />
                <div class="col-md-12">
                    <div class="py-2 sticky-bottom text-center">
                        <button type="submit"
                            class="btn btn-mw btn-primary btn-lg me-2 create_custom_form_page_data">Submit</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
