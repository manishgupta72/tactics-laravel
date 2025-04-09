@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>

                        <form id="ServiceFrm">
                            <div class="row form-group p-4">
                                <div class="col-lg-4 pb-0">
                                    @if (HasPermission('S1') == 'true')
                                    <!-- Add Service Button -->
                                    <button type="button" class="btn btn-primary w-50 add-service-btn" data-id="">+ Add Service</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="row p-4">
                            <table class="table table-bordered table-striped mb-0" id="ell-table">
                                <thead>
                                    <tr>
                                        <th>Service Name</th>
                                        <th>Thumbnail</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic Rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas for Adding/Editing Service -->
<div class="offcanvas offcanvas-end responsive-offcanvas" tabindex="-1" id="saveServiceOffcanvas" aria-labelledby="saveServiceOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="saveServiceOffcanvasLabel">Add/Edit Service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-center align-items-center">
        <div id="saveServiceContent" class="offcanvas-content">
            <p>Loading...</p>
        </div>
    </div>
</div>

<!-- Offcanvas for Viewing Service Details -->
<div class="offcanvas offcanvas-end responsive-offcanvas" tabindex="-1" id="serviceDetailsOffcanvas" aria-labelledby="serviceDetailsOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="serviceDetailsOffcanvasLabel">Service Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex justify-content-center align-items-center">
        <div id="serviceDetailsContent" class="offcanvas-content">
            <p>Loading...</p>
        </div>
    </div>
</div>

<style>
    /* Default styles for offcanvas */
    .responsive-offcanvas {
        width: 40% !important;
        /* Default width for larger screens */
        transition: width 0.3s ease-in-out;
        /* Smooth transitions */
    }

    .offcanvas-content {
        width: 100%;
        max-width: 600px;
        /* Maximum content width */
    }

    /* Adjust for tablets */
    @media (max-width: 1024px) {
        .responsive-offcanvas {
            width: 60% !important;
            /* Wider for tablets */
        }

        .offcanvas-content {
            max-width: 90%;
            /* Stretch content slightly more */
        }
    }

    /* Adjust for mobile */
    @media (max-width: 768px) {
        .responsive-offcanvas {
            width: 100% !important;
            /* Full-screen width for mobile */
        }

        .offcanvas-content {
            max-width: 100%;
            /* Allow full width */
        }

        .offcanvas-header {
            padding: 0.75rem 1rem;
            /* Compact header for smaller screens */
        }

        .offcanvas-body {
            padding: 1rem;
            /* Adjust body padding */
        }
    }
</style>

@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/services/services.js') }}"></script>
@endsection

@push('footer_scripts')
<script>
    var list_route = '{{ $list_route }}';
    var save_route = '{{ $save_route }}';
    var edit_route = '{{ $edit_route }}';
    var del_route = '{{ $del_route }}';
    var view_route = '{{ $view_route }}';
</script>
@endpush