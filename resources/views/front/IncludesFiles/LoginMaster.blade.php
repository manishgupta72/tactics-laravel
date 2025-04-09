<!DOCTYPE HTML>
<html lang="en">
  <head>
  <title>Knok knok</title>
     @include('front.IncludesFiles.Common-Css')
  </head>
    <body class="theme-light">

        <div id="preloader">
            <div class="spinner-border color-highlight" role="status">
                
            </div>
        </div>

        <div class="page-content mb-0 pb-0">

            <div class="card card-style mb-0 bg-transparent shadow-0 bg-3 mx-0 rounded-0" data-card-height="cover" style="height: 915px;">
                <div class="card-center">
                    <div class="card card-style shadow">
                        <div class="content">
                            <div class="text-center">
                            <img id="image-data" src="{{ static_asset('assets/front/images/my-img/user-avtar.jpg') }}" class="img-fluid rounded-s w-25 mb-4" alt="Company logo">
                            </div>
                            <h1 class="text-center font-800 font-30 mb-2">Sign In</h1>
                            <p class="text-center font-15 mt-n2 mb-3">Enter your Credentials</p>
                            <form id="LoginForm">
                                <div class="form-custom form-label form-icon mb-3">
                                    <i class="bi bi-phone font-14"></i>
                                    <select class="form-select rounded-xs" id="c6" aria-label="Floating label select example" name="type">
                                        <option value="">Select type</option>
                                        @if (check_valid_array($type_details))
                                            @foreach ($type_details as $key => $item)
                                                    <option value="{{$key}}">{{ $item }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="c1" class="color-theme">Your Mobile Number</label>
                                    <span>(required)</span>
                                </div>
                                <div class="form-custom form-label form-icon mb-3">
                                    <i class="bi bi-phone font-14"></i>
                                    <input name="LoginC" type="number" class="form-control rounded-xs" id="c1" placeholder="Mobile Number">
                                    <label for="c1" class="color-theme">Your Mobile Number</label>
                                    <span>(required)</span>
                                </div>
                                <div class="form-custom form-label form-icon mb-3">
                                    <i class="bi bi-asterisk font-12"></i>
                                    <input type="text" class="form-control rounded-xs" id="c2" name="LoginP" placeholder="Password">
                                    <label for="c2" class="color-theme">Password</label>
                                    <span>(required)</span>
                                </div>
                                <a href="#" class="btn rounded-sm btn-m bg-highlight text-uppercase font-15 font-700 mt-4 mb-3 btn-full shadow-bg shadow-bg-s" id="LoginFrm">Sign In</a>
                            </form>

    
                        </div>
                    </div>
                </div>
            </div>
    
        </div>

        @include('front.IncludesFiles.Common-Js')
    </body>
    
  </head>
</html>



@yield('file_script')


