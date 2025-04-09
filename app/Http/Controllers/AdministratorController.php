<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterRoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class AdministratorController extends Controller
{
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {
            if (HasPermission('B0') == 'true') {
                $data['MenuNames']                  = Admin_Side_Bar();
                $data['title']                      = "Administrator  List";
                $data['list_route']                 = route('AdministratorController.Filter') ?? "";
                $data['save_route']                 = route('save.AdministratorController') ?? "";
                $data['edit_route']                 = route('page.AdministratorController') ?? "";
                $data['del_route']                  = route('delete.AdministratorController') ?? "";
                $data['status']                     = config('constant.STATUS');
                view()->share($data);
                return view('administrator.admin', $data);
            } else {
                Session::flash('warning', 'You dont have permission of admin module ');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('warning', 'Sorry something went wrong with http request ');
            return redirect()->route('dashboard');
        }
    }

    public function pageAdministratorController(Request $req)
    {
        if ($req->isMethod('get')) {

            $data['role_list']                  = MasterRoll::where(['Status' => 1])->get(['RollId', 'RollName']);
            $data['list_route']                 = route('AdministratorController.Filter') ?? "";
            $data['save_route']                 = route('save.AdministratorController') ?? "";
            $data['edit_route']                 = route('page.AdministratorController') ?? "";
            $data['del_route']                  = route('delete.AdministratorController') ?? "";
            $data['EID']                        = "";

            if ($req->segment(3) == 'EID') {
                if (HasPermission('A7') == 'true') {
                    $data['EID']        = $req->segment(3);
                    $data['MenuNames']  = Admin_Side_Bar();
                    $data['title']      = ('Edit Administrator');
                    $roll_detail        = User::find($req->segment(4));
                    
                    if ($roll_detail != null && $req->segment(4) > 0) {

                        $data['detail']        = $roll_detail;
                        view()->share($data);
                        return view('administrator.admin_save', $data);
                    } else {
                        Session::flash('warning', 'Sorry something went wrong try again later ');
                        return redirect()->route('pages.AdministratorController');
                    }
                } else {

                    Session::flash('warning', 'You dont have permission of admin edit page module ');
                    return redirect()->route('pages.AdministratorController');
                }
            } else {
                if (HasPermission('A6') == 'true') {
                    $data['title']                      = "Add Administrator";
                    $data['MenuNames']                  = Admin_Side_Bar();
                    view()->share($data);
                    return view('administrator.admin_save', $data);
                } else {
                    Session::flash('warning', 'You dont have permission of admin save page module ');
                    return redirect()->route('pages.AdministratorController');
                }
            }
        } else {
            Session::flash('warning', 'Sorry something went wrong with http request ');
            return redirect()->route('dashboard');
        }
    }

    public function SaveAdministratorController(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $msgtype = '';
        $msgtype_title = '';
        $TargetURL = '';

        $id = $req->input('id') ? $req->input('id') : 0;
        $name = $req->input('name') ? $req->input('name') : "";
        $email = $req->input('email') ? $req->input('email') : "";
        $ward_no = $req->input('ward_no') ? $req->input('ward_no') : "";
        $UserType = $req->input('UserType') ? $req->input('UserType') : 0;
        $admin_type = $req->input('admin_type') ? $req->input('admin_type') : "";
        $password = $req->input('password') ? Hash::make($req->input('password')) : "";
        $status = $req->input('status') && $req->input('status') == 'on' ? 1 : 0;

        if ($req->ajax() && $req->isMethod('post')) {

            $validator = Validator::make(
                $req->all(),
                
                [
                    'name' => 'required',
                    'email' => [
                        'required',
                        'email',
                        function ($attribute, $value, $fail) use ($req) {
                            $id = $req->input('id');
                            if ($id == 0) {
                                $existsInDB = DB::table('users')->where('email', $value)->exists();
                                if ($existsInDB) {
                                    $fail('The email already exists in the records.');
                                }
                            }
                        },
                    ],
                    'UserType' => 'required|numeric|gt:0|exists:master_role,RollId',
                    'admin_type' => 'nullable|string|max:255',
                    'password' => [
                        Rule::requiredIf(function () use ($req) {
                            return $req->input('id') == 0;
                        }),
                        Rule::when($req->input('id') == 0, ['min:8']),
                    ],
                ],
                [
                    'name.required' => 'Enter Name',
                    'email.required' => 'Enter Email',
                    'email.email' => 'Please enter a valid email address.',
                    'UserType.required' => 'Invalid Selected Role',
                    'UserType.numeric' => 'Invalid Selected Role',
                    'UserType.gt' => 'Something Went Wrong With Selected Role',
                    'UserType.exists' => 'The selected role does not appear to be valid.',
                    'admin_type.required' => 'Enter a valid admin_type.',
                    'admin_type.max' => 'admin_type must not exceed 255 characters.',
                    'password.required' => 'The password field is required.',
                    'password.min' => 'The password must be at least 8 characters long.',
                ]
            );

            if ($validator->passes()) {
                if ($id > 0 && $id != "" && is_numeric($id)) {
                    if (HasPermission('A7') == 'true') {
                        $check_record_exit = User::find($id);
                        if ($check_record_exit != null) {
                            if (!empty($UserType) && $UserType > 0) {
                                $check_record_exit->update(['UserType' => $UserType]);
                            }

                            if (!empty($password) && $password) {
                                $check_record_exit->update(['password' => $password]);
                            }

                            $data = [
                                'name' => $name,
                                'email' => $email,
                                'ward_no' => $ward_no,
                                'admin_type' => $admin_type,
                                'status' => $status,
                                'updatedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type']
                            ];
                            $res = $check_record_exit->update($data);
                            
                            if ($res == 0 || $res) {
                                $TargetURL = route('pages.AdministratorController');
                                $msg = "Administrator Updated Successfully";
                                $msgclass = "bg-success";
                            } else {
                                $msg = "Something Went Wrong Try Again Later";
                                $msgclass = "bg-danger";
                            }
                        } else {
                            $msg = "Sorry selected administrator doesn't exist in our records";
                            $msgclass = "bg-danger";
                        }
                    } else {
                        $msg = "You do not have the required permissions to update admin.";
                        $msgclass = "bg-danger";
                        $msgtype = 'val_error';
                        $msgtype_title = 'Insufficient Permissions:';
                    }
                } else {
                    if (HasPermission('A6') == 'true') {
                        $data = [
                            'name' => $name,
                            'email' => $email,
                            'ward_no' => $ward_no,
                            'admin_type' => $admin_type,
                            'status' => $status,
                            'UserType' => $UserType,
                            'password' => $password,
                            'addedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type']
                        ];

                        $res = User::create($data);
                        $last_id = $res->id;
                        if ($last_id > 0) {
                            $TargetURL = route('pages.AdministratorController');
                            $msg = "Administrator added successfully";
                            $msgclass = "bg-success";
                        } else {
                            $msg = "Something went wrong try again after sometime";
                            $msgclass = "bg-danger";
                        }
                    } else {
                        $msg = "You do not have the required permissions to add administrator.";
                        $msgclass = "bg-danger";
                        $msgtype = 'val_error';
                        $msgtype_title = 'Insufficient Permissions:';
                    }
                }
            } else {
                $msg = throw_error($validator->errors()->all());
                $msgclass = "bg-danger";
                $msgtype = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = "Something Wrong With Http Request";
            $msgclass = "bg-danger";
        }

        echo json_encode([
            'msg' => $msg,
            'msgsuc' => $msgclass,
            'msgfail' => $msgclass,
            'msgtype' => $msgtype,
            'msgtype_title' => $msgtype_title,
            'TargetURL' => $TargetURL
        ]);
    }


    public function AdministratorControllerFilter(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('B0') == 'true') {
                if ($req->post('is_date_search')) {
                    $columns = array('name', 'email', 'status', 'UserType');
                    $Query = "select u.*, mr.RollName  from users u
                              left join master_role mr on mr.RollId = u.UserType
                                where u.UserType != 'ALL' ";
                    if ($req->post('is_date_search') == 'yes') {
                        $url_components = parse_url(admin_getBaseURL() . '?' . $req->post('date'));
                        parse_str($url_components['query'], $params);
                        foreach ($params as $key => $value) {
                            if ($value != "" && $key != 'reservation') {
                                if ($key == 'AddedOn') {
                                    $Date = explode('TO', $value);
                                    $Q[] =   'AddedOn Between "' . date('Y-m-d 00:00:00', strtotime($Date[0])) . '" ' . ' AND "' . date('Y-m-d 23:59:59', strtotime($Date[1])) . '" ';
                                } else {
                                    $Q[] =  'u.' . $key . '=' . "'" . $value . "'";
                                }
                            }
                        }
                        $Query .= ' AND ' . implode(' AND ', $Q);
                    }

                    if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                        $Query .= " AND (mr.RollName like '%" . $req->post('search')["value"] . "%' 
                                        OR u.name like '%" . $req->post('search')["value"] . "%'
                                        OR u.email  like '%" . $req->post('search')["value"] . "%') ";
                    }

                    if ($req->post('order') && !empty($req->post('order'))) {
                        $Col = $columns[$req->post('order')['0']['column']];
                        $Order = $req->post('order')['0']['dir'];
                        $Query .= " ORDER BY  u.$Col $Order ";
                    } else {
                        $Query .= ' ORDER BY u.id   DESC ';
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

                            $sub_array[] = $row["name"];
                            $sub_array[] = $row["email"];
                            $sub_array[] = $row['status'] === 1 ? 'Active' : 'Inactive';
                            $sub_array[] = $row["RollName"];
                            $sub_array[] = $row["admin_type"] > 0 && !empty($row["admin_type"]) ? config('constant.ADMIN_TYPE')[$row["admin_type"]] : "";

                            $action = '<div class="btn-group" role="group">
                                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                            <i class="mdi mdi-chevron-down"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';
                            if (HasPermission('A7') == 'true') {
                                $action .= '<a target="_blank" class="dropdown-item"  href="' . route('page.AdministratorController')  . '/EID/' . $row["id"] . '"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
                            }

                           

                            if (HasPermission('A8') == 'true') {
                                $action .= '<a class="dropdown-item delete_admin" data-name="' . $row["name"] . '"  data-id="' . $row["id"] . '"  href="JavaScript:void(0)"><i class="fa-solid fa-trash-can"></i>  Delete</a>';
                            }
                            $action .= '</div>';
                            $action .= ' </ul>';
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

    public function DeleteAdministratorController(Request $req)
    {
        $msg              = '';
        $msgclass         = '';
        $msgtype          = '';
        $msgtype_title    = '';
        $TargetURL        = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'id'                => 'required|numeric|gt:0|exists:users,id',
                ],
                [
                    'id.required'       => 'Invalid Selected user',
                    'id.numeric'        => 'Something went wront with selected user',
                    'id.gt'             => 'Something Went Wrong,Please Try Again Later',
                    'id.exists'         => ('I apologize, but the option you have user selected does not appear to be valid. Please review the available options and choose one that is listed.'),
                ]
            );
            if ($validator->passes()) {
                $id         =  $req->input('id') ?? 0;
                if (HasPermission('A8') == 'true') {
                    $data = User::find($id);
                    if ($data != null) {
                        if ($data->delete()) {
                            $msg = ("User Deleted Successfully Done");
                            $msgclass = ("bg-success");
                        } else {
                            $msg = ("Something Went Wrong Please Try Again Later");
                            $msgclass = ("bg-danger");
                            $msgtype        = 'val_error';
                        }
                    } else {
                        $msg = ("No Record Found In Our Users Records");
                        $msgclass = ("bg-danger");
                        $msgtype        = 'val_error';
                    }
                } else {
                    $msg = ("You Dont Have Permission To Delete User");
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
