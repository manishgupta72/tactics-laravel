<div class="offcanvas-body">
    <div class="card border-0">
        <div class="card-body">
            <!-- Media Title -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Media Title:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $media->mf_title }}</p>
                </div>
            </div>

            <!-- Media Type -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Media Type:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $media->mf_type }}</p>
                </div>
            </div>

            <!-- Media URL -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Media URL:</strong></p>
                </div>
                <div class="col-md-8">
                    @if ($media->mf_url)
                        <a href="{{ $media->mf_url }}" target="_blank">{{ $media->mf_url }}</a>
                    @else
                        <p class="mb-0">No URL available</p>
                    @endif
                </div>
            </div>

            <!-- Media Image -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Media Image:</strong></p>
                </div>
                <div class="col-md-8">
                    @if ($media->mf_image)
                        <img src="{{ asset('uploads/media_files/' . $media->mf_image) }}" alt="Media Image" class="img-fluid" style="max-width: 150px;">
                    @else
                        <p class="mb-0">No image available</p>
                    @endif
                </div>
            </div>

            <!-- Added On -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Added On:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ date('d-m-Y H:i:s', $media->addedon) }}</p>
                </div>
            </div>

            <!-- Updated On -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Updated On:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ date('d-m-Y H:i:s', $media->updatedon) }}</p>
                </div>
            </div>

            <!-- Created At -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Created At:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $media->created_at }}</p>
                </div>
            </div>

            <!-- Updated At -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="text-muted mb-0"><strong>Updated At:</strong></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">{{ $media->updated_at }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
