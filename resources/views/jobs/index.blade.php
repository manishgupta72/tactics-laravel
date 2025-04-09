@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>

                        <form id="FilterForm">
                            <div class="row form-group p-4">
                                <div class="col-lg-4 pb-0">
                                    @if (HasPermission('J1') == 'true')
                                    <!-- Add Job Opening Button -->
                                    <button type="button" class="btn btn-primary w-50 add-jobopening-btn" data-id="">+ Add Job Opening</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="row p-4">
                            <table class="table table-bordered table-striped mb-0" id="jobopening-table">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                     
                                        <th>Openings</th>
                                        <th>Salary</th>
                                        <th>Status</th>
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

<!-- Offcanvas for Adding/Editing Job Opening -->
<div class="offcanvas offcanvas-end responsive-offcanvas" tabindex="-1" id="saveJobOpeningOffcanvas" aria-labelledby="saveJobOpeningOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="saveJobOpeningOffcanvasLabel">Add/Edit Job Opening</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div id="saveJobOpeningContent" class="offcanvas-content">
            <p>Loading...</p>
        </div>
    </div>
</div>
<style>
    /* Default styles for offcanvas */
    .responsive-offcanvas {
        width: 40% !important;
        /* Default width for large screens */
        transition: width 0.3s ease-in-out;
        /* Smooth transition for width change */
    }

    .offcanvas-content {
        width: 100%;
        max-width: 500px;
        /* Limit the content width */
    }

    /* Adjust width for tablets */
    @media (max-width: 1024px) {
        .responsive-offcanvas {
            width: 60% !important;
        }

        .offcanvas-content {
            max-width: 90%;
            /* Adjust content width for tablets */
        }
    }

    /* Full width for mobile screens */
    @media (max-width: 768px) {
        .responsive-offcanvas {
            width: 100% !important;
            /* Full screen on mobile */
        }

        .offcanvas-content {
            max-width: 100%;
            /* Use full width for mobile */
        }

        .offcanvas-header {
            padding: 0.75rem 1rem;
            /* Compact header for mobile */
        }

        .offcanvas-body {
            padding: 1rem;
            /* Adjust body padding for mobile */
        }
    }
</style>
<!-- Offcanvas for Viewing Job Opening Details -->
<div class="offcanvas offcanvas-end responsive-offcanvas" tabindex="-1" id="jobOpeningDetailsOffcanvas" aria-labelledby="jobOpeningDetailsOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="jobOpeningDetailsOffcanvasLabel">Job Opening Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex justify-content-center align-items-center">
        <div id="jobOpeningDetailsContent" class="offcanvas-content">
            <p>Loading...</p>
        </div>
    </div>
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/jobs/jobs.js') }}"></script>
@endsection

@push('footer_scripts')
<script>
    var list_route = '{{ $list_route }}';
    var save_route = '{{ $save_route}}';
    var edit_route = '{{ $edit_route }}';
    var del_route = '{{ $del_route }}';
    var view_route = '{{ $view_route }}';
</script>
@endpush