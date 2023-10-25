@extends('layouts.landing')
@section('title', 'Hotel')
@section('contents')
    @php
        use Carbon\Carbon;
    @endphp
    <!-- fotorama.css & fotorama.js. -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script> <!-- 16 KB -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> --}}
    {{-- <section class="hero-wrap hero-wrap-2" style="background-image: url('/landing/travel/images/bg_1.jpg'); height:300px">
        <div class="overlay" style="height: 300px"></div>
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
    </section> --}}

    <div class="hero-wrap" style="height:400px">
        <div id="demo" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($slider as $index => $item)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';"
                            src="{{ $item->image }}" alt="{{ $item->image }}"
                            style="width:100%;height:600px; object-fit: cover;">
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-3 border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-9">
                                    <p class="star mb-2">
                                        @for($i = 0; $i < $vendordetail->hotel_star; $i++)
                                            <span class="fa fa-star"></span>
                                        @endfor
                                    </p>
                                    <h1>{{ $vendordetail->vendor_name }}</h1>
                                    <p><i class="fa fa-map-marker"></i> {{ $vendordetail->country }}</p>


                                </div>
                                <div class="col-lg-3">
                                    <div class="card mb-3">
                                        <div class="card-body">

                                            <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';"
                                                class="img-fluid" style="max-width: 80px; max-height:80px;"
                                                src="{{ $vendordetail->logo_img ?? asset('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjCX5TOKkOk3MBt8V-f8PbmGrdLHCi4BoUOs_yuZ1pekOp8U_yWcf40t66JZ4_e_JYpRTOVCl0m8ozEpLrs9Ip2Cm7kQz4fUnUFh8Jcv8fMFfPbfbyWEEKne0S9e_U6fWEmcz0oihuJM6sP1cGFqdJZbLjaEQnGdgJvcxctqhMbNw632OKuAMBMwL86/s414/pp%20kosong%20wa%20default.jpg') }}"
                                                alt="Profile Image">

                                            <div>
                                                <p class="m-0" style="font-weight: 700;sont-size:14px">
                                                    {{ $vendordetail->vendor_name }}</p>
                                                {{-- <p>Member since <br>
                                                        {{ \Carbon\Carbon::parse($vendordetail->created_at)->format('F Y') }}
                                                    </p> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-lg-12">
                                    <h3>Description</h3>
                                    <p>
                                        {{ $vendordetail->description }}
                                    </p>
                                </div> --}}
                                <div class="col-lg-12">
                                    <h3>Highlights</h3>
                                    <p>
                                        {{ $vendordetail->highlight }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <h3> Available Rooms</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form id="bookingForm" enctype="multipart/form-data">
                        @csrf
                        <div class="search-property-1" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                            <div class="row no-gutters">
                                <div class="col-md d-flex">
                                    <?php
                                    $checkin = date('m/d/Y', strtotime($datareq['checkin']));
                                    $checkout = date('m/d/Y', strtotime($datareq['checkout']));
                                    ?>
                                    <div class="form-group p-4">
                                        <label for="">CheckIn - CheckOut</label>
                                        <input class="form-control checkindate" type="text" name="dates"
                                            value="{{ $checkin }} - {{ $checkout }}" />
                                    </div>
                                    <input value="{{ $datareq['checkin'] }}" id="checkin" type="hidden" name="checkin"
                                        class="form-control checkindate" placeholder="Check In Date">
                                    <input value="{{ $datareq['checkout'] }}" id="checkout" type="hidden" name="checkout"
                                        class="form-control checkoutdate" placeholder="Check Out Date">
                                </div>
                                {{-- <div class="col-md d-flex">
                                    <div class="form-group p-4">
                                        <label for="#">Check-in</label>
                                        <div class="date-input-wrapper">
                                            <input value="{{ $datareq['checkin'] }}" onchange="checknight()" type="date"
                                                id="checkin" name="checkin" class="form-control checkindate"
                                                placeholder="Check In Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md d-flex">
                                    <div class="form-group p-4">
                                        <label for="#">Check-out</label>
                                        <div class="date-input-wrapper">
                                            <input value="{{ $datareq['checkout'] }}" onchange="checknight()" id="checkout"
                                                type="date" name="checkout" class="form-control checkoutdate"
                                                placeholder="Check Out Date" required>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-md d-flex">
                                    <div class="form-group p-4" style="    border-right: 1px solid rgba(0, 0, 0, 0.1);">
                                        <label for="#">Person</label>
                                        <div class="">
                                            <select name="person" id="person" class="form-control" required>
                                                <option @if (($datareq['person'] ?? '') == 1) selected @endif value="1">1
                                                </option>
                                                <option @if (($datareq['person'] ?? '') == 2) selected @endif value="2">2
                                                </option>
                                                <option @if (($datareq['person'] ?? '') == 3) selected @endif value="3">3
                                                </option>
                                                <option @if (($datareq['person'] ?? '') == 4) selected @endif value="4">4
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            @foreach ($data as $keyup => $item)

                                <div class="col-md-12 ftco-animate">
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';"
                                                    src="{{ $item->room->feature_image }}"
                                                    class="img img-fluid rounded-start"
                                                    alt="{{ $item->room->feature_image }}">

                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body pb-3">
                                                    <h3 class="card-title mb-0">
                                                        <button
                                                            class="btn font-weight-bold font-weight-bold p-0 text-primary"
                                                            style="font-size: 20px" type="button" data-toggle="modal"
                                                            data-target="#exampleModalx1234{{ $keyup }}">{{ $item->room->ratedesc }}</button>
                                                    </h3>
                                                    <p>{!! $item->room->content !!}</p>
                                                    
                                                    @if ($contractprice->count() != 0)
                                                        <a class="text-primary" style="font-weight:500;font-size:14px"
                                                            data-toggle="collapse"
                                                            href="#collapseExample{{ $keyup }}" role="button"
                                                            aria-expanded="false" aria-controls="collapseExample">
                                                            View Rates:
                                                        </a>
                                                        <div class="collapse" id="collapseExample{{ $keyup }}">
                                                            <div>

                                                                @foreach ($contractprice as $itemprice)
                                                                    @if ($itemprice->room_id == $item->room->id)
                                                                            @php
                                                                            // ========================================================= START CALENDAR ====================================

                                                                            $status = 1;
                                                                            $room_allow = 0;
                                                                            $no_checkout = 0;
                                                                            $no_checkin = 0;
                                                                            $hotelroomid = 0;
                                                                            $TotalHotelCalendar = 0;
                                                                            $TotalHotelCalendar1 = 0;
                                                                            $checkinDate = Carbon::parse($checkin);
                                                                            $checkoutDate = Carbon::parse($checkout);
                                                                            $totalpricecalendar = 0;
                                                                            $foundZero = false;
                                                                            $totalNights = $checkinDate->diffInDays($checkoutDate);
                                                                            $recomprice = $itemprice->recom_price;

                                                                            $totalx = 0;
                                                                            $room_allowx = 0;
                                                                            $totalDataCount = 0;

                                                                            $lang_of_stay = 1;
                                                                            
                                                                            if ($HotelCalendar->count() != 0) {
                                                                                foreach ($HotelCalendar as $key => $calendar) {

                                                                                    // var_dump($calendar->room_hotel_id,$itemprice->room_id);
                                                                                    if ($calendar->room_hotel_id == $itemprice->room_id && $calendar->active == 1) {

                                                                                        $startDate = Carbon::parse($calendar->start_date);
                                                                                        $endDate = Carbon::parse($calendar->end_date);


                                                                                        if ($calendar->room_allow == 0 || $calendar->active == 0) {
                                                                                                $status = 0;
                                                                                                if($calendar->start_date == $calendar->end_date && $calendar->room_allow == 0 && $calendar->active == 0 && $endDate == $checkoutDate){
                                                                                                    $room_allow = $itemprice->room->room_allow;
                                                                                                }elseif ($checkoutDate == $calendar->start_date && $calendar->room_allow == 0) {
                                                                                                    // var_dump('signatur laggon = ');
                                                                                                    $room_allow = $itemprice->room->room_allow;
                                                                                                }else{
                                                                                                    $foundZero = true; // Setel foundZero menjadi true jika nilai 0 ditemukan
                                                                                                }
                                                                                            } else {
                                                                                                // Jika tidak, gunakan nilai room_allow dari $calendar
                                                                                                $room_allow = $calendar->room_allow;
                                                                                            }

                                                                                        // var_dump($checkoutDate);
                                                                                        // Check apakah rentang tanggal kalender tumpang tindih dengan rentang check-in dan check-out
                                                                                        if ($startDate < $checkoutDate && $endDate >= $checkinDate && $foundZero == false && $startDate != $endDate) {
                                                                                            // Hitung jumlah malam yang termasuk dalam rentang tanggal
                                                                                            if ($checkoutDate > $endDate) {
                                                                                                $nights = min($endDate, $checkoutDate)->diffInDays(max($startDate, $checkinDate)) + 1;
                                                                                                // var_dump('melebihi malam di kalender night = '.$nights.' cek ='.$HotelCalendar->count());
                                                                                            }else{
                                                                                                $nights = min($endDate, $checkoutDate)->diffInDays(max($startDate, $checkinDate));
                                                                                                // var_dump('kurang dari malam di kalender night = '.$nights);
                                                                                            }


                                                                                            $day = min($endDate, $checkoutDate)->diffInDays(max($startDate, $checkinDate)) + 1;
                                                                                            $gap = ($totalNights + 1) - $day;

                                                                                            if($itemprice->contractrate->rolerate == 2){
                                                                                               $TotalHotelCalendar1 += $calendar->recom_price * ((100 - $itemprice->contractrate->percentage)/100);
                                                                                            }else{
                                                                                               $TotalHotelCalendar1 += $calendar->recom_price;
                                                                                            }


                                                                                            // var_dump($itemprice->recom_price);

                                                                                            if ($nights != $totalNights) {
                                                                                                $countNights1 = $totalNights - $nights;
                                                                                                $totalpricecalendar = $TotalHotelCalendar1 / $countNights1;
                                                                                                if ($TotalHotelCalendar1 != $itemprice->recom_price) {
                                                                                                    if ($nights == 0) {
                                                                                                        $countNights = $totalNights - 1;
                                                                                                        $TotalHotelCalendar = (($TotalHotelCalendar1 * 1) + ($itemprice->recom_price * $countNights)) / $totalNights;
                                                                                                    } else {
                                                                                                        if($gap <= 1){
                                                                                                            $countNights = $totalNights - $nights;
                                                                                                            $TotalHotelCalendar = (($TotalHotelCalendar1 * $nights) + ($recomprice * $countNights)) / $totalNights;
                                                                                                            // var_dump('gap - price ='.$TotalHotelCalendar.' gap ='.$gap);
                                                                                                        }else{
                                                                                                            // var_dump($calendar->end_date);
                                                                                                            if($endDate == $checkoutDate){
                                                                                                                $countNights = $totalNights - $nights;
                                                                                                                $TotalHotelCalendar = (($TotalHotelCalendar1 * $nights) + ($itemprice->recom_price * $countNights)) / $totalNights;
                                                                                                                // var_dump('no gap enddate checkoutdate price  = '.$TotalHotelCalendar.'');
                                                                                                            }else{
                                                                                                                $countNights = $totalNights - $nights;
                                                                                                                $TotalHotelCalendar = (($TotalHotelCalendar1 * $nights) + ($itemprice->recom_price * $countNights)) / $totalNights;
                                                                                                                // var_dump('no gap hight price = '.$countNights.' cek = '.$TotalHotelCalendar);
                                                                                                            }
                                                                                                        }

                                                                                                    }
                                                                                                }
                                                                                            } else if ($nights == $totalNights) {
                                                                                                $TotalHotelCalendar = $calendar->recom_price;
                                                                                            }

                                                                                            $status = $calendar->active;
                                                                                            $room_allow = $calendar->room_allow;

                                                                                            // var_dump($room_allow);
                                                                                            if ($room_allow == 0) {
                                                                                                $status = 2;
                                                                                            }
                                                                                            $hotelroomid = $calendar->room_hotel_id;
                                                                                        }
                                                                                        else{
                                                                                            //filter yang berfungsi untuk calendar rate
                                                                                            if($startDate < $checkoutDate && $endDate >= $checkinDate && $startDate == $endDate)
                                                                                            {
                                                                                                $totalx += $calendar->recom_price;
                                                                                                $totalDataCount++;

                                                                                                $status = $calendar->active;
                                                                                                $room_allowx = $calendar->room_allow;
                                                                                                $lang_of_stay = $calendar->night;
                                                                                                // var_dump($room_allow);
                                                                                                if ($room_allowx == 0) {
                                                                                                    $status = 2;
                                                                                                }
                                                                                                $hotelroomid = $calendar->room_hotel_id;
                                                                                            }
                                                                                            //filter yang berfungsi untuk calendar rate
                                                                                        }

                                                                                        if ($checkoutDate == $endDate) {
                                                                                            $no_checkout = $calendar->no_checkout;
                                                                                        }

                                                                                        if ($checkinDate == $startDate) {
                                                                                            $no_checkin = $calendar->no_checkin;
                                                                                        }

                                                                                        if ($checkinDate == $startDate) {
                                                                                            $lang_of_stay = $calendar->night;
                                                                                        }
                                                                                    } else {
                                                                                        if ($calendar->room_hotel_id == $itemprice->room_id && $foundZero == false) {
                                                                                            $status = $calendar->active;

                                                                                            // Periksa apakah room_allow sama dengan 0 atau active sama dengan 0
                                                                                            if ($calendar->room_allow == 0 || $calendar->active == 0) {
                                                                                                $status = 0;
                                                                                                // var_dump('ini berfungsi = ');
                                                                                                $endDate1 = Carbon::parse($calendar->end_date);
                                                                                                if($calendar->start_date == $calendar->end_date && $calendar->room_allow == 0 && $calendar->active == 0 && $endDate1 == $checkoutDate){
                                                                                                    $room_allow = $itemprice->room->room_allow;
                                                                                                }elseif ($checkoutDate == $calendar->start_date && $calendar->room_allow == 0) {
                                                                                                    $room_allow = $itemprice->room->room_allow;

                                                                                                } else{
                                                                                                    $foundZero = true; // Setel foundZero menjadi true jika nilai 0 ditemukan
                                                                                                }

                                                                                            } else {
                                                                                                // Jika tidak, gunakan nilai room_allow dari $calendar
                                                                                                $room_allow = $calendar->room_allow;

                                                                                            }

                                                                                            $hotelroomid = $calendar->room_hotel_id;
                                                                                        }
                                                                                    }
                                                                                }
                                                                                //filter yang berfungsi untuk calendar rate
                                                                                if($totalx != 0){
                                                                                    $countNights = $totalNights - $totalDataCount;
                                                                                    $TotalHotelCalendar = ($totalx + ($itemprice->recom_price * $countNights)) / $totalNights;
                                                                                }


                                                                                if($room_allowx != 0){
                                                                                    $room_allow = $room_allowx;
                                                                                }
                                                                                //filter yang berfungsi untuk calendar rate

                                                                            }


                                                                            // $Room_recomprice = ($TotalHotelCalendar <= 0) ? $itemprice->recom_price : $TotalHotelCalendar;
                                                                            if ($TotalHotelCalendar <= 0) {
                                                                                $Room_recomprice = $itemprice->recom_price;
                                                                            } else {
                                                                                if ($itemprice->recom_price == $TotalHotelCalendar) {
                                                                                    if($itemprice->contractrate->rolerate == 2){
                                                                                        $Room_recomprice = $totalpricecalendar * ((100 - $itemprice->contractrate->percentage)/100);

                                                                                    }else{
                                                                                        if($totalpricecalendar == 0){
                                                                                            $Room_recomprice = $TotalHotelCalendar;
                                                                                        }else{
                                                                                            $Room_recomprice = $totalpricecalendar;
                                                                                        }
                                                                                    }

                                                                                    // $Room_recomprice = $totalpricecalendar;
                                                                                } else {
                                                                                    if($itemprice->contractrate->rolerate == 2){
                                                                                        $Room_recomprice = $TotalHotelCalendar * ((100 - $itemprice->contractrate->percentage)/100);
                                                                                    }else{
                                                                                        $Room_recomprice = $TotalHotelCalendar;

                                                                                    }
                                                                                    // $Room_recomprice = $TotalHotelCalendar;
                                                                                }
                                                                            }

                                                                            // var_dump('calender sama nilai dari rate ='.$Room_recomprice.' total calendar = '.$totalx.' cek night ='.$totalDataCount);
                                                                            // ========================================================= END CALENDAR ====================================

                                                                            // ========================================================= ROOM ALLOWMENT ====================================
                                                                            $totalRoomBooking = 0; // Inisialisasi totalRoomBooking

                                                                            // var_dump($no_checkout);
                                                                            if ($status != 1 || $no_checkout != 0 || $no_checkin != 0) {
                                                                                $totalRoomBooking = $itemprice->room->room_allow;
                                                                            }
                                                                            // if($no_checkout != 1) {
                                                                            //     $totalRoomBooking = $itemprice->room->room_allow;
                                                                            // }

                                                                            if ($HotelRoomBooking->count() != 0) {
                                                                                foreach ($HotelRoomBooking as $key => $value) {
                                                                                    if ($value->room_id == $itemprice->room_id) {
                                                                                        $totalRoomBooking = $value->total_room;
                                                                                    }

                                                                                }
                                                                            }
                                                                            // Setel $RoomAllowment ke $itemprice->room->room_allow dikurangi total total_room yang sesuai
                                                                            if($itemprice->room_id == $item->room->id){
                                                                                $RoomAllowment = $itemprice->room->room_allow - $totalRoomBooking;
                                                                            }
                                                                            // var_dump($RoomAllowment);
                                                                            if ($foundZero) {
                                                                                $room_allow = 0;
                                                                            }

                                                                            if($lang_of_stay > $totalNights){
                                                                                $room_allow = 0;
                                                                            }else if($lang_of_stay == 1){
                                                                                $room_allow = $RoomAllowment;
                                                                            }else{
                                                                                $room_allow = $room_allow;
                                                                            }

                                                                            // ========================================================= ROOM ALLOWMENT ====================================

                                                                        @endphp
                                                                        @php

                                                                            $price = $itemprice->recom_price;
                                                                            if ($Room_recomprice) {
                                                                                    $price = $Room_recomprice;
                                                                            }

                                                                            if ($advancepurchase->count() > 0) {
                                                                                foreach ($advancepurchase as $advancevalue) {
                                                                                    if ($advancevalue->contract_id == $itemprice->contract_id && $advancevalue->room_id == $itemprice->room_id && $advancevalue->room_id == $item->room->id) {
                                                                                        if ($Room_recomprice != $itemprice->recom_price) {
                                                                                            $price = ($advancevalue->price / $itemprice->recom_price) * $Room_recomprice;
                                                                                        } else {
                                                                                            $price = $advancevalue->price;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                // if ($itemprice->contractrate->rolerate == 1) {
                                                                                    $price = $Room_recomprice;
                                                                                // } else {
                                                                                //     $price = $itemprice->recom_price;
                                                                                // }
                                                                            }
                                                                        @endphp
                                                                        {{-- <hr>
                                                                <p style="font-size:20px;font-weight:700" class="m-0 p-0">{{$itemprice->contractrate->codedesc}}</p> --}}
                                                                        <hr>
                                                                        <div class="row justify-content-between">
                                                                            <div class="col-lg">
                                                                                <p>Min Stay :
                                                                                    {{ $itemprice->contractrate->min_stay }}
                                                                                    nights</p>
                                                                            </div>
                                                                            <div class="col-lg">
                                                                                @foreach ($itemprice->contractrate->distribute as $distribute)
                                                                                    <span
                                                                                        class="badge badge-secondary mx-1">{{ $distribute }}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                        <div class="row justify-content-between">
                                                                            <div class="col-lg">
                                                                                <span class="price text-primary"
                                                                                    style="font-weight: 600">Rp.
                                                                                    {{ number_format($price + $itemprice->contractrate->vendors->system_markup, 0, ',', '.') }}
                                                                                    / night
                                                                                </span>
                                                                                <p class="m-0">Benefits :</p>
                                                                                <p>{!! $itemprice->contractrate->benefit_policy !!}</p>
                                                                            </div>
                                                                            <div class="col-lg">
                                                                                @php
                                                                                    if ($room_allow >= 0 && $hotelroomid == $itemprice->room_id) {
                                                                                        if($RoomAllowment >= 0 && $room_allow != 0 && $RoomAllowment == $room_allow){
                                                                                            $RoomAllowment = $RoomAllowment;
                                                                                            
                                                                                        }else if($room_allow >= 0 && $room_allow != $RoomAllowment){
                                                                                           $RoomAllowment = $room_allow;
                                                                                        }

                                                                                    } else {
                                                                                        $RoomAllowment = $RoomAllowment;

                                                                                    }
                                                                                @endphp
                                                                                @if ($RoomAllowment <= 0)
                                                                                    <span
                                                                                        class="badge badge-danger">Sold</span>
                                                                                @else
                                                                                    @if ($Nights == $itemprice->contractrate->min_stay)
                                                                                        <select
                                                                                            class="form-control room-quantity"
                                                                                            name="room_quantity"
                                                                                            style="width:200px"
                                                                                            onchange="calculateTotal()">
                                                                                            <option data-price="0"
                                                                                                value="0"
                                                                                                data-pricenomarkup="0">
                                                                                                0</option>
                                                                                            @for ($i = 1; $i <= $RoomAllowment; $i++)
                                                                                                <option
                                                                                                    data-contprice={{ $itemprice->id }}
                                                                                                    data-contractid={{ $itemprice->contract_id }}
                                                                                                    data-roomid={{ $itemprice->room->id }}
                                                                                                    data-price="{{ $i * ($price + $itemprice->contractrate->vendors->system_markup) }}"
                                                                                                    data-pricenomarkup="{{ $i * $price }}"
                                                                                                    value="{{ $i }}">
                                                                                                    {{ $i }}
                                                                                                    @if ($i == 1)
                                                                                                        room
                                                                                                    @else
                                                                                                        rooms
                                                                                                    @endif
                                                                                                </option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal" id="exampleModalx1234{{ $keyup }}" tabindex="-1"
                                    aria-labelledby="exampleModalx1234Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold" id="exampleModalx1234Label">
                                                    {{ $item->room->ratedesc }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-12">
                                                        <div class="fotorama mb-3" data-nav="thumbs" data-loop="true">
                                                            @foreach ($item->room->gallery as $key => $gallery)
                                                                <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';"
                                                                    src="{{ $gallery }}" alt="{{ $gallery }}"
                                                                    style="width:100%">
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-12">
                                                        <p class="font-weight-bold m-0">Room Amenities</p>
                                                        @foreach ($item->room->attribute as $facilities)
                                                            <span class="badge badge-success mr-2"> {{ $facilities }}
                                                            </span>
                                                        @endforeach
                                                    </div>
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
                                                        <input type="text" name="vendorid"
                                                            value="{{ $data[0]->contractrate->vendor_id }}" hidden>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p>Total Night: <span id="totalNight">0</span></p>
                                                        <input type="text" name="totalnight" value="" hidden>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-right" style="border-left: 1px solid #ccc;">
                                                <p>Total Price: <span class="text-danger fs-3 fw-bold">Rp.<span
                                                            id="totalPrice">0</span></span> </p>
                                                <input type="text" name="totalprice" value="" hidden>
                                                <input type="text" name="totalpricenomarkup" value="" hidden>
                                                <span style="display: none" id="totalPricenomarkup"></span>
                                                <a id="booking" class="btn btn-primary" href="#">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row my-3">
                                <div class="col-lg-12">
                                    <h3>Rules</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <p style="font-size: 20px;
                                   margin-bottom: 5px;">
                                        Hotel Policies</p>
                                </div>
                                <div class="col-lg-8">

                                    <p
                                        style="font-size: 15px;
                                    font-weight: 700;
                                    margin-bottom: 5px;">
                                        Deposit</p>
                                    {!! $data[0]->contractrate->deposit_policy !!}
                                    <p
                                        style="font-size: 15px;
                                    font-weight: 700;
                                    margin-bottom: 5px;">
                                        Cancellation</p>
                                    {!! $data[0]->contractrate->cencellation_policy !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script> --}}

    <script>
        night()
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

        function checknight() {
            var checkinDate = new Date(document.getElementById('checkin').value);
            var checkoutDate = new Date(document.getElementById('checkout').value);

            var totalPriceElements = document.getElementById('totalPrice');
            var totalPricenomarkupElements = document.getElementById('totalPricenomarkup');

            var timeDiff = Math.abs(checkoutDate.getTime() - checkinDate.getTime());
            var permalam = Math.ceil(timeDiff / (1000 * 3600 * 24));
            if (isNaN(permalam) || permalam === null) {
                permalam = 0;
            }
            var totalNightElement = document.getElementById('totalNight');
            totalNightElement.textContent = permalam;

            var totalNightInput = document.querySelector('input[name="totalnight"]');
            totalNightInput.value = permalam.toLocaleString();

            var totalPrice = document.querySelector('input[name="totalprice"]');
            // console.log(totalPrice.value, ">>>total price value");
            var cleanedPrice = totalPrice.value.replace(/,/g, '');

            var totalPricenomarkup = document.querySelector('input[name="totalpricenomarkup"]');
            // console.log(totalPricenomarkup.value, ">>>total price value");
            var cleanedPricenomarkup = totalPricenomarkup.value.replace(/,/g, '');

            // Cek apakah cleanedPrice adalah angka sebelum melakukan perhitungan
            if (!isNaN(parseInt(cleanedPrice))) {
                var priceintext = parseInt(cleanedPrice) * parseInt(permalam);
                // console.log(parseInt(cleanedPrice), ">>>total price inte");
                totalPriceElements.textContent = priceintext.toLocaleString();

                var pricenomarkupintext = parseInt(cleanedPricenomarkup) * parseInt(permalam);
                // console.log(parseInt(cleanedPricenomarkup), ">>>total price inte");
                totalPricenomarkupElements.textContent = pricenomarkupintext.toLocaleString();
            } else {
                // Jika cleanedPrice adalah NaN, set priceintext menjadi 0
                var priceintext = 0;
                totalPriceElements.textContent = priceintext.toLocaleString();
                var pricenomarkupintext = 0;
                totalPricenomarkupElements.textContent = pricenomarkupintext.toLocaleString();
            }


            var person = $('#person').val();

            // Ubah format tanggal menjadi YYYY-MM-DD
            var formattedCheckin = checkinDate.toISOString().slice(0, 10);
            var formattedCheckout = checkoutDate.toISOString().slice(0, 10);

            // Bentuk URL dengan parameter yang diinginkan
            var contractId = '{{ $data[0]->contract_id }}'; // Ganti dengan cara Anda mendapatkan contract_id
            var url = '/homepage/hotel/' + contractId +
                '?checkin=' + formattedCheckin +
                '&checkout=' + formattedCheckout +
                '&person=' + person;

            // Lakukan pengalihan ke halaman yang diinginkan
            window.location.href = url;
        }

        function night() {
            var checkinDate = new Date(document.getElementById('checkin').value);
            var checkoutDate = new Date(document.getElementById('checkout').value);

            var totalPriceElements = document.getElementById('totalPrice');
            var totalPricenomarkupElements = document.getElementById('totalPricenomarkup');

            var timeDiff = Math.abs(checkoutDate.getTime() - checkinDate.getTime());
            var permalam = Math.ceil(timeDiff / (1000 * 3600 * 24));
            if (isNaN(permalam) || permalam === null) {
                permalam = 0;
            }
            var totalNightElement = document.getElementById('totalNight');
            totalNightElement.textContent = permalam;

            var totalNightInput = document.querySelector('input[name="totalnight"]');
            totalNightInput.value = permalam.toLocaleString();

            var totalPrice = document.querySelector('input[name="totalprice"]');
            // console.log(totalPrice.value, ">>>total price value");
            var cleanedPrice = totalPrice.value.replace(/,/g, '');

            var totalPricenomarkup = document.querySelector('input[name="totalpricenomarkup"]');
            console.log(totalPricenomarkup.value, ">>>total price value");
            var cleanedPricenomarkup = totalPricenomarkup.value.replace(/,/g, '');

            // Cek apakah cleanedPrice adalah angka sebelum melakukan perhitungan
            if (!isNaN(parseInt(cleanedPrice))) {
                var priceintext = parseInt(cleanedPrice) * parseInt(permalam);
                // console.log(parseInt(cleanedPrice), ">>>total price inte");
                totalPriceElements.textContent = priceintext.toLocaleString();

                var pricenomarkupintext = parseInt(cleanedPricenomarkup) * parseInt(permalam);
                totalPricenomarkupElements.textContent = pricenomarkupintext.toLocaleString();
            } else {
                // Jika cleanedPrice adalah NaN, set priceintext menjadi 0
                var priceintext = 0;
                totalPriceElements.textContent = priceintext.toLocaleString();
                var pricenomarkupintext = 0;
                totalPricenomarkupElements.textContent = pricenomarkupintext.toLocaleString();
            }

        }

        function calculateTotal() {
            var roomQuantities = document.getElementsByClassName('room-quantity');
            var totalRoomElement = document.getElementById('totalRoom');
            var totalPriceElement = document.getElementById('totalPrice');
            var totalPricenomarkupElement = document.getElementById('totalPricenomarkup');
            var totalRoom = 0;
            var totalPrice = 0;
            var totalPricenomarkup = 0;
            var totalNight = document.querySelector('input[name="totalnight"]');

            // Mengambil data yang dipilih dan menyimpannya ke dalam array
            var selectedItems = [];

            for (var i = 0; i < roomQuantities.length; i++) {
                var quantity = parseInt(roomQuantities[i].value);
                var price = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.price);

                var pricenomarkup = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset
                    .pricenomarkup);
                var roomId = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.roomid);
                var contractId = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.contractid);
                var contpricetId = parseInt(roomQuantities[i].options[roomQuantities[i].selectedIndex].dataset.contprice);

                totalPricenomarkup += pricenomarkup;
                totalRoom += quantity;
                totalPrice += price;

                // console.log("Room Quantity:", quantity);
                // console.log("Price:", price);
                // console.log("Pricenomarkup:", pricenomarkup);

                if (quantity > 0) {
                    selectedItems.push({
                        roomId: roomId,
                        price: price,
                        pricenomarkup: pricenomarkup,
                        quantity: quantity,
                        contractid: contractId,
                        contpriceid: contpricetId
                    });
                }
            }

            // Menyimpan data yang dipilih ke localStorage
            var encryptionKey = 'KunciEnkripsiRahasia';
            saveEncryptedDataToLocalStorage(selectedItems, encryptionKey);

            // Mengambil dan mendekripsi data dari localStorage
            var decryptedData = getDecryptedDataFromLocalStorage(encryptionKey);
            // console.log(decryptedData);

            // console.log(totalPrice, totalPricenomarkup, ">>>totalnomarkup");
            totalRoomElement.textContent = totalRoom;
            var priceintext = parseInt(totalPrice * totalNight.value);
            totalPriceElement.textContent = priceintext.toLocaleString();
            var totalRoomInput = document.querySelector('input[name="totalroom"]');
            totalRoomInput.value = totalRoom;
            var totalPriceInput = document.querySelector('input[name="totalprice"]');
            totalPriceInput.value = totalPrice.toLocaleString();

            // Perhitungan untuk totalPricenomarkupInput
            var pricenomarkupintext = parseInt(totalPricenomarkup * totalNight.value);
            totalPricenomarkupElement.textContent = pricenomarkupintext.toLocaleString();
            var totalPricenomarkupInput = document.querySelector('input[name="totalpricenomarkup"]');
            totalPricenomarkupInput.value = totalPricenomarkup.toLocaleString();
        }

        //function untuk tanggal checkin

        // function setCheckInDateRestriction() {
        //     var currentDate = new Date();

        //     // Format tanggal sebagai "YYYY-MM-DD"
        //     var year = currentDate.getFullYear();
        //     var month = String(currentDate.getMonth() + 1).padStart(2, '0');
        //     var day = String(currentDate.getDate()).padStart(2, '0');
        //     var formattedDate = year + '-' + month + '-' + day;

        //     var checkinInput = document.querySelector('.checkindate');
        //     checkinInput.value = formattedDate;
        //     checkinInput.min = formattedDate;

        //     var checkoutInput = document.querySelector('.checkoutdate');

        //     checkinInput.addEventListener('input', function() {
        //         var checkinDate = new Date(this.value);
        //         var checkoutDate = new Date(checkoutInput.value);

        //         if (checkinDate > checkoutDate) {
        //             checkoutInput.value = '';
        //         }

        //         checkoutInput.min = this.value;
        //         highlightDateRange();
        //     });

        //     checkoutInput.addEventListener('input', function() {
        //         var checkinDate = new Date(checkinInput.value);
        //         var checkoutDate = new Date(this.value);

        //         if (checkinDate > checkoutDate) {
        //             checkoutInput.value = checkinInput.value;
        //         }

        //         highlightDateRange();
        //     });
        //     }

        //     function highlightDateRange() {
        //     var checkinInput = document.querySelector('.checkindate');
        //     var checkoutInput = document.querySelector('.checkoutdate');
        //     var checkinDate = new Date(checkinInput.value);
        //     var checkoutDate = new Date(checkoutInput.value);

        //     var inputs = document.querySelectorAll('input[type="date"]');
        //     inputs.forEach(function(input) {
        //         input.classList.remove('highlight');
        //     });

        //     if (checkinDate && checkoutDate && checkoutDate >= checkinDate) {
        //         var currentDate = new Date(checkinDate);
        //         while (currentDate <= checkoutDate) {
        //             var dateString = currentDate.toISOString().split('T')[0];
        //             var input = document.querySelector('input[value="' + dateString + '"]');
        //             if (input) {
        //                 input.classList.add('highlight');
        //             }
        //             currentDate.setDate(currentDate.getDate() + 1);
        //         }
        //     }
        //     }

        // Panggil fungsi setCheckInDateRestriction saat halaman dimuat
        // window.addEventListener('DOMContentLoaded', setCheckInDateRestriction);

        //==================================================
        //CODE JQUERY
        //==================================================

        // Ketika halaman direload, ambil data dari local storage dan tampilkan ke input


        $(document).ready(function() {
            // Cek apakah ada data di local storage

            // Ambil data terenkripsi dari local storage
            var encryptionKey = 'KunciEnkripsiRahasia';
            var decryptedData = getDecryptedDataFromLocalStorage(encryptionKey);
            // console.log(decryptedData, ">>>>>>>>decriptdata");
            // Tampilkan data ke dalam input elemen
            // $("#checkin").val(decryptedData.checkin);
            // $("#checkout").val(decryptedData.checkout);
            // $('input[name="totalroom"]').val(decryptedData.totalroom);
            // $("#totalprice").val(decryptedData.totalprice);
            // $('#totalroom').val(decryptedData.totalroom);
            // $('input[name="totalprice"]').val(decryptedData.totalprice);
            // $("#person").val(decryptedData.person);

        });

        $('.category').change(function() {
            var nilaiInput = $(this).val();
            // console.log(nilaiInput, ">>>>>>nilai select");

            // Tampilkan loading
            $('#load').append(
                '<div id="loading" class="d-flex"><div class="loader mx-2"></div><p>Loading...</p></div>');

            if (nilaiInput == 0) {
                window.location.reload();
            } else {
                $.ajax({
                    url: "{{ route('hoteldetail.homepage', $data[0]->contract_id) }}",
                    method: 'GET',
                    data: {
                        data: {
                            category: nilaiInput
                        }
                    },
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
            var totalPricenomarkup = $('input[name="totalpricenomarkup"]').val();
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
                    totalpricenomarkup: totalPricenomarkup,
                    person: person,
                    vendorid: vendorid
                },
                success: function(response) {
                    console.log('Data keranjang berhasil disimpan ke database.');
                    // console.log(response, "hasil");
                    var id = response[0];
                    localStorage.clear();
                    window.location.href = "{{ route('booking.agent.detail', ['id' => ':id']) }}"
                        .replace(':id', id);
                    // window.location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        // Pengguna tidak memiliki otorisasi (Unauthorized),
                        // arahkan ke halaman login atau halaman lain yang sesuai
                        localStorage.clear();
                        window.location.href = "{{ route('login.agent') }}";
                    } else {
                        console.log('Terjadi kesalahan saat menyimpan data.');
                        console.log(xhr);
                    }
                }
            });
        });
        //==================================================
        //CODE JQUERY
        //==================================================
    </script>
    <script>
        $('input[name="dates"]').daterangepicker();

        // Tambahkan event listener untuk deteksi klik tombol "Apply"
        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
            // Mengambil tanggal checkin dan checkout dari Date Range Picker
            const checkin = picker.startDate.format('YYYY-MM-DD');
            const checkout = picker.endDate.format('YYYY-MM-DD');

            // Memperbarui nilai input tanggal checkin dan checkout
            $('input[name="checkin"]').val(checkin);
            $('input[name="checkout"]').val(checkout);

            // Memperbarui nilai input dengan tampilan tanggal
            $(this).val(checkin + ' - ' + checkout);

            checknight()
        });
    </script>
    <style>
        .loader {
            border: 5px solid #8bc1f3;
            border-radius: 50%;
            border-top: 5px solid #f78787;
            width: 20px;
            height: 20px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Gaya untuk input tanggal */
        .date-input-wrapper input[type="date"] {
            /* Menghilangkan tombol kalender */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            /* Mengubah kursor menjadi tangan saat mengarah ke input */
        }

        /* Gaya saat input tanggal dalam keadaan fokus */
        .date-input-wrapper input[type="date"]:focus {
            outline: none;
            /* Menghilangkan garis pinggir saat fokus */
            border: 1px solid #ced4da;
            /* Menambahkan garis tepi */
            /* Anda dapat menyesuaikan gaya lain sesuai kebutuhan */
        }

        /* Hide the images by default */
        .mySlides {
            display: none;
        }

        /* Add a pointer when hovering over the thumbnail images */
        .cursor {
            cursor: pointer;
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 40%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            border-radius: 0 3px 3px 0;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* Container for image text */
        .caption-container {
            text-align: center;
            background-color: #222;
            padding: 2px 16px;
            color: white;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Six columns side by side */
        .column {
            float: left;
            width: 16.66%;
        }

        /* Add a transparency effect for thumnbail images */
        .demo {
            opacity: 0.6;
        }

        .active,
        .demo:hover {
            opacity: 1;
        }
    </style>

@endsection
