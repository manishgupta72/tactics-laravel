<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\MasterRoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CompanyController extends Controller
{
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {
            $data['MenuNames']  = Admin_Side_Bar();
            $data['title']      = "Company List";
            $data['list_route'] = route('CompanyController.Filter') ?? "";
            $data['save_route'] = route('save.CompanyController') ?? "";
            $data['edit_route'] = route('page.CompanyController') ?? "";
            $data['del_route']  = route('delete.CompanyController') ?? "";
            $data['view_route'] = route('view.CompanyController') ?? "";
            $data['status']     = config('constant.STATUS');
            view()->share($data);
            return view('company.index', $data);
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    public function pageCompanyController(Request $req)
    {
        if ($req->isMethod('get')) {
            $data['role_list']  = MasterRoll::where(['Status' => 1])->get(['RollId', 'RollName']);
            $data['list_route'] = route('CompanyController.Filter') ?? "";
            $data['save_route'] = route('save.CompanyController') ?? "";
            $data['edit_route'] = route('page.CompanyController') ?? "";
            $data['del_route']  = route('delete.CompanyController') ?? "";
            $data['view_route'] = route('view.CompanyController') ?? "";
            $data['status']     = config('constant.STATUS');
            $data['EID']        = "";

            if ($req->segment(3) == 'EID') {
                if (HasPermission('C2') == 'true') {
                    $data['EID']       = $req->segment(3);
                    $data['MenuNames'] = Admin_Side_Bar();
                    $data['title']     = 'Edit Company';
                    $company           = Company::find($req->segment(4));

                    if ($company != null && $req->segment(4) > 0) {

                        $data['detail'] = $company;
                        view()->share($data);
                        return view('company.save', $data);
                    } else {
                        Session::flash('warning', 'Sorry something went wrong try again later ');
                        return redirect()->route('pages.CompanyController');
                    }
                } else {

                    Session::flash('warning', 'You dont have permission of Company edit page module ');
                    return redirect()->route('pages.CompanyController');
                }
            } else {
                if (HasPermission('C1') == 'true') {
                    $data['title']     = "Add Company";
                    $data['MenuNames'] = Admin_Side_Bar();
                    view()->share($data);
                    return view('company.save', $data);
                } else {
                    Session::flash('warning', 'You dont have permission of Associates save page module ');
                    return redirect()->route('pages.AssociatesController');
                }
            }
        } else {
            Session::flash('warning', 'Sorry something went wrong with http request ');
            return redirect()->route('dashboard');
        }
    }

    public function saveCompanyController(Request $req)
    {
        $msg           = '';
        $msgclass      = '';
        $msgtype       = '';
        $msgtype_title = '';
        $TargetURL     = '';

        $comp_id       = $req->input('comp_id');
        $comp_name     = $req->input('comp_name') ?? '';
        $comp_location = $req->input('comp_location') ?? '';
        $comp_status   = $req->input('comp_status') ?? '';

        if ($req->ajax() && $req->isMethod('post')) {
            // Validate the incoming request
            $validator = Validator::make(
                $req->all(),
                [
                    'comp_name'     => 'required|string|max:255',
                    'comp_location' => 'required|string|max:255',
                    'comp_status'   => 'required|string',
                ],
                [
                    'comp_name.required'     => 'Enter the company name.',
                    'comp_location.required' => 'Enter the company location.',
                    'comp_status.required'   => 'Select the company status.',
                ]
            );

            // Handle validation errors
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->all(),
                ], 422); // HTTP 422 Unprocessable Entity
            }

            try {
                // Determine whether to create or update a company
                if ($comp_id > 0) {
                    // Update existing company
                    if (HasPermission('C2') == 'true') {
                        $company = Company::find($comp_id);

                        if ($company) {
                            $data = [
                                'comp_name'     => $comp_name,
                                'comp_location' => $comp_location,
                                'comp_status'   => $comp_status,
                                'updated_at'    => now(),
                                'updatedon'     => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                            ];

                            $company->update($data);

                            $TargetURL = route('pages.CompanyController');
                            $msg       = 'Company updated successfully.';
                            $msgclass  = 'bg-success';
                        } else {
                            $msg      = 'Sorry, the selected company does not exist in our records.';
                            $msgclass = 'bg-danger';
                        }
                    } else {
                        $msg      = 'You do not have permission to update companies.';
                        $msgclass = 'bg-danger';
                    }
                } else {
                    // Create a new company
                    if (HasPermission('C1') == 'true') {
                        $data = [
                            'comp_name'     => $comp_name,
                            'comp_location' => $comp_location,
                            'comp_status'   => $comp_status,
                            $data['created_at']                        = now(),
                            'addedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                        ];

                        $company = Company::create($data);

                        $TargetURL = route('pages.CompanyController');
                        $msg       = 'Company added successfully.';
                        $msgclass  = 'bg-success';
                    } else {
                        $msg      = 'You do not have permission to add companies.';
                        $msgclass = 'bg-danger';
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error in saveCompanyController: ' . $e->getMessage());
                $msg      = 'An unexpected error occurred. Please try again later.';
                $msgclass = 'bg-danger';
            }
        } else {
            $msg      = 'Invalid HTTP request.';
            $msgclass = 'bg-danger';
        }

        return response()->json([
            'msg'           => $msg,
            'msgsuc'        => $msgclass,
            'msgfail'       => $msgclass,
            'msgtype'       => $msgtype,
            'msgtype_title' => $msgtype_title,
            'TargetURL'     => $TargetURL,
        ]);
    }


    public function companyControllerFilter(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('C0') == 'true') {
                if ($req->post('is_date_search')) {
                    $columns = array('comp_name', 'comp_location', 'comp_status', 'created_at');
                    $Query   = "SELECT * FROM company WHERE 1=1";                                 // Start with a valid base query
                    $Q       = [];                                                                // Initialize conditions

                    if ($req->post('is_date_search') == 'yes') {
                        $url_components = parse_url(admin_getBaseURL() . '?' . $req->post('date'));
                        parse_str($url_components['query'], $params);
                        foreach ($params as $key => $value) {
                            if ($value != "" && $key != 'reservation') {
                                if ($key == 'created_at') {
                                    $Date = explode('TO', $value);
                                    $Q[]  = 'created_at BETWEEN "' . date('Y-m-d 00:00:00', strtotime($Date[0])) . '" AND "' . date('Y-m-d 23:59:59', strtotime($Date[1])) . '"';
                                } else {
                                    $Q[] = $key . " = '" . $value . "'";
                                }
                            }
                        }
                        $Query .= ' AND ' . implode(' AND ', $Q);
                    }

                    if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                        $searchValue  = $req->post('search')["value"];
                        $Query       .= " AND (comp_name LIKE '%$searchValue%' OR comp_location LIKE '%$searchValue%' OR comp_status LIKE '%$searchValue%')";
                    }

                    if ($req->post('order') && !empty($req->post('order'))) {
                        $Col    = $columns[$req->post('order')['0']['column']];
                        $Order  = $req->post('order')['0']['dir'];
                        $Query .= " ORDER BY $Col $Order ";
                    } else {
                        $Query .= ' ORDER BY comp_id DESC ';
                    }

                    $Query1 = '';
                    if ($req->post('length') != -1) {
                        $Query1 = ' LIMIT ' . $req->post('start') . ', ' . $req->post('length');
                    }

                    $res               = DB::select($Query);
                    $number_filter_row = count($res);
                    $result            = DB::select($Query . $Query1, array());
                    $data              = array();

                    if (count($result) > 0) {
                        $i = 1;
                        foreach (objectToArray($result) as $key => $row) {
                            $sub_array = array();

                            $sub_array[] = $row["comp_name"];
                            $sub_array[] = $row["comp_location"];
                            $sub_array[] = $row["comp_status"] > 0 && !empty($row["comp_status"]) ? config('constant.STATUS')[$row["comp_status"]] : "Inactive";


                            $action = '<div class="btn-group" role="group">
                                <button id = "btnGroupVerticalDrop1" type = "button" class = "btn btn-primary dropdown-toggle" data-bs-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">
                                    Action
                                    <i class = "mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';

                            if (HasPermission('C2') == 'true') {
                                $action .= '<a class="dropdown-item edit-company-btn" data-company_name="' . $row["comp_name"] . '" data-id="' . $row["comp_id"] . '" href="JavaScript:void(0)">Edit</a>';
                            }

                            if (HasPermission('C3') == 'true') {
                                $action .= '<a class="dropdown-item delete_row" data-company_name="' . $row["comp_name"] . '" data-id="' . $row["comp_id"] . '" href="JavaScript:void(0)"> Delete</a>';
                            }

                            // if (HasPermission('C4') == 'true') {
                            //     $action .= '<a class="dropdown-item view_company" data-id="' . $row["comp_id"] . '" href="JavaScript:void(0)">View</a>';
                            // }

                            $action .= '</div>';
                            $action .= '</ul>';
                            $action .= '</div>';

                            $sub_array[] = $action;
                            $data[]      = $sub_array;
                            $i++;
                        }
                    }

                    $output = array(
                        "draw"            => intval($req->post('draw')),
                        "recordsTotal"    => count(DB::select($Query)),
                        "recordsFiltered" => $number_filter_row,
                        "data"            => $data
                    );

                    echo json_encode($output);
                }
            }
        }
    }


    public function deleteCompanyController(Request $req)
    {
        $msg           = '';
        $msgclass      = '';
        $msgtype       = '';
        $msgtype_title = '';
        $TargetURL     = '';


        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'id' => 'required|numeric|exists:company,comp_id',
                ],
                [
                    'id.required' => 'Invalid selected company.',
                    'id.numeric'  => 'Something went wrong with the selected company.',
                    'id.gt'       => 'Something went wrong, please try again later.',
                    'id.exists'   => 'The selected company does not exist in our records.',
                ]
            );

            if ($validator->passes()) {
                $id = $req->input('id') ?? 0;
                if (HasPermission('C3') == 'true') {

                    $data = Company::find($id);
                    if ($data != null) {
                        if ($data->delete()) {
                            $msg      = "Company deleted successfully.";
                            $msgclass = "bg-success";
                        } else {
                            $msg      = "Something went wrong, please try again later.";
                            $msgclass = "bg-danger";
                            $msgtype  = 'val_error';
                        }
                    } else {
                        $msg      = "No record found for the selected company.";
                        $msgclass = "bg-danger";
                        $msgtype  = 'val_error';
                    }
                } else {
                    $msg      = "You don't have permission to delete this company.";
                    $msgclass = "bg-danger";
                    $msgtype  = 'val_error';
                }
            } else {
                $msg           = throw_error($validator->errors()->all());
                $msgclass      = "bg-danger";
                $msgtype       = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg      = "Something wrong with the HTTP request.";
            $msgclass = "bg-danger";
        }

        echo json_encode([
            'msg'           => $msg,
            'msgsuc'        => $msgclass,
            'msgfail'       => $msgclass,
            'msgtype'       => $msgtype,
            'msgtype_title' => $msgtype_title
        ]);
    }
}
