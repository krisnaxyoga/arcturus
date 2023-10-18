<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/dashboard_assets/css/styles.css" rel="stylesheet" />
    <link href="/dashboard_assets/cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="/dashboard_assets/cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link rel="icon" type="image/x-icon" href="/images/logo.png" />
    <script data-search-pseudo-elements defer
        src="/dashboard_assets/cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous">
    </script>
    <script src="/dashboard_assets/cdnjs.cloudflare.com/ajax/libs/feather-icons/4.27.0/feather.min.js"
        crossorigin="anonymous"></script>

    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script src="/dashboard_assets/cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" crossorigin="anonymous">
    </script>

    @stack('style')
    <title>Dashboard</title>
</head>

<body>
    <nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
        <a class="navbar-brand h-100 text-truncate" href="#">
            {{-- <img class="img-fluid" src="{{ asset('storage/logo/' . $setting->logo_image) }}" /> --}}
            Admin Panel
        </a>
        <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle"
            href="#"><i data-feather="menu"></i></button>

        <ul class="navbar-nav align-items-center ml-auto">
            <li class="nav-item dropdown no-caret mr-2 dropdown-user">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
                    href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="fa fa-user"></i>
                    {{-- <img class="img-fluid" src="{{ asset('storage/images/user.png') }}" /> --}}
                </a>
                <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up"
                    aria-labelledby="navbarDropdownUserImage">
                    {{-- <h6 class="dropdown-header d-flex align-items-center"> --}}

                        {{-- <img class="dropdown-user-img" src="{{ asset('storage/images/user.png') }}" /> --}}
                        {{-- <div class="dropdown-user-details">
                            <div class="dropdown-user-details-name">{{ Auth::user()->name }}</div>
                            <div class="dropdown-user-details-email">{{ Auth::user()->email }}</div>
                        </div> --}}
                    {{-- </h6> --}}
                    <div class="dropdown-divider"></div>
                    {{-- <a class="dropdown-item" href="">
                        <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                        Account
                    </a> --}}
                    {{-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{-- {{ Auth::user()->name }} --}}
                    {{-- </a> --}}
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sidenav shadow-right sidenav-light">
                <div class="sidenav-menu">
                    <div class="nav accordion" id="accordionSidenav">
                        <div class="sidenav-menu-heading">Main</div>
                        <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('dashboard.admin.booking') ? 'active' : '' }}" href="{{ route('dashboard.admin.booking') }}">
                            <div class="nav-link-icon"><i class="fas fa-fw fa-chart-area"></i></div>
                            Booking Confirmation
                        </a>
                        <a class="nav-link {{ request()->routeIs('dashboard.wallet.admin') ? 'active' : '' }}" href="{{ route('dashboard.wallet.admin') }}">
                            <div class="nav-link-icon"><i class="fas fa-fw fa-bell"></i></div>
                            Top Up Confirmation
                        </a>
                        <a class="nav-link {{ request()->Is('#') ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#agentCollapse"
                            aria-expanded="false" aria-controls="agentCollapse">
                            <div class="nav-link-icon"><i data-feather="box"></i></div>
                            Agents
                        </a>
                        <div id="agentCollapse" class="collapse">
                            <!-- Isi menu -->
                            <ul>
                                <li class="list-unstyled">
                                    <a class="nav-link {{ request()->routeIs('dashboard.agent') ? 'active' : '' }}" href="{{ route('dashboard.agent') }}">All agent</a>
                                </li>
                                <li class="list-unstyled">
                                    <a class="nav-link {{ request()->routeIs('dashboard.agent.create') ? 'active' : '' }}" href="{{ route('dashboard.agent.create') }}">
                                        Add New agent
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Tombol untuk memicu collapse -->
                        <a class="nav-link" href="#" data-toggle="collapse" data-target="#menuCollapse"
                            aria-expanded="false" aria-controls="menuCollapse">
                            <div class="nav-link-icon"><i class="fa fa-building"></i></div>
                            Hotel
                        </a>

                        <!-- Elemen yang akan di-collapse atau di-expand -->
                        <div id="menuCollapse" class="collapse">
                            <!-- Isi menu -->
                            <ul>
                                <li class="list-unstyled">
                                    <a href="{{ route('dashboard.hotel') }}" class="nav-link {{ request()->routeIs('dashboard.hotel') ? 'active' : '' }}">All hotel</a>
                                </li>
                                <li class="list-unstyled">
                                    <a class="nav-link {{ request()->routeIs('dashboard.hotel.create') ? 'active' : '' }}" href="{{ route('dashboard.hotel.create') }}">
                                        Add New hotel
                                    </a>
                                </li>
                                <li class="list-unstyled">
                                    <a href="{{ route('dashboard.roomtype') }}" class="nav-link {{ request()->routeIs('dashboard.roomtype') ? 'active' : '' }}">
                                        Room Types
                                    </a>
                                </li>
                                <li class="list-unstyled">
                                    <a href="{{ route('dashboard.attribute') }}" class="nav-link {{ request()->routeIs('dashboard.attribute') ? 'active' : '' }}">
                                        Room Atributes
                                    </a>
                                </li>
                                <li class="list-unstyled">
                                    <a href="{{ route('dashboard.paymenttohotel.index') }}" class="nav-link {{ request()->routeIs('dashboard.paymenttohotel.index') ? 'active' : '' }}">
                                       Payment To Hotel
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <a class="nav-link {{ request()->routeIs('dashboard.user') ? 'active' : '' }}" href="{{ route('dashboard.user') }}">
                            <div class="nav-link-icon"><i data-feather="user"></i></div>
                            Users
                        </a>
                        <a class="nav-link {{ request()->routeIs('dashboard.report') ? 'active' : '' }}" href="{{ route('dashboard.report') }}">
                            <div class="nav-link-icon"><i class="fas fa-fw fa-chart-area"></i></div>
                            Report
                        </a>
                        <a class="nav-link {{ request()->routeIs('dashboard.setting') ? 'active' : '' }}" href="{{ route('dashboard.setting') }}">
                            <div class="nav-link-icon"><i data-feather="settings"></i></div>
                            Setting
                        </a>
                    </div>
                </div>
                <div class="sidenav-footer">
                    <div class="sidenav-footer-content">
                        <div class="sidenav-footer-subtitle">Logged in as:</div>
                        <div class="sidenav-footer-title">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            <footer class="footer mt-auto footer-light">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 small">Copyright &#xA9; {{ date('Y') }} {{$setting->company_name}}</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="/dashboard_assets/code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="/dashboard_assets/stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="/dashboard_assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/dashboard_assets/assets/demo/chart-area-demo.js"></script>
    <script src="/dashboard_assets/assets/demo/chart-bar-demo.js"></script>
    <script src="/dashboard_assets/assets/demo/chart-pie-demo.js"></script>
    <script src="/dashboard_assets/cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="/dashboard_assets/cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous">
    </script>
    <script src="/dashboard_assets/assets/demo/datatables-demo.js"></script>
    <script src="/dashboard_assets/cdn.jsdelivr.net/momentjs/latest/moment.min.js" crossorigin="anonymous"></script>
    <script src="/dashboard_assets/cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" crossorigin="anonymous">
    </script>
    <script src="/dashboard_assets/assets/demo/date-range-picker-demo.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            //menu colapse pada hotel
            const menuCollapse = document.getElementById('menuCollapse');
            const activeMenu = menuCollapse.querySelector('.active');


            if (activeMenu) {
                menuCollapse.classList.add('show');
            }

            //agentcolapse
            const agentCollapse = document.getElementById('agentCollapse');
            const activeMenu2 = agentCollapse.querySelector('.active');

            if (activeMenu2) {
                agentCollapse.classList.add('show');
            }
        });
    </script>

    @stack('script')

</body>

</html>
