@extends('IncludesFiles.Master')

@section('container')
<div class="row mt-5">
    <div class="col">

        <section class="card card-modern card-big-info border-0">
            <div class="card-body p-4">
                <h4 class="card-title text-primary mb-4">
                    <h4 class="text-primary font-weight-bold mb-3">
                        <i class="bx bx-user-circle me-2 mb-2" style="font-size: 1.5rem; color: #007bff;"></i>
                       {{ $emp_name }} ({{ $emp_details->emp_code }})
                    </h4>
                </h4>
                <div class="tabs-modern">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs nav-tabs-modern mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="#basic" data-bs-toggle="tab" aria-selected="true" role="tab">
                                <i class="bx bx-user me-2"></i> Basic Details
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#pfesic" data-bs-toggle="tab" aria-selected="false" role="tab">
                                <i class="bx bx-id-card me-2"></i> PF/ESIC Details
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#bank" data-bs-toggle="tab" aria-selected="false" role="tab">
                                <i class="bx bx-bank me-2"></i> Bank Details
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#document" data-bs-toggle="tab" aria-selected="false" role="tab">
                                <i class="bx bx-file me-2"></i> Uploaded Documents
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content p-3 border rounded">
                        <!-- Basic Details Tab -->
                        <div id="basic" class="tab-pane active show" role="tabpanel">
                            <div class="card border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Basic Details</h5>
                                </div>
                                <div class="card-body">
                                    @include('employee.basic-employee-details', ['save_url' => route('employee.save.basic')])
                                </div>
                            </div>
                        </div>

                        <!-- PF/ESIC Details Tab -->
                        <div id="pfesic" class="tab-pane" role="tabpanel">
                            <div class="card border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">PF/ESIC Details</h5>
                                </div>
                                <div class="card-body">
                                    @include('employee.pfesic_form', ['save_url' => route('employee.save.pfesic')])
                                </div>
                            </div>
                        </div>

                        <!-- Bank Details Tab -->
                        <div id="bank" class="tab-pane" role="tabpanel">
                            <div class="card border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Bank Details</h5>
                                </div>
                                <div class="card-body">
                                    @include('employee.bank-detail', ['save_url' => route('employee.save.bank')])
                                </div>
                            </div>
                        </div>

                        <!-- Uploaded Documents Tab -->
                        <div id="document" class="tab-pane" role="tabpanel">
                            <div class="card border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Uploaded Documents</h5>
                                </div>
                                <div class="card-body">
                                    @include('employee.document_form', ['save_url' => route('employee.save.document')])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ asset('assets/Frontend/employee/employee-details.js') }}"></script>
@endsection

@push('footer_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const activeTabKey = 'activeTab';
        const triggerTabList = [].slice.call(document.querySelectorAll('.nav-tabs .nav-link'));

        // Restore active tab on page load
        const activeTab = localStorage.getItem(activeTabKey);
        if (activeTab) {
            const tabTrigger = triggerTabList.find(el => el.getAttribute('href') === activeTab);
            if (tabTrigger) new bootstrap.Tab(tabTrigger).show();
        }

        // Save active tab to local storage
        triggerTabList.forEach(triggerEl => {
            triggerEl.addEventListener('click', function (event) {
                localStorage.setItem(activeTabKey, this.getAttribute('href'));
            });
        });
    });

    var employee_id = '{{ $emp_details->id ?? 0 }}';
    var employee_basic_detail_save = '{{ route('employee.save.basic') }}';
    var employee_pf_detail_save = '{{ route('employee.save.pfesic') }}';
    var employee_bank_detail_save = '{{ route('employee.save.bank') }}';
    var employee_save_document = '{{ route('employee.save.document') }}';

</script>
@endpush
