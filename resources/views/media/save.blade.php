@extends('IncludesFiles.Master2')

<div class="offcanvas-body">
    <div class="card border-0">
        <div class="card-body">
            <form id="AdminFrms" enctype="multipart/form-data">
                <div class="row g-3">
                    <!-- Media Title -->
                    <div class="col-md-6">
                        <label for="mf_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mf_title" name="mf_title" placeholder="Enter Title"
                            value="{{ isset($detail->mf_title) ? $detail->mf_title : '' }}">
                    </div>

                    <!-- Media Type -->
                    <div class="col-md-6">
                        <label for="mf_type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="mf_type" name="mf_type">
                            <option value="">Select Media Type</option>
                            <option value="Slider" {{ isset($detail->mf_type) && $detail->mf_type == 'Slider' ? 'selected' : '' }}>Slider</option>
                            <option value="Clients" {{ isset($detail->mf_type) && $detail->mf_type == 'Clients' ? 'selected' : '' }}>Clients</option>
                            <option value="About1" {{ isset($detail->mf_type) && $detail->mf_type == 'About1' ? 'selected' : '' }}>About 1</option>
                            <option value="About2" {{ isset($detail->mf_type) && $detail->mf_type == 'About2' ? 'selected' : '' }}>About 2</option>
                            <option value="HomeAbout" {{ isset($detail->mf_type) && $detail->mf_type == 'HomeAbout' ? 'selected' : '' }}>Home About</option>
                            <option value="Contact" {{ isset($detail->mf_type) && $detail->mf_type == 'Contact' ? 'selected' : '' }}>Contact</option>
                            <option value="Contract Staffing" {{ isset($detail->mf_type) && $detail->mf_type == 'Contract Staffing' ? 'selected' : '' }}>Contract Staffing</option>
                            <option value="Permanent Staffing" {{ isset($detail->mf_type) && $detail->mf_type == 'Permanent Staffing' ? 'selected' : '' }}>Permanent Staffing</option>
                            <option value="Payroll Outsourcing" {{ isset($detail->mf_type) && $detail->mf_type == 'Payroll Outsourcing' ? 'selected' : '' }}>Payroll Outsourcing</option>
                        </select>
                    </div>


                    <!-- Media URL -->
                    <div class="col-md-12">
                        <label for="mf_url" class="form-label">URL</label>
                        <input type="text" class="form-control" id="mf_url" name="mf_url" placeholder="Enter Media URL"
                            value="{{ isset($detail->mf_url) ? $detail->mf_url : '' }}">
                    </div>

                    <!-- Media Image -->
                    <div class="col-md-6">
                        <label for="mf_image" class="form-label">Media Image</label>
                        <input type="file" class="form-control" id="mf_image" name="mf_image" accept="image/*">
                        @if (isset($detail->mf_image))
                            <img src="{{ asset('assets/media_files/' . $detail->mf_image) }}" alt="Media Image"
                                class="img-thumbnail mt-2" style="max-width: 150px;height:100px">
                        @endif
                    </div>

                    <!-- Hidden ID Field -->
                    <input type="hidden" name="id" value="{{ isset($detail->mf_id) ? $detail->mf_id : 0 }}">
                </div>

                <!-- Save Button -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" class="btn btn-primary create_media_page_data">
                            {{ isset($detail->mf_id) ? "Update" : "Save" }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script>
        $(document).ready(function () {
            // Initialize Summernote or other JS plugins if needed
        });
    </script>
@endpush