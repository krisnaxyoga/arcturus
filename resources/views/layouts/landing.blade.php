<!DOCTYPE html>
<html lang="en">
<head>
	<title>Arcturus</title>
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
    
     <!-- Include jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <!-- Include Date Range Picker Plugin -->
     <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
     <!-- Include CSS styles for Date Range Picker -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    @if(isset(Auth::user()->id) && Auth::user()->role_id == 2)
    <link rel="icon" href="{{ $vendor->logo_img ?? '/images/pms-sistem-1.png' }}">
     @else
   <link rel="icon" href="{{ $settings->logo_image ?? '/images/pms-sistem-1.png' }}">
    @endif
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
       <style>
        /* Gaya untuk tombol WhatsApp */
        .whatsapp-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar ftco-navbar-light" id="ftco-navbar">
		<div class="container">
			{{-- <a class="navbar-brand" href="index.html">Pacific<span>Travel Agency</span></a> --}}
            <a class="navbar-brand" href="/">
                {{-- @if(isset(Auth::user()->id) && Auth::user()->role_id == 2)
                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" style="width: 40px" src="{{ $vendor->logo_img ?? '/images/pms-sistem-1.png' }}" alt="Logo">
                @else
                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" style="width: 40px" src="{{ $settings->logo_image ?? '/images/pms-sistem-1.png' }}" alt="Logo">
                @endif --}}
                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" style="width: 40px" src="{{ $settings->logo_image }}" alt="{{ $settings->logo_image }}">
            </a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="oi oi-menu"></span> Menu
			</button>

			<div class="collapse navbar-collapse" id="ftco-nav">
				<ul class="navbar-nav ml-auto">
					{{-- <li class="nav-item {{ request()->is('/') ? 'active' : '' }}"><a href="/" class="nav-link">Home</a></li> --}}
                    {{-- <li class="nav-item {{ request()->routeIs('about.homepage') ? 'active' : '' }}"><a href="{{route('about.homepage')}}" class="nav-link">About</a></li> --}}
                    {{-- <li class="nav-item {{ request()->is('destination') ? 'active' : '' }}"><a href="destination.html" class="nav-link">Destination</a></li> --}}
                    @if(isset(Auth::user()->id) && Auth::user()->role_id == 3)
                    <li class="nav-item {{ request()->routeIs('hotel.homepage') ? 'active' : '' }}"><a href="{{ route('hotel.homepage') }}" class="nav-link">Hotel</a></li>
                    {{-- <li class="nav-item {{ request()->is('blog') ? 'active' : '' }}"><a href="blog.html" class="nav-link">Blog</a></li> --}}
                    @endif
                    {{-- <li class="nav-item {{ request()->routeIs('contact.homepage') ? 'active' : '' }}"><a href="{{ route('contact.homepage') }}" class="nav-link">Contact</a></li> --}}
                    @if(isset(Auth::user()->id))
                        <li class="nav-item"><a href="{{route('login')}}" class="nav-link" @if(count($slider) == 0) style="color: rgb(8, 8, 179)" @endif>dashboard</a></li>
                    @else
                    <li class="nav-item"><a href="{{route('agentregist')}}" class="nav-link">Register Agent</a></li>
                    <li class="nav-item"><a href="{{route('vendorregist')}}" class="nav-link">Register Hotel</a></li>
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

    <a href="https://api.whatsapp.com/send?phone=6287888375939" target="_blank" class="whatsapp-button">
        <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" src="data:image/png;base64,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" alt="WhatsApp" width="60" height="60">
    </a>
    <footer class="ftco-footer bg-bottom ftco-no-pt" style="background-image: url(images/bg_3.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">

                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://turus.my.id" target="_blank">arcturus</a>
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

        <script>
            $('input[name="dates"]').daterangepicker();
        </script>
        <script>
         document.getElementById('whatsapp-button').addEventListener('click', function() {
            var phoneNumber = 'PHONE_NUMBER'; // Ganti dengan nomor WhatsApp yang sesuai
            var message = 'Halo! Saya ingin bertanya tentang...'; // Ganti dengan pesan yang sesuai
            var whatsappURL = 'https://api.whatsapp.com/send?phone=' + phoneNumber + '&text=' + encodeURIComponent(message);
            window.open(whatsappURL, '_blank');
        });

        </script>
    </body>
</html>
