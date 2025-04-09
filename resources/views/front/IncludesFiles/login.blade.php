@include('front.IncludesFiles.head')
@include('front.IncludesFiles.menu')
@include('front.IncludesFiles.page-title', ['title' => 'Login'])
<section>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 p-5 mb-lg-0 shadow border-all-light">
                <h2 class="font-weight-bold text-5 mb- text-center">Login for Candidate Only</h2>
                <form id="frmSignIn" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <!-- Mobile Number Field -->
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">
                                Your Registered Mobile Number <span class="text-color-danger">*</span>
                            </label>
                            <input type="number" value="" class="form-control form-control-lg text-4" required
                                placeholder="10 Digit Mobile Number" name="mobile">
                            <button type="button" id="sendOtpBtn" class="btn btn-primary w-100 mt-3">Send OTP</button>
                        </div>
                    </div>
                    <div class="row mt-4 d-none" id="otpSection">
                        <!-- OTP Field -->
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">
                                OTP <span class="text-color-danger">*</span>
                            </label>
                            <input id="otpInput" type="number" value="" class="form-control form-control-lg text-4"
                                required placeholder="Six Digit OTP" name="otp">
                        </div>
                    </div>
                    <div class="row mt-3 d-none" id="loginSection">
                        <!-- Login Button -->
                        <div class="form-group col">
                            <button type="button" id="loginBtn"
                                class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3"
                                data-loading-text="Loading...">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>



@include('front.IncludesFiles.footer')