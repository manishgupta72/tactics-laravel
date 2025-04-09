<?php

namespace App\Http\Controllers;

use App\Models\SalarySlip;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Session;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SalarySlipImport;

class SalarySlipController extends Controller
{
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {
            if (HasPermission('R0') == 'true') {
                $data['MenuNames']               = Admin_Side_Bar();
                $data['title']                   = "Salary Slip List";
                $data['list_route']              = route('SalarySlipController.Filter') ?? "";
                $data['save_route']              = route('save.SalarySlipController') ?? "";
                $data['edit_route']              = route('page.SalarySlipController') ?? "";
                $data['del_route']               = route('delete.SalarySlipController') ?? "";
                $data['view_salary_slip_data']   = route('vw.sl.sp.dt') ?? "";
                $data['status']                  = config('constant.STATUS');
                $data['company_list']            = Company::where(['comp_status' => 1])->get();
                view()->share($data);
                return view('salary-slip.index', $data);
            } else {
                Session::flash('warning', 'You dont have permission of salary slip module ');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('warning', 'Sorry something went wrong with http request ');
            return redirect()->route('dashboard');
        }
    }

    public function pageSalarySlipController(Request $req)
    {
        if ($req->isMethod('get')) {
            if (HasPermission('R0') == 'true') {
                $data = [];
                $data['title'] = "Add/Edit Salary Slip";
                $data['companies'] = Company::where('comp_status', 1)->get();


                return view('salary-slip.save', $data);
            } else {
                Session::flash('warning', 'You do not have permission to access this page.');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('warning', 'Invalid request method.');
            return redirect()->route('dashboard');
        }
    }
    public function SaveSalarySlipController(Request $req)
    {

        $msg                        = '';
        $msgclass                   = '';
        $msgtype                    = '';
        $msgtype_title              = '';
        $TargetURL                  = '';


        $salary_slip_id                           =   $req->input('salary_slip_id') ? $req->input('salary_slip_id') : 0;
        $monthYearPicker                          =   $req->input('monthYearPicker') ? $req->input('monthYearPicker') : "";
        $company_id                               =   $req->input('company_id') ? $req->input('company_id') : 0;

        if ($req->isMethod('post')) {

            $validator = Validator::make(
                $req->all(),
                [
                    'file'                                                  => 'required|mimes:xls,xlsx',
                    'company_id'                                            => [
                        'required',
                        'numeric',
                        'gt:0',
                        function ($attribute, $value, $fail) use ($req) {
                            $existsInDB = DB::table('company')
                                ->where('comp_id', $value)
                                ->where('comp_status', 1)
                                ->exists();
                            if (!$existsInDB) {
                                $fail('The selected company doesnt exist in our records');
                            }
                        },
                    ],
                    'monthYearPicker'                                       => [
                        'required',
                    ],
                ],
                [
                    'monthYearPicker.required' => ('Select Month Year'),
                    'company_id.required'      => "Select company detail",
                    'company_id.numeric'       => "Something went wrong with company detail",
                    'company_id.gt'            => "Something went wrong with company detail",

                ]
            );

            if ($validator->passes()) {

                if ($salary_slip_id  > 0 && $salary_slip_id != "" && is_numeric($salary_slip_id)) {
                    if (HasPermission('A57') == 'true') {
                        $check_record_exit = Faq::find($faq_id);
                        if ($check_record_exit != null) {

                            if (isset($faq_status)) {
                                $check_record_exit->update(['faq_status' => $faq_status], ['where' => ['faq_id', '=', $faq_id]]);
                            }


                            $data = [
                                'faq_question'                                      => $faq_question,
                                'faq_answer'                                        => $faq_answer,
                                'updatedon'                                         => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type']
                            ];
                            $res =   $check_record_exit->update($data, ['where' => ['faq_id', '=', $faq_id]]);
                            if ($res == 0 || $res) {
                                $msg = ("Faq Updated Successfully");
                                $msgclass = ("bg-success");
                            } else {
                                $msg = "Something Went Wrong Try Again Later";
                                $msgclass = ("bg-danger");
                            }
                        } else {
                            $msg = ("Sorry selected faq doesnt exist in our records");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg            =  " You do not have the required permissions to update faq . Please contact your system administrator or the person
                                            responsible for managing user permissions.";
                        $msgclass       = ("bg-danger");
                        $msgtype        = 'val_error';
                        $msgtype_title  = 'Insufficient Permissions:';
                    }
                } else {

                    if (HasPermission('A88') == 'true') {
                        $month_year_explode = explode('-', $monthYearPicker);

                        $import = new SalarySlipImport($company_id, $monthYearPicker);
                        Excel::import($import, $req->file('file'));

                        $employee_ids = $import->getEmployeeIds();
                        if (check_valid_array($employee_ids)) {

                            Employee::whereIn('emp_id', $employee_ids)
                                ->where('emp_company_id', '=', $company_id)
                                ->update(['emp_status' => 1]);

                            // Employee::whereNotIn('employee_id', $employee_ids)
                            //                 ->where('company_id','=',$company_id)
                            //                 ->update(['status' => 0]);
                        }


                        if ($import) {
                            $msg = ("Saved successfully");
                            $msgclass = ("bg-success");
                        } else {
                            $msg = ("Something went wrong try again after sometime");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg            =  " You do not have the required permissions to add faq. Please contact your system administrator or the person
                                            responsible for managing user permissions.";
                        $msgclass       = ("bg-danger");
                        $msgtype        = 'val_error';
                        $msgtype_title  = 'Insufficient Permissions:';
                    }
                }
            } else {
                $msg = throw_error($validator->errors()->all());
                $msgclass = ("bg-danger");
                $msgtype  = 'val_error';
                $msgtype_title  = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }

        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title));
    }

    public function SalarySlipControllerFilter(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('R0') == 'true') {
                if ($req->post('is_date_search')) {
                    $columns = array('ss.salary_slip_id', 'ss.company_id', 'ss.month_p', 'total_employee', 'total_uploaded', 'total_previous');

                    $Query = "select ss.* ,
                                      c.comp_name,
                                        (SELECT count(em.emp_id) FROM master_employee em WHERE em.emp_company_id = c.comp_id ) total_employee,
                                        (SELECT count(em.emp_id) FROM master_employee em WHERE em.emp_company_id = c.comp_id AND em.emp_status=0 ) total_previous,
                                        count(ss.employee_id) total_uploaded
                                        from salary_slip ss
                                             LEFT JOIN (
                                                        SELECT * FROM company WHERE comp_status = 1
                                                    ) AS c ON ss.company_id = c.comp_id
                                 where 1";


                    if ($req->post('is_date_search') == 'yes') {
                        $url_components = parse_url(admin_getBaseURL() . '?' . $req->post('date'));
                        parse_str($url_components['query'], $params);
                        foreach ($params as $key => $value) {
                            if ($value != "" && $key != 'reservation') {
                                if ($key == 'AddedOn') {
                                    $Date = explode('TO', $value);
                                    $Q[] =   'AddedOn Between "' . date('Y-m-d 00:00:00', strtotime($Date[0])) . '" ' . ' AND "' . date('Y-m-d 23:59:59', strtotime($Date[1])) . '" ';
                                } else {
                                    $Q[] =   $key . '=' . "'" . $value . "'";
                                }
                            }
                        }
                        $Query .= ' AND ' . implode(' AND ', $Q);
                    }

                    if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                        $Query .= " AND
                                    (
                                        c.comp_name like '%" . $req->post('search')["value"] . "%' 
                                    )
                                    ";
                    }
                    $Query .= ' GROUP  BY ss.company_id,ss.year_p,ss.month_p ';
                    if ($req->post('order') && !empty($req->post('order'))) {
                        $Col = $columns[$req->post('order')['0']['column']];
                        $Order = $req->post('order')['0']['dir'];
                        $Query .= " ORDER BY  $Col $Order ";
                    } else {
                        $Query .= ' ORDER BY ss.salary_slip_id  DESC ';
                    }

                    $Query1 = '';
                    if ($req->post('length') != -1) {
                        $Query1 = 'LIMIT ' . $req->post('start') . ', ' . $req->post('length');
                    }

                    $res = DB::select($Query, array());
                    $number_filter_row = count($res);
                    $result = DB::select($Query . $Query1, array());
                    $data = array();
                    if (count($result) > 0) {
                        $i = 1;
                        foreach (objectToArray($result) as $key => $row) {
                            $sub_array = array();

                            $sub_array[] = $row["salary_slip_id"] ? $row["salary_slip_id"] : 0;
                            $sub_array[] = $row["comp_name"] ? $row["comp_name"] : "";
                            $sub_array[] = $row["month_p"] . " - " . $row["year_p"];
                            $sub_array[] = $row["total_employee"] ?: 0;
                            $sub_array[] = $row["total_uploaded"] ?: 0;
                            $sub_array[] = $row["total_previous"] ?: 0;



                            $action = '<div class="btn-group" role="group">
                            <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                    <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';
                            if (HasPermission('A89') == 'true') {
                                $action .= '<a target="_blank" class="dropdown-item text-1 view_data" data-company_id="' . $row["company_id"] . '" data-year_p="' . $row["year_p"] . '" data-month_p="' . $row["month_p"] . '" data-company_name="' . $row["comp_name"] . '"    href="JavaScript:void(0)"><i class="fa fa-eye"></i>View</a>';
                            }
                            // if (HasPermission('A57') == 'true') {
                            //     $action .= '<a target="_blank" class="dropdown-item text-1 edit_faq" data-company_id="'. $row["company_id"] .'"    href="JavaScript:void(0)"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
                            // }


                            if (HasPermission('A91') == 'true') {
                                $action .= '<a class="dropdown-item text-1 delete_company_data" data-company_id="' . $row["company_id"] . '" data-year_p="' . $row["year_p"] . '" data-month_p="' . $row["month_p"] . '" data-company_name="' . $row["comp_name"] . '"    href="JavaScript:void(0)"><i class="fa-solid fa-trash-can"></i>  Delete</a>';
                            }
                            $action .= '</div>';
                            $action .= '</ul>';
                            $action .= '</div>';
                            $sub_array[]  = $action;
                            $data[] = $sub_array;
                            $i++;
                        }
                    }

                    $output = array(
                        "draw"    => intval($req->post('draw')),
                        "recordsTotal"  => count(DB::select($Query)),
                        "recordsFiltered" => $number_filter_row,
                        "data"    => $data
                    );

                    echo json_encode($output);
                }
            }
        }
    }

    public function view_salary_slip_data(Request $req)
    {
        
        $msg              = '';
        $msgclass         = '';
        $msgtype          = '';
        $msgtype_title    = '';
        $TargetURL        = '';
        $contact_details  = [];
        $html             = '';

        $year_p     = $req->input('year_p') ? $req->input('year_p') : "";
        $month_p    = $req->input('month_p') ? $req->input('month_p') : "";
        $company_id = $req->input('company_id') ? $req->input('company_id') : 0;

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'company_id'                                            => [
                        'required',
                        'numeric',
                        'gt:0',
                        function ($attribute, $value, $fail) use ($req) {
                            $existsInDB = DB::table('company')
                                ->where('comp_id', $value)
                                ->where('comp_status', 1)
                                ->exists();
                            if (!$existsInDB) {
                                $fail('The selected company doesnt exist in our records');
                            }
                        },
                    ],
                    'year_p'                                                => [
                        'required',
                    ],
                    'month_p'                                                => [
                        'required',
                    ],
                ],
                [
                    'year_p.required'                                        => ('Select Year'),
                    'month_p.required'                                       => ('Select Month'),
                    'company_id.required'                                    => "Select company detail",
                    'company_id.numeric'                                     => "Something went wrong with company detail",
                    'company_id.gt'                                          => "Something went wrong with company detail",

                ]
            );

            if ($validator->passes()) {

                $contact_detail                     = SalarySlip::where(['company_id' => $company_id, 'year_p' => $year_p, 'month_p' => $month_p])->get();
                if ($contact_detail->count() > 0) {
                    $action    = 1;
                    $html      = view('salary-slip.partial.__view_company_details')->with(compact(['contact_detail', 'action']))->render();
                    $msg       = ("success");
                    $msgclass  = ("bg-success");
                } else {
                    $msg = ("Something went wrong try again later");
                    $msgclass = ("bg-danger");
                }
            } else {
                $msg            =  throw_error($validator->errors()->all());;
                $msgclass       = ("bg-danger");
                $msgtype        = 'val_error';
                $msgtype_title  = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something wrong with http request");
            $msgclass = ("bg-danger");
        }
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'html' => $html));
    }


    public function DeleteSalarySlipController(Request $req)
    {
        $msg           = '';
        $msgclass      = '';
        $msgtype       = '';
        $msgtype_title = '';
        $TargetURL     = '';
        $year_p        = $req->input('year_p') ? $req->input('year_p') : "";
        $month_p       = $req->input('month_p') ? $req->input('month_p') : "";
        $company_id    = $req->input('company_id') ? $req->input('company_id') : 0;

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'company_id'                                            => [
                        'required',
                        'numeric',
                        'gt:0',
                        function ($attribute, $value, $fail) use ($req) {
                            $existsInDB = DB::table('company')
                                ->where('comp_id', $value)
                                ->where('comp_status', 1)
                                ->exists();
                            if (!$existsInDB) {
                                $fail('The selected company doesnt exist in our records');
                            }
                        },
                    ],
                    'year_p'                                                => [
                        'required',
                    ],
                    'month_p'                                                => [
                        'required',
                    ],
                ],
                [
                    'year_p.required'                                        => ('Select Year'),
                    'month_p.required'                                       => ('Select Month'),
                    'company_id.required'                                    => "Select company detail",
                    'company_id.numeric'                                     => "Something went wrong with company detail",
                    'company_id.gt'                                          => "Something went wrong with company detail",

                ]
            );
            if ($validator->passes()) {
                if (HasPermission('A91') == 'true') {
                    $data = SalarySlip::where(['company_id' => $company_id, 'year_p' => $year_p, 'month_p' => $month_p]);
                    if ($data) {
                        if ($data->delete()) {
                            $msg = (" Deleted Successfully Done");
                            $msgclass = ("bg-success");
                        } else {
                            $msg = ("Something Went Wrong Please Try Again Later");
                            $msgclass = ("bg-danger");
                            $msgtype        = 'val_error';
                        }
                    } else {
                        $msg = ("No Record Found In Our  Records");
                        $msgclass = ("bg-danger");
                        $msgtype        = 'val_error';
                    }
                } else {
                    $msg = ("You Dont Have Permission To Delete company details");
                    $msgclass = ("bg-danger");
                    $msgtype        = 'val_error';
                }
            } else {
                $msg            =  throw_error($validator->errors()->all());;
                $msgclass       = ("bg-danger");
                $msgtype        = 'val_error';
                $msgtype_title  = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title));
    }
   
}
