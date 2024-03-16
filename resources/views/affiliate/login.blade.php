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
    <style>
        #intro {
          background-image: url(https://mdbootstrap.com/img/new/fluid/city/008.jpg);
          height: 114vh;
        }

        /* Height for devices larger than 576px */
        @media (min-width: 992px) {
          #intro {
            margin-top: -58.59px;
          }
        }

        .navbar .nav-link {
          color: #fff !important;
        }
      </style>
</head>

<body>

    <!-- Background image -->
  <div id="intro" class="bg-image shadow-2-strong">
    <div class="mask d-flex align-items-center h-100" style="background-color: rgba(0, 0, 0, 0.8);">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-md-8">
            <form class="bg-white rounded shadow-5-strong p-5" action="{{route('auth.affiliator.dologin')}}" method="POST">
                @csrf
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
              <!-- Email input -->
              <h1 class="text-center">
                Login
              </h1>
              <div class="form-outline mb-4" data-mdb-input-init>
                <input type="email" id="form1Example1" class="form-control" name="email"/>
                <label class="form-label" for="form1Example1">Email address</label>
              </div>

              <!-- Password input -->
              <div class="form-outline mb-4" data-mdb-input-init>
                <input type="password" id="form1Example2" class="form-control" name="password"/>
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="form2Example28">Password</label>
                  <p class="small"><a class="text-muted" href="{{route('auth.affiliator.forgotpassword')}}">Forgot password?</a></p>
              </div>
              </div>
              <!-- 2 column grid layout for inline styling -->


              <!-- Submit button -->
              <button type="submit" class="btn btn-primary btn-block" data-mdb-ripple-init>Sign in</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
