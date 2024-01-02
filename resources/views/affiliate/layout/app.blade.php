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
    <title>Dashboard affiliate</title>
</head>

<body>
    @if($code)
    <nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
        <a class="navbar-brand h-100 text-truncate" href="#">
            {{-- <img class="img-fluid" src="{{ asset('storage/logo/' . $setting->logo_image) }}" /> --}}
           Marketing
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
                    <a class="dropdown-item" href="{{ route('auth.affiliator.logout',['code'=>$code,'id'=>$id]) }}"
                        onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('auth.affiliator.logout',['code'=>$code,'id'=>$id]) }}" method="GET" class="d-none">
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
                        <a class="nav-link {{ request()->routeIs('auth.affiliator',['code'=>$code,'id'=>$id]) ? 'active' : '' }}" href="{{ route('auth.affiliator',['code'=>$code,'id'=>$id]) }}">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('auth.affiliator.link',['code'=>$code,'id'=>$id]) ? 'active' : '' }}" href="{{ route('auth.affiliator.link',['code'=>$code,'id'=>$id]) }}">
                            <div class="nav-link-icon"><i class="fas fa-fw fa-link"></i></div>
                            Link Affiliate
                        </a>
                        <a class="nav-link {{ request()->routeIs('auth.affiliator.hotel',['code'=>$code,'id'=>$id]) ? 'active' : '' }}" href="{{ route('auth.affiliator.hotel',['code'=>$code,'id'=>$id]) }}">
                            <div class="nav-link-icon"><i class="fas fa-fw fa-building"></i></div>
                            Hotel
                        </a>
                        <a class="nav-link {{ request()->routeIs('auth.affiliator.profile',['code'=>$code,'id'=>$id]) ? 'active' : '' }}" href="{{ route('auth.affiliator.profile',['code'=>$code,'id'=>$id]) }}">
                            <div class="nav-link-icon"><i class="fas fa-fw fa-user"></i></div>
                            Profile
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('auth.affiliator.logout',['code'=>$code,'id'=>$id]) ? 'active' : '' }}" href="{{ route('auth.affiliator.logout',['code'=>$code,'id'=>$id]) }}">
                            <div class="nav-link-icon"><i data-feather="settings"></i></div>
                            logout
                        </a>
                    </div>
                </div>
                <div class="sidenav-footer">
                    <div class="sidenav-footer-content">
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
                        <div class="col-md-12 small">Copyright &#xA9; {{ date('Y') }} {{$name}}</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @else
    <div class="container mt-5">
        <div class="row">
            <div class="jumbotron jumbotron-fluid bg-primary">
           <div class="container">
             <h1 class="display-4 text-light">Please Contact Admin...</h1>
             <a href="mailto:admin@arcturus.my.id" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 10px 20px; background-color: #db3434; color: #ffffff; text-decoration: none; border-radius: 5px;">Email Admin</a>

           </div>
         </div>
       </div>
    </div>
    @endif
   
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

             //agentcolapse
             const TransportCollapse = document.getElementById('TransportCollapse');
            const activeMenu3 = TransportCollapse.querySelector('.active');

            if (activeMenu3) {
                TransportCollapse.classList.add('show');
            }
        });
    </script>

    @stack('script')

</body>

</html>
