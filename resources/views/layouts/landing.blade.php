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

           
        /* Style untuk popup */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
                z-index: 2;

        }
        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .close-btn {
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
    </style>
</head>
<body>
    <div class="popup-overlay" id="popup" onclick="closePopup()">
        <div class="popup-content p-0">
           <img class="img-fluid" style="width:600px" src="https://arcturus.my.id/public/images/popup.png" alt="">
        </div>
    </div> 

    <script>
        // Tampilkan popup saat halaman dimuat
        window.onload = function() {
            showPopup();
        };

        // Fungsi untuk menampilkan popup
        function showPopup() {
            document.getElementById('popup').style.display = 'flex';
        }

        // Fungsi untuk menutup popup
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        
    </script>
	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar ftco-navbar-light" id="ftco-navbar">
		<div class="container">
			{{-- <a class="navbar-brand" href="index.html">Pacific<span>Travel Agency</span></a> --}}
            <a class="navbar-brand" href="/">
                {{-- @if(isset(Auth::user()->id) && Auth::user()->role_id == 2)
                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" style="width: 40px" src="{{ $vendor->logo_img ?? '/images/pms-sistem-1.png' }}" alt="Logo">
                @else
                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" style="width: 40px" src="{{ $settings->logo_image ?? '/images/pms-sistem-1.png' }}" alt="Logo">
                @endif --}}
                <img onerror="this.onerror=null; this.src='https://arcturus.my.id/logo/system/1695599539.png';" style="width: 40px" src="{{ $settings->logo_image }}" alt="{{ $settings->logo_image }}">
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
                        <li class="nav-item"><a href="{{route('login')}}" class="nav-link" @if(count($slider) == 0 && Auth::user()->role_id == 2) style="color: rgb(8, 8, 179)" @endif>dashboard</a></li>
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
        <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" src="https://www.transparentpng.com/thumb/whatsapp/GrwKkd-wp-logo-whatsapp-cut-out-png.png" alt="WhatsApp" width="60" height="60">
    </a>
    <footer class="ftco-footer bg-bottom ftco-no-pt pb-0" style="background-image: url(images/bg_3.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">

                    <p class="m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | 
                        <!--This is made with <i class="fa fa-heart" aria-hidden="true"></i> by -->
                        <a href="https://www.vibeshospitality.id/" target="_blank">ViBES Management</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                        <p style="font-size:13px">Jln. Raya Kuta-Mawun,Kuta Mandalika, Pujut,Lombok 83573</p>
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


<script>
    // Fungsi untuk menampilkan popup
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
</script>

<style>
    /* Style untuk popup */
    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: 999;
    }

    /* Style untuk overlay background */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
    }

    /* Style untuk gambar di dalam popup */
    .popup img {
        max-width: 100%;
        height: auto;
    }
</style>
    </body>
</html>
