@extends('affiliate.layout.app')
@section('title','Dashboard')
@section('content')
<section>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container py-5 h-100">
          <div class="row d-flex h-100">
            <div class="col-md-12 col-xl-4">
      
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
          </div>
        </div>
      </section>
</section>
@endsection
@push('script')
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