@extends('layouts.landing')
@section('title', 'Hotel')
@section('contents')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <section class="hero-wrap hero-wrap-2" style="background-image: url('/landing/travel/images/bg_1.jpg'); height:300px">
        <div class="container">
            <div class="row no-gutters slider-text align-items-end justify-content-center" style="height:300px">
                <div class="col-md-9 ftco-animate pb-5 text-center">
                    <p class="breadcrumbs"><span class="mr-2"><a href="/">Home <i
                                    class="fa fa-chevron-right"></i></a></span> <span><a href="{{ route('hotel.homepage') }}">Hotel <i
                                class="fa fa-chevron-right"></i></a></span></p>
                    <h1 class="mb-0 bread">{{$vendordetail->vendor_name}}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row">
                {{-- <div class="col-lg-3">
                    <div class="card ftco-animate mb-3">
                        <div class="card-body">
                            <form action="">
                                <div class="mb-3">
                                    <label for="">select room rate</label>
                                    <select name="" class="category form-control" id="">
                                        @foreach ($roomtype as $item)
                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            <span id="load"></span>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-12">
                    <form id="bookingForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="text-center">AVAILABLE ROOM</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="search-property-1">
                            <div class="row no-gutters">
                                <div class="col-md d-flex">
                                    <div class="form-group p-4">
                                        <label for="#">Check-in</label>
                                        <div class="form-field">
                                            <input type="date" name="checkin" class="form-control checkindate" placeholder="Check In Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md d-flex">
                                    <div class="form-group p-4">
                                        <label for="#">Check-out</label>
                                        <div class="form-field">
                                            <input type="date" name="checkout" class="form-control checkoutdate" placeholder="Check Out Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md d-flex">
                                    <div class="form-group p-4" style="    border-right: 1px solid rgba(0, 0, 0, 0.1);">
                                        <label for="#">Person</label>
                                        <div class="form-field">
                                            <div class="select-wrap">
                                                <div class="icon"><span class="fa fa-chevron-down"></span></div>
                                                <select name="person" id="person" class="form-control" required>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md d-flex">
                                    <div class="form-group d-flex w-100 border-0">
                                        <div class="form-field w-100 align-items-center d-flex">
                                            <a href="#" style="padding: 2rem;" class="align-self-stretch form-control btn btn-primary">Check Availability</a>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <div class="row">
                            @foreach ($data as $item)
                            <div class="col-md-12 ftco-animate">
                                <div class="card mb-3">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="{{$item->room->feature_image}}" class="img img-fluid rounded-start" alt="{{$item->room->feature_image}}">
                                           
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h3 class="card-title"><a href="#">{{$item->room->title}}</a></h3>
                                                <span class="price">Rp. {{ number_format($item->price, 0, ',', '.')}}/{{$item->contractrate->min_stay}} night</span>
                                                <p class="card-text"><small class="text-body-secondary"></small></p>
                                                <select class="form-control room-quantity" name="room_quantity" style="width:200px" onchange="calculateTotal()">
                                                    <option data-price="0" value="0">0</option>
                                                    @for ($i = 1; $i <= $item->room->room_allow; $i++)
                                                        <option data-contprice={{$item->id}} data-contractid={{$item->contract_id}} data-roomid={{$item->room->id}} data-price="{{($i * $item->price) }}" value="{{$i}}">{{$i}} room (Rp. {{ number_format(($i * $item->price), 0, ',', '.')}})</option>
                                                    @endfor
                                                </select>
                                                <p>Facilities :  @foreach ($item->room->attribute as $facilities)
                                                    {{ $facilities }},
                                                    @endforeach</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p>Total Room: <span id="totalRoom">0</span></p>
                                                        <input type="text" name="totalroom" value="" hidden>
                                                        <input type="text" name="vendorid" value="{{$data[0]->contractrate->vendor_id}}" hidden>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-right" style="border-left: 1px solid #ccc;">
                                                <p>Total Price: <span class="text-danger fs-3 fw-bold">Rp.<span id="totalPrice">0</span></span> </p>
                                                <input type="text" name="totalprice" value="" hidden>
                                                <a id="booking" class="btn btn-primary" href="#">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>



    <section class="ftco-intro ftco-section ftco-no-pt">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <div class="img" style="background-image: url(/landing/travel/images/bg_2.jpg);">
                        <div class="overlay"></div>
                        <h2>We Are Pacific A Travel Agency</h2>
                        <p>We can manage your dream building A small river named Duden flows by their place</p>
                        <p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Ask For A Quote</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script> --}}

    <script>
        //vanilla javascript

        // Fungsi enkripsi AES
        function encryptData(data, key) {
            var encryptedData = CryptoJS.AES.encrypt(JSON.stringify(data), key).toString();
            return encryptedData;
        }

        // Fungsi dekripsi AES
        function decryptData(encryptedData, key) {
            var decryptedBytes = CryptoJS.AES.decrypt(encryptedData, key);
            var decryptedData = JSON.parse(decryptedBytes.toString(CryptoJS.enc.Utf8));
            return decryptedData;
        }

        // Contoh penggunaan untuk menyimpan data terenkripsi di localStorage
        function saveEncryptedDataToLocalStorage(data, key) {
            var encryptedData = encryptData(data, key);
            localStorage.setItem('encryptedData', encryptedData);
        }

        // Contoh penggunaan untuk mengambil dan mendekripsi data dari localStorage
        function getDecryptedDataFromLocalStorage(key) {
            var encryptedData = localStorage.getItem('encryptedData');
            var decryptedData = decryptData(encryptedData, key);
            return decryptedData;
        }

        function calculateTotal() {
            var roomQuantities = document.getElementsByClassName('room-quantity');
            var totalRoomElement = document.getElementById('totalRoom');
            var totalPriceElement = document.getElementById('totalPrice');
            var totalRoom = 0;
            var totalPrice = 0;

            // Mengambil data yang dipilih dan menyimpannya ke dalam array
            var selectedItems = [];

            for (var i = 0; i < roomQuantities.length; i++) {
                var quantity = parseInt(roomQuantities[i].value);
                var price = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.price);
                var roomId = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.roomid);
                var contractId = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.contractid);
                var contpricetId = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.contprice);

                totalRoom += quantity;
                totalPrice += price;

                if (quantity > 0) {
                    selectedItems.push({
                        roomId: roomId,
                        price: price,
                        quantity: quantity,
                        contractid: contractId,
                        contpriceid: contpricetId
                    });
                }
            }

            // Menyimpan data yang dipilih ke localStorage
            // localStorage.setItem('selectedItems', JSON.stringify(selectedItems));
                // Menyimpan keranjang yang telah diperbarui di localStorage
            var encryptionKey = 'KunciEnkripsiRahasia';
            saveEncryptedDataToLocalStorage(selectedItems, encryptionKey);

            // Mengambil dan mendekripsi data dari localStorage
            var decryptedData = getDecryptedDataFromLocalStorage(encryptionKey);
            console.log(decryptedData);

            totalRoomElement.textContent = totalRoom;
            totalPriceElement.textContent = totalPrice.toLocaleString();
            var totalRoomInput = document.querySelector('input[name="totalroom"]');
            totalRoomInput.value = totalRoom;
            var totalPriceInput = document.querySelector('input[name="totalprice"]');
            totalPriceInput.value = totalPrice.toLocaleString();
        }
        
        
        //function untuk tanggal checkin

        function setCheckInDateRestriction() {
            var currentDate = new Date();

            // Format tanggal sebagai "YYYY-MM-DD"
            var year = currentDate.getFullYear();
            var month = String(currentDate.getMonth() + 1).padStart(2, '0');
            var day = String(currentDate.getDate()).padStart(2, '0');
            var formattedDate = year + '-' + month + '-' + day;

            var checkinInput = document.querySelector('.checkindate');
            checkinInput.value = formattedDate;
            checkinInput.min = formattedDate;

            var checkoutInput = document.querySelector('.checkoutdate');

            checkinInput.addEventListener('input', function() {
                var checkinDate = new Date(this.value);
                var checkoutDate = new Date(checkoutInput.value);

                if (checkinDate > checkoutDate) {
                    checkoutInput.value = '';
                }

                checkoutInput.min = this.value;
                highlightDateRange();
            });

            checkoutInput.addEventListener('input', function() {
                var checkinDate = new Date(checkinInput.value);
                var checkoutDate = new Date(this.value);

                if (checkinDate > checkoutDate) {
                    checkoutInput.value = checkinInput.value;
                }

                highlightDateRange();
            });
            }

            function highlightDateRange() {
            var checkinInput = document.querySelector('.checkindate');
            var checkoutInput = document.querySelector('.checkoutdate');
            var checkinDate = new Date(checkinInput.value);
            var checkoutDate = new Date(checkoutInput.value);

            var inputs = document.querySelectorAll('input[type="date"]');
            inputs.forEach(function(input) {
                input.classList.remove('highlight');
            });

            if (checkinDate && checkoutDate && checkoutDate >= checkinDate) {
                var currentDate = new Date(checkinDate);
                while (currentDate <= checkoutDate) {
                    var dateString = currentDate.toISOString().split('T')[0];
                    var input = document.querySelector('input[value="' + dateString + '"]');
                    if (input) {
                        input.classList.add('highlight');
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            }
            }

            // Panggil fungsi setCheckInDateRestriction saat halaman dimuat
            window.addEventListener('DOMContentLoaded', setCheckInDateRestriction);

            //==================================================
            //CODE JQUERY
            //==================================================

            // Ketika halaman direload, ambil data dari local storage dan tampilkan ke input

            $(document).ready(function() {
                // Cek apakah ada data di local storage
               
                // Ambil data terenkripsi dari local storage
                var encryptionKey = 'KunciEnkripsiRahasia';
                var decryptedData = getDecryptedDataFromLocalStorage(encryptionKey);
                console.log(decryptedData,">>>>>>>>decriptdata");
                // Tampilkan data ke dalam input elemen
                $("#checkin").val(decryptedData.checkin);
                $("#checkout").val(decryptedData.checkout);
                $('input[name="totalroom"]').val(decryptedData.totalroom);
                $("#totalprice").val(decryptedData.totalprice);
                $('#totalroom').val(decryptedData.totalroom);
                $('input[name="totalprice"]').val(decryptedData.totalprice);
                $("#person").val(decryptedData.person);
                
            });

            $('.category').change(function() {
            var nilaiInput = $(this).val();
            console.log(nilaiInput,">>>>>>nilai select");

            // Tampilkan loading
            $('#load').append('<div id="loading" class="d-flex"><div class="loader mx-2"></div><p>Loading...</p></div>');

            if(nilaiInput == 0){
                window.location.reload();
            }else{
                $.ajax({
                url: "{{route('hoteldetail.homepage',$data[0]->contract_id)}}",
                method: 'GET',
                data: { data:{category:nilaiInput} },
                success: function(response) {
                    $('#loading').remove();
                    $('body').html(response);
                },
                error: function(error) {
                    $('#loading').remove();
                    console.log('Errorr.');
                    console.log(error);
                }
                });
            }
        });

        $('#booking').off('click').on('click', function() {
            var totalRoom = $('input[name="totalroom"]').val();
            var totalPrice = $('input[name="totalprice"]').val();
            var checkIn = $('input[name="checkin"]').val();
            var checkOut = $('input[name="checkout"]').val();
            var vendorid = $('input[name="vendorid"]').val();
            var person = $('#person').val();
            var encryptionKey = 'KunciEnkripsiRahasia';
            var decryptedData = getDecryptedDataFromLocalStorage(encryptionKey);

            // Mengirim data ke server menggunakan AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('booking.agent.create') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    room: JSON.stringify(decryptedData),
                    checkin: checkIn,
                    checkout: checkOut,
                    totalroom: totalRoom,
                    totalprice: totalPrice,
                    person: person,
                    vendorid: vendorid
                },
                success: function(response) {
                    console.log('Data keranjang berhasil disimpan ke database.');
                    console.log(response, "hasil");
                    var id = response[0];
                    localStorage.clear();
                    window.location.href = "{{ route('booking.agent.detail', ['id' => ':id']) }}".replace(':id', id);
                    // window.location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        // Pengguna tidak memiliki otorisasi (Unauthorized),
                        // arahkan ke halaman login atau halaman lain yang sesuai
                        localStorage.clear();
                        window.location.href = "{{ route('login.agent') }}";
                    } else {
                        console.log('Terjadi kesalahan saat menyimpan data keranjang.');
                        console.log(xhr);
                    }
                }
            });
        });
         //==================================================
        //CODE JQUERY
        //==================================================
    </script>

     <style>
        .loader {
          border: 5px solid #8bc1f3;
          border-radius: 50%;
          border-top: 5px solid #f78787;
          width: 20px;
          height: 20px;
          -webkit-animation: spin 2s linear infinite; /* Safari */
          animation: spin 2s linear infinite;
        }
        
        /* Safari */
        @-webkit-keyframes spin {
          0% { -webkit-transform: rotate(0deg); }
          100% { -webkit-transform: rotate(360deg); }
        }
        
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        </style>
@endsection
