<header id="header" class="header-effect-shrink"
    data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': true, 'stickyStartAt': 120, 'stickyHeaderContainerHeight': 70}">
    <div class="header-body border-top-0">
        <div class="header-top header-top-default header-top-borders border-bottom-0 bg-color-light">
            <div class="container h-100">
                <div class="header-row h-100">
                    <div class="header-column justify-content-between">
                        <div class="header-row">
                            <nav class="header-nav-top w-100">
                                <ul class="nav nav-pills justify-content-between w-100 h-100">
                                    <li class="nav-item py-2 d-none d-xl-inline-flex">
                                        <span
                                            class="header-top-phone py-2 d-flex align-items-center text-color-primary font-weight-semibold text-uppercase">
                                            <i class="icon-phone icons text-5 me-2"></i> <a
                                                href="tel:{{get_settings('general_settings', 'support_number')}}">{{get_settings('general_settings', 'support_number')}}</a>
                                        </span>
                                        <span
                                            class="header-top-email px-0 font-weight-normal d-flex align-items-center"><i
                                                class="far fa-envelope text-4"></i> <a class="text-color-default"
                                                href="mailto:{{get_settings('general_settings', 'support_email')}}">{{get_settings('general_settings', 'support_email')}}</a></span>
                                        <span
                                            class="header-top-opening-hours px-0 font-weight-normal d-flex align-items-center"><i
                                                class="far fa-clock text-4"></i>Mon - Sat 9:00am - 6:00pm / Sunday -
                                            CLOSED</span>
                                    </li>
                                    <li class="nav-item nav-item-header-top-socials d-flex justify-content-between">
                                        <span class="header-top-socials p-0 h-100">
                                            <ul class="d-flex align-items-center h-100 p-0">
                                                <li class="list-unstyled">
                                                    <a target="_blank"
                                                        href="{{get_settings('general_settings', 'insta_link')}}"><i
                                                            class="fab fa-instagram text-color-quaternary text-hover-primary"></i></a>
                                                </li>
                                                <li class="list-unstyled">
                                                    <a target="_blank"
                                                        href="{{get_settings('general_settings', 'facebook_link')}}"><i
                                                            class="fab fa-facebook-f text-color-quaternary text-hover-primary"></i></a>
                                                </li>
                                                <li class="list-unstyled">
                                                    <a target="_blank"
                                                        href="{{get_settings('general_settings', 'x_link')}}"><i
                                                            class="fab fa-x-twitter text-color-quaternary text-hover-primary"></i></a>
                                                </li>
                                            </ul>
                                        </span>
                                        @if(Session::has('employee_mobile'))
                                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit"
                                                class="header-top-button-make-as-appoitment d-inline-flex align-items-center justify-content-center h-100 p-0 align-top btn-primary font-weight-bold text-decoration-none border-0 text-uppercase rounded-0">
                                                Logout
                                            </button>
                                        </form>
                                        @else
                                        <span
                                            class="header-top-button-make-as-appoitment d-inline-flex align-items-center justify-content-center h-100 p-0 align-top">
                                            <a href="/tactics/login"
                                                class="d-flex text-4 align-items-center justify-content-center h-100 w-100 btn-primary font-weight-normal text-decoration-none">
                                                CANDIDATE LOGIN
                                            </a>
                                        </span>
                                        @endif

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
                                            data-bs-toggle="dropdown">
                                            🌍 Language
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                            <li><a class="dropdown-item change-lang" href="#" data-lang="en">English</a>
                                            </li>
                                            <li><a class="dropdown-item change-lang" href="#"
                                                    data-lang="fr">Français</a></li>
                                            <li><a class="dropdown-item change-lang" href="#" data-lang="de">Deutsch</a>
                                            </li>
                                            <li><a class="dropdown-item change-lang" href="#" data-lang="hi">हिन्दी</a>
                                            </li>
                                        </ul>
                                    </li>


                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-container container bg-color-light">
            <div class="header-row">
                <div class="header-column header-column-logo">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="/tactics">
                                <!-- <img alt="Porto" width="123" height="48" src="img/demos/medical-2/logos/logo.png"> -->
                                <img width="123" height="48" src="{{ get_settings('login_screen_logo', '')   }}"
                                    alt="Company logo">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="header-column header-column-nav-menu justify-content-end">
                    <div class="header-row">
                        <div class="header-nav header-nav-links order-2 order-lg-1">
                            <div
                                class="header-nav-main header-nav-main-square header-nav-main-effect-1 header-nav-main-sub-effect-1">
                                <nav class="collapse">
                                    <ul class="nav nav-pills" id="mainNav">
                                        <li class="dropdown-secondary">
                                            <a class="nav-link active" href="/tactics">
                                                Home
                                            </a>
                                        </li>
                                        <li class="dropdown-secondary">
                                            <a class="nav-link" href="/tactics/about" data-i18n="about">
                                                About Us
                                            </a>
                                        </li>
                                        <li class="dropdown dropdown-secondary">
                                            <a class="nav-link dropdown-toggle" class="dropdown-toggle" href="#"
                                                data-i18n="services">
                                                Services
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item font-weight-normal"
                                                        href="/tactics/contract">
                                                        Contract Staffing
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item font-weight-normal"
                                                        href="/tactics/permanent">
                                                        Permanent Staffing
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item font-weight-normal" href="/tactics/payroll">
                                                        Payroll Outsourcing
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="dropdown-secondary">
                                            <a class="nav-link" href="/tactics/job-opening">
                                                Job Openings
                                            </a>
                                        </li>
                                        <li class="dropdown-secondary">
                                            <a class="nav-link" href="/tactics/contact">
                                                Contact Us
                                            </a>
                                        </li>
                                        <!-- Dashboard (Only for logged-in users) -->
                                        @if(Session::has('employee_mobile'))
                                        <li class="dropdown-secondary">
                                            <a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a>
                                        </li>

                                        @endif



                                    </ul>
                                </nav>
                            </div>
                            <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse"
                                data-bs-target=".header-nav-main nav">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>