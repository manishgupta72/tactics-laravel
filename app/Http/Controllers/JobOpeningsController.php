<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JobOpening;
use App\Models\Jobs;
use Validator;
use Session;

class JobOpeningsController extends Controller
{
    public function index(Request $req)
    {
      
            $data['MenuNames'] = Admin_Side_Bar();
            $data['title'] = "Job Openings";
            $data['list_route'] = route('JobOpeningsController.Filter') ?? "";
            $data['save_route'] = route('save.JobOpeningsController') ?? "";
            $data['edit_route'] = route('page.JobOpeningsController') ?? "";
            $data['del_route'] = route('delete.JobOpeningsController') ?? "";
            $data['view_route'] = route('view.JobOpeningsController') ?? "";
            $data['status'] = config('constant.STATUS');
            view()->share($data);
            return view('jobs.index', $data);
        
    }

    public function pageJobOpeningsController(Request $req)
    {
        if ($req->isMethod('get')) {
            $data['list_route'] = route('JobOpeningsController.Filter') ?? "";
            $data['save_route'] = route('save.JobOpeningsController') ?? "";
            $data['edit_route'] = route('page.JobOpeningsController') ?? "";
            $data['del_route'] = route('delete.JobOpeningsController') ?? "";
            $data['view_route'] = route('view.JobOpeningsController') ?? "";
            $data['status'] = config('constant.STATUS');
            $data['EID'] = "";

            if ($req->segment(3) == 'EID') {
                $data['EID'] = $req->segment(3);
                $data['MenuNames'] = Admin_Side_Bar();
                $data['title'] = 'Edit Job Opening';
                $jobOpening = Jobs::find($req->segment(4));

                if ($jobOpening != null && $req->segment(4) > 0) {
                    $data['detail'] = $jobOpening;
                    view()->share($data);
                    return view('jobs.save', $data);
                } else {
                    Session::flash('warning', 'Something went wrong. Try again later.');
                    return redirect()->route('pages.JobOpeningsController');
                }
            } else {
                $data['title'] = "Add Job Opening";
                $data['MenuNames'] = Admin_Side_Bar();
                view()->share($data);
                return view('jobs.save', $data);
            }
        } else {
            Session::flash('warning', 'Invalid HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    public function saveJobOpeningsController(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $TargetURL = '';

        $jobs_id = $req->input('jobs_id');
        $job_title = $req->input('job_title') ?? '';
        $job_language = $req->input('job_language') ?? '';
        $job_location = $req->input('job_location') ?? '';
        $job_experience = $req->input('job_experience') ?? '';
        $job_salary = $req->input('job_salary') ?? '';
        $job_opening = $req->input('job_opening') ?? '';
        $job_status = $req->input('job_status') ?? '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'job_title' => 'required|string|max:255',
                    'job_language' => 'required|string|max:255',
                    'job_location' => 'required|string|max:255',
                    'job_experience' => 'required|string|max:50',
                    'job_salary' => 'required|integer',


                ],
                [
                    'job_title.required' => 'Enter the job title.',
                    'job_language.required' => 'Enter the job language.',
                    'job_location.required' => 'Enter the job language.',
                    'job_experience.required' => 'Enter the job experience.',
                    'job_salary.integer' => 'Salary must be number',

                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->all(),
                ], 422);
            }

            try {
                if ($jobs_id > 0) {
                    $jobOpening = Jobs::find($jobs_id);

                    if ($jobOpening) {
                        $data = [
                            'job_title' => $job_title,
                            'job_language' => $job_language,
                            'job_location' => $job_location,
                            'job_experience' => $job_experience,
                            'job_salary' => $job_salary,
                            'job_opening' => $job_opening,
                            'job_status' => $job_status,
                            'updated_at' => now(),
                            'updatedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                        ];

                        $jobOpening->update($data);
                        $TargetURL = route('pages.JobOpeningsController');
                        $msg = 'Job opening updated successfully.';
                        $msgclass = 'bg-success';
                    } else {
                        $msg = 'The selected job opening does not exist.';
                        $msgclass = 'bg-danger';
                    }
                } else {
                    $data = [
                        'job_title' => $job_title,
                        'job_language' => $job_language,
                        'job_location' => $job_location,
                        'job_experience' => $job_experience,
                        'job_salary' => $job_salary,
                        'job_opening' => $job_opening,
                        'job_status' => $job_status,
                        'created_at' => now(),
                        'addedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                    ];

                    Jobs::create($data);
                    $TargetURL = route('pages.JobOpeningsController');
                    $msg = 'Job opening added successfully.';
                    $msgclass = 'bg-success';
                }
            } catch (\Exception $e) {
                \Log::error('Error in saveJobOpeningsController: ' . $e->getMessage());
                $msg = 'An unexpected error occurred. Please try again later.';
                $msgclass = 'bg-danger';
            }
        } else {
            $msg = 'Invalid HTTP request.';
            $msgclass = 'bg-danger';
        }

        return response()->json([
            'msg' => $msg,
            'msgsuc' => $msgclass,
            'TargetURL' => $TargetURL,
        ]);
    }

    public function JobOpeningsControllerFilter(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('J0') == 'true') { // Replace `JO0` with the actual permission key for job openings
                if ($req->post('is_date_search')) {
                    $columns = array('job_title', 'job_language','job_location', 'job_experience', 'job_salary', 'job_opening', 'job_status', 'created_at');
                    $Query = "SELECT * FROM jobs_opening WHERE 1=1"; // Start with a valid base query
                    $Q = []; // Initialize conditions

                    if ($req->post('is_date_search') == 'yes') {
                        $url_components = parse_url(admin_getBaseURL() . '?' . $req->post('date'));
                        parse_str($url_components['query'], $params);
                        foreach ($params as $key => $value) {
                            if ($value != "" && $key != 'reservation') {
                                if ($key == 'created_at') {
                                    $Date = explode('TO', $value);
                                    $Q[] = 'created_at BETWEEN "' . date('Y-m-d 00:00:00', strtotime($Date[0])) . '" AND "' . date('Y-m-d 23:59:59', strtotime($Date[1])) . '"';
                                } else {
                                    $Q[] = $key . " = '" . $value . "'";
                                }
                            }
                        }
                        $Query .= ' AND ' . implode(' AND ', $Q);
                    }

                    if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                        $searchValue = $req->post('search')["value"];
                        $Query .= " AND (job_title LIKE '%$searchValue%' OR job_language LIKE '%$searchValue%' OR job_experience LIKE '%$searchValue%' OR job_salary LIKE '%$searchValue%')";
                    }

                    if ($req->post('order') && !empty($req->post('order'))) {
                        $Col = $columns[$req->post('order')['0']['column']];
                        $Order = $req->post('order')['0']['dir'];
                        $Query .= " ORDER BY $Col $Order ";
                    } else {
                        $Query .= ' ORDER BY jobs_id DESC ';
                    }

                    $Query1 = '';
                    if ($req->post('length') != -1) {
                        $Query1 = ' LIMIT ' . $req->post('start') . ', ' . $req->post('length');
                    }

                    $res = DB::select($Query);
                    $number_filter_row = count($res);
                    $result = DB::select($Query . $Query1, array());
                    $data = array();

                    if (count($result) > 0) {
                        $i = 1;
                        foreach (objectToArray($result) as $key => $row) {
                            $sub_array = array();

                            $sub_array[] = $row["job_title"];
                            $sub_array[] = $row["job_location"];
                           
                            $sub_array[] = $row["job_opening"];
                            $sub_array[] = $row["job_salary"];
                            $sub_array[] = $row["job_status"] > 0 && !empty($row["job_status"]) ? config('constant.STATUS')[$row["job_status"]] : "Inactive";

                            $action = '<div class="btn-group" role="group">
                            <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';

                            if (HasPermission('J2') == 'true') { // Replace `JO2` with the actual permission key for edit
                                $action .= '<a class="dropdown-item edit-jobopening-btn" data-job_title="' . $row["job_title"] . '" data-id="' . $row["jobs_id"] . '" href="JavaScript:void(0)">Edit</a>';
                            }

                            if (HasPermission('J3') == 'true') { // Replace `JO3` with the actual permission key for delete
                                $action .= '<a class="dropdown-item delete_jobopening" data-job_title="' . $row["job_title"] . '" data-id="' . $row["jobs_id"] . '" href="JavaScript:void(0)"> Delete</a>';
                            }

                            $action .= '</div>';
                            $action .= '</ul>';
                            $action .= '</div>';

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


    public function deleteJobOpeningsController(Request $req)
    {
        $msg = '';
        $msgclass = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'id' => 'required|numeric|exists:jobs_opening,jobs_id',
                ],
                [
                    'id.required' => 'Invalid selected job opening.',
                    'id.exists' => 'The selected job opening does not exist.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first(),
                ], 422);
            }

            $id = $req->input('id');
            $jobOpening = Jobs::find($id);

            if ($jobOpening) {
                $jobOpening->delete();
                $msg = 'Job opening deleted successfully.';
                $msgclass = 'bg-success';
            } else {
                $msg = 'Job opening not found.';
                $msgclass = 'bg-danger';
            }
        } else {
            $msg = 'Invalid HTTP request.';
            $msgclass = 'bg-danger';
        }

        return response()->json([
            'msg' => $msg,
            'msgsuc' => $msgclass,
        ]);
    }
}
