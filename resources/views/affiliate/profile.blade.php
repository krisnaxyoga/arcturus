@extends('affiliate.layout.app')
@section('title','Dashboard')
@section('content')
<section>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container py-5 h-100">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
          <div class="row d-flex h-100">
            <div class="col-md-12 col-xl-4 mb-3">

              <div class="card" style="border-radius: 15px;">
                <div class="card-body text-center">
                  <div class="mt-3 mb-4">
                    <img onerror="this.onerror=null; this.src='https://arcturus.my.id/logo/system/1695599539.png';" src="{{ $settings->logo_image }}" alt="{{ $settings->logo_image }}"
                      class="rounded-circle img-fluid" style="width: 100px;" />
                  </div>
                  <h4 class="mb-2">{{$user->name}}</h4>
                  <p class="text-muted mb-4">Email <span class="mx-2">|</span> <a
                      href="#!">{{$user->email}}</a></p>
                  <div>
                    <p>Password:
                        <span id="password" class="badge badge-primary">
                            <?php
                                // $be_code adalah variabel yang berisi password
                                $be_code_hidden = str_repeat('*', strlen($be_code)); // Mengubah password menjadi tanda *
                                echo $be_code_hidden; // Menampilkan password tersembunyi
                            ?>
                        </span>
                        <button id="showPasswordBtn" onclick="togglePassword()" type="button" class="btn">
                            <i id="eyeIcon" class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </p>
                    <p>Affiliate Code : <span class="badge badge-primary">{{$user->code}}</span></p>
                    <input type="text" class="form-control mb-1" disabled id="link" value="{{route('vendorregist.affiliate',['affiliate'=>$user->code])}}">
                    <button type="button" class="btn btn-secondary" onclick="myFunction()">
                        <i class="fa fa-copy"></i>
                    </button>
                  </div>
                  <div class="d-flex justify-content-center text-center mt-5 mb-2">

                    <div class="px-3">
                      <p class="mb-2 h5">{{$vendoraffiliate->count()}}</p>
                      <p class="text-muted mb-0">Hotel amounts</p>
                    </div>

                  </div>
                </div>
              </div>

            </div>
            <div class="col-xl-6">
              <div class="card">
                <div class="card-body">
                  <form action="{{ route('auth.affiliator.changepassword',['code'=>$code,'id'=>$id]) }}" method="post"enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <label for="" class="text-danger" style="font-weight: 700; font-size:13px">Change
                      password</label>

                     
                      <!-- Tambahkan icon eye untuk menampilkan/hide password -->
                      <div class="input-group mb-3">
                          <input type="password" name="Ldxgk4pAAAAAN1ktD9C8WWq2QSSXXv2x_PWQpR2" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2" id="password1">
                          <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2" onclick="togglePasswordVisibility('password1')">
                                  <i class="fa fa-eye" id="eyeIcon1"></i>
                              </span>
                          </div>
                      </div>

                      <div class="input-group mb-3">
                          <input type="password" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="basic-addon2" id="password2">
                          <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2" onclick="togglePasswordVisibility('password2')">
                                  <i class="fa fa-eye" id="eyeIcon2"></i>
                              </span>
                          </div>
                      </div>
                      <!-- Tambahkan div untuk menampilkan pesan kesalahan -->
                      <div id="passwordError" class="text-danger"></div>
                       <!-- Pastikan untuk mengganti "your-site-key" dengan Site Key yang Anda dapatkan -->
                       <script src="https://www.google.com/recaptcha/api.js?render=6Ldxgk4pAAAAAN1ktD9C8WWq2QSSXXv2x_PWQpR2"></script>
                       <div class="g-recaptcha" data-sitekey="your-site-key"></div>
 
                    <button class="btn btn-primary" type="submit" onclick="return validatePassword()">save</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</section>
@endsection
@push('script')
<script>
  function togglePasswordVisibility(passwordId) {
      var passwordInput = document.getElementById(passwordId);
      var eyeIcon = document.getElementById('eyeIcon' + passwordId.slice(-1));

      if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          eyeIcon.className = 'fa fa-eye-slash';
      } else {
          passwordInput.type = 'password';
          eyeIcon.className = 'fa fa-eye';
      }
  }

  function validatePassword() {
      var password1 = document.getElementById('password1').value;
      var password2 = document.getElementById('password2').value;
      var passwordError = document.getElementById('passwordError');

      if (password1 !== password2) {
          passwordError.innerHTML = 'Passwords do not match!';
          document.getElementById('password1').style.border = '1px solid red';
          document.getElementById('password2').style.border = '1px solid red';
          return false;
      } else {
          passwordError.innerHTML = '';
          document.getElementById('password1').style.border = '';
          document.getElementById('password2').style.border = '';
          return true;
      }
  }
</script>
<script>
function myFunction() {
    /* Get the text field */
    var copyText = document.getElementById("link");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);

    /* Alert the copied text */
    alert("Copied the text: " + copyText.value);
}

function togglePassword() {
    var passwordField = document.getElementById("password");
    var eyeIcon = document.getElementById("eyeIcon");

    if (passwordField.innerHTML.includes("*")) {
        passwordField.innerHTML = "<?php echo $be_code ?>"; // Menampilkan password sebenarnya
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.innerHTML = "<?php echo $be_code_hidden ?>"; // Menampilkan password tersembunyi
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}

</script>
@endpush
