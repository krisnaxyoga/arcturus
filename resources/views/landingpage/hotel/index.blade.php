@extends('layouts.landing')
@section('title', 'Hotel')
@section('contents')
<style>
    .slidecontainer {
      width: 100%;
    }
    
    .slider {
      -webkit-appearance: none;
      width: 100%;
      height: 25px;
      background: #d3d3d3;
      outline: none;
      opacity: 0.7;
      -webkit-transition: .2s;
      transition: opacity .2s;
      border-radius: 17px;
    }
    
    .slider:hover {
      opacity: 1;
    }
    
    .slider::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 25px;
      height: 25px;
      background:  #f15d30;
      cursor: pointer;
      border-radius: 17px;
    }
    
    .slider::-moz-range-thumb {
      width: 25px;
      height: 25px;
      background:  #f15d30;
      cursor: pointer;
      border-radius: 17px;
    }
    </style>

<section class="hero-wrap hero-wrap-2" style="background-image: url('/landing/travel/images/bg_1.jpg'); height:300px">
    <div class="overlay" style="height: 300px"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center" style="height:300px">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="/">Home <i
                                class="fa fa-chevron-right"></i></a></span> <span>Hotel <i
                            class="fa fa-chevron-right"></i></span></p>
                <h1 class="mb-0 bread">Hotel</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section ftco-no-pb pt-0" style="bottom: 2rem;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-wrap-1 ftco-animate">
                    <form action="{{ route('hotel.homepage') }}" method="get"
                    class="search-property-1">
                    @csrf
                    <div class="g-field-search">
                        <div class="row">
                            <div class="col-md d-flex">
                                <div class="form-group border-0 mb-3 mt-2 mx-2">
                                    <label class="pl-3 mt-3" for="">country</label>
                                    <select name="country" id=""
                                    class="form-control ">
                                    <option value="">{{ __('-- Select --') }}
                                    </option>
                                    @foreach (get_country_lists() as $id => $name)
                                        <option @if (($requestdata['country'] ?? '') == $name) selected @endif value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group mb-3 mt-2 mx-2">
                                    <label class="pl-3 mt-3" for="#">State</label>
                                    <div class="form-field">
                                        {{-- <div class="icon"><span class="fa fa-search"></span></div> --}}
                                        <input value="{{ $requestdata['state'] }}" type="text" name="state" class="form-control" placeholder="state...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group mb-3 mt-2 mx-2">
                                    <label class="pl-3 mt-3" for="#">Check-in</label>
                                    <div class="form-field">

                                        <input value="{{ $requestdata['checkin'] }}" type="date" name="checkin"
                                            class="form-control checkindate"
                                            placeholder="Check In Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group mb-3 mt-2 mx-2">
                                    <label class="pl-3 mt-3" for="#">Check-out</label>
                                    <div class="form-field">

                                        <input value="{{ $requestdata['checkout'] }}" type="date" name="checkout"
                                            class="form-control checkoutdate"
                                            placeholder="Check Out Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group mb-3 mt-2 mx-2">
                                    <label class="pl-3 mt-3" for="#">Person</label>
                                    <select name="person" id="" class="form-control">
                                        <option  @if (($requestdata['person'] ?? '') == 1) selected @endif value="1">1</option>
                                        <option @if (($requestdata['person'] ?? '') == 2) selected @endif value="2">2</option>
                                        <option @if (($requestdata['person'] ?? '') == 3) selected @endif value="3">3</option>
                                        <option @if (($requestdata['person'] ?? '') == 4) selected @endif value="4">4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                {{-- <div class="form-group border-0">
                                    <div style="height: 7rem" class="form-field align-items-center">
                                        <input type="submit" value="Search"
                                        class="form-control btn btn-primary">
                                    </div>
                                </div> --}}
                                <button class="btn btn-primary w-100 rounded-0" type="submit">
                                    search
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
                    
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="ftco-section pt-2">
        @if ($requestdata['country'] === null && $requestdata['state'] === null && $requestdata['person'] === null && $requestdata['checkin'] === null && $requestdata['checkout'] === null)

        <div>

        </div>
        @else
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="m-0" style="color: #1a2b48;
                            font-size: 20px;
                            font-weight: 500;
                        ">Filter By</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <a class="text-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                Filter Price
                              </a>
                              <div class="collapse show" id="collapseExample">
                                <div>
                                    <form method="post" action="/action_page_post.php">
                                        <div data-role="rangeslider">
                                          <label for="price-min">Range:</label>
                                          <input type="range" min="1000000" max="10000000" value="5000000" class="slider" id="myRange">
                                          <p>Value: <span id="demo"></span></p>
                                        </div>
                                          <input class="btn btn-secondary" type="submit" data-inline="true" value="Submit">
                                         </form>

                                </div>
                              </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <a class="text-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                Property type
                              </a>
                              <div class="collapse show" id="collapseExample">
                                <div>
                                    <form action="/action_page.php">
                                        <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                                        <label for="vehicle1"> Hotels</label><br>
                                        <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
                                        <label for="vehicle2"> Villas</label><br>
                                        <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
                                        <label for="vehicle2"> Apartements</label><br>
                                        <input type="checkbox" id="vehicle3" name="vehicle3" value="Boat">
                                        <label for="vehicle3"> Resorts</label><br><br>
                                        <input class="btn btn-secondary" type="submit" value="Submit">
                                      </form>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-between">
                            <span class="price" style="color: #1a2b48;
                                        font-size: 20px;
                                        font-weight: 500;
                                    ">
                            {{ $data->count() }} Hotel Founds
                            </span>
                            <span>
                                <form action="">
                                    <p class="d-flex">
                                        <span>Show on the map | Sort by:</span>
                                        <select type="text" class="form-control">
                                            <option value="recomended">recomended</option>
                                            <option value="recomended">Price (Low to Hight)</option>
                                            <option value="recomended">Price (Hight to Low)</option>
                                        </select>
                                    </p>
                                </form>
                            </span>
                        </div>

                        @foreach ($data as $key=>$item)
                        <div class="col-md-12 ftco-animate">
                            <div class="">
                                <div class="item-loop-list">
                                    <div class="thumb-image">
                                        <a href="{{ route('hoteldetail.homepage', ['id' => $item->contract_id]) }}?{{ http_build_query($requestdata) }}">
                                            <img src="{{$item->room->feature_image}}" class="img img-fluid rounded-start" alt="{{$item->room->feature_image}}">
                                        </a>
                                    </div>
                                    <div class="g-info">
                                        <p class="star mb-2">
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </p>
                                        <h3><a href="{{ route('hoteldetail.homepage', ['id' => $item->contract_id]) }}?{{ http_build_query($requestdata) }}">{{$item->contractrate->vendors->vendor_name}}</a></h3>

                                        <p class="m-0"><i class="fa fa-trophy" aria-hidden="true"></i> Benefits :
                                            @if (strlen($item->contractrate->benefit_policy) > 40)
                                            {!! Str::limit($item->contractrate->benefit_policy, 40) !!}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="g-rate-price">
                                        <span class="price" style="color: #1a2b48;
                                        font-size: 18px;
                                        font-weight: 500;
                                    ">
                                        <span style="color: #5e6d77;
                                        font-size: 14px;
                                        font-weight: 400;">
                                            From
                                        </span> <br>
                                        Rp. {{ number_format(($item->recom_price + $item->contractrate->vendors->system_markup), 0, ',', '.')}}</span>

                                        <span style="color: #5e6d77;
                                        font-size: 10px;
                                        font-weight: 400;">
                                            /Night
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- JavaScript untuk mengontrol tampilan elemen tambahan -->
                     <!-- JavaScript untuk mengontrol tampilan elemen tambahan -->
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col text-center">
                    <div class="block-27">
                       {{$data->appends($requestdata)->links()}}
                    </div>
                </div>
            </div>
        </div>
        @endif

    </section>



    {{-- <section class="ftco-intro ftco-section ftco-no-pt">
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
    </section> --}}
        {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen input tanggal check-in dan check-out
            const checkinInput = document.querySelector('.checkindate');
            const checkoutInput = document.querySelector('.checkoutdate');

            // Buat objek tanggal untuk tanggal saat ini
            const currentDate = new Date();

            // Tambahkan 1 hari untuk mendapatkan tanggal check-in default (hari besok)
            currentDate.setDate(currentDate.getDate() + 1);

            // Konversi tanggal menjadi format ISO (yyyy-MM-dd) agar bisa diatur ke input tanggal
            const defaultCheckinDate = currentDate.toISOString().slice(0, 10);

            // Tambahkan 1 hari lagi untuk mendapatkan tanggal check-out default (1 malam setelah check-in)
            currentDate.setDate(currentDate.getDate() + 1);
            const defaultCheckoutDate = currentDate.toISOString().slice(0, 10);

            // Setel nilai default untuk input tanggal check-in dan check-out
            checkinInput.value = defaultCheckinDate;
            checkoutInput.value = defaultCheckoutDate;

            // Tambahkan event listener ke input tanggal check-in
            // Jika nilai check-in berubah, perbarui nilai default check-out menjadi 1 hari setelah check-in
            checkinInput.addEventListener('change', function() {
                const checkinDate = new Date(checkinInput.value);
                checkinDate.setDate(checkinDate.getDate() + 1);
                const checkoutDate = checkinDate.toISOString().slice(0, 10);
                checkoutInput.value = checkoutDate;


                checkoutInput.setAttribute('min', checkinInput.value);
            });

        });
    </script> --}}
    <!-- Tambahkan script Nouislider -->
<link href="https://cdn.jsdelivr.net/npm/nouislider@16.0.2/distribute/nouislider.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/nouislider@16.0.2/distribute/nouislider.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceRange = document.getElementById('priceRange');

        // Inisialisasi Nouislider
        const slider = noUiSlider.create(priceRange, {
            start: [100, 1000], // Nilai awal untuk range harga
            connect: true, // Menghubungkan slider
            range: {
                'min': 0,
                'max': 2000 // Nilai maksimum untuk range harga
            }
        });

        // Menangani perubahan pada slider
        slider.on('update', function(values, handle) {
            const minPrice = values[0];
            const maxPrice = values[1];

            // Tampilkan nilai harga pada elemen label atau input sesuai kebutuhan Anda
            // Contoh: document.getElementById('minPriceLabel').textContent = minPrice;
            // Contoh: document.getElementById('maxPriceLabel').textContent = maxPrice;
        });

        // Menangani perubahan pada form (submit, AJAX, dsb.)
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // Dapatkan nilai range harga yang dipilih
            const selectedRange = slider.get();

            // Kirim data range harga ke server atau lakukan yang sesuai dengan kebutuhan aplikasi Anda
            console.log('Selected Price Range:', selectedRange);
        });
    });
</script>
<script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    output.innerHTML = slider.value;
    
    slider.oninput = function() {
      output.innerHTML = this.value;
    }
    </script>
@endsection
