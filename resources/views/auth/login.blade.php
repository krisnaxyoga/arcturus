@extends('layouts.auth')

@section('contents')
<style>
    .bg-image-vertical {
    position: relative;
    overflow: hidden;
    background-repeat: no-repeat;
    background-position: right center;
    background-size: auto 100%;
    }

    .login-image {
    height: 100vh;
    object-fit: cover;
    object-position: left;
    }
</style>
<section class="vh-100">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 col-md-12  col-lg-6 text-black">
  
          <div class="px-5 ms-xl-4 mt-5 text-center">
            <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
            <img style="width: 40px" src="{{ $settings->logo_image ?? '/images/pms-sistem-1.png' }}" alt="Logo">
          </div>
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
          <div class="align-items-center px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
  
            <form method="post" action="/login">
                @csrf
  
              <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>
  
              <div class="form-outline mb-4">
                <input type="email" name="email" id="form2Example18" class="form-control form-control-lg @error('email') is-invalid @enderror" />
                <label class="form-label" for="form2Example18">Email address</label>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
              @enderror
              </div>
  
              <div class="form-outline mb-4">
                <input type="password" name="password" id="form2Example28" class="form-control form-control-lg @error('password') is-invalid @enderror" />
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="form2Example28">Password</label>
                    <p class="small"><a class="text-muted" href="{{route('forgetpassword.user')}}">Forgot password?</a></p>
                </div>
                
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
              </div>
  
              <div class="d-flex justify-content-between pt-1 mb-4">
                <button class="btn btn-info btn-lg btn-block" type="submit">Login</button>
                <a href="/" class="btn btn-danger btn-lg btn-block">back</a>
              </div>
  
              
              {{-- <p>Don't have an account? <a href="#!" class="link-info">Register here</a></p> --}}
  
            </form>
  
          </div>
  
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6 px-0 d-none d-sm-none d-lg-block d-xl-block d-md-none">
          <img src="https://w0.peakpx.com/wallpaper/269/841/HD-wallpaper-nusa-penida-nusa-penida-stock-nusa-penida-island.jpg"
            alt="Login image" class="w-100 login-image" style="object-fit: cover; object-position: left;">
        </div>
      </div>
    </div>
  </section>
@endsection
