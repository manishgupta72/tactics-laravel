<div class="offcanvas-body">
    <div class="card border-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Company Name:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $company->comp_name }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Location:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $company->comp_location }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Status:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ config('constant.STATUS')[$company->comp_status] }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Created At:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $company->created_at }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Updated At:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $company->updated_at }}</p>
                </div>
            </div>
        </div>
    </div>
</div>