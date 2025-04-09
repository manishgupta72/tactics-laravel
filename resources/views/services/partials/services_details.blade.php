<div class="offcanvas-body">
    <div class="card border-0">
        <div class="card-body">
            <!-- ser_name Description -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Service Name:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $service->ser_name }}</p>
                </div>
            </div>
            <!-- Service Short Description -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Short Description:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $service->ser_short_disc }}</p>
                </div>
            </div>

            <!-- Service About -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>About Service:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $service->ser_about }}</p>
                </div>
            </div>

            <!-- Thumbnail Image -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Thumbnail Image:</strong></p>
                </div>
                <div class="col-md-8">
                    @if ($service->ser_thum_img)
                    <img src="{{ asset('uploads/services/' . $service->ser_thum_img) }}" alt="Thumbnail Image" class="img-fluid" style="max-width: 150px;">
                    @else
                    <p class="mb-0">No thumbnail image available</p>
                    @endif
                </div>
            </div>

            <!-- Full Image -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Full Image:</strong></p>
                </div>
                <div class="col-md-8">
                    @if ($service->ser_full_img)
                    <img src="{{ asset('uploads/services/' . $service->ser_full_img) }}" alt="Full Image" class="img-fluid" style="max-width: 150px;">
                    @else
                    <p class="mb-0">No full image available</p>
                    @endif
                </div>
            </div>

            <!-- Added On -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Added On:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ date('d-m-Y H:i:s', $service->addedon) }}</p>
                </div>
            </div>

            <!-- Updated On -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Updated On:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ date('d-m-Y H:i:s', $service->updatedon) }}</p>
                </div>
            </div>

            <!-- Created At -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Created At:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $service->created_at }}</p>
                </div>
            </div>

            <!-- Updated At -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Updated At:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $service->updated_at }}</p>
                </div>
            </div>
        </div>
    </div>
</div>