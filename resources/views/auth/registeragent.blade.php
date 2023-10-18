@extends('layouts.auth')
@section('title', 'REGISTER AGENT')
@section('contents')

<!-- Section: Design Block -->
<section class="text-center text-lg-start">
    <style>
      .cascading-right {
        margin-right: -50px;
      }
  
      @media (max-width: 991.98px) {
        .cascading-right {
          margin-right: 0;
        }
      }
      .field-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
  
    <!-- Jumbotron -->
    <div class="container py-4">
      <div class="row g-0 align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="card cascading-right" style="
              background: hsla(0, 0%, 100%, 0.55);
              backdrop-filter: blur(30px);
              border-radius:1rem;
              ">
             
            <div class="card-body p-5 shadow-5 text-center">
                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" style="width: 40px" src="{{ $settings->logo_image ?? '/images/pms-sistem-1.png' }}" alt="Logo">
              <h2 class="fw-bold mb-5">Sign up for Agent</h2>
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
              <form method="post" id="registerform" action="{{ route('agentregist.store') }}">
                @csrf
                <!-- 2 column grid layout with text inputs for the first and last names -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-outline mb-4">
                            
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                id="name">
                            @error('first_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="name" class="form-label">First Name</label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                      
                        <input type="text" name="last_name"
                            class="form-control @error('last_name') is-invalid @enderror" id="name">
                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <label for="name" class="form-label">Last Name</label>
                    </div>
                </div>
                <div class="form-outline mb-4">
                    
                    <input type="text" name="busisnes_name"
                        class="form-control @error('busisnes_name') is-invalid @enderror" id="name">
                    @error('busisnes_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <label for="name" class="form-label">Agent Name</label>
                </div>
                {{-- <div class="form-outline mb-4">
                    <label for="name" class="form-label">Company Name</label>
                    <input type="text" name="company_name"
                        class="form-control @error('company_name') is-invalid @enderror" id="name">
                        @error('company_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                </div> --}}
                <div class="form-outline mb-4">
                    
                    <input type="text" name="address"
                        class="form-control @error('address') is-invalid @enderror" id="name">
                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <label for="name" class="form-label">Address</label>
                </div>
                <div class="form-outline mb-4">
                   
                    <input type="email" name="email"
                    inputmode="email"
                        class="form-control @error('email') is-invalid @enderror" id="email" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <label for="email" class="form-label">Email</label>
                </div>
                <div class="form-outline mb-4">
                    
                    <input type="text" name="phone"
                        class="form-control @error('phone') is-invalid @enderror" id="name" required>
                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <label for="name" class="form-label">Phone</label>
                </div>
                <div class="form-outline mb-4">
                    
                   
                    <select name="country" class="form-control" required>
                        <option value="">{{ __('-- Select --') }}</option>
                        @foreach (get_country_lists() as $id => $name)
                            <option @if (($user->country ?? '') == $id) selected @endif
                                value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex justify-content-between">
                        <label for="name" class="form-label">Country <span class="text-danger">*</span></label>
                        <p class="text-danger" style="font-size:11px">agent verifications</p>
                   </div>
                </div>
                <div class="row">
                   
                    <div class="col-lg-6">
                        <div class="form-outline mb-4">
                            
                            <input type="text" name="state"
                                class="form-control @error('state') is-invalid @enderror" id="name">
                            @error('state')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="name" class="form-label">State</label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                         <div class="form-outline mb-4">
                            
                            <input type="text" name="city"
                                class="form-control @error('city') is-invalid @enderror" id="name">
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="name" class="form-label">City</label>
                        </div>
                    </div>
                </div>
                <div class="form-outline mb-4">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                    <label class="form-label" for="password">Password</label>
                    <span toggle="#password" class="toggle-password field-icon"><i class="fa fa-fw fa-eye toggle-icon"></i></span>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
  
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4">
                  Sign up
                </button>
                <a href="/" class="btn btn-danger btn-block mb-4">back</a>
  
              </form>
            </div>
          </div>
        </div>
  
        <div class="col-lg-6 mb-5 mb-lg-0 d-none d-lg-block">
          <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" style="border-radius: 1rem;" src="https://w0.peakpx.com/wallpaper/269/841/HD-wallpaper-nusa-penida-nusa-penida-stock-nusa-penida-island.jpg" class="w-100 rounded-5 shadow-4"
            alt="" />
        </div>
      </div>
    </div>
    <!-- Jumbotron -->
  </section>
  <!-- Section: Design Block -->
  <script>
    const togglePassword = document.querySelectorAll('.toggle-password');

    togglePassword.forEach(icon => {
        icon.addEventListener('click', function () {
            const passwordField = document.querySelector(icon.getAttribute('toggle'));
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.querySelector('.toggle-icon').classList.remove('fa-eye');
                icon.querySelector('.toggle-icon').classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.querySelector('.toggle-icon').classList.remove('fa-eye-slash');
                icon.querySelector('.toggle-icon').classList.add('fa-eye');
            }
        });
    });
</script>

@endsection
