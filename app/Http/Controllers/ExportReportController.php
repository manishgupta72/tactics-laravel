<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class ExportReportController extends Controller
{
    public function index(Request $req)
    {
        if ($req->isMethod('get')) {

            $data['MenuNames'] = Admin_Side_Bar();
            $data['title'] = "Export Report";
            view()->share($data);
            return view('report.index', $data);
        } else {
            Session::flash('warning', 'Something went wrong with the HTTP request.');
            return redirect()->route('dashboard');
        }
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $reportType = $request->input('report_type');

        if ($reportType == 1) {
            return (new ExportReport($startDate, $endDate))->download('master_employee_report.xlsx');
        }

        return redirect()->back()->withErrors(['error' => 'Invalid report type selected.']);
    }
}