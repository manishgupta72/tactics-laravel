<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="#" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ get_settings('system_logo', '') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ get_settings('system_logo', '') }}" alt="" height="17">
                    </span>
                </a>

                <a href="#" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ get_settings('system_logo', '') }}" alt="" width="40px" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ get_settings('system_logo', '') }}" alt=""  width="170px" height="40">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
            </button>


        </div>

        <div class="d-flex">


            <div class="dropdown d-none d-lg-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="mdi mdi-fullscreen"></i>
                </button>
            </div>


            <div class="dropdown d-inline-block">
                <button class="header-item font-size-18" style="font-weight: 500;">
                    @php
                        $fullname = '';
                        $user_data = get_user_data();
                        if ($user_data != null) {
                            $fullname = $user_data->name ?? '';
                        }

                    @endphp
                    Hi, {{ $fullname }}
                </button>
            </div>

        </div>
    </div>
</header>
