@include('front.IncludesFiles.head')
@include('front.IncludesFiles.menu')
@include('front.IncludesFiles.page-title', ['title' => 'Login'])
<div role="main" class="main">
    <hr>
    <section>
        <div class="container my-5">

            <div class="row mt-5">
                <div class="col">
                    <h4 class="text-color-dark font-weight-bold text-capitalize mb-2">
                        Hi, {{ $employee->full_name ?? 'Employee' }},
                        <span class="text-color-primary">({{ $employee->emp_code ?? 'N/A' }})</span>
                    </h4>


                    <div class="row">
                        <div class="col-lg-4">
                            <div class="tabs tabs-vertical tabs-right tabs-navigation tabs-navigation-simple">
                                <ul class="nav nav-tabs  col-sm-3" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active text-3-5" href="#employee" data-bs-toggle="tab"
                                            aria-selected="true" role="tab">Employee Details</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link text-3-5" href="#salaryslip" data-bs-toggle="tab"
                                            aria-selected="false" tabindex="-1" role="tab">Salary Slip</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link text-3-5 " href="#pfandesic" data-bs-toggle="tab"
                                            aria-selected="false" tabindex="-1" role="tab">PF & ESIC Details</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link text-3-5" href="#bank" data-bs-toggle="tab"
                                            aria-selected="false" tabindex="-1" role="tab">Bank Details</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link text-3-5" href="#documents" data-bs-toggle="tab"
                                            aria-selected="false" tabindex="-1" role="tab">Your Documents</a>
                                    </li>
                                    <!-- <li class="nav-item" role="presentation">
                                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <a type="submit" class="nav-link text-3-5"  data-bs-toggle="tab"
                                                aria-selected="false" tabindex="-1" role="tab">Logout</a>
                                        </form>
                                    </li> -->
                                    <!-- <li class="nav-item" role="presentation">
                                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="nav-link text-3-5 border-0 bg-transparent"
                                                style="cursor: pointer;">
                                                Logout
                                            </button>
                                        </form>
                                    </li> -->


                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="tab-pane tab-pane-navigation active" id="employee" role="tabpanel">
                                <h4>Employee Details</h4>
                                <table class="responsive-tabs-container table table-hover">
                                    <tr>
                                        <td class="text-color-primary font-weight-black">Name :</td>
                                        <td>{{ $employee->full_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-color-primary font-weight-black">Aadhar Number :</td>
                                        <td>{{ $employee->emp_aadhar ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-color-primary font-weight-black">Mobile Number :</td>
                                        <td>{{ $employee->emp_mobile ?? 'N/A' }}</td>
                                    </tr>
                                </table>

                            </div>
                            <div class="tab-pane tab-pane-navigation" id="salaryslip" role="tabpanel">
                                <h4>Salary Slip</h4>
                                <table class="responsive-tabs-container table table-hover">
                                    @forelse ($salarySlips as $slip)
                                        <tr>
                                            <td><b>{{ $slip->month_p }}-{{ $slip->year_p }}</b></td>
                                            <td>
                                                <button class="btn btn-primary download-slip-btn"
                                                    data-employee-id="{{ $employee->emp_id }}"
                                                    data-salary-slip-id="{{ $slip->salary_slip_id }}">
                                                    Download
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">No salary slips available.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                            <div class="tab-pane tab-pane-navigation" id="pfandesic" role="tabpanel">
                                <h4>PF and ESIC</h4>
                                <table class="responsive-tabs-container table table-hover">
                                    <tr>
                                        <td class="text-color-primary font-weight-black">PF Number :</td>
                                        <td>{{ $pf_esic_details->emp_PF_no ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-color-primary font-weight-black">ESIC Number :</td>
                                        <td>{{ $pf_esic_details->emp_ESIC_no ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-color-primary font-weight-black">ESIC State :</td>
                                        <td>{{ $pf_esic_details->emp_esic_State ?? 'N/A' }}</td>
                                    </tr>
                                </table>

                            </div>
                            <div class="tab-pane tab-pane-navigation" id="bank" role="tabpanel">
                                <h4>Bank Details</h4>
                                <table class="responsive-tabs-container table table-hover">
                                    <tr>
                                        <td class="text-color-primary font-weight-black">Account Holder Name :</td>
                                        <td>{{ $bank_details->emp_bank_fullname ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-color-primary font-weight-black">Bank Name :</td>
                                        <td>{{ $bank_details->emp_bank_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-color-primary font-weight-black">Account Number :</td>
                                        <td>{{ $bank_details->emp_account_no ?? 'N/A' }}</td>
                                    </tr>
                                </table>

                            </div>
                            <div class="tab-pane tab-pane-navigation" id="documents" role="tabpanel">
                                <h4>Your Uploaded Documnets</h4>
                                <table class="responsive-tabs-container table table-hover">
                                    @forelse ($documents as $document)
                                        <tr>
                                            <td><b>{{ $document->emp_doc_type }}</b></td>
                                            <td><a href="{{ asset('assets/emp_document/' . $document->emp_file) }}"
                                                    target="_blank">Download</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">No documents available.</td>
                                        </tr>
                                    @endforelse
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
    .tab-pane {
        scroll-margin-top: 150px;
        /* Adjust based on your header height */
    }
</style>


@include('front.IncludesFiles.footer')
<script>
    console.log('Script loaded with manish');
    $(document).on('click', '.download-slip-btn', function () {
        const employeeId = $(this).data('employee-id');
        const salarySlipId = $(this).data('salary-slip-id');

        $.post("{{ route('salary_slip') }}", {
            employee_id: employeeId,
            salary_slip_id: salarySlipId,
            _token: '{{ csrf_token() }}',
        })
            .done(function (response) {
                if (response.file_url) {
                    window.location.href = response.file_url; // Open PDF
                } else {
                    Swal.fire('Error', response.msg, 'error');
                }
            })
            .fail(function () {
                Swal.fire('Error', 'Failed to process your request.', 'error');
            });
    });
</script>