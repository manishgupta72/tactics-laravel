@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col">
                <section class="card border-0 shadow-sm rounded">
                    <div class="card-body p-4">
                        <h5 class="mb-3 fw-bold">
                            <i class="bx bx-upload me-2"></i> Bulk Employee Upload (By Section)
                        </h5>

                        @if(session('upload_summary'))
                        <div class="alert alert-info">
                            {{ session('upload_summary') }}
                        </div>
                        @endif

                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#basic" data-bs-toggle="tab">Basic Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#bank" data-bs-toggle="tab">Bank Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#pfesic" data-bs-toggle="tab">PF/ESIC Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#document" data-bs-toggle="tab">Documents</a>
                            </li>
                        </ul>

                        <div class="tab-content border rounded p-3 bg-light">
                            <!-- BASIC -->
                            <div id="basic" class="tab-pane fade show active">
                                <form action="{{ route('emp.uploads.data') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="upload_type" value="basic">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Basic Details Excel <span class="text-danger">*</span></label>
                                        <input type="file" name="excel_file" class="form-control" required accept=".xlsx,.xls">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-primary">Upload Basic</button>
                                        <a href="{{ asset('assets/basic_employee.xlsx') }}" class="btn btn-success btn-sm">Download Format</a>
                                    </div>
                                </form>
                            </div>

                            <!-- BANK -->
                            <div id="bank" class="tab-pane fade">
                                <form action="{{ route('emp.uploads.data') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="upload_type" value="bank">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Bank Details Excel <span class="text-danger">*</span></label>
                                        <input type="file" name="excel_file" class="form-control" required accept=".xlsx,.xls">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-success">Upload Bank</button>
                                        <a href="{{ asset('assets/bank_employee.xlsx') }}" class="btn btn-success btn-sm">Download Format</a>
                                    </div>
                                </form>
                            </div>

                            <!-- PF/ESIC -->
                            <div id="pfesic" class="tab-pane fade">
                                <form action="{{ route('emp.uploads.data') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="upload_type" value="pfesic">
                                    <div class="mb-3">
                                        <label class="form-label">Upload PF/ESIC Excel <span class="text-danger">*</span></label>
                                        <input type="file" name="excel_file" class="form-control" required accept=".xlsx,.xls">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-warning">Upload PF/ESIC</button>
                                        <a href="{{ asset('assets/pf_esic_employee.xlsx') }}" class="btn btn-success btn-sm">Download Format</a>
                                    </div>
                                </form>
                            </div>

                            <!-- DOCUMENT -->
                            <div id="document" class="tab-pane fade">
                                <form action="{{ route('emp.uploads.data') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="upload_type" value="document">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Document Excel <span class="text-danger">*</span></label>
                                        <input type="file" name="excel_file" class="form-control" required accept=".xlsx,.xls">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-dark">Upload Documents</button>
                                        <a href="{{ asset('assets/document_employee.xlsx') }}" class="btn btn-success btn-sm">Download Format</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/employee/employee.js') }}"></script>
@endsection