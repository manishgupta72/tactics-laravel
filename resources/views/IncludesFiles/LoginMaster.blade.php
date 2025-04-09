<!doctype html>
<html lang="en">

<head>
    <title>Tactics</title>
    @include('IncludesFiles.Common-Css')
</head>

<body data-sidebar="colored">
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="bg-primary">
                            <div class="text-primary text-center p-4">
                                <h3 class="text-white font-size-25">{{ get_settings('general_settings','system_name')   }}</h3>
                                <a href="index.html" class="logo logo-admin">
                                    <img src="{{ get_settings('login_screen_logo','')   }}" height="40" alt="logo">
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="p-3">
                                <form id="LoginForm" class="mt-4">

                                    <div class="mb-3">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="LoginC"
                                            placeholder="Enter username">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="userpassword">Password</label>
                                        <input type="password" class="form-control" id="userpassword" name="LoginP"
                                            placeholder="Enter password">
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-sm-6">

                                        </div>
                                        <div class="col-sm-6 text-end">
                                            <button type="submit" style="background-color: #042B48 !important; border:0" class="btn btn-primary  w-md waves-effect waves-light"
                                                id="LoginFrm">Log In</button>
                                        </div>
                                    </div>

                                    <!-- <div class="mt-2 mb-0 row">
                                        <div class="col-12 mt-4">
                                            <a href="pages-recoverpw.html"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                        </div>
                                    </div> -->

                                </form>

                            </div>
                        </div>

                    </div>

                    <div class="mt-5 text-center">
                        {{-- <p class="mb-0">©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> 
                            Veltrix. Crafted with <i class="mdi mdi-heart text-danger"></i>
                        </p> --}}
                    </div>


                </div>
            </div>
        </div>
    </div>
    @include('IncludesFiles.Common-Js')
</body>

</head>

</html>



@yield('file_script')