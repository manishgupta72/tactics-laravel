<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportReport implements WithMultipleSheets
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    // Constants for mapping
    const STATUS = ['1' => 'Active', '0' => 'Inactive'];

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            // Master Employee Sheet
            new class($this->startDate, $this->endDate) implements FromCollection, WithHeadings {
                protected $startDate;
                protected $endDate;

                public function __construct($startDate, $endDate)
                {
                    $this->startDate = $startDate;
                    $this->endDate = $endDate;
                }

                public function collection()
                {
                    return DB::table('master_employee')
                        ->join('company', 'master_employee.emp_company_id', '=', 'company.comp_id')
                        ->select(
                            'master_employee.emp_id',
                            'master_employee.full_name',
                            'master_employee.emp_code',
                            'master_employee.emp_mobile',
                            'company.comp_name as company_name',
                            'master_employee.emp_aadhar',
                            'master_employee.emp_status',
                            'master_employee.created_at',
                            'master_employee.updated_at'
                        )
                        ->whereBetween('master_employee.created_at', [$this->startDate, $this->endDate])
                        ->get()
                        ->map(function ($employee) {
                            $employee->emp_status = ExportReport::STATUS[$employee->emp_status] ?? 'Unknown';
                            return $employee;
                        });
                }

                public function headings(): array
                {
                    return ['ID', 'Full Name', 'Employee Code', 'Mobile', 'Company Name', 'Aadhar', 'Status', 'Created At', 'Updated At'];
                }
            },

            // Basic Employee Sheet
            new class($this->startDate, $this->endDate) implements FromCollection, WithHeadings {
                protected $startDate;
                protected $endDate;

                public function __construct($startDate, $endDate)
                {
                    $this->startDate = $startDate;
                    $this->endDate = $endDate;
                }

                public function collection()
                {
                    return DB::table('basic_employee')
                        ->join('master_employee', 'basic_employee.emp_id', '=', 'master_employee.emp_id')
                        ->select(
                            'basic_employee.id',
                            'basic_employee.emp_id',
                            'basic_employee.father_husband_name',
                            'basic_employee.father_husband_dob',
                            'basic_employee.mother_name',
                            'basic_employee.mother_dob',
                            'basic_employee.emergency_contact_no',
                            'basic_employee.emergency_contact_relation',
                            'basic_employee.email',
                            'basic_employee.gender',
                            'basic_employee.dob',
                            'basic_employee.date_of_joining',
                            'basic_employee.designation',
                            'basic_employee.nth_pm',
                            'basic_employee.pan_card_no',
                            'basic_employee.address_as_per_aadhar',
                            'basic_employee.present_address',
                            'basic_employee.nominee_name',
                            'basic_employee.marital_status',
                            'basic_employee.religion',
                            'basic_employee.spouse_name',
                            'basic_employee.spouse_dob',
                            'basic_employee.first_child_name',
                            'basic_employee.first_child_dob',
                            'basic_employee.second_child_name',
                            'basic_employee.second_child_dob',
                            'basic_employee.created_at',
                            'basic_employee.updated_at'
                        )
                        ->whereBetween('basic_employee.created_at', [$this->startDate, $this->endDate])
                        ->get();
                }

                public function headings(): array
                {
                    return ['ID', 'Emp ID', 'Father/Husband Name', 'Father DOB', 'Mother Name', 'Mother DOB', 'Emergency Contact', 'Relation', 'Email', 'Gender', 'DOB', 'Joining Date', 'Designation', 'NTH PM', 'PAN', 'Aadhar Address', 'Present Address', 'Nominee', 'Marital Status', 'Religion', 'Spouse Name', 'Spouse DOB', 'First Child', 'First Child DOB', 'Second Child', 'Second Child DOB', 'Created At', 'Updated At'];
                }
            },

            // Bank Employee Sheet
            new class($this->startDate, $this->endDate) implements FromCollection, WithHeadings {
                protected $startDate;
                protected $endDate;

                public function __construct($startDate, $endDate)
                {
                    $this->startDate = $startDate;
                    $this->endDate = $endDate;
                }

                public function collection()
                {
                    return DB::table('bank_employee')
                        ->join('master_employee', 'bank_employee.emp_id', '=', 'master_employee.emp_id')
                        ->select(
                            'bank_employee.emp_bank_id',
                            'bank_employee.emp_id',
                            'bank_employee.emp_bank_fullname',
                            'bank_employee.emp_bank_name',
                            'bank_employee.emp_account_no',
                            'bank_employee.emp_ifsc_code',
                            'bank_employee.emp_branch',
                            'bank_employee.emp_bank_status',
                            'bank_employee.created_at',
                            'bank_employee.updated_at'
                        )
                        ->whereBetween('bank_employee.created_at', [$this->startDate, $this->endDate])
                        ->get()
                        ->map(function ($bank) {
                            $bank->emp_bank_status = ExportReport::STATUS[$bank->emp_bank_status] ?? 'Unknown';
                            return $bank;
                        });
                }

                public function headings(): array
                {
                    return ['Bank ID', 'Emp ID', 'Full Name', 'Bank Name', 'Account No', 'IFSC', 'Branch', 'Status', 'Created At', 'Updated At'];
                }
            },

            // PF/ESIC Employee Sheet
            new class($this->startDate, $this->endDate) implements FromCollection, WithHeadings {
                protected $startDate;
                protected $endDate;

                public function __construct($startDate, $endDate)
                {
                    $this->startDate = $startDate;
                    $this->endDate = $endDate;
                }

                public function collection()
                {
                    return DB::table('pf_esic_employee')
                        ->join('master_employee', 'pf_esic_employee.emp_id', '=', 'master_employee.emp_id')
                        ->select(
                            'pf_esic_employee.emp_pfes_id',
                            'pf_esic_employee.emp_id',
                            'pf_esic_employee.emp_PF_no',
                            'pf_esic_employee.emp_ESIC_no',
                            'pf_esic_employee.emp_esic_State',
                            'pf_esic_employee.emp_pf_status',
                            'pf_esic_employee.created_at',
                            'pf_esic_employee.updated_at'
                        )
                        ->whereBetween('pf_esic_employee.created_at', [$this->startDate, $this->endDate])
                        ->get()
                        ->map(function ($pf) {
                            $pf->emp_pf_status = ExportReport::STATUS[$pf->emp_pf_status] ?? 'Unknown';
                            $pf->emp_esic_State = config('constant.STATES')[$pf->emp_esic_State] ?? 'Unknown';
                            return $pf;
                        });
                }

                public function headings(): array
                {
                    return ['PF ESIC ID', 'Emp ID', 'PF No', 'ESIC No', 'ESIC State', 'Status', 'Created At', 'Updated At'];
                }
            }
        ];
    }
}
