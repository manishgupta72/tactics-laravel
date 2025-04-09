<?php

namespace App\Http\Controllers;

use App\Models\MasterRoll;
use Illuminate\Http\Request;
use Validator;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    // List Media Files
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {
            if (HasPermission('M0') == 'true') {
                $data['MenuNames'] = Admin_Side_Bar();
                $data['title'] = "Media Files List";
                $data['list_route'] = route('MediaController.Filter') ?? "";
                $data['save_route'] = route('save.MediaController') ?? "";
                $data['edit_route'] = route('page.MediaController') ?? "";
                $data['del_route'] = route('delete.MediaController') ?? "";
                $data['view_route'] = route('view.MediaController') ?? "";
                view()->share($data);
                return view('media.index', $data);
            } else {
                Session::flash('warning', 'You do not have permission for the Media Files module.');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    // Add/Edit Media File Page
    public function pageMediaController(Request $req)
    {
        if ($req->isMethod('get')) {
            $data['role_list'] = MasterRoll::where(['Status' => 1])->get(['RollId', 'RollName']);
            $data['list_route'] = route('MediaController.Filter') ?? "";
            $data['save_route'] = route('save.MediaController') ?? "";
            $data['edit_route'] = route('page.MediaController') ?? "";
            $data['del_route'] = route('delete.MediaController') ?? "";
            $data['view_route'] = route('view.MediaController') ?? "";
            $data['EID'] = "";

            if ($req->segment(3) == 'EID') {
                if (HasPermission('M2') == 'true') {
                    $data['EID'] = $req->segment(3);
                    $data['MenuNames'] = Admin_Side_Bar();
                    $data['title'] = 'Edit Media File';
                    $media = DB::table('media_files')->where('mf_id', $req->segment(4))->first();


                    if ($media != null && $req->segment(4) > 0) {
                        $data['detail'] = $media;
                        view()->share($data);
                        return view('media.save', $data);
                    } else {
                        Session::flash('warning', 'Something went wrong, try again later.');
                        return redirect()->route('pages.MediaController');
                    }
                } else {
                    Session::flash('warning', 'You do not have permission to edit the Media Files module.');
                    return redirect()->route('pages.MediaController');
                }
            } else {
                if (HasPermission('M1') == 'true') {
                    $data['title'] = "Add Media File";
                    $data['MenuNames'] = Admin_Side_Bar();
                    view()->share($data);
                    return view('media.save', $data);
                } else {
                    Session::flash('warning', 'You do not have permission to add Media Files.');
                    return redirect()->route('pages.MediaController');
                }
            }
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    // Save Media File
    public function SaveMediaController(Request $req)
    {
        $msg = '';
        $msgclass = '';
        $TargetURL = '';

        $id = $req->input('id');
        $mf_title = $req->input('mf_title') ?? '';
        $mf_type = $req->input('mf_type') ?? '';
        $mf_url = $req->input('mf_url') ?? '';
        $existing_image = $req->input('existing_image') ?? null;

        if ($req->ajax() && $req->isMethod('post')) {
            // Validation
            $validator = Validator::make(
                $req->all(),
                [
                    'mf_title' => 'required|string|max:255',
                    'mf_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                    'mf_type' => 'required|string|max:50',
                ],
                [
                    'mf_title.required' => 'Title is required.',
                    'mf_image.image' => 'Image must be a valid image file.',
                    'mf_image.mimes' => 'Allowed formats are jpeg, png, jpg, gif.',
                    'mf_type.required' => 'Type is required.',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->all()], 422);
            }

            try {
                $data = [
                    'mf_title' => $mf_title,
                    'mf_type' => $mf_type,
                    'mf_url' => $mf_url,
                    'updatedon' => Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'],
                    'updated_at' => now(),
                ];

                // Handle Image Upload
                if ($req->hasFile('mf_image')) {
                    $file = $req->file('mf_image');
                    $path = config('constant.media_files'); // Define `media_files` path in your constants
                    $uploadedFile = uploadFile($file, $path, 'Files_storage');
                    $data['mf_image'] = basename($uploadedFile);
                } else {
                    $data['mf_image'] = $existing_image ?? DB::table('media_files')->where('mf_id', $id)->value('mf_image');
                }
                // dd($data);
                if ($id > 0) {
                    // Update Media File
                    if (HasPermission('M2') == 'true') {
                        $media = DB::table('media_files')->where('mf_id', $id)->first();
                        if ($media) {
                            DB::table('media_files')->where('mf_id', $id)->update($data);
                            $TargetURL = route('pages.MediaController');
                            $msg = 'Media file updated successfully.';
                            $msgclass = 'bg-success';
                        } else {
                            $msg = 'Media file not found.';
                            $msgclass = 'bg-danger';
                        }
                    } else {
                        $msg = 'You do not have permission to update Media Files.';
                        $msgclass = 'bg-danger';
                    }
                } else {
                    // Add Media File
                    if (HasPermission('M1') == 'true') {
                        $data['addedon'] = Session::get('UserData')['Type'] == 'ALL' ? 0 : Session::get('UserData')['Type'];
                        $data['created_at'] = now();
                        DB::table('media_files')->insert($data);
                        $TargetURL = route('pages.MediaController');
                        $msg = 'Media file added successfully.';
                        $msgclass = 'bg-success';
                    } else {
                        $msg = 'You do not have permission to add Media Files.';
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

    // Filter Media Files
    public function MediaControllerFilter(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            if (HasPermission('M0') == 'true') {
                $columns = ['mf_title', 'mf_type', 'addedon'];
                $Query = "SELECT * FROM media_files WHERE 1=1"; // Base query
                $Q = []; // Conditions

                if ($req->post('search')["value"] && !empty($req->post('search')["value"])) {
                    $searchValue = $req->post('search')["value"];
                    $Q[] = "(mf_title LIKE '%$searchValue%' OR mf_type LIKE '%$searchValue%')";
                }

                if (!empty($Q)) {
                    $Query .= ' AND ' . implode(' AND ', $Q);
                }

                if ($req->post('order') && !empty($req->post('order'))) {
                    $Col = $columns[$req->post('order')['0']['column']];
                    $Order = $req->post('order')['0']['dir'];
                    $Query .= " ORDER BY $Col $Order ";
                } else {
                    $Query .= ' ORDER BY mf_id DESC ';
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
                        $sub_array[] = $row->mf_title;
                        $sub_array[] = !empty($row->mf_image)
                            ? '<img src="' . asset("assets/media_files/" . $row->mf_image) . '" alt="Media Image" width="50">'
                            : '<img src="' . asset("assets/media_files/default.png") . '" alt="Default Image" width="50">';
                        $sub_array[] = $row->mf_type;
                        $sub_array[] = $row->mf_url;

                        $action = '<div class="btn-group" role="group">
                                      <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Action
                                          <i class="mdi mdi-chevron-down"></i>
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">';

                        if (HasPermission('M2') == 'true') {
                            $action .= '<a class="dropdown-item edit-media-btn" data-id="' . $row->mf_id . '" href="JavaScript:void(0)">Edit</a>';
                        }

                        if (HasPermission('M3') == 'true') {
                            $action .= '<a class="dropdown-item delete-media-btn" data-id="' . $row->mf_id . '" data-media_title="' . $row->mf_title . '" href="JavaScript:void(0)">Delete</a>';
                        }

                        // if (HasPermission('M4') == 'true') {
                        //     $action .= '<a class="dropdown-item view-media-btn" data-id="' . $row->mf_id . '" href="JavaScript:void(0)">View</a>';
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

    // Delete Media File
    public function DeleteMediaController(Request $req)
    {
        $msg = '';
        $msgclass = '';

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make(
                $req->all(),
                [
                    'id' => 'required|numeric|exists:media_files,mf_id',
                ],
                [
                    'id.required' => 'Invalid selected media file.',
                    'id.numeric' => 'Invalid media file ID.',
                    'id.exists' => 'The selected media file does not exist.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first(),
                    'msgsuc' => 'bg-danger',
                ], 422);
            }

            try {
                $mediaId = $req->input('id');

                if (HasPermission('M3') == 'true') {
                    DB::table('media_files')->where('mf_id', $mediaId)->delete();
                    $msg = "Media file deleted successfully.";
                    $msgclass = "bg-success";
                } else {
                    $msg = "You do not have permission to delete this media file.";
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

    // View Media File Details
    public function ViewMediaController(Request $req)
    {
        $id = $req->input('id'); // Media File ID

        if ($req->ajax() && $req->isMethod('post')) {
            $validator = Validator::make($req->all(), [
                'id' => 'required|numeric|exists:media_files,mf_id',
            ], [
                'id.required' => 'Media file ID is required.',
                'id.numeric' => 'Media file ID must be a number.',
                'id.exists' => 'The selected media file does not exist.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first(),
                    'msgsuc' => 'bg-danger',
                ], 422);
            }

            $media = DB::table('media_files')->find($id);

            if ($media) {
                $html = view('media.partials.media_details', compact('media'))->render();

                return response()->json([
                    'html' => $html,
                    'msg' => 'Media file details loaded successfully.',
                    'msgsuc' => 'bg-success',
                ]);
            } else {
                return response()->json([
                    'msg' => 'Media file not found.',
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