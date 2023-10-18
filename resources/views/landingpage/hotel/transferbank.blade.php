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
            @php
                $saldo = Auth::user()->saldo;
            @endphp
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div>
                                <p class="text-dark" style="font-weight: 700;font-size: 20px;">Total Payment : Rp. {{number_format($total_as_saldo, 0, ',', '.')}}</p>
                            </div>
                            <div class="card border-0">
                                <div class="card-body p-1">
                                     <div class="d-flex justify-content-between">
                                        <span><p class="m-0 text-info" style="font-weight: 700;font-size: 18px; @if($saldo <= $total_as_saldo) color: #cdcdcd !important; @endif">Saldo : Rp. {{number_format($saldo, 0, ',', '.')}}</p></span>
                                        <span>
                                            @if($saldo <= $total_as_saldo)
                                            <a href="{{route('agent.wallet')}}" class="btn btn-primary">top up</a>
                                            @else  
                                            <a href="{{route('agent.wallet.pay',$booking)}}" class="btn btn-primary">pay</a>
                                            @endif
                                          
                                        </span>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex">
                                <span class="mx-4">
                                    <img src="https://www.bca.co.id/-/media/Feature/Card/List-Card/Tentang-BCA/Brand-Assets/Logo-BCA/Logo-BCA_Biru.png"
                                        style="width:100px" alt="">
                                </span>
                                <span>
                                    <p style="font-weight: 700; color:#000000">BANK BCA (manually checked)</p>
                                    <p>PT Surya Langit Biru</p>
                                    <div class="d-flex">
                                        <p class="badge badge-success text-center m-0" style="font-size: 30px;"
                                            id="textToCopy">2027999995</p>
                                        <button class="copy-button mx-2 btn btn-success" onclick="copyToClipboard()"><i
                                                class="fa fa-copy"></i></button>
                                    </div>
                                </span>

                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <a class="text-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <p style="font-weight: 700; font-size:18px">Manual Transfer to BCA</p>
                            </a>
                            <div class="collapse show" id="collapseExample">
                                <p>Here are the steps on how to manually transfer money to a BCA bank account with the account
                                    number <b>2027999995</b> :</p>
    
                             
    
                                <p>
    
                                <ul>
                                    <li>
                                        <p>use ATM/e-Banking/mBanking/cash deposit to transfer to ARCTURUS account</p>
                                    </li>
                                    
                                   
                                </ul>
    
                                <p>After receiving the transfer receipt, you can follow these steps to upload the transfer
                                    receipt to the Arcturus system:</p>
    
                                <ol>
                                    <li>Take a clear photo of the transfer receipt.</li>
                                    <li>Upload the photo of the transfer receipt to the Arcturus system through the Arcturus
                                        website or app.</li>
                                    <li>Wait for verification from the Arcturus admin for 1 x 24 hours or contact WA admin.</li>
                                </ol>
    
                                <p>The Arcturus admin will verify your transfer receipt for 1 x 24 hours.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('payment.upbanktransfer') }}"method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <div class="mb-3">
                                    <label for="image">Upload bank transfer</label>
                                    <input id="image-input" type="file" name="image" class="form-control">
                                    @error('image')
                                        <p style="font-weight: 700; font-size: 13px;" class="text-danger">{{ $message }}
                                        </p>
                                    @enderror
                                    <p class="text-danger" style="font-weight: 700; font-size: 13px;">The image must be in
                                        PNG, JPG, or JPEG format, The image size cannot exceed 2MB.</p>
                                    <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';"
                                        id="image-preview" class="mt-3" style="width: 200px" src="#"
                                        alt="Preview">
                                </div>
                                <input type="hidden" value="{{ $booking }}" name="idbooking">
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
                    alert('The image size cannot exceed 2MB, otherwise contact admin');
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
