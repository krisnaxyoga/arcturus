<!DOCTYPE html>
<html lang="en">
<head>
	<title>Pacific - Free Bootstrap 4 Template by Colorlib</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Arizonia&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="/landing/travel/css/animate.css">
	<link rel="stylesheet" href="/landing/travel/css/owl.carousel.min.css">
	<link rel="stylesheet" href="/landing/travel/css/owl.theme.default.min.css">
	<link rel="stylesheet" href="/landing/travel/css/magnific-popup.css">
	<link rel="stylesheet" href="/landing/travel/css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="/landing/travel/css/jquery.timepicker.css">
	<link rel="stylesheet" href="/landing/travel/css/flaticon.css">
	<link rel="stylesheet" href="/landing/travel/css/style.css">
   
    <script src="/js/crypto.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
          window.addEventListener('load', function () {
            navigator.serviceWorker.register('/service-worker.js').then(function (registration) {
              console.log('Service Worker registered: ', registration);
            }).catch(function (error) {
              console.log('Service Worker registration failed: ', error);
            });
          });
        }
      </script>
</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar ftco-navbar-light ftco-navbar-dongker" id="ftco-navbar">
		<div class="container">
			{{-- <a class="navbar-brand" href="index.html">Pacific<span>Travel Agency</span></a> --}}
            <a class="navbar-brand" href="/">
                <img src="/images/pms-sistem-1.png" alt="">
            </a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="oi oi-menu"></span> Menu
			</button>

			<div class="collapse navbar-collapse" id="ftco-nav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item {{ request()->is('/') ? 'active' : '' }}"><a href="/" class="nav-link">Home</a></li>
                    <li class="nav-item {{ request()->is('about') ? 'active' : '' }}"><a href="about.html" class="nav-link">About</a></li>
                    {{-- <li class="nav-item {{ request()->is('destination') ? 'active' : '' }}"><a href="destination.html" class="nav-link">Destination</a></li> --}}
                    @if(isset(Auth::user()->id))
                    <li class="nav-item {{ request()->routeIs('hotel.homepage') ? 'active' : '' }}"><a href="{{ route('hotel.homepage') }}" class="nav-link">Hotel</a></li>
                    {{-- <li class="nav-item {{ request()->is('blog') ? 'active' : '' }}"><a href="blog.html" class="nav-link">Blog</a></li> --}}
                    @endif 
                    <li class="nav-item {{ request()->is('contact') ? 'active' : '' }}"><a href="contact.html" class="nav-link">Contact</a></li>
                    @if(isset(Auth::user()->id))
                    <li class="nav-item"><a href="{{route('login')}}" class="nav-link">dashboard</a></li>
                    @else
                    <li class="nav-item"><a href="{{route('login.agent')}}" class="nav-link">Login</a></li>
                    @endif

				</ul>
			</div>
		</div>
	</nav>
	<!-- END nav -->
    <main>
        @yield('contents')
    </main>


    <footer class="ftco-footer bg-bottom ftco-no-pt" style="background-image: url(images/bg_3.jpg);">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md pt-5">
                    <div class="ftco-footer-widget pt-md-5 mb-4">
                        <h2 class="ftco-heading-2">About</h2>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                        <ul class="ftco-footer-social list-unstyled float-md-left float-lft">
                            <li class="ftco-animate"><a href="#"><span class="fa fa-twitter"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="fa fa-facebook"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="fa fa-instagram"></span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md pt-5 border-left">
                    <div class="ftco-footer-widget pt-md-5 mb-4 ml-md-5">
                        <h2 class="ftco-heading-2">Infromation</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-2 d-block">Online Enquiry</a></li>
                            <li><a href="#" class="py-2 d-block">General Enquiries</a></li>
                            <li><a href="#" class="py-2 d-block">Booking Conditions</a></li>
                            <li><a href="#" class="py-2 d-block">Privacy and Policy</a></li>
                            <li><a href="#" class="py-2 d-block">Refund Policy</a></li>
                            <li><a href="#" class="py-2 d-block">Call Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md pt-5 border-left">
                    <div class="ftco-footer-widget pt-md-5 mb-4">
                        <h2 class="ftco-heading-2">Experience</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-2 d-block">Adventure</a></li>
                            <li><a href="#" class="py-2 d-block">Hotel and Restaurant</a></li>
                            <li><a href="#" class="py-2 d-block">Beach</a></li>
                            <li><a href="#" class="py-2 d-block">Nature</a></li>
                            <li><a href="#" class="py-2 d-block">Camping</a></li>
                            <li><a href="#" class="py-2 d-block">Party</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md pt-5 border-left">
                    <div class="ftco-footer-widget pt-md-5 mb-4">
                        <h2 class="ftco-heading-2">Have a Questions?</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li><span class="icon fa fa-map-marker"></span><span class="text">203 Fake St. Mountain View, San Francisco, California, USA</span></li>
                                <li><a href="#"><span class="icon fa fa-phone"></span><span class="text">+2 392 3929 210</span></a></li>
                                <li><a href="#"><span class="icon fa fa-paper-plane"></span><span class="text">info@yourdomain.com</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">

                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://turus.my.id" target="_blank">arcturus</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                </div>
            </div>
        </footer>



        <!-- loader -->
        <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


        <script src="/landing/travel/js/jquery.min.js"></script>
        <script src="/landing/travel/js/jquery-migrate-3.0.1.min.js"></script>
        <script src="/landing/travel/js/popper.min.js"></script>
        <script src="/landing/travel/js/bootstrap.min.js"></script>
        <script src="/landing/travel/js/jquery.easing.1.3.js"></script>
        <script src="/landing/travel/js/jquery.waypoints.min.js"></script>
        <script src="/landing/travel/js/jquery.stellar.min.js"></script>
        <script src="/landing/travel/js/owl.carousel.min.js"></script>
        <script src="/landing/travel/js/jquery.magnific-popup.min.js"></script>
        <script src="/landing/travel/js/jquery.animateNumber.min.js"></script>
        <script src="/landing/travel/js/bootstrap-datepicker.js"></script>
        <script src="/landing/travel/js/scrollax.min.js"></script>
        {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script> --}}
        <script src="/landing/travel/js/google-map.js"></script>
        <script src="/landing/travel/js/main.js"></script>

    </body>
</html>
