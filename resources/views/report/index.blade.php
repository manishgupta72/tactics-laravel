@extends('IncludesFiles.Master')
@section('container')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ $title }}</h4>

                        <form id="exportForm" action="{{ route('reports.export') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <!-- Report Type -->
                                <div class="col-md-6">
                                    <label for="report_type" class="form-label">Select Report Type <span
                                            class="text-danger">*</span></label><br>
                                    <select name="report_type" id="report_type" class="form-control select2" required>
                                        <option value="">Select Report Type</option>
                                        @foreach (config('constant.EXPORT_TYPE') as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Start Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        placeholder="Select Start Date" required>
                                </div>

                                <!-- End Date -->
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">End Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        placeholder="Select End Date" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Export Report
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @endsection