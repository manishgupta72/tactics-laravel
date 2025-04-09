<?php

namespace App\Http\Controllers;

use App;
use Session;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AdminController extends BaseController
{
    public function index()
    {

        return view('index');
    }

    public function change_password()
    {
        $data['title']          = 'Dashboard';
        $data['MenuNames']      = Admin_Side_Bar();
        $data['password_route'] = route('chng.paswrd') ?? "";
        view()->share($data);
        return view('change-password', $data);
    }

    function clearCache(Request $request)
    {
        Artisan::call('optimize:clear');
        //   flash(('Cache cleared successfully'))->success();
        Session::flash('success', 'Cache cleared successfully');
        return back();
    }
    public function admin_login(Request $req)
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
                    'LoginC' => 'required',
                    'LoginP' => 'required|min:4|max:20|',
                ],
                [
                    'LoginC.required' => 'Username Or Email Required',
                    'LoginP.required' => 'Password is Required',
                    'LoginP.min'      => 'Password should be atleat 4 character',
                    'LoginP.max'      => 'Password should be atleat 20 character',
                ]
            );
            if ($validator->passes()) {
                // if (valid_email($req->input('LoginC'))) {
                //     $UserLabel  = 'email';
                // } else {
                //     $UserLabel  = 'UserName';
                // }
                $UserLabel = 'email';
                $AdminSql  = User::where([$UserLabel => $req->input('LoginC')])->limit(1)->get();

                if ($AdminSql->count() == 1) {
                    $AdminRow = $AdminSql->toArray()[0];
                    // dd($AdminRow);
                    $Pass     = $req->input('LoginP');
                    $id       = !empty($AdminRow['id']) ? $AdminRow['id'] : 0;
                    $name     = !empty($AdminRow['name']) ? $AdminRow['name'] : "";
                    $email    = !empty($AdminRow['email']) ? $AdminRow['email'] : "";
                    $password = !empty($AdminRow['password']) ? $AdminRow['password'] : "";
                    $UserType = !empty($AdminRow['UserType']) ? $AdminRow['UserType'] : "";
                    $ward_no  = !empty($AdminRow['ward_no']) ? $AdminRow['ward_no'] : "";

                    if ($AdminRow[$UserLabel] != $req->input('LoginC')) {
                        $msg           = " Please Contact Administrator.";
                        $msgclass      = 'bg-danger';
                        $msgtype       = 'val_error';
                        $msgtype_title = 'Please correct the following errors and try again:';
                    } elseif (!Hash::check($Pass, $password)) {
                        $msg           = "Incorrect Password!";
                        $msgclass      = 'bg-danger';
                        $msgtype       = 'val_error';
                        $msgtype_title = 'Please correct the following errors and try again:';
                    } elseif (trim($req->input('LoginP')) == "") {
                        $msg           = "Incorrect Login Details!";
                        $msgclass      = 'bg-danger';
                        $msgtype       = 'val_error';
                        $msgtype_title = 'Please correct the following errors and try again:';
                    } else {
                        $UserDatas = array('ward_no' => $ward_no, 'Type' => $UserType, 'UID'  => $id, 'FullName' => $name, 'Email' => $email, 'Login' => true);
                        Session::put('UserData', $UserDatas);
                        $msg       = ("Login SuccessFully");
                        $msgclass  = ("bg-success");
                        $TargetURL = route('dashboard');
                    }
                } else {
                    $msg           = ("No Records Found For This Email! Please Contact Administrator");
                    $msgclass      = ("bg-danger");
                    $msgtype       = 'val_error';
                    $msgtype_title = 'Please correct the following errors and try again:';
                }
            } else {
                $msg           = throw_error($validator->errors()->all());
                $msgclass      = ("bg-danger");
                $msgtype       = 'val_error';
                $msgtype_title = 'Please correct the following errors and try again:';
            }
        } else {
            $msg      = ("Something Wrong With Http Request");
            $msgclass = ("bg-danger");
        }
        // echo json_encode(array('msg' => $msg, 'msgsuc' => $msgclass, 'msgfail' => $msgclass, 'TargetURL' => $TargetURL));
        return response()->json([
            'msg'           => $msg,
            'msgsuc'        => $msgclass,
            'msgfail'       => $msgclass,
            'TargetURL'     => $TargetURL,
            'msgtype'       => $msgtype,
            'msgtype_title' => $msgtype_title
        ]);
    }
    public function admin_logout()
    {
        if (Session::has('UserData')) {
            // Session::flush();
            Session::forget('UserData');
            return redirect()->route('admin');
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function dashboard()
    {
        $account_id        = Session::get('UserData')['UID'];
        $data['title']     = 'Dashboard';
        $data['MenuNames'] = Admin_Side_Bar();

        // Get all active users where user_type is 3
        $activeUsers = DB::table('users')
            ->where('status', 1)
            ->where('UserType', 21)
            ->get()
            ->toArray();  // Converts to an array to avoid count() errors 

        // // Get all active users where user revisted
        $revisitedUsers = DB::table('master_employee')
            ->where('re_visited_employee', 1)
            ->get()
            ->toArray(); // Converts to an array to avoid count() errors


        // Get company data with employee counts
        // Check if logged-in user is a Recruiter (UserType = 21)
        $isRecruiter = Session::get('UserData')['Type'] == 21;
        // dd(Session::get('UserData')['Type']);
        $loggedInUserID = Session::get('UserData')['UID'];

        // Get company data with employee counts
        $companyDataQuery = DB::table('company')
            ->leftJoin('master_employee', 'company.comp_id', '=', 'master_employee.emp_company_id')
            ->select(
                'company.comp_name',
                DB::raw('COUNT(CASE WHEN MONTH(master_employee.created_at) = MONTH(CURRENT_DATE()) THEN 1 END) as current_month'),
                DB::raw('COUNT(master_employee.emp_id) as till_date')
            )
            ->groupBy('company.comp_id', 'company.comp_name');

        if ($isRecruiter) {
            // If Recruiter, show only their added employees
            $companyDataQuery->where('master_employee.addedon', $loggedInUserID);
        }

        $companyData = $companyDataQuery->get()->toArray();


        // Get employee performance only for recruiters (UserType = 21)
        $employeePerformanceQuery = DB::table('users')
            ->leftJoin('master_employee', 'users.id', '=', 'master_employee.addedon')
            ->select(
                'users.name as user_name',
                DB::raw('COUNT(CASE WHEN MONTH(master_employee.created_at) = MONTH(CURRENT_DATE()) THEN 1 END) as current_count')
            )
            ->where('users.status', 1)            // Only active users
            ->where('users.UserType', 21)         // Only recruiters
            ->groupBy('users.id', 'users.name');

        if ($isRecruiter) {
            // If the logged-in user is a recruiter, show only their performance
            $employeePerformanceQuery->where('master_employee.addedon', $loggedInUserID);
        }

        $employeePerformance = $employeePerformanceQuery->get()->toArray();


        $data['activeUsers']         = $activeUsers;
        $data['revisitedUsers']         = $revisitedUsers;
        $data['companyData']         = $companyData;
        $data['employeePerformance'] = $employeePerformance;
        // Get dropdown employee list
        // Fetch recruiter list (UserType = 21)
        $employees = DB::table('users')
            ->where('status', 1)
            ->where('UserType', 21)
            ->select('id as emp_id', 'name as full_name')
            ->get();
        $data['employees']           = $employees;

        view()->share($data);
        return view('dashboard', $data);
    }

    // Controller method for recruiter summary
    public function getRecruiterSummary(Request $request)
    {
        $recruiterId = $request->input('recruiter_id');

        if (!$recruiterId) {
            return response()->json(['error' => 'Recruiter ID is required'], 400);
        }

        $currentMonth  = date('m');
        $currentYear   = date('Y');
        $previousMonth = date('m', strtotime('-1 month'));
        $previousYear  = $previousMonth == 12 ? $currentYear - 1 : $currentYear;

        $employeeIds = DB::table('master_employee')
            ->where('addedon', $recruiterId)
            ->pluck('emp_id')
            ->toArray();

        $getCount = function ($table, $column = 'emp_id', $month = null, $year = null) use ($employeeIds) {
            $query = DB::table($table)->whereIn($column, $employeeIds);
            if ($month) $query->whereMonth('created_at', $month);
            if ($year)  $query->whereYear('created_at', $year);
            return $query->count();
        };

        $data = [
            'current_month' => [
                'bank'      => $getCount('bank_employee', 'emp_id', $currentMonth, $currentYear),
                'basic'     => $getCount('basic_employee', 'emp_id', $currentMonth, $currentYear),
                'pf_esic'   => $getCount('pf_esic_employee', 'emp_id', $currentMonth, $currentYear),
                'documents' => $getCount('document_employee', 'emp_id', $currentMonth, $currentYear),
            ],
            'previous_month' => [
                'bank'      => $getCount('bank_employee', 'emp_id', $previousMonth, $previousYear),
                'basic'     => $getCount('basic_employee', 'emp_id', $previousMonth, $previousYear),
                'pf_esic'   => $getCount('pf_esic_employee', 'emp_id', $previousMonth, $previousYear),
                'documents' => $getCount('document_employee', 'emp_id', $previousMonth, $previousYear),
            ],
            'all_time' => [
                'bank'      => $getCount('bank_employee'),
                'basic'     => $getCount('basic_employee'),
                'pf_esic'   => $getCount('pf_esic_employee'),
                'documents' => $getCount('document_employee'),
            ],
        ];

        return response()->json($data);
    }
}
