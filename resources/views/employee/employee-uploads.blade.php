@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row mt-4 g-4">
            <!-- SUMMARY -->
            @if(session('upload_summary'))
            @php
            $summary = session('upload_summary');
            preg_match('/Inserted: (\d+)/', $summary, $insertedMatch);
            preg_match('/Skipped: (\d+)/', $summary, $skippedMatch);
            $inserted = $insertedMatch[1] ?? 0;
            $skipped = $skippedMatch[1] ?? 0;
            @endphp
            <div class="col-12">
                <div class="alert alert-info">
                    ✅ <span class="text-success fw-bold">Inserted: {{ $inserted }}</span> &nbsp; | &nbsp;
                    ⚠️ <span class="text-danger fw-bold">Skipped: {{ $skipped }}</span>
                </div>
            </div>
            @endif

            <!-- BASIC EMPLOYEE UPLOAD -->
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-2 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upload Basic Details</h5>
                        <a href="{{ asset('assets/basic_employee.xlsx') }}" class="btn btn-danger btn-sm">Download Format</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('emp.uploads.data') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="upload_type" value="basic">
                            <div class="mb-3">
                                <label class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                                <input type="file" name="excel_file" class="form-control" required accept=".xlsx,.xls">
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BANK DETAILS UPLOAD -->
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-2 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upload Bank Details</h5>
                        <a href="{{ asset('assets/bank_employee.xlsx') }}" class="btn btn-danger btn-sm">Download Format</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('emp.uploads.data') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="upload_type" value="bank">
                            <div class="mb-3">
                                <label class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                                <input type="file" name="excel_file" class="form-control" required accept=".xlsx,.xls">
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PF/ESIC DETAILS UPLOAD -->
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-2 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upload PF/ESIC Details</h5>
                        <a href="{{ asset('assets/pf_esic_employee.xlsx') }}" class="btn btn-danger btn-sm">Download Format</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('emp.uploads.data') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="upload_type" value="pfesic">
                            <div class="mb-3">
                                <label class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                                <input type="file" name="excel_file" class="form-control" required accept=".xlsx,.xls">
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/employee/employee.js') }}"></script>
@endsection