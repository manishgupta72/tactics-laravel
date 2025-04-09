@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>
                        <form id="FormFrm">
                            <div class="row form-group p-4">
                                <div class="col-lg-4 pb-0">
                                    @if (HasPermission('C1') == 'true')
                                        <button type="button" class="btn btn-primary w-50 add-company-btn" data-id="">+ Add
                                            Company</button>
                                    @endif
                                    <a href="{{ asset('assets/PAYSLIP FORMATE EXCEL.xlsx') }}" download
                                        class="btn btn-success">Download Sample</a>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="row p-4">
                            <table class="table table-bordered table-striped mb-0" id="ell-table">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas for Add/Edit Company -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="saveCompanyOffcanvas"
    aria-labelledby="saveCompanyOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="saveCompanyOffcanvasLabel">Add/Edit Company</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="saveCompanyContent">
            <p>Loading...</p>
        </div>
    </div>
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/company/company.js') }}"></script>
@endsection


@push('footer_scripts')

    <script>

        var list_route = '{{ $list_route }}';
        var save_route = '{{ $save_route }}';
        var edit_route = '{{ $edit_route }}';
        var del_route = '{{ $del_route }}';
    </script>
@endpush