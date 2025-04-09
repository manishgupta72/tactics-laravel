<?php

use App\Http\Controllers\HomeController;

ini_set('max_execution_time', 60);

use App\Models\Menu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssociatesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportReportController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\RollsController;
use App\Http\Controllers\MasterSettingController;
use App\Http\Controllers\SalarySlipController;
use App\Http\Controllers\ServicesController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// FrontEnd Routes end here


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contract', [HomeController::class, 'contract'])->name('contract');
Route::get('/permanent', [HomeController::class, 'permanent'])->name('permanent');
Route::get('/payroll', [HomeController::class, 'payroll'])->name('payroll');
Route::get('/job-opening', [HomeController::class, 'job_opening'])->name('job-opening');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/fetch-jobs', [HomeController::class, 'fetch_jobs'])->name('fetch.jobs');
Route::get('/login-validate', [HomeController::class, 'login'])->name('login-validate');
Route::get('/login', [HomeController::class, 'login_candidate'])->name('login');
Route::get('/userdashboard', [HomeController::class, 'user_dashboard'])->name('user.dashboard');
Route::post('/send-otp', [HomeController::class, 'send_otp'])->name('send.otp');
Route::post('/verify-otp', [HomeController::class, 'verify_otp'])->name('verify.otp');
Route::post('/logout', [HomeController::class, 'logout_user'])->name('logout');



Route::get('backend/', [AdminController::class, 'index'])->name('admin')->middleware('checkauth', 'preventbackhistory');
Route::post('/login', [AdminController::class, 'admin_login']);
Route::post('admin_login/', [AdminController::class, 'admin_login'])->name('admin_login');

// Route to fetch employee-specific summary data via AJAX
Route::post('/get-employee-data', [AdminController::class, 'getRecruiterSummary'])->name('get.employee.summary');

Route::group(['prefix' => 'backend', 'middleware' => ['adminauth', 'preventbackhistory']], function () {

    Route::get('clear-cache/', [AdminController::class, 'clearCache'])->name('cache.clear');
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [AdminController::class, 'admin_logout'])->name('admin.logout');
    Route::get('change-password', [AdminController::class, 'change_password'])->name('change.password');

    Route::post('list-of-permissson', [RollsController::class, 'list_of_permissson'])->name('list.prmsn');

    Route::post('save-master-data', [MasterSettingController::class, 'save_master_data'])->name('sv.mstrdata');
    Route::post('list-master-data', [MasterSettingController::class, 'list_master_data'])->name('lst.mstrdata');
    Route::post('edit-master-data', [MasterSettingController::class, 'edit_master_data'])->name('edt.mstrdata');
    Route::post('delete-master-data', [MasterSettingController::class, 'delete_master_data'])->name('del.mstrdata');
    Route::post('change-password', [MasterSettingController::class, 'change_password'])->name('chng.paswrd');
    // Employee routes here 
    Route::get('employee-details/{emp_id}', [EmployeeController::class, 'employeeDetails'])->name('employee.details');
    Route::post('save-basic-details', [EmployeeController::class, 'saveBasicDetails'])->name('employee.save.basic');
    Route::post('save-bank-details', [EmployeeController::class, 'saveBankDetails'])->name('employee.save.bank');
    Route::post('save-pfesic-details', [EmployeeController::class, 'savePFESICDetails'])->name('employee.save.pfesic');
    Route::post('save-document-details', [EmployeeController::class, 'saveDocumentDetails'])->name('employee.save.document');
    Route::get('get-documents/{emp_id}', [EmployeeController::class, 'getDocuments'])->name('employee.get.documents');
    Route::delete('delete-document/{id}', [EmployeeController::class, 'deleteDocument'])->name('employee.delete.document');
    Route::post('view-salary-slip-data', [SalarySlipController::class, 'view_salary_slip_data'])->name('vw.sl.sp.dt');

    Route::post('employee-view-salary-slip-data', [EmployeeController::class, 'employee_view_salary_slip_data'])->name('emp.sl.sp.dt');
    Route::post('employee-view-salary-slip-download', [EmployeeController::class, 'employee_view_salary_slip_download'])->name('emp.sl.sp.download');
    Route::get('employee-view-salary-slip-download-company', [EmployeeController::class, 'employee_view_salary_slip_download_company'])->name('emp.sl.sp.download.c');
    Route::post('employee-delete-file-from-server', [EmployeeController::class, 'employee_delete_file_from_server'])->name('emp.sl.sp.del');
    Route::post('/salary-slip/download', [EmployeeController::class, 'employee_salary_slip_download'])->name('salary_slip');
    Route::post('/reports/export', [ExportReportController::class, 'export'])->name('reports.export');

    Route::post('/employee/import', [EmployeeController::class, 'importExcel'])->name('employee.import');
    Route::get('employee/download-excel', function (Request $request) {
        $filename = $request->query('file');

        if (!$filename) {
            abort(404, 'No file specified');
        }

        $path = storage_path("app/excel_exports/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download($path)->deleteFileAfterSend(true);
    })->name('employee.download.excel');

    Route::get('/employee/excel-view', [EmployeeController::class, 'view_excel_employee_data'])->name('emp.view.emp.exl.data');
    Route::post('/employee/uploads', [EmployeeController::class, 'upload_employee'])->name('emp.uploads.data');



    $urls = Menu::where(['type' => ''])->get()->toArray();

    if (!empty($urls) && is_array($urls)) {
        foreach ($urls as $key => $value) {

            if (!empty($value) && is_array($value) && count($value)) {
                $PageController = explode('/', $value['PageUrl']);
                if (!empty($PageController) && is_array($PageController) && count($PageController) && array_filter($PageController)) {
                    if ($PageController[2] != "" && $value['Status'] == 'Y') {
                        $controller_name = $PageController[2];

                        Route::get($PageController[2], [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), 'index'])->name('pages.' . $PageController[2]);
                        Route::get('page' . $PageController[2], [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), 'page' . $controller_name])->name('page.' . $PageController[2]);
                        Route::get('page' . $PageController[2] . '/EID/{id}', [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), 'page' . $controller_name])->where('id', '[0-9]+');
                        Route::post('Save' . $PageController[2], [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), 'Save' . $PageController[2]])->name('save.' . $PageController[2]);
                        Route::post($PageController[2] . 'St', [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), $PageController[2] . 'Status'])->name($PageController[2] . '.status');
                        Route::post($PageController[2] . 'Fltr', [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), $PageController[2] . 'Filter'])->name($PageController[2] . '.Filter');
                        Route::post('Delete' . $PageController[2], [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), 'Delete' . $PageController[2]])->name('delete.' . $PageController[2]);
                        Route::post('View' . $PageController[2], [str_replace(' ', '', 'App\Http\Controllers\ ' . $controller_name), 'View' . $PageController[2]])->name('view.' . $PageController[2]);
                    }
                }
            }
        }
    }
});


Route::fallback(function () {
    if (!request()->is('backend/*') && !request()->is('backend')) {
        // return response()
        //     ->view('frontend.error.404', ['title'=>404], 404);
        return response()->view('error.404', ['title' => '404'], 404);
    }
    return response()->view('error.404', ['title' => '404'], 404);
});
