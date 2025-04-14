@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>

                        <form id="EmployeeFrm">
                            <div class="row form-group p-4">
                                <div class="col-lg-4 pb-0 d-flex justify-content-between align-items-center">
                                    @if (HasPermission('E1') == 'true')
                                    <!-- Add Employee Button -->
                                    <a class="btn btn-primary" href="{{ route('page.EmployeeController') }}">+ Add Employee</a>
                                    @endif

                                    @if (HasPermission('UP0') == 'true')
                                    <!-- upload Employee Button -->
                                    <a class="btn btn-dark " href="{{ route('emp.view.emp.exl.data') }}">+ Upload Employee Excel Data</a>
                                    @endif

                                </div>

                            </div>
                        </form>
                        <hr>
                        <div class="row p-4">
                            <table class="table table-bordered table-striped mb-0" id="ell-table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Basic</th>
                                        <th>PF/ESIC</th>
                                        <th>Bank</th>
                                        <th>Document</th>
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

<!-- Offcanvas for Adding/Editing Employee -->
<div class="offcanvas offcanvas-end responsive-offcanvas" tabindex="-1" id="saveEmployeeOffcanvas" aria-labelledby="saveEmployeeOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="saveEmployeeOffcanvasLabel">Add/Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="saveEmployeeContent" class="offcanvas-content">
            <p>Loading...</p>
        </div>
    </div>
</div>

<!-- Offcanvas for Viewing Employee Details -->
<div class="offcanvas offcanvas-end responsive-offcanvas" tabindex="-1" id="employeeDetailsOffcanvas" aria-labelledby="employeeDetailsOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="employeeDetailsOffcanvasLabel">Employee Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="employeeDetailsContent" class="offcanvas-content">
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
<script type="text/javascript" src="{{ static_asset('assets/Frontend/employee/employee.js') }}"></script>
@endsection

@push('footer_scripts')
<script>
    var list_route = '{{ $list_route }}';
    var save_route = '{{ $save_route }}';
    var edit_route = '{{ $edit_route }}';
    var del_route = '{{ $del_route }}';
    var view_route = '{{ $view_route }}';
    var view_emp_excel = '{{ $view_emp_excel }}';
</script>
@endpush