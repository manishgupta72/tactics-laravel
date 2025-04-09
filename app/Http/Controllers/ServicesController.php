<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\MasterRoll;
use App\Models\Media;
use Illuminate\Http\Request;
use Validator;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServicesController extends Controller
{
    // List Services
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {
            if (HasPermission('S0') == 'true') {
                $data['MenuNames'] = Admin_Side_Bar();
                $data['title'] = "Services List";
                $data['list_route'] = route('ServicesController.Filter') ?? "";
                $data['save_route'] = route('save.ServicesController') ?? "";
                $data['edit_route'] = route('page.ServicesController') ?? "";
                $data['del_route'] = route('delete.ServicesController') ?? "";
                $data['view_route'] = route('view.ServicesController') ?? "";
                view()->share($data);
                return view('services.index', $data);
            } else {
                Session::flash('warning', 'You do not have permission for the Services module.');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    // Add/Edit Service Page
    public function pageServicesController(Request $req)
    {
        if ($req->isMethod('get')) {
            $data['role_list']  = MasterRoll::where(['Status' => 1])->get(['RollId', 'RollName']);
            $data['list_route'] = route('ServicesController.Filter') ?? "";
            $data['save_route'] = route('save.ServicesController') ?? "";
            $data['edit_route'] = route('page.ServicesController') ?? "";
            $data['del_route'] = route('delete.ServicesController') ?? "";
            $data['view_route'] = route('view.ServicesController') ?? "";
            $data['EID'] = "";

            if ($req->segment(3) == 'EID') {
                if (HasPermission('S2') == 'true') {
                    $data['EID'] = $req->segment(3);
                    $data['MenuNames'] = Admin_Side_Bar();
                    $data['title'] = 'Edit Service';
                    $service = DB::table('services')->where('ser_id', $req->segment(4))->first();


                    if ($service != null && $req->segment(4) > 0) {
                        $data['detail'] = $service;
                        view()->share($data);
                        return view('services.save', $data);
                    } else {
                        Session::flash('warning', 'Something went wrong, try again later.');
                        return redirect()->route('pages.ServicesController');
                    }
                } else {
                    Session::flash('warning', 'You do not have permission to edit the Services module.');
                    return redirect()->route('pages.ServicesController');
                }
            } else {
                if (HasPermission('S1') == 'true') {
                    $data['title'] = "Add Service";
                    $data['MenuNames'] = Admin_Side_Bar();
                    view()->share($data);
                    return view('services.save', $data);
                } else {
                    Session::flash('warning', 'You do not have permission to add Services.');
                    return redirect()->route('pages.ServicesController');
                }
            }
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    // Save Service
    public function SaveServicesController(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $TargetURL = '';

        $id = $req->input('id');
        $ser_name = $req->input('ser_name');
        $ser_short_disc = $req->input('ser_short_disc') ?? '';
        $ser_about = $req->input('ser_about') ?? '';
        $existing_thum_img = $req->input('existing_thum_img') ?? null;
        $existing_full_img = $req->input('existing_full_img') ?? null;

        if ($req->ajax() && $req->isMethod('post')) {
            // Validation
            $validator = Validator::make(
                $req->all(),
                [
                    'ser_name' => 'required|string|max:255',
                    'ser_thum_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'ser_full_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ],
                [
                    'ser_name.required' => 'Service Name is required.',
                    'ser_thum_img.image' => 'Thumbnail image must be a valid image file.',
                    'ser_thum_img.mimes' => 'Allowed formats are jpeg, png, jpg, gif.',
                    'ser_full_img.image' => 'Full image must be a valid image file.',
                    'ser_full_img.mimes' => 'Allowed formats are jpeg, png, jpg, gif.',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->all()], 422);
            }

            try {
                $data = [
                    'ser_name' => $ser_name,
                    'ser_short_disc' => $ser_short_disc,
                    'ser_about' => $ser_about,
                    'updatedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                    'updated_at' => now(),
                ];

                // Handle Thumbnail Image Upload
                if ($req->hasFile('ser_thum_img')) {
                    $file = $req->file('ser_thum_img');
                    $path = config('constant.services'); // Define `services` path in your constants
                    $uploadedFile = uploadFile($file, $path, 'Files_storage');
                    $data['ser_thum_img'] = basename($uploadedFile);
                } else {
                    $data['ser_thum_img'] = $existing_thum_img ?? DB::table('services')->where('ser_id', $id)->value('ser_thum_img');
                }

                // Handle Full Image Upload
                if ($req->hasFile('ser_full_img')) {
                    $file = $req->file('ser_full_img');
                    $path = config('constant.services');
                    $uploadedFile = uploadFile($file, $path, 'Files_storage');
                    $data['ser_full_img'] = basename($uploadedFile);
                } else {
                    $data['ser_full_img'] = $existing_full_img ?? DB::table('services')->where('ser_id', $id)->value('ser_full_img');
                }

                if ($id > 0) {
                    // Update Service
                    if (HasPermission('S2') == 'true') {
                        $service = DB::table('services')->where('ser_id', $id)->first();
                        if ($service) {
                            DB::table('services')->where('ser_id', $id)->update($data);
                            $TargetURL = route('pages.ServicesController');
                            $msg = 'Service updated successfully.';
                            $msgclass = 'bg-success';
                        } else {
                            $msg = 'Service not found.';
                            $msgclass = 'bg-danger';
                        }
                    } else {
                        $msg = 'You do not have permission to update Services.';
                        $msgclass = 'bg-danger';
                    }
                } else {
                    // Add Service
                    if (HasPermission('S1') == 'true') {
                        $data['addedon'] = Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'];
                        $data['created_at'] = now();
                        DB::table('services')->insert($data);
                        $TargetURL = route('pages.ServicesController');
                        $msg = 'Service added successfully.';
                        $msgclass = 'bg-success';
                    } else {
                        $msg = 'You do not have permission to add Services.';
                        $msgclass = 'bg-danger';
                    }
                }
            } catch (\Exception $e) {
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

    public function ServicesControllerFilter(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('S0') == 'true') {
                $columns = ['ser_name', 'ser_about', 'addedon'];
                $Query = "SELECT * FROM services WHERE 1=1"; // Base query
                $Q = []; // Conditions

                if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                    $searchValue = $req->post('search')["value"];
                    $Q[] = "(ser_name LIKE '%$searchValue%' OR ser_about LIKE '%$searchValue%')";
                }

                if (!empty($Q)) {
                    $Query .= ' AND ' . implode(' AND ', $Q);
                }

                if ($req->post('order') && !empty($req->post('order'))) {
                    $Col = $columns[$req->post('order')['0']['column']];
                    $Order = $req->post('order')['0']['dir'];
                    $Query .= " ORDER BY $Col $Order ";
                } else {
                    $Query .= ' ORDER BY ser_id DESC ';
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
                    foreach ($result as $row) {
                        $sub_array = array();

                        $sub_array[] = $row->ser_name;
                        $sub_array[] = !empty($row->ser_thum_img)
                            ? '<img src="' . asset("assets/services/" . $row->ser_thum_img) . '" alt="Thumbnail" width="50">'
                            : '<img src="' . asset("assets/services/default.png") . '" alt="Default Thumbnail" width="50">';

                        $action = '<div class="btn-group" role="group">
                                    <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                        <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';

                        if (HasPermission('S2') == 'true') {
                            $action .= '<a class="dropdown-item edit-service-btn" data-id="' . $row->ser_id . '" href="JavaScript:void(0)">Edit</a>';
                        }

                        if (HasPermission('S3') == 'true') {
                            $action .= '<a class="dropdown-item delete-service-btn" data-id="' . $row->ser_id . '" data-service_name="' . $row->ser_id . '" href="JavaScript:void(0)">Delete</a>';
                        }

                        // if (HasPermission('S4') == 'true') {
                        //     $action .= '<a class="dropdown-item view-service-btn" data-id="' . $row->ser_id . '" href="JavaScript:void(0)">View</a>';
                        // }

                        $action .= '</div></div>';
                        $sub_array[] = $action;
                        $data[] = $sub_array;
                    }
                }

                $output = array(
                    "draw" => intval($req->post('draw')),
                    "recordsTotal" => count(DB::select($Query)),
                    "recordsFiltered" => $number_filter_row,
                    "data" => $data,
                );

                echo json_encode($output);
            }
        }
    }

    public function DeleteServicesController(Request $req)
    {
        $msg = '';
        $msgclass = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'id' => 'required|numeric|exists:services,ser_id',
                ],
                [
                    'id.required' => 'Invalid selected service.',
                    'id.numeric' => 'Invalid service ID.',
                    'id.exists' => 'The selected service does not exist.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first(),
                    'msgsuc' => 'bg-danger',
                ], 422);
            }

            try {
                $serviceId = $req->input('id');

                if (HasPermission('S3') == 'true') {
                    DB::table('services')->where('ser_id', $serviceId)->delete();
                    $msg = "Service deleted successfully.";
                    $msgclass = "bg-success";
                } else {
                    $msg = "You do not have permission to delete this service.";
                    $msgclass = "bg-danger";
                }
            } catch (\Exception $e) {
                $msg = "An unexpected error occurred. Please try again later.";
                $msgclass = "bg-danger";
            }
        } else {
            $msg = "Invalid HTTP request.";
            $msgclass = "bg-danger";
        }

        return response()->json([
            'msg' => $msg,
            'msgsuc' => $msgclass,
        ]);
    }

    public function ViewServicesController(Request $req)
    {
        $id = $req->input('id'); // Service ID

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make($req->all(), [
                'id' => 'required|numeric|exists:services,ser_id',
            ], [
                'id.required' => 'Service ID is required.',
                'id.numeric' => 'Service ID must be a number.',
                'id.exists' => 'The selected service does not exist.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first(),
                    'msgsuc' => 'bg-danger',
                ], 422);
            }

            $service = DB::table('services')->find($id);

            if ($service) {
                $html = view('services.partials.services_details', compact('service'))->render();

                return response()->json([
                    'html' => $html,
                    'msg' => 'Service details loaded successfully.',
                    'msgsuc' => 'bg-success',
                ]);
            } else {
                return response()->json([
                    'msg' => 'Service not found.',
                    'msgsuc' => 'bg-danger',
                ]);
            }
        }

        return response()->json([
            'msg' => 'Invalid request.',
            'msgsuc' => 'bg-danger',
        ], 400);
    }
}
