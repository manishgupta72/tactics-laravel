<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Carbon\Carbon;
use App\Models\Menu;
use App\Models\MasterRoll;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class RollsController extends Controller
{
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {
            if (HasPermission('A0') == 'true') {
                $data['MenuNames']                  = Admin_Side_Bar();
                $data['title']                      = "Roles List";
                $data['list_route']                 = route('RollsController.Filter') ?? "";
                $data['save_route']                 = route('save.RollsController') ?? "";
                $data['edit_route']                 = route('page.RollsController') ?? "";
                $data['del_route']                  = route('delete.RollsController') ?? "";
                $data['status']                     = config('constant.STATUS');
                $data['view_permisssion']           = route('list.prmsn') ?? "";
                view()->share($data);
                return view('roles.roles', $data);
            } else {
                Session::flash('warning', 'You dont have permission of role save module ');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('warning', 'Sorry something went wrong with http request ');
            return redirect()->route('dashboard');
        }
    }

    public function pageRollsController(Request $req)
    {
        if ($req->isMethod('get')) {

            $data['menu_list']                  = Menu::where(['Status' => 'Y'])->get(['MenuID', 'MenuTitle', 'SubMenuID']);
            $data['save_route']                 = route('save.RollsController') ?? "";
            $data['list_route']                 = route('pages.RollsController') ?? "";
            $data['save_route']                 = route('save.RollsController') ?? "";
            $data['edit_route']                 = route('page.RollsController') ?? "";
            $data['del_route']                  = route('delete.RollsController') ?? "";
            $data['view_permisssion']           = route('list.prmsn') ?? "";
            $data['EID']                        = "";

            if ($req->segment(3) == 'EID') {
                if (HasPermission('A2') == 'true') {
                    $data['EID']        = $req->segment(3);
                    $data['MenuNames']  = Admin_Side_Bar();
                    $data['title']      = ('Edit Role');
                    $roll_detail        = MasterRoll::where(['RollId' => $req->segment(4)])->select('RollName', 'RollAssignID', 'Status', 'RollId')->first();

                    if ($roll_detail != null && $req->segment(4) > 0) {

                        $data['roll_detail']        = $roll_detail;
                        view()->share($data);
                        return view('roles.roles_save', $data);
                    } else {
                        Session::flash('warning', 'Sorry something went wrong try again later ');
                        return redirect()->route('pages.RollsController');
                    }
                } else {

                    Session::flash('warning', 'You dont have permission of role edit page module ');
                    return redirect()->route('pages.RollsController');
                }
            } else {
                if (HasPermission('A1') == 'true') {
                    $data['title']                      = "Add Role";
                    $data['MenuNames']                  = Admin_Side_Bar();
                    view()->share($data);
                    return view('roles.roles_save', $data);
                } else {
                    Session::flash('warning', 'You dont have permission of role save page module ');
                    return redirect()->route('dashboard');
                }
            }
        } else {
            Session::flash('warning', 'Sorry something went wrong with http request ');
            return redirect()->route('dashboard');
        }
    }

    public function SaveRollsController(Request $req)
    {
        $msg              = '';
        $msgclass         = '';
        $msgtype          = '';
        $msgtype_title    = '';
        $TargetURL        = '';

        $RollId           =   $req->input('RollId') ? $req->input('RollId') : 0;
        $RollName         =   $req->input('RollName') ? $req->input('RollName') : "";
        $RollAssignID     =   $req->input('RollAssignID') ? implode(',', $req->input('RollAssignID')) : "";
        $Status           =   $req->input('Status') && $req->input('Status') == 'on' ? 1 : 0;



        if ($req->ajax() && $req->isMethod('post')) {

            $validator = Validator::make(
                $req->all(),
                [
                    'RollName'                  => 'required',
                    'RollAssignID'              => 'required|array|exists:menu,SubMenuID',

                ],
                [
                    'RollName.required'         => 'Enter Roll Name',
                    'RollAssignID.required'     => 'Select Permission',
                    'RollAssignID.array'        => 'Select atlease one permission',
                    'RollAssignID.exists'       => 'Select  permission doesnt exist in our records',
                ]
            );
            if ($validator->passes()) {
                if ($RollId  > 0 && $RollId != "" && is_numeric($RollId)) {

                    if (HasPermission('A2') == 'true') {
                        $check_record_exit = MasterRoll::find($RollId);
                        if ($check_record_exit != null) {
                            $data = [
                                'RollName'                                      => $RollName,
                                'RollAssignID'                                  => $RollAssignID,
                                'Status'                                        => $Status,
                                'updatedon'                                     => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type']
                            ];

                            $res =   $check_record_exit->update($data);
                            if ($res == 0 || $res) {
                                $TargetURL = route('pages.RollsController');
                                $msg = ("Role Updated Successfully");
                                $msgclass = ("bg-success");
                            } else {
                                $msg = "Something Went Wrong Try Again Later";
                                $msgclass = ("bg-danger");
                            }
                        } else {
                            $msg = ("Sorry selected role doesnt exist in our records");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg            =  " You do not have the required permissions to update role. Please contact your system administrator or the person 
                                            responsible for managing user permissions.";
                        $msgclass       = ("bg-danger");
                        $msgtype        = 'val_error';
                        $msgtype_title  = 'Insufficient Permissions:';
                    }
                } else {

                    if (HasPermission('A1') == 'true') {
                        $data = [
                            'RollName'                                      => $RollName,
                            'RollAssignID'                                  => $RollAssignID,
                            'Status'                                        => $Status,
                            'addedon'                                       => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type']
                        ];

                        $res = MasterRoll::create($data);
                        $last_id = $res->RollId;
                        if ($last_id   > 0) {
                            $TargetURL = route('pages.RollsController');
                            $msg = ("Role added successfully");
                            $msgclass = ("bg-success");
                        } else {
                            $msg = ("Something went wrong try again after sometime");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg            =  " You do not have the required permissions to add role. Please contact your system administrator or the person 
                                            responsible for managing user permissions.";
                        $msgclass       = ("bg-danger");
                        $msgtype        = 'val_error';
                        $msgtype_title  = 'Insufficient Permissions:';
                    }
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
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'TargetURL' => $TargetURL));
    }

    public function RollsControllerFilter(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('A0') == 'true') {
                if ($req->post('is_date_search')) {
                    $columns = array('RollName ', 'RollAssignID', 'Status');

                    $Query = "select RollId , RollName,RollAssignID,Status,created_at from master_role where 1";


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
                        $Query .= " AND RollName like '%" . $req->post('search')["value"] . "%'";
                    }

                    if ($req->post('order') && !empty($req->post('order'))) {
                        $Col = $columns[$req->post('order')['0']['column']];
                        $Order = $req->post('order')['0']['dir'];
                        $Query .= " ORDER BY  $Col $Order ";
                    } else {
                        $Query .= ' ORDER BY RollId DESC ';
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

                            $sub_array[] = $row["RollName"];
                            $sub_array[] = !empty($row["RollId"]) ? '
                            <a delete_role" data-RollName="' . $row["RollName"] . '"  data-RollId="' . $row["RollId"] . '" href="JavaScript:void(0)"  data-id="' . $row["RollId"] . '"  class="btn btn-primary btn-xs CheckRoles">View Assigned Rolls</a>
                            ' : '<button class="btn btn-primary btn-xs" disabled>	View Assigned Rolls</button>';
                            $sub_array[] = $row['Status'] === 1 ? 'Active' : 'Inactive';


                            $action = '<div class="btn-group" role="group">
                                    <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                        <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';
                            if (HasPermission('A2') == 'true') {
                                $action .= '<a target="_blank" class="dropdown-item"  href="' . route('page.RollsController')  . '/EID/' . $row["RollId"] . '"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
                            }

                            // if (HasPermission('A4') == 'true') {
                            //     $action .= '<a class="dropdown-item" id="view_rolls" data-id="' . $row["RollId"] . '" href="#"><i class="fa fa-eye"></i>  View</a>';
                            // }

                            if (HasPermission('A3') == 'true') {
                                $action .= '<a class="dropdown-item delete_role" data-RollName="' . $row["RollName"] . '"  data-RollId="' . $row["RollId"] . '"  href="JavaScript:void(0)"><i class="fa-solid fa-trash-can"></i>  Delete</a>';
                            }
                            $action .= '</div></div>';
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

    public function DeleteRollsController(Request $req)
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
                    'RollId'                => 'required|numeric|gt:0|exists:master_role,RollId',
                ],
                [
                    'RollId.required'       => 'Invalid Selected Role',
                    'RollId.numeric'        => 'Something went wront with selected role',
                    'RollId.gt'             => 'Something Went Wrong,Please Try Again Later',
                    'RollId.exists'         => ('I apologize, but the option you have role selected does not appear to be valid. Please review the available options and choose one that is listed.'),
                ]
            );
            if ($validator->passes()) {
                $RollId         =  $req->input('RollId') ?? 0;
                if (HasPermission('A3') == 'true') {
                    $data = MasterRoll::find($RollId);
                    if ($data != null) {
                        if ($data->delete()) {
                            $msg = ("Role Deleted Successfully Done");
                            $msgclass = ("bg-success");
                        } else {
                            $msg = ("Something Went Wrong Please Try Again Later");
                            $msgclass = ("bg-danger");
                            $msgtype        = 'val_error';
                        }
                    } else {
                        $msg = ("No Record Found In Our Roles Records");
                        $msgclass = ("bg-danger");
                        $msgtype        = 'val_error';
                    }
                } else {
                    $msg = ("You Dont Have Permission To Delete Role");
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

    public function list_of_permissson(Request $req)
    {
        $msg              = '';
        $msgclass         = '';
        $msgtype          = '';
        $msgtype_title    = '';
        $TargetURL        = '';
        $html             = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'RollId'                => 'required|numeric|gt:0|exists:master_role,RollId',
                ],
                [
                    'RollId.required'       => 'Invalid Selected Role',
                    'RollId.numeric'        => 'Reload The Page',
                    'RollId.gt'             => 'Something Went Wrong,Please Try Again Later',
                    'RollId.exists'         => ('I apologize, but the option you have role selected does not appear to be valid. Please review the available options and choose one that is listed.'),
                ]
            );
            if ($validator->passes()) {
                if (HasPermission('A15') == 'true') {
                    $RollId         =  $req->input('RollId') ?? 0;
                    $data = MasterRoll::find($RollId, ['RollAssignID']);


                    if ($data) {
                        $rolls = explode(',', $data->RollAssignID);

                        if (!empty($rolls) && is_array($rolls)) {
                            $html = '<table class="table">';
                            $html .= '<tr>';
                            $html .= '<th>Sr No.</th>';
                            $html .= '<th>Permission</th>';
                            $html .= '</tr>';
                            $i = 1;
                            foreach ($rolls as $key => $value) {
                                $menu_data = Menu::where('SubMenuID', $value)
                                    ->limit(1)
                                    ->select('MenuName')
                                    ->get()
                                    ->toArray();
                                // dump($menu_data );
                                $menu_name = !empty($menu_data) && is_array($menu_data) ? $menu_data[0]['MenuName'] : 'Not Found';
                                $html .= '<tr>';
                                $html .= '<td>' . $i . '</td>';
                                $html .= '<td>' . $menu_name . '</td>';
                                $html .= '</tr>';
                                $i++;
                            }
                            $html .= '</table>';
                            $msg = ("Exist");
                            $msgclass = ("bg-success");
                        } else {
                            $msg = ("No Permission Founds");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg = ("No Record Found In Our Roles Records");
                        $msgclass = ("bg-danger");
                    }
                } else {
                    $msg = ("You Dont Have Permission To View Permission");
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
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'html' => $html));
    }
}
