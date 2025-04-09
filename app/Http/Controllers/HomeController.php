<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class HomeController extends Controller
{
    public function index()
    {
        $data['title'] = 'Home - Tactics Services';
        $data['aboutMediaFiles'] = DB::table('media_files')->where('mf_type', 'HomeAbout')->get();
        // Fetch all media files where type is 'Clients'
        $data['clientMediaFiles'] = DB::table('media_files')->where('mf_type', 'Clients')->get();
        $data['sliders'] = DB::table('media_files')->where('mf_type', 'Slider')->get();
        return view('front.index', $data);
    }


    public function send_otp(Request $request)
    {
        $mobile = $request->input('mobile');

        // Validate the mobile number exists in `master_employee`
        $employee = DB::table('master_employee')->where('emp_mobile', $mobile)->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Invalid mobile number'], 400);
        }

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Store or update OTP in the database
        DB::table('otp')->insert([
            'mobile' => $mobile,
            'otp' => $otp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);



        $smsText = urlencode("Your OTP for PIDA Chapter verification is $otp. Please use this code to complete your authentication. Pediatric Infectious Diseases Academy");

        $url = "http://vas.mobilogi.com/api.php?username=pidaip&password=Pass@1234&route=1&sender=PIDAIP&mobile[]=$mobile&message[]=$smsText&templateid=1207172648999221087";


        try {
            // Make the API request
            $response = Http::get($url);
            // dd($response);
            // Log the API response for debugging
            \Log::info("SMS API Response: $response");

            return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);
        } catch (\Exception $e) {
            \Log::error("SMS API Error: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to send OTP.'], 500);
        }
    }



    public function verify_otp(Request $request)
    {
        $mobile = $request->input('mobile');
        $inputOtp = $request->input('otp');

        // Fetch the OTP record for the mobile number with status 0
        // Fetch the latest OTP record for the mobile number with status 0
        $otpRecord = DB::table('otp')
            ->where('mobile', $mobile)
            ->where('status', 0)
            ->orderBy('created_at', 'desc') // Get the latest record
            ->first();

        if (!$otpRecord) {
            return response()->json(['success' => false, 'message' => 'Mobile number not found or OTP already verified.'], 400);
        }

        // Check if the OTP matches
        if ($otpRecord->otp == $inputOtp) {
            // Update the OTP status to 1 after successful verification
            DB::table('otp')->where('id', $otpRecord->id)->update(['status' => 1]);

            // Fetch employee details
            $employee = DB::table('master_employee')->where('emp_mobile', $mobile)->first();

            // Store details in session
            Session::put('employee_mobile', $mobile);
            Session::put('employee', $employee);

            return response()->json(['success' => true, 'message' => 'OTP verified successfully.']);
        }

        return response()->json(['error' => false, 'message' => 'Invalid OTP.'], 400);
    }



    public function about()
    {
        $data['title'] = 'About Us - Tactics Services';

        // Fetch all media files of type "About" using raw query
        $data['aboutMediaFiles'] = DB::table('media_files')
            ->whereIn('mf_type', ['About1', 'About2'])->get();

        return view('front.about', $data);
    }
    public function contract()
    {
        $data['title'] = 'Contract Services - Tactics Services';
        $data['aboutMediaFiles'] = DB::table('media_files')
            ->where('mf_type', 'Contract Staffing')->get();
        return view('front.IncludesFiles.contract-staffing', $data);
    }
    public function permanent()
    {
        $data['title'] = 'Permanent Services - Tactics Services';
        $data['aboutMediaFiles'] = DB::table('media_files')
            ->where('mf_type', 'Permanent Staffing')->get();
        return view('front.IncludesFiles.parmanent-staffing', $data);
    }
    public function payroll()
    {
        $data['title'] = 'Payroll Services - Tactics Services';
        $data['aboutMediaFiles'] = DB::table('media_files')
            ->where('mf_type', 'Payroll Outsourcing')->get();
        return view('front.IncludesFiles.payroll-outsourcing', $data);
    }

    public function job_opening()
    {
        $data['title'] = 'Job Openings - Tactics Services';
        return view('front.job-opening', $data);
    }

    public function fetch_jobs()
    {
        $jobs = DB::table('jobs_opening')
            ->where('job_status', 1) // Assuming 1 means active
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'jobs' => $jobs->items(),
            'next_page_url' => $jobs->nextPageUrl(),
        ]);
    }
    public function login_candidate()
    {

        $data['title'] = 'User Login - Tactics Services';

        return view('front.IncludesFiles.login', $data);
    }
    public function login(Request $request)
    {
        $mobile = $request->input('mobile');

        // Validate the mobile number
        $employee = DB::table('master_employee')->where('emp_mobile', $mobile)->first();

        if (!$employee) {
            return redirect()->route('login')->withErrors(['Invalid mobile number.']);
        }

        // Store the mobile number in the session
        $request->session()->put('employee_mobile', $mobile);

        return redirect()->route('user.dashboard');
    }
    public function user_dashboard(Request $request)
    {
        $data['title'] = 'User Dashboard - Tactics Services';

        // // Retrieve the logged-in user's mobile number
        $mobile = $request->session()->get('employee_mobile');

        if (!$mobile) {
            return redirect()->route('login')->withErrors(['Please log in first.']);
        }

        // Fetch Employee Details using the mobile number
        $employee = DB::table('master_employee')->where('emp_mobile', $mobile)->first();

        if (!$employee) {
            return redirect()->route('login')->withErrors(['Employee not found.']);
        }

        $emp_id = $employee->emp_id;

        // Fetch additional details
        $data['employee'] = $employee;

        // Fetch Bank Details
        $data['bank_details'] = DB::table('bank_employee')->where('emp_id', $emp_id)->first();

        // Fetch PF & ESIC Details
        $data['pf_esic_details'] = DB::table('pf_esic_employee')->where('emp_id', $emp_id)->first();

        // Fetch Uploaded Documents
        $data['documents'] = DB::table('document_employee')->where('emp_id', $emp_id)->get();
        // Fetch Salary Slips
        $data['salarySlips'] = DB::table('salary_slip')
            ->where('employee_id', $emp_id)
            ->get();
        // Pass the data to the view
        return view('front.user-dashboard', $data);
    }

    public function contact()
    {

        $data['title'] = 'Contact Us - Tactics Services';
        $data['aboutMediaFiles'] = DB::table('media_files')->where('mf_type', 'Contact')->get();
        return view('front.contact', $data);
    }
    public function logout_user()
    {

        // Clear the session
        Session::flush();

        // Redirect to the login page or home page
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
