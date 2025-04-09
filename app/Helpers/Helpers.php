<?php


use App\Models\Menu;
use App\Models\User;
use App\Models\MasterRoll;
use App\Models\Mastersettings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;;

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        @$root  = (isHttps() ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
        $root  .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}


function delete_file_from_space($file_name, $foldername)
{
    $return = false;
    if (!empty($file_name) && $file_name) {
        $path_parts = pathinfo($file_name);
        $filename   = $foldername . $path_parts['basename'];
        if (Storage::disk('spaces')->exists($filename)) {
            Storage::disk('spaces')->delete($filename);
            $return = true;
        }
    }
    return $return;
}





if (!function_exists('admin_getBaseURL')) {
    function admin_getBaseURL()
    {
        $root  = (isHttps() ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root . 'backend/';
    }
}

if (!function_exists('throw_error')) {

    function throw_error($data)
    {
        $html_error = '';
        if (check_valid_array($data)) {
            $html_error .= '<ul class="submit-error no-bullets">';
            foreach ($data as $key => $value) {
                $html_error .= '<li>' . $value . '</li>';
            }
            $html_error .= '</ul>';
        }

        return $html_error;
    }
}



if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } else {
            return getBaseURL() . 'public/';
        }
    }
}

if (!function_exists('isHttps')) {
    function isHttps()
    {
        return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
    }
}





if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}



if (!function_exists('valid_email')) {
    /**
     * Validate email address
     *
     * @deprecated  3.0.0   Use PHP's filter_var() instead
     * @param   string  $email
     * @return  bool
     */
    function valid_email($email)
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('GeneratePwd')) {
    function GeneratePwd($input, $rounds = 7)
    {
        $salt       = "";
        $salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        for ($i = 0; $i < 22; $i++) {
            $salt .= $salt_chars[array_rand($salt_chars)];
        }
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }
}

function password_generate($chars)
{
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($data), 0, $chars);
}

function get_initials($str)
{
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
}



if (!function_exists('is_obj')) {
    function is_obj($obj)
    {
        if (!is_object($obj)) {
            return false;
        }

        return $obj;
    }
}

if (!function_exists('CheckAuthLogin')) {
    function CheckAuthLogin()
    {
        $AllData = Session::get('UserData');
        if (!empty($AllData['UID']) && !empty($AllData['SID']) && !empty($AllData['Type']) && $AllData['Login'] && Session::has('UserData')) {
            return true;
        } else {
            return false;
        }
    }
}




if (!function_exists('get_user_id')) {
    function get_user_id()
    {
        return Session::has('UserData') ? Session::get('UserData')['UID'] : 0;
    }
}

if (!function_exists('get_user_fullname')) {
    function get_user_fullname()
    {
        return Session::has('UserData') ? Session::get('UserData')['FullName'] : 0;
    }
}

if (!function_exists('admin_activity')) {
    function admin_activity($lastid, $title, $data, $type, $table)
    {
        if ($type == 'added') {
            $type = 'Last Inserted Data';
        } elseif ($type == 'updated') {
            $type = 'Last Updated Data';
        } elseif ($type == 'deleted') {
            $type = 'Last Deleted Data';
        } elseif ($type == 'duplicated') {
            $type = 'Last Duplicate Data';
        } elseif ($type == 'status') {
            $type = 'Last Status Data';
        } else {
            $type = 'Not Found';
        }

        $Desc = ucwords(Session::get('UserData')['FullName']) . ' ' . $title . ' ' . $type . json_encode($data) . ' Last Operation ID ' . $lastid;
        RecordAdminActivity($title, $table, $Desc, get_user_id());
    }
}


function objectToArray($object)
{
    if (!is_object($object) && !is_array($object)) {
        return $object;
    }
    return array_map('objectToArray', (array) $object);
}

function get_last_query()
{
    $query = DB::getQueryLog();
    $query = end($query);
    return $query;
}

if (!function_exists('delete_file_from_server')) {
    function delete_file_from_server($path)
    {
        $full_path = public_path('assets') . '/' . $path;
        if (strlen($path) > 15 && file_exists($full_path)) {
            unlink($full_path);
        }
    }
}


if (!function_exists('check_image_extension')) {
    function check_image_extension($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension = 'gif') {
            return false;
        }
        return true;
    }
}



function is_in_array($array, $key, $key_value)
{
    $within_array = 'no';
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            $within_array = is_in_array($v, $key, $key_value);
            if ($within_array == 'yes') {
                break;
            }
        } else {
            if ($v == $key_value && $k == $key) {
                $within_array = 'yes';
                break;
            }
        }
    }
    return $within_array;
}

function check_valid_array($data)
{
    if (!empty($data) && is_array($data) && count($data) > 0) {
        return true;
    } else {
        return false;
    }
}




function uploadFile(UploadedFile $file, string $path, string $disk)
{
    $filename = uniqid() . '.' . $file->getClientOriginalExtension();

    $storedFile = $file->storeAs($path, $filename, $disk);

    return $storedFile;
}

function UploadFile_single($type, $name, UploadedFile $files, $path, $table = '', $setcolumn = '', $WhereCondition = array(), $base_path)
{
    $album_File = time() . '_' . $files->getClientOriginalName();
    $tempPath   = $path . $album_File;
    $data       = null;

    // Move the file to the specified path
    if ($files->move($path, $album_File)) {
        // Check if the file is an image based on its extension
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'];  // Extended image formats
        if (in_array(strtolower($files->getClientOriginalExtension()), $imageExtensions)) {
            // Process the image if it's one of the recognized types
            $img = Image::make($tempPath);
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();  // Prevent upscale
            });
            $img->save($tempPath, 60);  // Compress and save with a quality of 60%
            $data = $album_File;
        } else {
            // If not an image, just return the file name
            $data = $album_File;
        }

        // Handle database update and file replacement for 'edit' type
        if ($type == 'edit' && !empty($table) && !empty($setcolumn)) {
            $previousData     = DB::table($table)->selectRaw($setcolumn)->where($WhereCondition)->get()->toArray()[0];
            $previousFilename = pathinfo($previousData->$setcolumn, PATHINFO_BASENAME);

            if (File::exists($path . $previousFilename)) {
                File::delete($path . $previousFilename);  // Delete the old file
            }

            // Update the database with the new file name
            DB::table($table)
                ->where($WhereCondition)
                ->limit(1)
                ->update([$setcolumn => $album_File]);
        }
    }

    return $data;
}

function deleteFile($diskName, $path)
{
    $disk = Storage::disk($diskName);
    if ($disk->exists($path)) {
        $disk->delete($path);
        return true;
    }
    return false;
}

function get_filename($diskName, $path)
{
    $disk = Storage::disk($diskName);
    if ($disk->exists($path)) {
        $filename = pathinfo($path, PATHINFO_EXTENSION);
        //dump($filename);
        if ($filename != "") {
            return getFileBaseURL() . "assets/" . $path;
        }
    } else {
        return false;
    }
}


function compressImage(string $sourcePath, string $destinationPath, int $quality = 0): string
{
    $return = false;
    // Open the image file
    $image = Image::make($sourcePath);
    // Compress and save the image by specifying a quality value (0-100)
    $res = $image->save($destinationPath, $quality);
    if ($res) {
        $return = true;
    }

    return $return;
}



//sections

function Get_Rolls($RollId)
{

    $query = MasterRoll::where('RollId', $RollId)->limit(1)->get();
    if ($query->count() == 1) {
        $AdminRow = $query->toArray()[0];
        return Get_Menu($AdminRow['RollAssignID']);
    } else {
        if (Session::get('UserData')['Type'] == 'ALL') {
            return Get_Menu($RollId = '');
        } else {
            return false;
        }
    }
}


function Get_Menu($RollAssingId)
{
    if ($RollAssingId && !empty($RollAssingId)) {
        $Assign  = str_replace(",", "','", $RollAssingId);
        $Assign1 = "'" . $Assign . "'";
        return $query   = Menu::whereIn('SubMenuID', explode(',', $RollAssingId))
            ->where('Status', '=', 'Y')
            ->where('type', '=', '')
            ->orderBy('Orders', 'asc')
            ->get()->toArray();
    } elseif (empty($RollAssingId)) {
        return $query = Menu::where('Status', '=', 'Y')
            ->where('type', '=', '')
            ->orderBy('Orders', 'asc')
            ->get()->toArray();
    }
}


function Admin_Side_Bar()
{
    $Menu = Get_Rolls(Session::get('UserData')['Type']);
    if (count(array_values($Menu)) > 0 && $Menu) {
        $MenuName = array();
        foreach ($Menu as $key => $value) {
            if (substr($value['SubMenuID'], -1) == '0') {
                $MenuName[] = Get_Menu($value['SubMenuID']);
            }
        }
        return $MenuName;
    } else {
        return false;
    }
}



function CheckRolls()
{
    $Menu = Get_Rolls(Session::get('UserData')['Type']);
    if (count(array_values($Menu)) > 0 && $Menu) {
        foreach ($Menu as $key => $value) {
            $MenuName[] = $value['SubMenuID'];
        }
        return $MenuName;
    } else {
        return false;
    }
}


function HasPermission($Role)
{
    $Menu = CheckRolls();
    if (!empty($Menu) && count($Menu) > 0) {
        if (!in_array($Role, array_values(($Menu)))) {
            return 'false';
        } else {
            return 'true';
        }
    }
}


function HasPermissionModel($Role)
{
    $Menu = CheckRollsModel();
    if (!empty($Menu) && count($Menu) > 0) {
        if (!in_array($Role, array_values(($Menu)))) {
            return 'false';
        } else {
            return 'true';
        }
    }
}


function CheckRollsModel()
{
    $Menu = Get_Rolls(Session::get('UserData')['Type']);
    if (count(array_values($Menu)) > 0 && $Menu) {
        foreach ($Menu as $key => $value) {
            $MenuName[] = $value['SubMenuID'];
        }
        return $MenuName;
    } else {
        return false;
    }
}


function get_user_data()
{
    return User::find(Session::get('UserData')['UID']);
}






function get_settings($column, $param = null)
{
    $var  = null;
    $data = Mastersettings::select([$column])->where('id', 1)->first();

    if ($data != null) {
        if ($column == 'general_settings') {
            // Decode the JSON data from the 'general_settings' column
            $general_setting_data = json_decode($data->general_settings, true);

            if (check_valid_array($general_setting_data)) {
                // If a specific parameter is requested, return it
                if ($param !== null) {
                    $var = $general_setting_data[$param] ?? null;
                } else {
                    // Otherwise, return the entire JSON data as an associative array
                    $var = $general_setting_data;
                }
            }
        } elseif ($column == 'system_logo' || $column == 'login_screen_logo') {
            $logo = $column == 'system_logo' ? $data->system_logo : $data->login_screen_logo;

            if (!empty($logo)) {
                $filename = get_filename('Files_storage', config('constant.mastersetting') . "/" . $logo);
                if ($filename) {
                    $var = $filename;
                }
            }
        }
    }

    return $var;
}