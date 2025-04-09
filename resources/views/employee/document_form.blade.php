<div class="container">
    <form id="DocumentForm" enctype="multipart/form-data">
        <input type="hidden" name="emp_id" value="{{ $emp_id ?? '' }}">

        <div class="row">
            <!-- Document Type -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_doc_type" class="form-label">Document Type</label><br>
                <select class="form-control" id="emp_doc_type" name="emp_doc_type">
                    <option value="">Select Document Type</option>
                    @foreach ($documentTypes as $type)
                    <option value="{{ $type->master_data_name }}">{{ $type->master_data_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- File Upload -->
            <div class="form-group col-md-6 mb-3">
                <label for="emp_file">Upload Document</label>
                <input type="file" class="form-control" id="emp_file" name="emp_file">
            </div>
        </div>

        <!-- Save Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button type="button" class="btn btn-primary SaveDocumentDetails">Save</button>
            </div>
        </div>
    </form>

    <!-- Document List -->
    <div id="documentList" class="mt-4"></div>
</div>