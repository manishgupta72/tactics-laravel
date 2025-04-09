@extends('IncludesFiles.Master2')

<!-- Offcanvas for Adding/Editing Service -->
<div class="offcanvas-body">
    <div class="card border-0">
        <div class="card-body mt-5">
            <form id="AdminFrms" enctype="multipart/form-data">
                <div class="row g-3">
                    <!-- Service Name -->
                    <div class="col-md-6">
                        <label for="ser_name" class="form-label">Service Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ser_name" name="ser_name" placeholder="Enter Service Name"
                            value="{{ isset($detail->ser_name) ? $detail->ser_name : '' }}">
                    </div>



                    <!-- Short Description -->
                    <div class="col-md-6">
                        <label for="ser_short_disc" class="form-label">Short Description</label>
                        <input type="text" class="form-control" id="ser_short_disc" name="ser_short_disc" placeholder="Enter Short Description"
                            value="{{ isset($detail->ser_short_disc) ? $detail->ser_short_disc : '' }}">
                    </div>

                    <!-- About Service -->
                    <div class="col-md-12">
                        <label for="ser_about" class="form-label">About Service</label>
                        <textarea class="form-control" id="ser_about" name="ser_about" placeholder="Enter About Service">{{ isset($detail->ser_about) ? $detail->ser_about : '' }}</textarea>
                    </div>

                    <!-- Full Image -->
                    <div class="col-md-6">
                        <label for="ser_full_img" class="form-label">Full Image</label>
                        <input type="file" class="form-control" id="ser_full_img" name="ser_full_img" accept="image/*">
                        @if (isset($detail->ser_full_img))
                        <img src="{{ asset('assets/services/' . $detail->ser_full_img) }}" alt="Full Image" class="img-thumbnail mt-2" style="max-width: 150px; height: 100px;">
                        @endif
                    </div>

                    <!-- Service Thumbnail Image -->
                    <div class="col-md-6">
                        <label for="ser_thum_img" class="form-label">Thumbnail Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="ser_thum_img" name="ser_thum_img" accept="image/*">
                        @if (isset($detail->ser_thum_img))
                        <img src="{{ asset('assets/services/' . $detail->ser_thum_img) }}" alt="Thumbnail Image" class="img-thumbnail mt-2" style="max-width: 150px; height: 100px;">
                        @endif
                    </div>

                    <!-- Hidden ID Field -->
                    <input type="hidden" name="id" value="{{ isset($detail->ser_id) ? $detail->ser_id : 0 }}">
                </div>

                <!-- Save Button -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" class="btn btn-primary create_service_page_data">
                            {{ isset($detail->ser_id) ? "Update" : "Save" }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Summernote JS -->
@push('footer_scripts')
<script>
    $(document).ready(function() {
        // Initialize Summernote for "ser_about"
        $('#ser_about').summernote({
            height: 150, // Set editor height
            placeholder: 'Enter About Service...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush