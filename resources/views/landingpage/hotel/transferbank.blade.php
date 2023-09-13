@extends('layouts.landing')
@section('title', 'Hotel')
@section('contents')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<section class="hero-wrap hero-wrap-2" style="background-image: url('/landing/travel/images/bg_1.jpg'); height:300px">
    <div class="overlay" style="height: 300px"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center" style="height:300px">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="/">Home <i
                                class="fa fa-chevron-right"></i></a></span> <span>Transfer bank<i
                            class="fa fa-chevron-right"></i></span></p>
                <h1 class="mb-0 bread">Hotel</h1>
            </div>
        </div>
    </div>
</section>


    <section class="ftco-section">
      <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2>BCA</h2>
                        <h3>PT Surya Langit Biru</h3>
                        <div class="d-flex">
                             <h3 class="badge badge-success text-center m-0" style="font-size: 30px;" id="textToCopy">2027999995</h3>
                             <button class="copy-button mx-2 btn btn-success" onclick="copyToClipboard()"><i class="fa fa-copy"></i></button>
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('payment.upbanktransfer')}}"method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                                <label for="image">Upload bank transfer</label>
                                <input id="image-input" type="file" name="image" class="form-control">
                                @error('image')
                                <p style="font-weight: 700; font-size: 13px;" class="text-danger">{{ $message }}</p>
                                @enderror
                                <p class="text-danger" style="font-weight: 700; font-size: 13px;">The image must be in PNG, JPG, or JPEG format, The image size cannot exceed 2MB.</p>
                                <img id="image-preview" class="mt-3" style="width: 200px" src="#" alt="Preview">
                            </div>
                            <input type="hidden" value="{{$booking}}" name="idbooking">
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>

    </section>
    <script>
        document.getElementById('image-input').addEventListener('change', function() {
            const fileInput = this;
            const imagePreview = document.getElementById('image-preview');
    
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const validExtensions = ['jpg', 'jpeg', 'png'];
                const maxFileSize = 2 * 1024 * 1024; // 2MB
    
                const extension = file.name.split('.').pop().toLowerCase();
                const fileSize = file.size;
    
                if (validExtensions.indexOf(extension) === -1) {
                    alert('The image must be in PNG, JPG, or JPEG format.');
                    fileInput.value = ''; // Clear the input
                    imagePreview.src = ''; // Clear the preview
                } else if (fileSize > maxFileSize) {
                    alert('The image size cannot exceed 2MB.');
                    fileInput.value = ''; // Clear the input
                    imagePreview.src = ''; // Clear the preview
                } else {
                    // Valid file, display the preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
    <script>
        function copyToClipboard() {
            const textToCopy = document.getElementById('textToCopy');
            const textArea = document.createElement('textarea');
            textArea.value = textToCopy.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Text copied to clipboard');
        }
    </script>
    <script>
        $(document).ready(function() {
          // Mengaktifkan event change pada input file
          $('#image-input').change(function() {
            // Mengecek apakah ada file yang dipilih
            if (this.files && this.files[0]) {
              var reader = new FileReader();
        
              reader.onload = function(e) {
                // Menampilkan pratinjau gambar pada elemen img
                $('#image-preview').attr('src', e.target.result);
              }
        
              reader.readAsDataURL(this.files[0]);
            }
          });
        });
        </script>
@endsection
