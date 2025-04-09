<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ti-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if ($MenuNames && !empty($MenuNames))
                    @foreach ($MenuNames as $key => $MenuName)
                        @php
                            $page_url =
                                !empty(json_decode($MenuName[0]['MenuSub'])) &&
                                count(json_decode($MenuName[0]['MenuSub']))
                                    ? 'JavaScript:void(0)'
                                    : rtrim(getBaseURL(), '/') . $MenuName[0]['PageUrl'];
                        @endphp
                        <li>
                            <a href="{{ $page_url }}"
                                class="{{ !empty(json_decode($MenuName[0]['MenuSub'])) && count(json_decode($MenuName[0]['MenuSub'])) ? 'has-arrow waves-effect' : '' }}">
                                <i class="{{ $MenuName[0]['Icon'] ?? '' }}"></i>
                                <span>{{ $MenuName[0]['MenuTitle'] ?? '' }}</span>
                            </a>
                            @if (!empty(json_decode($MenuName[0]['MenuSub'])) && count(json_decode($MenuName[0]['MenuSub'])))
                                <ul class="sub-menu" aria-expanded="false">
                                    @foreach (json_decode($MenuName[0]['MenuSub']) as $key => $value)
                                        @if (HasPermissionModel($value) == 'true')
                                            @php
                                                $b = App\Models\Menu::get_single_record('SubMenuID', $value);
                                            @endphp
                                            @if ($b != 'false')
                                                @if (substr($b['SubMenuID'], -1) == '0')
                                                    <li><a href="{{ rtrim(getBaseURL(), '/') . $b['PageUrl'] }}">
                                                            {{ $b['MenuTitle'] ?? '' }}</a></li>
                                                @else
                                                    @php
                                                        $sub_menu_url =
                                                            'page' . explode('/', $MenuName[0]['PageUrl'])[2];
                                                    @endphp
                                                    <li><a href="{{ admin_getBaseURL() . $sub_menu_url }}">
                                                            {{ $b['MenuTitle'] ?? '' }}</a></li>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @endif
                @if (Session::get('UserData')['UID'] && is_numeric(Session::get('UserData')['UID']) && Session::has('UserData'))
                        <li>
                            <a class="waves-effect" href="{{ route('change.password') }}"">
                                 <i class="dripicons-lock"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                @endif
                @if (Session::get('UserData')['UID'] && is_numeric(Session::get('UserData')['UID']) && Session::has('UserData'))
                    <li>
                        <a href="{{ route('admin.logout') }}" class="waves-effect">
                            <i class="dripicons-enter"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
