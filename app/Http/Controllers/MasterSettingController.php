<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use App\Models\User;
use App\Models\MasterData;
use Illuminate\Http\Request;
use App\Models\Mastersettings;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class MasterSettingController extends Controller
{
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {
            if (HasPermission('E0') == 'true') {
                $general_settings = [];
                $system_logo = "";
                $login_screen_logo = "";
                $data['MenuNames'] = Admin_Side_Bar();
                $data['title'] = "Mastersetting  List";
                $data['save_route'] = route('save.MasterSettingController') ?? "";
                $data['save_master_route'] = route('sv.mstrdata') ?? "";
                $data['lst_master_route'] = route('lst.mstrdata') ?? "";
                $data['edt_master_route'] = route('edt.mstrdata') ?? "";
                $data['del_master_route'] = route('del.mstrdata') ?? "";
                $data['password_route'] = route('chng.paswrd') ?? "";
                $data['status'] = config('constant.STATUS');

                $generalSettings = Mastersettings::find(1);

                if ($generalSettings->general_settings) {
                    $general_settings_datas = json_decode($generalSettings->general_settings, true);
                    if (check_valid_array($general_settings_datas)) {
                        $general_settings = $general_settings_datas;
                    }
                }





                if ($generalSettings->system_logo) {
                    if ($generalSettings->system_logo != "") {
                        $file_url = "";
                        $filename = get_filename('Files_storage', config('constant.mastersetting') . "/" . $generalSettings->system_logo);
                        if ($filename) {
                            $file_url = $filename;
                        }
                    }
                    $system_logo = $file_url;
                }

                if ($generalSettings->login_screen_logo) {
                    if ($generalSettings->login_screen_logo != "") {
                        $file_url1 = "";
                        $filename1 = get_filename('Files_storage', config('constant.mastersetting') . "/" . $generalSettings->login_screen_logo);
                        if ($filename1) {
                            $file_url1 = $filename1;
                        }
                    }
                    $login_screen_logo = $file_url1;
                }

                $data['general_settings'] = $general_settings;

                $data['system_logo'] = $system_logo;
                $data['login_screen_logo'] = $login_screen_logo;
                $data['master_data'] = config('constant.Master_DATA');
                view()->share($data);
                return view('mastersetting.mastersetting-save', $data);
            } else {
                Session::flash('warning', 'You dont have permission of mastersetting module ');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('warning', 'Sorry something went wrong with http request ');
            return redirect()->route('dashboard');
        }
    }

    public function SaveMasterSettingController(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $msgtype = '';
        $msgtype_title = '';
        $TargetURL = '';
        $mimes_types =
            [
                'image/jpeg',
                'image/png',
            ];
        $Action = $req->input('Action') ? $req->input('Action') : 0;
        $id = $req->input('id') ? $req->input('id') : 0;
        if ($req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'id' => [
                        'required',
                        'numeric',
                        'gt:0',
                        Rule::exists('mastersetting', 'id')->where(function ($query) use ($req) {
                            $query->where(
                                [
                                    'id' => 1
                                ]
                            );
                        })
                    ],
                    'system_logo' => [
                        'sometimes',
                        'required',
                        function ($attribute, $value, $fail) use ($mimes_types) {

                            $allowedMimes = $mimes_types;
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mime = finfo_file($finfo, $value);
                            finfo_close($finfo);
                            if (!in_array($mime, $allowedMimes)) {
                                $fail("The " . $value->getClientOriginalName() . " must be a file of type: " . implode(', ', $allowedMimes));
                            }
                        },
                    ],
                    'login_screen_logo' => [
                        'sometimes',
                        'required',
                        function ($attribute, $value, $fail) use ($mimes_types) {

                            $allowedMimes = $mimes_types;
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mime = finfo_file($finfo, $value);
                            finfo_close($finfo);
                            if (!in_array($mime, $allowedMimes)) {
                                $fail("The " . $value->getClientOriginalName() . " must be a file of type: " . implode(', ', $allowedMimes));
                            }
                        },
                    ],
                ],
                [
                    'id.required' => ('Something went wrong with given parameters settting'),
                    'id.numeric' => ('Something went wrong with given parameters settting'),
                    'id.gt' => ('Something went wrong with given parameters settting'),
                    'id.exists' => ('I apologize, but the option you have given parameters setting  does not appear to be valid. Please review the available options and choose one that is listed.'),
                    'system_logo.required' => 'Please upload at least one system logo.',
                    'system_logo.mimes' => 'The file must be a ' . implode(', ', $mimes_types),
                    'login_screen_logo.required' => 'Please upload at least one login screen logo.',
                    'login_screen_logo.mimes' => 'The file must be a ' . implode(', ', $mimes_types),

                ]
            );
            if ($validator->passes()) {
                if (HasPermission('E0') == 'true') {
                    $check_record_exit = Mastersettings::find($id);
                    if ($check_record_exit) {

                        if ($Action == 1) {

                            $system_name = $req->input('system_name') ? $req->input('system_name') : "";
                            $support_email = $req->input('support_email') ? $req->input('support_email') : "";
                            $aplication_title = $req->input('aplication_title') ? $req->input('aplication_title') : "";
                            $support_number = $req->input('support_number') ? $req->input('support_number') : "";
                            $support_whatsapp_number = $req->input('support_whatsapp_number') ? $req->input('support_whatsapp_number') : "";
                            $display_text = $req->input('display_text') ? $req->input('display_text') : "";
                            $copyright = $req->input('copyright') ? $req->input('copyright') : "";
                            $insta_link = $req->input('insta_link') ? $req->input('insta_link') : "";
                            $facebook_link = $req->input('facebook_link') ? $req->input('facebook_link') : "";
                            $x_link = $req->input('x_link') ? $req->input('x_link') : "";
                            $address = $req->input('address') ? $req->input('address') : "";
                            $system_logo = "";
                            $login_screen_logo = "";

                            if ($req->hasFile('system_logo')) {
                                $file = $req->file('system_logo');
                                $system_logo = UploadFile_single('edit', 'system_logo', $file, public_path('assets/images/mastersetting/'), $table = 'mastersetting', $setcolumn = 'system_logo', $WhereCondition = ['id' => $id], "assets/" . config('constant.mastersetting'));
                            }

                            if ($req->hasFile('login_screen_logo')) {
                                $file = $req->file('login_screen_logo');
                                $login_screen_logo = UploadFile_single('edit', 'login_screen_logo', $file, public_path('assets/images/mastersetting/'), $table = 'mastersetting', $setcolumn = 'login_screen_logo', $WhereCondition = ['id' => $id], "assets/" . config('constant.mastersetting'));
                            }

                            $general_setting_data = [
                                'system_name' => $system_name,
                                'support_email' => $support_email,
                                'aplication_title' => $aplication_title,
                                'support_number' => $support_number,
                                'support_whatsapp_number' => $support_whatsapp_number,
                                'display_text' => $display_text,
                                'copyright' => $copyright,
                                'insta_link' => $insta_link,
                                'facebook_link' => $facebook_link,
                                'x_link' => $x_link,
                                'address' => $address,
                                'system_logo' => $system_logo,
                                'login_screen_logo' => $login_screen_logo,
                            ];

                            $res = $check_record_exit->update(['general_settings' => json_encode($general_setting_data, true)]);

                            if ($res) {
                                $msg = ("General setting updated successfully");
                                $msgclass = ("bg-success");
                                $TargetURL = route('pages.MasterSettingController');
                            } else {
                                $msg = ("Something went wrong while updating the settings");
                                $msgclass = ("bg-danger");
                            }
                        } elseif ($Action == 2) {
                            $smtp_server = $req->input('smtp_server') ? $req->input('smtp_server') : "";
                            $smtp_port = $req->input('smtp_port') ? $req->input('smtp_port') : "";
                            $smtp_username = $req->input('smtp_username') ? $req->input('smtp_username') : "";
                            $smtp_password = $req->input('smtp_password') ? $req->input('smtp_password') : "";

                            $email_setting_data = [
                                'smtp_server' => $smtp_server,
                                'smtp_port' => $smtp_port,
                                'smtp_username' => $smtp_username,
                                'smtp_password' => $smtp_password
                            ];

                            $res = $check_record_exit->update(['email_settings' => json_encode($email_setting_data, true)]);

                            if ($res) {
                                $msg = ("Email setting updated successfully");
                                $msgclass = ("bg-success");
                                $TargetURL = route('pages.MasterSettingController');
                            } else {
                                $msg = ("Something went wrong while updating the settings");
                                $msgclass = ("bg-danger");
                            }
                        } elseif ($Action == 3) {
                            $term_condition = $req->input('term_condition') ? $req->input('term_condition') : "";
                            $privacy_policy = $req->input('privacy_policy') ? $req->input('privacy_policy') : "";

                            $page_setting_data = [
                                'term_condition' => $term_condition,
                                'privacy_policy' => $privacy_policy
                            ];

                            $res = $check_record_exit->update(['page_settings' => json_encode($page_setting_data, true)]);

                            if ($res) {
                                $msg = ("Page setting updated successfully");
                                $msgclass = ("bg-success");
                                $TargetURL = route('pages.MasterSettingController');
                            } else {
                                $msg = ("Something went wrong while updating the settings");
                                $msgclass = ("bg-danger");
                            }
                        } else {
                            $msg = ("Error: Invalid selection of action");
                            $msgclass = ("bg-danger");
                            $msgtype = 'val_error';
                            $msgtype_title = 'Please correct the following errors and try again:';
                        }
                    } else {
                        $msg = ("Error: Selected record not found");
                        $msgclass = ("bg-danger");
                        $msgtype = 'val_error';
                        $msgtype_title = 'Please correct the following errors and try again:';
                    }
                } else {
                    $msg = ("Error: You dont have permission of mastersetting module ");
                    $msgclass = ("bg-danger");
                    $msgtype = 'val_error';
                    $msgtype_title = 'Insufficient Permissions:';
                }
            } else {
                $msg = throw_error($validator->errors()->all());
                $msgclass = ("bg-danger");
                $msgtype = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }

        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'TargetURL' => $TargetURL));
    }

    public function save_master_data(Request $req)
    {
        $msgclass = '';
        $msgtype = '';
        $msgtype_title = '';
        $TargetURL = '';

        $master_data_id = $req->input('master_data_id') ? $req->input('master_data_id') : 0;
        $master_data_name = $req->input('master_data_name') ? $req->input('master_data_name') : "";
        $master_data_description = $req->input('master_data_description') ? $req->input('master_data_description') : "";
        $mid = $req->input('mid') ? $req->input('mid') : 0;



        if ($req->ajax() && $req->isMethod('post')) {

            $validator = Validator::make(
                $req->all(),
                [
                    'master_data_name' => 'required',
                    'mid' => 'required|numeric|gt:0|in:' . implode(',', array_keys(config('constant.Master_DATA'))),

                ],
                [
                    'master_data_name.required' => 'Enter  Name',

                    'mid.required' => "Select master field is required.",
                    'mid.numeric' => "Something went wrong with master",
                    'mid.gt' => "Something went wrong with master",
                    'mid.in' => "Sorry selected master doesnt exist in our records",
                ]
            );
            if ($validator->passes()) {
                if ($master_data_id > 0 && $master_data_id != "" && is_numeric($master_data_id)) {

                    if (HasPermission('E0') == 'true') {
                        $check_record_exit = MasterData::find($master_data_id);
                        if ($check_record_exit != null) {

                            if (!empty($mid) && $mid > 0) {
                                $check_record_exit->update(['mid' => $mid]);
                            }

                            $data = [
                                'master_data_name' => $master_data_name,
                                'master_data_description' => $master_data_description,
                                'updatedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type']
                            ];

                            $res = $check_record_exit->update($data);
                            if ($res == 0 || $res) {
                                $msg = ("Master Data Updated Successfully");
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
                        $msg = " You do not have the required permissions to update mastersetting. Please contact your system administrator or the person 
                                            responsible for managing user permissions.";
                        $msgclass = ("bg-danger");
                        $msgtype = 'val_error';
                        $msgtype_title = 'Insufficient Permissions:';
                    }
                } else {

                    if (HasPermission('E0') == 'true') {

                        $data = [
                            'master_data_name' => $master_data_name,
                            'master_data_description' => $master_data_description,
                            'mid' => $mid,
                            'addedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type']
                        ];

                        $res = MasterData::create($data);
                        $last_id = $res->master_data_id;
                        if ($last_id > 0) {
                            $msg = ("Master Data added successfully");
                            $msgclass = ("bg-success");
                        } else {
                            $msg = ("Something went wrong try again after sometime");
                            $msgclass = ("bg-danger");
                        }
                    } else {
                        $msg = " You do not have the required permissions to add master data. Please contact your system administrator or the person 
                                            responsible for managing master data permissions.";
                        $msgclass = ("bg-danger");
                        $msgtype = 'val_error';
                        $msgtype_title = 'Insufficient Permissions:';
                    }
                }
            } else {
                $msg = throw_error($validator->errors()->all());
                ;
                $msgclass = ("bg-danger");
                $msgtype = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'TargetURL' => $TargetURL));
    }

    public function list_master_data(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('E0') == 'true') {
                if ($req->post('is_date_search')) {
                    $columns = array('master_data_name', 'master_data_description', 'mid');
                    $Query = "select * from master_data md
                                where 1 ";
                    if ($req->post('is_date_search') == 'yes') {
                        $url_components = parse_url(admin_getBaseURL() . '?' . $req->post('date'));
                        parse_str($url_components['query'], $params);
                        foreach ($params as $key => $value) {
                            if ($value != "" && $key != 'reservation') {
                                if ($key == 'AddedOn') {
                                    $Date = explode('TO', $value);
                                    $Q[] = 'AddedOn Between "' . date('Y-m-d 00:00:00', strtotime($Date[0])) . '" ' . ' AND "' . date('Y-m-d 23:59:59', strtotime($Date[1])) . '" ';
                                } else {
                                    $Q[] = 'md.' . $key . '=' . "'" . $value . "'";
                                }
                            }
                        }
                        $Query .= ' AND ' . implode(' AND ', $Q);
                    }

                    if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                        $Query .= " AND (md.master_data_name like '%" . $req->post('search')["value"] . "%' 
                                        OR md.master_data_description  like '%" . $req->post('search')["value"] . "%') ";
                    }

                    if ($req->post('order') && !empty($req->post('order'))) {
                        $Col = $columns[$req->post('order')['0']['column']];
                        $Order = $req->post('order')['0']['dir'];
                        $Query .= " ORDER BY  md.$Col $Order ";
                    } else {
                        $Query .= ' ORDER BY md.master_data_id   DESC ';
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

                            $sub_array[] = $row["master_data_name"] ?? "";
                            $sub_array[] = $row["master_data_description"] ?? "";

                            $sub_array[] = $row["mid"] > 0 && !empty($row["mid"]) ? config('constant.Master_DATA')[$row["mid"]] : "";

                            $action = ' <a href="JavaScript:void(0)" data-master_data_id="' . $row["master_data_id"] . '"  class="edit-row"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="JavaScript:void(0)" data-master_data_id="' . $row["master_data_id"] . '" data-master_data_name="' . $row["master_data_name"] . '" class="delete-row"><i class="far fa-trash-alt"></i></a>';

                            $sub_array[] = $action;
                            $data[] = $sub_array;
                            $i++;
                        }
                    }

                    $output = array(
                        "draw" => intval($req->post('draw')),
                        "recordsTotal" => count(DB::select($Query)),
                        "recordsFiltered" => $number_filter_row,
                        "data" => $data
                    );

                    echo json_encode($output);
                }
            }
        }
    }

    public function edit_master_data(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $msgtype = '';
        $msgtype_title = '';
        $TargetURL = '';
        $contact_details = [];

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'master_data_id' => 'required|numeric|gt:0|exists:master_data,master_data_id',
                ],
                [

                    'master_data_id.required' => ('Select master detail'),
                    'master_data_id.numeric' => ('Something went wrong with selected master detail'),
                    'master_data_id.gt' => ('Something went wrong with selected master detail'),
                    'master_data_id.exists' => ('I apologize, but the option you have master detail selected does not appear to be valid. Please review the available options and choose one that is listed.'),

                ]
            );

            if ($validator->passes()) {

                $master_data_id = $req->input('master_data_id') ? $req->input('master_data_id') : 0;
                $contact_detail = MasterData::where(['master_data_id' => $master_data_id])->first();
                if ($contact_detail != null) {
                    $contact_details = $contact_detail->toArray();
                    $msg = ("success");
                    $msgclass = ("bg-success");
                } else {
                    $msg = ("Something went wrong try again later");
                    $msgclass = ("bg-danger");
                }
            } else {
                $msg = throw_error($validator->errors()->all());
                ;
                $msgclass = ("bg-danger");
                $msgtype = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something wrong with http request");
            $msgclass = ("bg-danger");
        }
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'contact_details' => $contact_details));
    }

    public function delete_master_data(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $msgtype = '';
        $msgtype_title = '';
        $TargetURL = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'master_data_id' => 'required|numeric|gt:0|exists:master_data,master_data_id',
                ],
                [

                    'master_data_id.required' => ('Select master detail'),
                    'master_data_id.numeric' => ('Something went wrong with selected master detail'),
                    'master_data_id.gt' => ('Something went wrong with selected master detail'),
                    'master_data_id.exists' => ('I apologize, but the option you have master detail selected does not appear to be valid. Please review the available options and choose one that is listed.'),

                ]
            );

            if ($validator->passes()) {
                $master_data_id = $req->input('master_data_id') ? $req->input('master_data_id') : 0;

                $res = MasterData::find($master_data_id);
                if ($res != null) {
                    if ($res->delete()) {
                        $msg = ("Master Data Deleted Successfully Done");
                        $msgclass = ("bg-success");
                    } else {
                        $msg = ("Something Went Wrong Please Try Again Later");
                        $msgclass = ("bg-danger");
                        $msgtype = 'val_error';
                    }
                } else {
                    $msg = ("No Record Found In Our Master Data Records");
                    $msgclass = ("bg-danger");
                    $msgtype = 'val_error';
                }
            } else {
                $msg = throw_error($validator->errors()->all());
                ;
                $msgclass = ("bg-danger");
                $msgtype = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title));
    }


    public function change_password(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $msgtype = '';
        $msgtype_title = '';
        $TargetURL = '';
        $UID = Session::get('UserData')['UID'];
        $check_user = User::find($UID);

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'email' => 'required|email|exists:users,email',
                    'old_password' => [
                        'required',
                        function ($attribute, $value, $fail) use ($check_user) {
                            if (!Hash::check($value, $check_user->password)) {
                                $fail('Old password is incorrect.');
                            }
                        }
                    ],
                    'password' => 'required|confirmed|min:8',
                ],
                [

                    'email.required' => ('Enter email'),
                    'email.email' => ('Enter valid email'),
                    'email.exists' => ('I apologize, but the option you have email detail selected does not appear to be valid. Please review the available options and choose one that is listed.'),

                ]
            );

            if ($validator->passes()) {

                $id = Session::get('UserData')['UID'] ? Session::get('UserData')['UID'] : 0;
                $password = $req->input('password') ? Hash::make($req->input('password')) : "";

                if ($check_user != null) {
                    $res = $check_user->update(['password' => $password]);
                    if ($res) {
                        $TargetURL = route('admin.logout');
                        $msg = ("Password changed successfully.");
                        $msgclass = ("bg-success");
                    } else {
                        $msg = ("Something Went Wrong Please Try Again Later");
                        $msgclass = ("bg-danger");
                        $msgtype = 'val_error';
                    }
                } else {
                    $msg = ("No Record Found In Our  Records");
                    $msgclass = ("bg-danger");
                    $msgtype = 'val_error';
                }
            } else {
                $msg = throw_error($validator->errors()->all());
                ;
                $msgclass = ("bg-danger");
                $msgtype = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'msgtype' => $msgtype, 'msgtype_title' => $msgtype_title, 'TargetURL' => $TargetURL));
    }
}