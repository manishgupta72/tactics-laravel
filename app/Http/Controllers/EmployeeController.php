<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeBankDetails;
use App\Models\EmployeeBasicDetails;
use App\Models\EmployeeDocumentDetails;
use App\Models\EmployeePFESICDetails;

use App\Models\SalarySlip;
use Illuminate\Http\Request;
use Validator;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Services\PDFService;
use App\Models\MasterRoll;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }


    public function importExcel(Request $request)
    {
        // Increase max execution time to prevent timeout
        ini_set('max_execution_time', 180);

        // Step 1: Validate input
        $request->validate([
            'employee_excel' => 'required|file|mimes:xlsx,xls',
            'emp_company_id' => 'required|integer',
        ]);

        try {
            // Step 2: Read Excel
            $file        = $request->file('employee_excel');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray();

            $inserted = [];
            $skipped  = 0;

            // Step 3: DB transaction for safety
            DB::beginTransaction();

            foreach (array_slice($rows, 1) as $row) {
                $fullname = trim($row[0] ?? '');
                $mobile   = trim($row[1] ?? '');
                $aadhar   = trim($row[2] ?? '');

                // Skip invalid or empty rows
                if (!$fullname || !$mobile || !$aadhar) continue;

                // Avoid duplicate Aadhaar
                $exists = DB::table('master_employee')->where('emp_aadhar', $aadhar)->exists();
                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Generate emp_code
                $emp_code = $this->generateEmpCode();

                // Insert new employee
                $emp_id = DB::table('master_employee')->insertGetId([
                    'full_name'      => $fullname,
                    'emp_mobile'     => $mobile,
                    'emp_aadhar'     => $aadhar,
                    'emp_code'       => $emp_code,
                    'emp_company_id' => $request->emp_company_id,
                    'emp_status'     => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $inserted[] = [
                    'emp_id'    => $emp_id,
                    'full_name' => $fullname,
                ];
            }

            DB::commit();

            // Step 4: Export inserted records to Excel
            $export = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet  = $export->getActiveSheet();
            $sheet->setCellValue('A1', 'Employee ID');
            $sheet->setCellValue('B1', 'Full Name');

            $row = 2;
            foreach ($inserted as $emp) {
                $sheet->setCellValue("A{$row}", $emp['emp_id']);
                $sheet->setCellValue("B{$row}", $emp['full_name']);
                $row++;
            }

            $filename     = 'uploaded_employees_' . now()->format('YmdHis') . '.xlsx';
            $relativePath = "excel_exports/{$filename}";
            $absolutePath = storage_path("app/{$relativePath}");

            // Ensure folder exists
            \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($absolutePath));

            // Save file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($export);
            $writer->save($absolutePath);

            // Step 5: Redirect with session data
            return redirect()->back()->with([
                'upload_inserted'   => count($inserted),
                'upload_skipped'    => $skipped,
                'download_filename' => $filename,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);  // optional: log the error
            return redirect()->back()->with('error', 'Something went wrong while importing employees. Please try again.');
        }
    }


    // List Employees
    public function index(Request $req)
    {


        if ($req->isMethod('get')) {

            $data['MenuNames']                          = Admin_Side_Bar();
            $data['title']                              = "Employee List";
            $data['list_route']                         = route('EmployeeController.Filter') ?? "";
            $data['save_route']                         = route('save.EmployeeController') ?? "";
            $data['edit_route']                         = route('page.EmployeeController') ?? "";
            $data['del_route']                          = route('delete.EmployeeController') ?? "";
            $data['view_route']                         = route('view.EmployeeController') ?? "";
            $data['employee_view_salary_slip_data']     = route('emp.sl.sp.dt') ?? "";
            $data['employee_view_salary_slip_download'] = route('emp.sl.sp.download') ?? "";
            $data['employee_delete_file_from_server']   = route('emp.sl.sp.del') ?? "";
            $data['view_emp_excel']                     = route('emp.view.emp.exl.data') ?? "";
            view()->share($data);
            return view('employee.index', $data);
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    public function view_excel_employee_data(Request $request)
    {
        $data['MenuNames'] = Admin_Side_Bar();
        $data['title']     = "Employee Excel Uploads ";
        view()->share($data);
        return view('employee.employee-uploads', $data);
    }

    public function upload_employee(Request $request)
    {
        $request->validate([
            'excel_file'  => 'required|mimes:xlsx,xls',
            'upload_type' => 'required|in:basic,bank,pfesic,document',
        ]);

        $type = $request->upload_type;
        $file = $request->file('excel_file');

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $rows        = $spreadsheet->getActiveSheet()->toArray();

        $inserted = 0;
        $skipped  = 0;

        foreach (array_slice($rows, 1) as $row) {
            $emp_id = trim($row[0] ?? '');

            if (!$emp_id || !DB::table('master_employee')->where('emp_id', $emp_id)->exists()) {
                $skipped++;
                continue;
            }

            switch ($type) {
                case 'basic':
                    $exists = DB::table('basic_employee')->where('emp_id', $emp_id)->exists();

                    $data = [
                        'father_husband_name'        => $row[1] ?? null,
                        'father_husband_dob'         => $row[2] ?? null,
                        'mother_name'                => $row[3] ?? null,
                        'mother_dob'                 => $row[4] ?? null,
                        'emergency_contact_no'       => $row[5] ?? null,
                        'emergency_contact_relation' => $row[6] ?? null,
                        'email'                      => $row[7] ?? null,
                        'gender'                     => $row[8] ?? null,
                        'dob'                        => $row[9] ?? null,
                        'age'                        => $row[10] ?? null,
                        'date_of_joining'            => $row[11] ?? null,
                        'designation'                => $row[12] ?? null,
                        'nth_pm'                     => $row[13] ?? null,
                        'pan_card_no'                => $row[14] ?? null,
                        'address_as_per_aadhar'      => $row[15] ?? null,
                        'present_address'            => $row[16] ?? null,
                        'nominee_name'               => $row[17] ?? null,
                        'marital_status'             => $row[18] ?? null,
                        'religion'                   => $row[19] ?? null,
                        'spouse_name'                => $row[20] ?? null,
                        'spouse_dob'                 => $row[21] ?? null,
                        'first_child_name'           => $row[22] ?? null,
                        'first_child_dob'            => $row[23] ?? null,
                        'second_child_name'          => $row[24] ?? null,
                        'second_child_dob'           => $row[25] ?? null,
                        'updated_at'                 => now(),
                    ];

                    if ($exists) {
                        DB::table('basic_employee')->where('emp_id', $emp_id)->update($data);
                        $skipped++;
                    } else {
                        $data['emp_id']     = $emp_id;
                        $data['created_at'] = now();
                        DB::table('basic_employee')->insert($data);
                        $inserted++;
                    }
                    break;

                case 'bank':
                    $exists = DB::table('bank_employee')->where('emp_id', $emp_id)->exists();

                    $data = [
                        'emp_bank_fullname' => $row[1] ?? null,
                        'emp_bank_name'     => $row[2] ?? null,
                        'emp_account_no'    => $row[3] ?? null,
                        'emp_ifsc_code'     => $row[4] ?? null,
                        'emp_branch'        => $row[5] ?? null,
                        'emp_bank_status'   => $row[6] ?? 1,
                        'updated_at'        => now(),
                    ];

                    if ($exists) {
                        DB::table('bank_employee')->where('emp_id', $emp_id)->update($data);
                        $skipped++;
                    } else {
                        $data['emp_id']     = $emp_id;
                        $data['created_at'] = now();
                        DB::table('bank_employee')->insert($data);
                        $inserted++;
                    }
                    break;

                case 'pfesic':
                    $exists = DB::table('pf_esic_employee')->where('emp_id', $emp_id)->exists();

                    $data = [
                        'emp_PF_no'      => $row[1] ?? null,
                        'emp_ESIC_no'    => $row[2] ?? null,
                        'emp_esic_State' => $row[3] ?? null,
                        'emp_pf_status'  => $row[4] ?? 1,
                        'updated_at'     => now(),
                    ];

                    if ($exists) {
                        DB::table('pf_esic_employee')->where('emp_id', $emp_id)->update($data);
                        $skipped++;
                    } else {
                        $data['emp_id']     = $emp_id;
                        $data['created_at'] = now();
                        DB::table('pf_esic_employee')->insert($data);
                        $inserted++;
                    }
                    break;

                case 'document':
                    // Document: no update, only insert
                    DB::table('document_employee')->insert([
                        'emp_id'       => $emp_id,
                        'emp_doc_type' => $row[1] ?? null,
                        'emp_file'     => $row[2] ?? null,   // assuming file name or path
                        'created_at'   => now(),
                        'updated_at'   => now()
                    ]);
                    $inserted++;
                    break;
            }
        }

        return back()->with('upload_summary', "Inserted: $inserted, Skipped: $skipped");
    }


    // Add/Edit Employee Page
    public function pageEmployeeController(Request $req)
    {
        if ($req->isMethod('get')) {

            $data['role_list']    = MasterRoll::where(['Status' => 1])->get(['RollId', 'RollName']);
            $data['company_list'] = DB::table('company')
                ->select('comp_id', 'comp_name')
                ->where('comp_status', '1')  // Only fetch active companies
                ->get();

            $data['list_route'] = route('EmployeeController.Filter') ?? "";
            $data['save_route'] = route('save.EmployeeController') ?? "";
            $data['edit_route'] = route('page.EmployeeController') ?? "";
            $data['del_route']  = route('delete.EmployeeController') ?? "";
            $data['view_route'] = route('view.EmployeeController') ?? "";
            $data['EID']        = "";

            if ($req->segment(3) == 'EID') {
                if (HasPermission('E2') == 'true') {
                    $data['EID']       = $req->segment(3);
                    $data['MenuNames'] = Admin_Side_Bar();
                    $data['title']     = 'Edit Employee';
                    $employee          = DB::table('master_employee')
                        ->where('emp_id', $req->segment(4))
                        ->first();

                    if ($employee != null && $req->segment(4) > 0) {
                        $data['detail'] = $employee;
                        view()->share($data);
                        return view('employee.save', $data);
                    } else {
                        Session::flash('warning', 'Something went wrong, try again later.');
                        return redirect()->route('pages.EmployeeController');
                    }
                } else {
                    Session::flash('warning', 'You do not have permission to edit the Employee module.');
                    return redirect()->route('pages.EmployeeController');
                }
            } else {
                if (HasPermission('E1') == 'true') {
                    $data['title']     = "Add Employee";
                    $data['MenuNames'] = Admin_Side_Bar();
                    view()->share($data);
                    return view('employee.save', $data);
                } else {
                    Session::flash('warning', 'You do not have permission to add Employees.');
                    return redirect()->route('pages.EmployeeController');
                }
            }
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }


    // Save Employee
    public function saveEmployeeController(Request $req)
    {
        $msg       = '';
        $msgclass  = '';
        $TargetURL = '';

        $id             = $req->input('id');
        $emp_mobile     = $req->input('emp_mobile');
        $emp_company_id = $req->input('emp_company_id');
        $emp_aadhar     = $req->input('emp_aadhar');
        $emp_status     = $req->input('emp_status');
        $emp_first_name = $req->input('emp_first_name');
        $emp_last_name  = $req->input('emp_last_name');
        $full_name      = trim("$emp_first_name $emp_last_name");
        $proceed        = $req->input('proceed', false);

        if ($req->ajax() && $req->isMethod('post')) {
            // **Validation rules**
            $rules = [
                'emp_mobile'     => 'required|digits:10',
                'emp_company_id' => 'required|integer',
                'emp_aadhar'     => 'required|digits:12',
                'emp_status'     => 'required|string|max:20',
                'emp_first_name' => 'required|string|max:100',
                'emp_last_name'  => 'required|string|max:100',
            ];

            // Aadhaar should **not** be validated when updating an existing employee
            if ($id > 0) {
                unset($rules['emp_aadhar']);
            }

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->all()], 422);
            }

            try {
                DB::beginTransaction();  // Start DB transaction to ensure integrity

                if ($id == 0) {
                    // **Check if Aadhaar already exists (only for new employees)**
                    $existingEmployee = DB::table('master_employee')
                        ->where('emp_aadhar', $emp_aadhar)
                        ->where('emp_status', 1)
                        ->first();

                    if ($existingEmployee && !$proceed) {
                        return response()->json([
                            'warning'  => true,
                            'employee' => $existingEmployee,
                            'msg'      => 'The Aadhaar number already exists. Do you want to deactivate the existing employee and proceed?',
                        ]);
                    }

                    if ($existingEmployee) {
                        // **If "Proceed" is clicked, deactivate the existing employee**
                        DB::table('master_employee')
                            ->where('emp_id', $existingEmployee->emp_id)
                            ->update([
                                'emp_status'          => 0,
                                're_visited_employee' => 1,
                                'updated_at'          => now()
                            ]);
                    }

                    // **Auto-generate emp_code for new employees**
                    $emp_code = $this->generateEmpCode();
                } else {
                    // **Keep existing emp_code when updating**
                    $emp_code = DB::table('master_employee')
                        ->where('emp_id', $id)
                        ->value('emp_code');

                    if (!$emp_code) {
                        throw new \Exception('Employee code missing for update');
                    }
                }

                $data = [
                    'emp_code'       => $emp_code,
                    'emp_mobile'     => $emp_mobile,
                    'emp_company_id' => $emp_company_id,
                    'emp_status'     => $emp_status,
                    'full_name'      => $full_name,
                    'updatedon'      => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['UID'],
                    'updated_at'     => now(),
                ];

                if ($id > 0) {
                    // **Update existing employee**
                    DB::table('master_employee')->where('emp_id', $id)->update($data);
                    $msg       = 'Employee updated successfully.';
                    $TargetURL = route('pages.EmployeeController');
                    $msgclass  = 'bg-success';
                } else {
                    // **Insert new employee**
                    $data['emp_aadhar'] = $emp_aadhar;
                    $data['addedon']    = Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['UID'];
                    $data['created_at'] = now();

                    DB::table('master_employee')->insert($data);
                    $msg       = 'Employee added successfully.';
                    $TargetURL = route('pages.EmployeeController');
                    $msgclass  = 'bg-success';
                }

                DB::commit();  // Commit transaction if everything is successful
            } catch (\Exception $e) {
                DB::rollBack();  // Rollback transaction on failure
                \Log::error('Error in SaveEmployeeController: ' . $e->getMessage());
                $msg      = 'An unexpected error occurred. Please try again later.';
                $msgclass = 'bg-danger';
            }
        } else {
            $msg      = 'Invalid HTTP request.';
            $msgclass = 'bg-danger';
        }

        return response()->json([
            'msg'       => $msg,
            'msgsuc'    => $msgclass,
            'TargetURL' => $TargetURL,
        ]);
    }


    /**
     * Generate the next available emp_code with prefix "TAC-"
     */
    private function generateEmpCode()
    {
        // Get the highest emp_code in the table
        $lastEmpCode = DB::table('master_employee')
            ->where('emp_code', 'LIKE', 'TAC-%')
            ->max('emp_code');

        // Extract numeric part from the last emp_code
        $lastNumericPart = $lastEmpCode ? (int) str_replace('TAC-', '', $lastEmpCode) : 0;

        // Generate the next numeric code
        $nextCode = $lastNumericPart + 1;

        // Format as 5-digit code with prefix "TAC-"
        return 'TAC-' . str_pad($nextCode, 5, '0', STR_PAD_LEFT);
    }




    public function EmployeeControllerFilter(Request $req)
    {

        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('EM0') == 'true') {
                if ($req->post('is_date_search')) {
                    $columns = ['emp_code', 'emp_mobile', 'emp_aadhar', 'emp_status', 'addedon'];
                    $Query   = "SELECT
                            me.emp_id,
                            me.full_name,
                            me.emp_code,
                            me.emp_mobile,
                            me.emp_aadhar,
                            me.emp_status,
                            me.addedon,
                            (SELECT COUNT(*) FROM basic_employee be WHERE be.emp_id = me.emp_id) AS basic_completed,
                            (SELECT COUNT(*) FROM pf_esic_employee pf WHERE pf.emp_id = me.emp_id) AS pf_completed,
                            (SELECT COUNT(*) FROM bank_employee b WHERE b.emp_id = me.emp_id) AS bank_completed,
                            (SELECT COUNT(*) FROM document_employee de WHERE de.emp_id = me.emp_id) AS document_completed
                        FROM master_employee me WHERE 1=1";
                    $Q = [];

                    if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                        $searchValue = $req->post('search')["value"];
                        $Q[]         = "(me.full_name LIKE '%$searchValue%' OR me.emp_code LIKE '%$searchValue%' OR me.emp_mobile LIKE '%$searchValue%' OR me.emp_aadhar LIKE '%$searchValue%')";
                    }

                    if (!empty($Q)) {
                        $Query .= ' AND ' . implode(' AND ', $Q);
                    }

                    if ($req->post('order') && !empty($req->post('order'))) {
                        $Col    = $columns[$req->post('order')['0']['column']];
                        $Order  = $req->post('order')['0']['dir'];
                        $Query .= " ORDER BY $Col $Order ";
                    } else {
                        $Query .= ' ORDER BY me.emp_id DESC ';
                    }

                    $Query1 = '';
                    if ($req->post('length') != -1) {
                        $Query1 = ' LIMIT ' . $req->post('start') . ', ' . $req->post('length');
                    }

                    $res               = DB::select($Query);
                    $number_filter_row = count($res);
                    $result            = DB::select($Query . $Query1, []);
                    $data              = [];

                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            $sub_array = [];

                            $sub_array[] = '<a href="' . route('employee.details', ['emp_id' => $row->emp_id]) . '">' . $row->full_name . ' (' . $row->emp_code . ')</a>';

                            // Basic Status
                            $sub_array[] = $row->basic_completed > 0
                                ? "<span class='badge rounded-pill font-size-12 text-bg-success'>Completed</span>"
                                :     "<span class='badge rounded-pill font-size-12 text-bg-danger'>Pending</span>";

                            // PF/ESIC Status
                            $sub_array[] = $row->pf_completed > 0
                                ? "<span class='badge rounded-pill font-size-12 text-bg-success'>Completed</span>"
                                :     "<span class='badge rounded-pill font-size-12 text-bg-danger'>Pending</span>";

                            // Bank Status
                            $sub_array[] = $row->bank_completed > 0
                                ? "<span class='badge rounded-pill font-size-12 text-bg-success'>Completed</span>"
                                :     "<span class='badge rounded-pill font-size-12 text-bg-danger'>Pending</span>";

                            // Document Status
                            $sub_array[] = $row->document_completed > 0
                                ? "<span class='badge rounded-pill font-size-12 text-bg-success'>Completed</span>"
                                :     "<span class='badge rounded-pill font-size-12 text-bg-danger'>Pending</span>";

                            $sub_array[] = $row->emp_status > 0 && !empty($row->emp_status) ? config('constant.STATUS')[$row->emp_status] : "Inactive";

                            // Action buttons
                            $action = '<div class="btn-group" role="group">
                        <button id = "btnGroupVerticalDrop1" type = "button" class = "btn btn-primary dropdown-toggle" data-bs-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">
                            Action
                            <i class = "mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';

                            if (HasPermission('E2') == 'true') {
                                $action .= '<a target="_blank" class="dropdown-item" href="' . route('page.EmployeeController') . '/EID/' . $row->emp_id . '"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
                            }

                            if (HasPermission('E3') == 'true') {
                                $action .= '<a class="dropdown-item delete_row" data-id="' . $row->emp_id . '" data-full_name="' . $row->full_name . '" href="JavaScript:void(0)">Delete</a>';
                            }

                            if (HasPermission('A92') == 'true') {
                                $action .= '<a class="dropdown-item text-1 view_salary_details" data-emp_code="' . $row->emp_code . '"  data-employee_id="' . $row->emp_id . '"  href="JavaScript:void(0)"><i class="fa-regular fa-file"></i>  View Salary Slip</a>';
                            }

                            $action .= '</div>';
                            $action .= '</ul>';
                            $action .= '</div>';

                            $sub_array[] = $action;



                            $data[] = $sub_array;
                        }
                    }

                    $output = [
                        "draw"            => intval($req->post('draw')),
                        "recordsTotal"    => count(DB::select($Query)),
                        "recordsFiltered" => $number_filter_row,
                        "data"            => $data,
                    ];

                    echo json_encode($output);
                }
            }
        }
    }

    public function DeleteEmployeeController(Request $req)
    {

        $msg      = '';
        $msgclass = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'id' => 'required|numeric|exists:master_employee,emp_id',
                ],
                [
                    'id.required' => 'Invalid selected employee.',
                    'id.numeric'  => 'Invalid employee ID.',
                    'id.exists'   => 'The selected employee does not exist.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'msg'    => $validator->errors()->first(),
                    'msgsuc' => 'bg-danger',
                ], 422);
            }


            try {
                $employeeId = $req->input('id');

                if (HasPermission('E3') == 'true') {
                    DB::beginTransaction();

                    // Delete related data from all associated tables
                    DB::table('basic_employee')->where('emp_id', $employeeId)->delete();
                    DB::table('bank_employee')->where('emp_id', $employeeId)->delete();
                    DB::table('pf_esic_employee')->where('emp_id', $employeeId)->delete();
                    DB::table('document_employee')->where('emp_id', $employeeId)->delete();

                    // Delete from master_employee
                    DB::table('master_employee')->where('emp_id', $employeeId)->delete();

                    DB::commit();

                    $msg      = "Employee and all related data deleted successfully.";
                    $msgclass = "bg-success";
                } else {
                    $msg      = "You do not have permission to delete this employee.";
                    $msgclass = "bg-danger";
                }
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Error deleting employee: " . $e->getMessage());

                $msg      = "An unexpected error occurred. Please try again later.";
                $msgclass = "bg-danger";
            }
        } else {
            $msg      = "Invalid HTTP request.";
            $msgclass = "bg-danger";
        }

        return response()->json([
            'msg'    => $msg,
            'msgsuc' => $msgclass,
        ]);
    }

    public function ViewEmployeeController(Request $req)
    {
        $id = $req->input('id');

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make($req->all(), [
                'id' => 'required|numeric|exists:master_employee,emp_id',
            ], [
                'id.required' => 'Employee ID is required.',
                'id.numeric'  => 'Employee ID must be a number.',
                'id.exists'   => 'The selected employee does not exist.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'msg'    => $validator->errors()->first(),
                    'msgsuc' => 'bg-danger',
                ], 422);
            }

            $employee = DB::table('master_employee')->where('emp_id', $id)->first();

            if ($employee) {
                $html = view('employee.partials.employee_details', compact('employee'))->render();

                return response()->json([
                    'html'   => $html,
                    'msg'    => 'Employee details loaded successfully.',
                    'msgsuc' => 'bg-success',
                ]);
            } else {
                return response()->json([
                    'msg'    => 'Employee not found.',
                    'msgsuc' => 'bg-danger',
                ]);
            }
        }

        return response()->json([
            'msg'    => 'Invalid request.',
            'msgsuc' => 'bg-danger',
        ], 400);
    }

    // Employee open form functions here
    // Route: employee-details/{emp_id} -> employee.details
    public function employeeDetails(Request $req)
    {

        if ($req->isMethod('get')) {
            $data['MenuNames'] = Admin_Side_Bar();
            $employee_id       = $req->segment(3) ?  $req->segment(3) : '';

            $data['emp_id'] = $employee_id;
            $data['title']  = "Employee Details";
            //$emp_details                                 = EmployeeMaster::where(['employee_id'=>$employee_id,'emp_code'=>$emp_code])->first();
            $emp_details               = Employee::where(['emp_id' => $employee_id])->first();
            $emp_basic_details         = EmployeeBasicDetails::where('emp_id', $employee_id)->first();
            $emp_bank_details          = DB::table('bank_employee')->where('emp_id', $employee_id)->first();
            $emp_pfesic_details        = DB::table('pf_esic_employee')->where('emp_id', $employee_id)->first();
            $emp_doc_details           = DB::table('document_employee')->where('emp_id', $employee_id)->get();
            $emp_name                  = DB::table('master_employee')->where('emp_id', $employee_id)->pluck('full_name')->first();
            $documentTypes             = DB::table('master_data')->select('master_data_id', 'master_data_name')->where('mid', 1)->get();
            $data['emp_basic_details'] = $emp_basic_details;

            if ($req->ajax()) {
                return response()->json([
                    'data' => $emp_basic_details,
                ], 200);
            }
            if ($emp_details != null) {

                $data['savePFESICDetails']          = route('employee.save.pfesic') ?? "";
                $data['employee_bank_detail_save']  = route('employee.save.bank') ?? "";
                $data['employee_basic_detail_save'] = route('employee.save.basic') ?? "";
                $data['employee_save_document']     = route('employee.save.document') ?? "";


                view()->share($data, $emp_basic_details);
                return view('employee.details', [
                    'emp_id'             => $employee_id,
                    'emp_bank_details'   => $emp_bank_details,
                    'emp_pfesic_details' => $emp_pfesic_details,
                    'emp_doc_details'    => $emp_doc_details,
                    'emp_name'           => $emp_name,
                    'emp_details'        => $emp_details,
                    'documentTypes'      => $documentTypes,

                ]);
            } else {
                Session::flash('warning', 'Sorry something went wrong with http request ');
                return redirect()->route('dashboard');
            }
        }
    }
    // Route: save-basic-details -> employee.save.basic
    public function saveBasicDetails(Request $request)
    {
        $msg       = '';
        $msgclass  = '';
        $TargetURL = '';

        $emp_id                     = $request->input('emp_id');
        $id                         = $request->input('id');
        $father_husband_name        = $request->input('father_husband_name');
        $father_husband_dob         = $request->input('father_husband_dob');
        $mother_name                = $request->input('mother_name');
        $mother_dob                 = $request->input('mother_dob');
        $emergency_contact_no       = $request->input('emergency_contact_no');
        $emergency_contact_relation = $request->input('emergency_contact_relation');
        $email                      = $request->input('email');
        $gender                     = $request->input('gender');
        $dob                        = $request->input('dob');
        $age                        = $request->input('age');
        $date_of_joining            = $request->input('date_of_joining');
        $designation                = $request->input('designation');
        $nth_pm                     = $request->input('nth_pm');
        $pan_card_no                = $request->input('pan_card_no');
        $address_as_per_aadhar      = $request->input('address_as_per_aadhar');
        $present_address            = $request->input('present_address');
        $nominee_name               = $request->input('nominee_name');
        $company_emp_code           = $request->input('company_emp_code');
        $marital_status             = $request->input('marital_status');
        $religion                   = $request->input('religion');
        $spouse_name                = $request->input('spouse_name');
        $spouse_dob                 = $request->input('spouse_dob');
        $first_child_name           = $request->input('first_child_name');
        $first_child_dob            = $request->input('first_child_dob');
        $second_child_name          = $request->input('second_child_name');
        $second_child_dob           = $request->input('second_child_dob');



        if ($request->ajax() && $request->isMethod('post')) {
            // Validation
            $validator = Validator::make(
                $request->all(),
                [
                    'emp_id' => 'required|exists:master_employee,emp_id',

                    'father_husband_name'        => 'required|string',
                    'emergency_contact_no'       => 'required|string|max:15',
                    'emergency_contact_relation' => 'required|string|max:50',
                    'email'                      => 'required|email|max:255',
                    'gender'                     => 'required|string|in:Male,Female,Other',
                    'dob'                        => 'required|date',
                    'age'                        => 'nullable|integer|min:0|max:120',
                    'date_of_joining'            => 'required|date',
                    'designation'                => 'required|string|max:255',
                    'nth_pm'                     => 'nullable|string|max:255',
                    'pan_card_no'                => [
                        'required',
                        'string',
                        'max:10',
                        function ($attribute, $value, $fail) {
                            if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $value)) {
                                $fail('The PAN card number is invalid.');
                            }
                        },
                    ],
                    'address_as_per_aadhar' => 'nullable|string|max:255',
                    'present_address'       => 'nullable|string|max:255',
                    'nominee_name'          => 'nullable|string|max:255',
                    'marital_status'        => 'nullable|string',
                    'religion'              => 'nullable|string|max:50',
                    'spouse_name'           => 'nullable|string|max:255',
                    'spouse_dob'            => 'nullable|date',
                    'first_child_name'      => 'nullable|string|max:255',
                    'first_child_dob'       => 'nullable|date',
                    'second_child_name'     => 'nullable|string|max:255',
                    'second_child_dob'      => 'nullable|date',
                ],
                [
                    // Employee ID
                    'emp_id.required' => 'Employee ID is required.',
                    'emp_id.exists'   => 'The Employee ID does not exist.',

                    'father_husband_name.required' => 'Father/Husband Name is required.',
                    // Emergency Contact Number
                    'emergency_contact_no.required' => 'Emergency contact number is required.',
                    'emergency_contact_no.number'   => 'Emergency contact number must be a valid string.',
                    'emergency_contact_no.max'      => 'Emergency contact number cannot exceed 15 characters.',

                    // Emergency Contact Relation
                    'emergency_contact_relation.required' => 'Emergency contact relation is required.',
                    'emergency_contact_relation.string'   => 'Emergency contact relation must be a valid string.',
                    'emergency_contact_relation.max'      => 'Emergency contact relation cannot exceed 50 characters.',

                    // Email
                    'email.required' => 'Email is required.',
                    'email.email'    => 'Email must be a valid email address.',
                    'email.max'      => 'Email cannot exceed 255 characters.',

                    // Gender
                    'gender.required' => 'Gender is required.',



                    // Date of Birth
                    'dob.required' => 'Date of birth is required.',


                    // Age
                    'age.integer' => 'Age must be a valid number.',
                    'age.min'     => 'Age cannot be less than 0.',
                    'age.max'     => 'Age cannot exceed 100.',

                    // Date of Joining
                    'date_of_joining.required' => 'Date of joining is required.',


                    // Designation
                    'designation.required' => 'Designation is required.',
                    'designation.string'   => 'Designation must be a valid string.',
                    'designation.max'      => 'Designation cannot exceed 255 characters.',

                    // // NTH/PM
                    // 'nth_pm.string' => 'NTH/PM must be a valid string.',
                    // 'nth_pm.max' => 'NTH/PM cannot exceed 255 characters.',

                    // PAN Card Number
                    'pan_card_no.required' => 'PAN card number is required.',
                    'pan_card_no.string'   => 'PAN card number must be a valid string.',
                    'pan_card_no.max'      => 'PAN card number cannot exceed 10 characters.',
                    'pan_card_no.regex'    => 'The PAN card number must follow the format XXXXX0000X.',

                    // // Address as per Aadhar
                    // 'address_as_per_aadhar.string' => 'Address as per Aadhar must be a valid string.',
                    // 'address_as_per_aadhar.max' => 'Address as per Aadhar cannot exceed 255 characters.',

                    // // Present Address
                    // 'present_address.string' => 'Present address must be a valid string.',
                    // 'present_address.max' => 'Present address cannot exceed 255 characters.',

                    // // Nominee Name
                    // 'nominee_name.string' => 'Nominee name must be a valid string.',
                    // 'nominee_name.max' => 'Nominee name cannot exceed 255 characters.',

                    // // Marital Status
                    // 'marital_status.string' => 'Marital status must be a valid string.',
                    // 'marital_status.in' => 'Marital status must be Single or Married.',

                    // // Religion
                    // 'religion.string' => 'Religion must be a valid string.',
                    // 'religion.max' => 'Religion cannot exceed 50 characters.',

                    // // Spouse Name
                    // 'spouse_name.string' => 'Spouse name must be a valid string.',
                    // 'spouse_name.max' => 'Spouse name cannot exceed 255 characters.',

                    // // Spouse DOB
                    // 'spouse_dob.date' => 'Spouse date of birth must be a valid date.',

                    // // First Child Name
                    // 'first_child_name.string' => 'First child name must be a valid string.',
                    // 'first_child_name.max' => 'First child name cannot exceed 255 characters.',

                    // // First Child DOB
                    // 'first_child_dob.date' => 'First child date of birth must be a valid date.',

                    // // Second Child Name
                    // 'second_child_name.string' => 'Second child name must be a valid string.',
                    // 'second_child_name.max' => 'Second child name cannot exceed 255 characters.',

                    // // Second Child DOB
                    // 'second_child_dob.date' => 'Second child date of birth must be a valid date.',
                ]

            );

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->all()], 422);
            }

            try {
                // Check if `father_husband_name` exists in the database
                $existingRecord = EmployeeBasicDetails::where('emp_id', $emp_id)
                    ->where('id', $id)
                    ->first();

                $data = [
                    'emp_id'                     => $emp_id,
                    'father_husband_name'        => $father_husband_name,
                    'father_husband_dob'         => $father_husband_dob,
                    'mother_name'                => $mother_name,
                    'mother_dob'                 => $mother_dob,
                    'emergency_contact_no'       => $emergency_contact_no,
                    'emergency_contact_relation' => $emergency_contact_relation,
                    'email'                      => $email,
                    'gender'                     => $gender,
                    'dob'                        => $dob,
                    'age'                        => $age,
                    'date_of_joining'            => $date_of_joining,
                    'designation'                => $designation,
                    'nth_pm'                     => $nth_pm,
                    'pan_card_no'                => $pan_card_no,
                    'address_as_per_aadhar'      => $address_as_per_aadhar,
                    'present_address'            => $present_address,
                    'nominee_name'               => $nominee_name,
                    'company_emp_code'           => $company_emp_code,
                    'marital_status'             => $marital_status,
                    'religion'                   => $religion,
                    'spouse_name'                => $spouse_name,
                    'spouse_dob'                 => $spouse_dob,
                    'first_child_name'           => $first_child_name,
                    'first_child_dob'            => $first_child_dob,
                    'second_child_name'          => $second_child_name,
                    'second_child_dob'           => $second_child_dob,
                    'updatedon'                  => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                    'updated_at'                 => now(),
                ];

                if ($existingRecord) {
                    // Update existing record
                    $existingRecord = DB::table('basic_employee')->where('emp_id', $emp_id)->update($data);;
                    $msg            = 'Basic details updated successfully.';
                    $msgclass       = 'bg-success';
                } else {
                    // Create new record
                    $data['created_at'] = now();
                    $data['addedon']    = Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'];

                    DB::table('basic_employee')->insert($data);


                    $msg      = 'Basic details saved successfully.';
                    $msgclass = 'bg-success';
                }
            } catch (\Exception $e) {
                \Log::error('Error in saveBasicDetails: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                $msg      = 'An unexpected error occurred. Please try again later.';
                $msgclass = 'bg-danger';
            }
        } else {
            $msg      = 'Invalid HTTP request.';
            $msgclass = 'bg-danger';
        }

        return response()->json([
            'msg'       => $msg,
            'msgsuc'    => $msgclass,
            'TargetURL' => '',
        ]);
    }

    // Route: save-bank-details -> employee.save.bank
    public function saveBankDetails(Request $request)
    {
        $msg      = '';
        $msgclass = '';

        if ($request->ajax() && $request->isMethod('post')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'emp_id'            => 'required|exists:master_employee,emp_id',
                    'emp_bank_fullname' => 'required|string|max:255',
                    'emp_bank_name'     => 'required|string|max:255',
                    'emp_account_no'    => [
                        'required',
                        'string',
                        'max:20',                      // Account numbers typically have a maximum of 20 characters
                        'regex:/^\d{9,20}$/'  // Only allows digits, with a length between 9 and 20
                    ],
                    'emp_ifsc_code' => [
                        'required',
                        'string',
                        'size:11',                                  // IFSC codes are exactly 11 characters
                        'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'  // Matches standard IFSC format
                    ],
                ],
                [
                    'emp_id.required'            => 'Employee ID is required.',
                    'emp_id.exists'              => 'The Employee ID does not exist.',
                    'emp_bank_fullname.required' => 'Bank account full name is required.',
                    'emp_bank_name.required'     => 'Bank name is required.',
                    'emp_account_no.required'    => 'Account number is required.',
                    'emp_account_no.regex'       => 'Account number must be between 9 and 20 digits.',
                    'emp_ifsc_code.required'     => 'IFSC code is required.',
                    'emp_ifsc_code.regex'        => 'IFSC code must be 11 characters long and follow the standard format (e.g., XXXX0YYYYYY).',
                ]

            );

            if (!empty($request->input('emp_bank_fullname')) && !empty($request->input('emp_bank_name')) &&  !empty($request->input('emp_ifsc_code')) && !empty($request->input('emp_account_no'))) {
                $emp_status = 1;  // bank details are present
            } else {
                $emp_status = 0;  // Either bank details is missing
            }
            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->all()], 422);
            }


            try {
                $data = [
                    'emp_id'            => $request->input('emp_id'),
                    'emp_bank_fullname' => $request->input('emp_bank_fullname'),
                    'emp_bank_name'     => $request->input('emp_bank_name'),
                    'emp_account_no'    => $request->input('emp_account_no'),
                    'emp_ifsc_code'     => $request->input('emp_ifsc_code'),
                    'emp_branch'        => $request->input('emp_branch'),
                    'emp_bank_status'   => $emp_status,
                    'updatedon'         => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                    'updated_at'        => now(),
                ];
                $existingRecord = DB::table('bank_employee')->where('emp_id', $request->input('emp_id'))->first();


                if ($existingRecord) {
                    DB::table('bank_employee')->where('emp_id', $request->input('emp_id'))->update($data);
                    $msg = 'Bank details updated successfully.';
                } else {
                    $data['created_at'] = now();
                    $data['addedon']    = Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'];

                    DB::table('bank_employee')->insert($data);
                    $msg = 'Bank details saved successfully.';
                }

                $msgclass = 'bg-success';
            } catch (\Exception $e) {

                $msg      = 'An unexpected error occurred. Please try again later.';
                $msgclass = 'bg-danger';
            }
        } else {
            $msg      = 'Invalid HTTP request.';
            $msgclass = 'bg-danger';
        }

        return response()->json([
            'msg'    => $msg,
            'msgsuc' => $msgclass,
        ]);
    }

    // Route: save-document-details -> employee.save.document
    public function getDocuments($emp_id)
    {

        try {
            $documents = DB::table('document_employee')
                ->where('emp_id', $emp_id)
                ->get();

            if ($documents->isEmpty()) {
                return response()->json(['documents' => []], 200);
            }

            return response()->json(['documents' => $documents], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching documents: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch documents.'], 500);
        }
    }

    public function saveDocumentDetails(Request $req)
    {
        $emp_doc_id = $req->input('emp_doc_id');
        $validator  = Validator::make(
            $req->all(),
            [
                'emp_id'       => 'required|exists:master_employee,emp_id',
                'emp_doc_type' => 'required|string|max:255',
                'emp_file'     => 'required|file|mimes:pdf,jpg,png|max:2048',
            ],
            [
                'emp_id.required'       => 'Employee ID is required.',
                'emp_id.exists'         => 'The Employee ID does not exist.',
                'emp_doc_type.required' => 'Document type is required.',
                'emp_file.required'     => 'Document file is required.',
                'emp_file.file'         => 'Document must be a valid file.',
                'emp_file.mimes'        => 'Allowed file formats are PDF, JPG, and PNG.',
                'emp_file.max'          => 'The file size must not exceed 2MB.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()->all()], 422);
        }

        try {
            $data = [
                'emp_id'       => $req->input('emp_id'),
                'emp_doc_type' => $req->input('emp_doc_type'),
                'created_at'   => now(),
                'addedon'      => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                'updated_at'   => now(),
            ];

            // Handle File Upload
            if ($req->hasFile('emp_file')) {
                $file             = $req->file('emp_file');
                $path             = config('constant.emp_document');            //Define the folder for document storage
                $uploadedFile     = uploadFile($file, $path, 'Files_storage');  // Store in storage/app/public/documents
                $data['emp_file'] = basename($uploadedFile);
            } else {
                $data['emp_file'] = $existing_file ?? DB::table('document_employee')->where('emp_doc_id', $emp_doc_id)->value('emp_file');
            }
            DB::table('document_employee')->insert($data);

            return response()->json(['msg' => 'Document saved successfully.'], 200);
        } catch (\Exception $e) {
            \Log::error('Error in saveDocument: ' . $e->getMessage());
            return response()->json(['msg' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }


    public function deleteDocument($id)
    {
        try {
            $document = DB::table('document_employee')->where('emp_doc_id', $id)->first();

            if (!$document) {
                return response()->json(['msg' => 'Document not found.'], 404);
            }

            // Delete the file from storage
            $filePath = storage_path('app/public/emp_documents/' . $document->emp_file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete the document record
            DB::table('document_employee')->where('emp_doc_id', $id)->delete();

            return response()->json(['msg' => 'Document deleted successfully.'], 200);
        } catch (\Exception $e) {
            \Log::error('Error in deleteDocument: ' . $e->getMessage());
            return response()->json(['msg' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    // Route: save-pfesic-details -> employee.save.pfesic
    public function savePFESICDetails(Request $request)
    {
        $msg      = '';
        $msgclass = '';

        if ($request->ajax() && $request->isMethod('post')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'emp_id'         => 'required|exists:master_employee,emp_id',
                    'emp_PF_no'      => 'required|string|max:50',
                    'emp_ESIC_no'    => 'required|string|max:50',
                    'emp_esic_State' => 'required|string|max:255',

                ],
                [
                    'emp_id.required'         => 'Employee ID is required.',
                    'emp_id.exists'           => 'The Employee ID does not exist.',
                    'emp_PF_no.required'      => 'PF number is required.',
                    'emp_ESIC_no.required'    => 'ESIC number is required.',
                    'emp_esic_State.required' => 'ESIC state is required.',


                ]
            );
            if (!empty($request->input('emp_PF_no')) && !empty($request->input('emp_ESIC_no'))) {
                $emp_status = 1;  // Both PF and ESIC numbers are present
            } else {
                $emp_status = 0;  // Either PF or ESIC number is missing
            }

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->all()], 422);
            }

            try {
                $data = [
                    'emp_id'         => $request->input('emp_id'),
                    'emp_PF_no'      => $request->input('emp_PF_no'),
                    'emp_ESIC_no'    => $request->input('emp_ESIC_no'),
                    'emp_esic_State' => $request->input('emp_esic_State'),
                    'emp_pf_status'  => $emp_status,
                    'updatedon'      => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                    'updated_at'     => now(),
                ];

                $existingRecord = DB::table('pf_esic_employee')->where('emp_id', $request->input('emp_id'))->first();

                if ($existingRecord) {
                    DB::table('pf_esic_employee')->where('emp_id', $request->input('emp_id'))->update($data);
                    $msg = 'PF/ESIC details updated successfully.';
                } else {
                    $data['created_at'] = now();
                    $data['addedon']    = Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'];
                    DB::table('pf_esic_employee')->insert($data);
                    $msg = 'PF/ESIC details saved successfully.';
                }

                $msgclass = 'bg-success';
            } catch (\Exception $e) {
                Log::error('Error in savePFESICDetails: ' . $e->getMessage());
                $msg      = 'An unexpected error occurred. Please try again later.';
                $msgclass = 'bg-danger';
            }
        } else {
            $msg      = 'Invalid HTTP request.';
            $msgclass = 'bg-danger';
        }

        return response()->json([
            'msg'    => $msg,
            'msgsuc' => $msgclass,
        ]);
    }

    public function employee_view_salary_slip_download(Request $req)
    {
        $msg            = '';
        $msgclass       = '';
        $msgtype        = '';
        $msgtype_title  = '';
        $TargetURL      = '';
        $employee_id    = $req->input('employee_id') ?: 0;
        $salary_slip_id = $req->input('salary_slip_id') ?: 0;
        $base64Pdf      = '';
        $file_url       = '';
        $fileName       = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'employee_id'    => 'required|numeric|gt:0|exists:master_employee,emp_id',
                    'salary_slip_id' => 'required|numeric|gt:0|exists:salary_slip,salary_slip_id',
                ],
                [
                    'employee_id.required' => 'Invalid Selected employee details',
                    'employee_id.numeric'  => 'Something went wront with selected employee details',
                    'employee_id.gt'       => 'Something Went Wrong,Please Try Again employee details',
                    'employee_id.exists'   => ('I apologize, but the option you have employee details selected does not appear to be valid. Please review the available options and choose one that is listed.'),

                    'salary_slip_id.required' => 'Invalid Selected salary slip details',
                    'salary_slip_id.numeric'  => 'Something went wront with selected salary slip details',
                    'salary_slip_id.gt'       => 'Something Went Wrong,Please Try Again salary slip details',
                    'salary_slip_id.exists'   => ('I apologize, but the option you have salary slip details selected does not appear to be valid. Please review the available options and choose one that is listed.'),
                ]
            );
            if ($validator->passes()) {

                if (HasPermission('A93') == 'true') {
                    $data = Employee::find($employee_id);
                    if ($data) {
                        $contact_detail = SalarySlip::with(['components'])
                            ->where([
                                'employee_id'    => $employee_id,
                                'salary_slip_id' => $salary_slip_id
                            ])
                            ->first();
                        if ($contact_detail) {
                            $action            = 1;
                            $dynamicEarnings   = $contact_detail->components->where('type', 'ER')->values();
                            $dynamicDeductions = $contact_detail->components->where('type', 'DE')->values();
                            $htmlContent       = view('employee.partials._employee_salary_slip')->with(compact(['contact_detail', 'dynamicEarnings', 'dynamicDeductions']))->render();
                            $filepath          = $this->pdfService->generatePDF($htmlContent, time() . '.pdf', 'F');
                            $fileName          = pathinfo($filepath, PATHINFO_FILENAME) . ".pdf";

                            $filename = get_filename('Files_storage', config('constant.invoice_path_v') . "/" . $fileName);
                            if ($filename) {
                                $file_url = $filename;
                            }

                            $msg      = ("success");
                            $msgclass = ("bg-success");
                        } else {
                            $msg      = ("Something went wrong try again later");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg      = ("No Record Found In Our Employee Records");
                        $msgclass = ("bg-danger");
                        $msgtype  = 'val_error';
                    }
                } else {
                    $msg      = ("You Dont Have Permission To download salary slip file");
                    $msgclass = ("bg-danger");
                    $msgtype  = 'val_error';
                }
            } else {
                $msg           = throw_error($validator->errors()->all());;
                $msgclass      = ("bg-danger");
                $msgtype       = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg      = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        return response()->json(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'file_url' => $file_url, 'fileName' => $fileName));
    }

    public function employee_delete_file_from_server(Request $req)
    {
        $msg           = '';
        $msgclass      = '';
        $msgtype       = '';
        $msgtype_title = '';
        $TargetURL     = '';
        $filename      = $req->input('filename') ?? 0;

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'filename' => 'required',
                ],
                [
                    'filename.required' => 'Filename is requried',
                ]
            );
            if ($validator->passes()) {

                if (HasPermission('A93') == 'true') {
                    deleteFile('Files_storage', config('constant.invoice_path_v') . "/" . $filename);
                } else {
                    $msg      = ("You Dont Have Permission To download salary slip file");
                    $msgclass = ("bg-danger");
                    $msgtype  = 'val_error';
                }
            } else {
                $msg           = throw_error($validator->errors()->all());;
                $msgclass      = ("bg-danger");
                $msgtype       = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg      = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        return response()->json(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title));
    }

    public function employee_view_salary_slip_data(Request $req)
    {
        $msg           = '';
        $msgclass      = '';
        $msgtype       = '';
        $msgtype_title = '';
        $TargetURL     = '';
        $employee_id   = $req->input('employee_id') ?: 0;
        $html          = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'employee_id' => 'required|numeric|gt:0|exists:master_employee,emp_id',
                    // 'status'                     => 'required|in:' . implode(',',[0,1]),
                ],
                [
                    'employee_id.required' => 'Invalid Selected employee details',
                    'employee_id.numeric'  => 'Something went wront with selected employee details',
                    'employee_id.gt'       => 'Something Went Wrong,Please Try Again employee details',
                    'employee_id.exists'   => ('I apologize, but the option you have employee details selected does not appear to be valid. Please review the available options and choose one that is listed.'),

                    'status.numeric' => "Something went wrong with selected  status",
                    'status.in'      => "Sorry   selected status  doesnt exist in our records",
                ]
            );
            if ($validator->passes()) {

                if (HasPermission('A92') == 'true') {
                    $data = Employee::find($employee_id);
                    if ($data) {
                        $contact_detail = SalarySlip::with(['company_detail'])->where(['employee_id' => $employee_id])->get();
                        if ($contact_detail->count() > 0) {
                            $action   = 1;
                            $html     = view('employee.partials._employee_salary_slip_data')->with(compact(['contact_detail', 'action']))->render();
                            $msg      = ("success");
                            $msgclass = ("bg-success");
                        } else {
                            $msg      = ("Something went wrong try again later");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg      = ("No Record Found In Our Employee Records");
                        $msgclass = ("bg-danger");
                        $msgtype  = 'val_error';
                    }
                } else {
                    $msg      = ("You Dont Have Permission To change status");
                    $msgclass = ("bg-danger");
                    $msgtype  = 'val_error';
                }
            } else {
                $msg           = throw_error($validator->errors()->all());;
                $msgclass      = ("bg-danger");
                $msgtype       = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg      = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'html' => $html));
    }

    //manish added code

    public function employee_salary_slip_download(Request $req)
    {
        $msg            = '';
        $msgclass       = '';
        $msgtype        = '';
        $msgtype_title  = '';
        $TargetURL      = '';
        $employee_id    = $req->input('employee_id') ?: 0;
        $salary_slip_id = $req->input('salary_slip_id') ?: 0;
        $base64Pdf      = '';
        $file_url       = '';
        $fileName       = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'employee_id'    => 'required|numeric|gt:0|exists:master_employee,emp_id',
                    'salary_slip_id' => 'required|numeric|gt:0|exists:salary_slip,salary_slip_id',
                ],
                [
                    'employee_id.required' => 'Invalid Selected employee details',
                    'employee_id.numeric'  => 'Something went wront with selected employee details',
                    'employee_id.gt'       => 'Something Went Wrong,Please Try Again employee details',
                    'employee_id.exists'   => ('I apologize, but the option you have employee details selected does not appear to be valid. Please review the available options and choose one that is listed.'),

                    'salary_slip_id.required' => 'Invalid Selected salary slip details',
                    'salary_slip_id.numeric'  => 'Something went wront with selected salary slip details',
                    'salary_slip_id.gt'       => 'Something Went Wrong,Please Try Again salary slip details',
                    'salary_slip_id.exists'   => ('I apologize, but the option you have salary slip details selected does not appear to be valid. Please review the available options and choose one that is listed.'),
                ]
            );
            if ($validator->passes()) {


                $data = Employee::find($employee_id);
                if ($data) {
                    $contact_detail = SalarySlip::where(['employee_id' => $employee_id, 'salary_slip_id' => $salary_slip_id])->first();
                    if ($contact_detail) {
                        $action      = 1;
                        $htmlContent = view('employee.partials._employee_salary_slip')->with(compact(['contact_detail']))->render();
                        $filepath    = $this->pdfService->generatePDF($htmlContent, time() . '.pdf', 'F');
                        $fileName    = pathinfo($filepath, PATHINFO_FILENAME) . ".pdf";

                        $filename = get_filename('Files_storage', config('constant.invoice_path_v') . "/" . $fileName);
                        if ($filename) {
                            $file_url = $filename;
                        }

                        $msg      = ("success");
                        $msgclass = ("bg-success");
                    } else {
                        $msg      = ("Something went wrong try again later");
                        $msgclass = ("bg-danger");
                    }
                } else {
                    $msg      = ("No Record Found In Our Employee Records");
                    $msgclass = ("bg-danger");
                    $msgtype  = 'val_error';
                }
            } else {
                $msg           = throw_error($validator->errors()->all());;
                $msgclass      = ("bg-danger");
                $msgtype       = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg      = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        return response()->json(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'file_url' => $file_url, 'fileName' => $fileName));
    }
}
