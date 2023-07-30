@extends('layouts.landing')
@section('title', 'Home Page')
@section('contents')

    {{-- <div class="hero-wrap js-fullheight" style="background-image: url('/landing/travel/images/bg_5.jpg');"> --}}
    <div class="hero-wrap" style="height:600px">
        {{-- <div class="overlay"></div> --}}
        <div id="demo" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($slider as $index=>$item)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{$item->image}}" alt="{{$item->image}}" style="width:100%;height:600px; object-fit: cover;">
                </div>
                @endforeach
                {{-- <div class="carousel-item">
                    <img src="/landing/travel/images/bg_2.jpg" alt="Chicago" style="width:100%;height:600px; object-fit: cover;">
                    {{-- <div class="carousel-caption">
                        <h3>Chicago</h3>
                        <p>Thank you, Chicago!</p>
                    </div> --}}
                {{-- </div> --}}
            </div>
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>


    @if (isset(Auth::user()->id) && Auth::user()->role_id == 3)
        <section class="ftco-section ftco-no-pb ftco-no-pt">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ftco-search d-flex justify-content-center">
                            <div class="row">
                                <div class="col-md-12 nav-link-wrap">
                                    <div class="nav nav-pills text-center" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        {{-- <a class="nav-link active mr-md-1" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Search Tour</a> --}}

                                        <a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2"
                                            role="tab" aria-controls="v-pills-2" aria-selected="true">Hotel</a>

                                    </div>
                                </div>
                                <div class="col-md-12 tab-wrap">

                                    <div class="tab-content" id="v-pills-tabContent">

                                        <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel"
                                            aria-labelledby="v-pills-nextgen-tab">
                                            <form action="{{ route('hotel.homepage') }}" method="get"
                                                class="search-property-1">
                                                @csrf
                                                <div class="row no-gutters">
                                                    <div class="col-md d-flex">
                                                        <div class="form-group p-4 border-0">
                                                            <label for="#">Country</label>
                                                            <div class="form-field">
                                                                <div class="select-wrap">
                                                                    <div class="icon"><span
                                                                            class="fa fa-chevron-down"></span></div>
                                                                    <select name="country" id=""
                                                                        class="form-control">
                                                                        <option value="">{{ __('-- Select --') }}
                                                                        </option>
                                                                        @foreach (get_country_lists() as $id => $name)
                                                                            <option
                                                                                @if (($user->country ?? '') == $id) selected @endif
                                                                                value="{{ $name }}">
                                                                                {{ $name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md d-flex">
                                                        <div class="form-group p-4 border-0">
                                                            <label for="#">State</label>
                                                            <div class="form-field">
                                                                {{-- <div class="icon"><span class="fa fa-search"></span></div> --}}
                                                                <input type="text" name="state" class="form-control"
                                                                    placeholder="state...">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md d-flex">
                                                        <div class="form-group p-4 border-0">
                                                            <label for="#">City</label>
                                                            <div class="form-field"> --}}
                                                                {{-- <div class="icon"><span class="fa fa-search"></span></div> --}}
                                                                {{-- <input type="text" name="city" class="form-control"
                                                                    placeholder="city...">
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md d-flex">
                                                        <div class="form-group p-4">
                                                            <label for="#">Check-in</label>
                                                            <div class="form-field">
                                                                <div class="icon"><span class="fa fa-calendar"></span>
                                                                </div>
                                                                <input type="date" name="checkin"
                                                                    class="form-control checkindate"
                                                                    placeholder="Check In Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md d-flex">
                                                        <div class="form-group p-4">
                                                            <label for="#">Check-out</label>
                                                            <div class="form-field">
                                                                <div class="icon"><span class="fa fa-calendar"></span>
                                                                </div>
                                                                <input type="date" name="checkout"
                                                                    class="form-control checkoutdate"
                                                                    placeholder="Check Out Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md d-flex">
                                                        <div class="form-group p-4">
                                                            <label for="#">Person</label>
                                                            <div class="form-field">
                                                                <div class="select-wrap">
                                                                    <div class="icon"><span
                                                                            class="fa fa-chevron-down"></span></div>
                                                                    <select name="person" id=""
                                                                        class="form-control">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md d-flex">
                                                        <div class="form-group d-flex w-100 border-0">
                                                            <div class="form-field w-100 align-items-center d-flex">
                                                                <input type="submit" value="Search"
                                                                    class="align-self-stretch form-control btn btn-primary">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-2" role="tabpanel"
                                            aria-labelledby="v-pills-performance-tab">
                                            <form action="#" class="search-property-1">
                                                <div class="row no-gutters">
                                                    <div class="col-lg d-flex">
                                                        <div class="form-group p-4 border-0">
                                                            <label for="#">Destination</label>
                                                            <div class="form-field">
                                                                <div class="icon"><span class="fa fa-search"></span>
                                                                </div>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Search place">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg d-flex">
                                                        <div class="form-group p-4">
                                                            <label for="#">Check-in date</label>
                                                            <div class="form-field">
                                                                <div class="icon"><span class="fa fa-calendar"></span>
                                                                </div>
                                                                <input type="text" class="form-control checkin_date"
                                                                    placeholder="Check In Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg d-flex">
                                                        <div class="form-group p-4">
                                                            <label for="#">Check-out date</label>
                                                            <div class="form-field">
                                                                <div class="icon"><span class="fa fa-calendar"></span>
                                                                </div>
                                                                <input type="text" class="form-control checkout_date"
                                                                    placeholder="Check Out Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg d-flex">
                                                        <div class="form-group p-4">
                                                            <label for="#">Price Limit</label>
                                                            <div class="form-field">
                                                                <div class="select-wrap">
                                                                    <div class="icon"><span
                                                                            class="fa fa-chevron-down"></span></div>
                                                                    <select name="" id=""
                                                                        class="form-control">
                                                                        <option value="">$100</option>
                                                                        <option value="">$10,000</option>
                                                                        <option value="">$50,000</option>
                                                                        <option value="">$100,000</option>
                                                                        <option value="">$200,000</option>
                                                                        <option value="">$300,000</option>
                                                                        <option value="">$400,000</option>
                                                                        <option value="">$500,000</option>
                                                                        <option value="">$600,000</option>
                                                                        <option value="">$700,000</option>
                                                                        <option value="">$800,000</option>
                                                                        <option value="">$900,000</option>
                                                                        <option value="">$1,000,000</option>
                                                                        <option value="">$2,000,000</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg d-flex">
                                                        <div class="form-group d-flex w-100 border-0">
                                                            <div class="form-field w-100 align-items-center d-flex">
                                                                <input type="submit" value="Search"
                                                                    class="align-self-stretch form-control btn btn-primary p-0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    @endif

    <section class="ftco-section services-section">
        <div class="container">
            <div class="row d-flex mb-5">
                <div class="col-md-12 order-md-last heading-section pl-md-5 ftco-animate d-flex align-items-center">
                    <div class="w-100">
                        <span class="subheading">Welcome to Arcturus</span>
                        <h2 class="mb-4">It's time to start your adventure</h2>
                        <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It
                            is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
                            a large language ocean.
                            A small river named Duden flows by their place and supplies it with the necessary regelialia.
                        </p>

                    </div>
                </div>

            </div>
            <div class="row d-flex justify-content-center mt-4">
                <div class="col-md-4 order-md-last heading-section pl-md-5 ftco-animate d-flex justify-content-center align-items-center">
                    <div class="card">
                        <div class="card-body">
                            <h3>Hotel</h3>
                            <p class="text-center" style="font-size: 16px">0</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 order-md-last heading-section pl-md-5 ftco-animate d-flex justify-content-center align-items-center">
                    <div class="card">
                        <div class="card-body">
                            <h3>Agent</h3>
                            <p class="text-center" style="font-size: 16px">0</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 order-md-last heading-section pl-md-5 ftco-animate d-flex justify-content-center align-items-center">
                    <div class="card">
                        <div class="card-body">
                            <h3>Others</h3>
                            <p class="text-center" style="font-size: 16px">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{{--
    <section class="ftco-section img ftco-select-destination"
        style="background-image: url(/landing/travel/images/bg_3.jpg);">
        <div class="container">
            <div class="row justify-content-center pb-4">
                <div class="col-md-12 heading-section text-center ftco-animate">
                    <span class="subheading">Pacific Provide Places</span>
                    <h2 class="mb-4">Select Your Destination</h2>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="carousel-destination owl-carousel ftco-animate">
                        <div class="item">
                            <div class="project-destination">
                                <a href="#" class="img"
                                    style="background-image: url(/landing/travel/images/place-1.jpg);">
                                    <div class="text">
                                        <h3>Philippines</h3>
                                        <span>8 Tours</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="project-destination">
                                <a href="#" class="img"
                                    style="background-image: url(/landing/travel/images/place-2.jpg);">
                                    <div class="text">
                                        <h3>Canada</h3>
                                        <span>2 Tours</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="project-destination">
                                <a href="#" class="img"
                                    style="background-image: url(/landing/travel/images/place-3.jpg);">
                                    <div class="text">
                                        <h3>Thailand</h3>
                                        <span>5 Tours</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="project-destination">
                                <a href="#" class="img"
                                    style="background-image: url(/landing/travel/images/place-4.jpg);">
                                    <div class="text">
                                        <h3>Autralia</h3>
                                        <span>5 Tours</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="project-destination">
                                <a href="#" class="img"
                                    style="background-image: url(/landing/travel/images/place-5.jpg);">
                                    <div class="text">
                                        <h3>Greece</h3>
                                        <span>7 Tours</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
{{--
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center pb-4">
                <div class="col-md-12 heading-section text-center ftco-animate">
                    <span class="subheading">Destination</span>
                    <h2 class="mb-4">Tour Destination</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 ftco-animate">
                    <div class="project-wrap">
                        <a href="#" class="img"
                            style="background-image: url(/landing/travel/images/destination-1.jpg);">
                            <span class="price">$550/person</span>
                        </a>
                        <div class="text p-4">
                            <span class="days">8 Days Tour</span>
                            <h3><a href="#">Banaue Rice Terraces</a></h3>
                            <p class="location"><span class="fa fa-map-marker"></span> Banaue, Ifugao, Philippines</p>
                            <ul>
                                <li><span class="flaticon-shower"></span>2</li>
                                <li><span class="flaticon-king-size"></span>3</li>
                                <li><span class="flaticon-mountains"></span>Near Mountain</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ftco-animate">
                    <div class="project-wrap">
                        <a href="#" class="img"
                            style="background-image: url(/landing/travel/images/destination-2.jpg);">
                            <span class="price">$550/person</span>
                        </a>
                        <div class="text p-4">
                            <span class="days">10 Days Tour</span>
                            <h3><a href="#">Banaue Rice Terraces</a></h3>
                            <p class="location"><span class="fa fa-map-marker"></span> Banaue, Ifugao, Philippines</p>
                            <ul>
                                <li><span class="flaticon-shower"></span>2</li>
                                <li><span class="flaticon-king-size"></span>3</li>
                                <li><span class="flaticon-sun-umbrella"></span>Near Beach</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ftco-animate">
                    <div class="project-wrap">
                        <a href="#" class="img"
                            style="background-image: url(/landing/travel/images/destination-3.jpg);">
                            <span class="price">$550/person</span>
                        </a>
                        <div class="text p-4">
                            <span class="days">7 Days Tour</span>
                            <h3><a href="#">Banaue Rice Terraces</a></h3>
                            <p class="location"><span class="fa fa-map-marker"></span> Banaue, Ifugao, Philippines</p>
                            <ul>
                                <li><span class="flaticon-shower"></span>2</li>
                                <li><span class="flaticon-king-size"></span>3</li>
                                <li><span class="flaticon-sun-umbrella"></span>Near Beach</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 ftco-animate">
                    <div class="project-wrap">
                        <a href="#" class="img"
                            style="background-image: url(/landing/travel/images/destination-4.jpg);">
                            <span class="price">$550/person</span>
                        </a>
                        <div class="text p-4">
                            <span class="days">8 Days Tour</span>
                            <h3><a href="#">Banaue Rice Terraces</a></h3>
                            <p class="location"><span class="fa fa-map-marker"></span> Banaue, Ifugao, Philippines</p>
                            <ul>
                                <li><span class="flaticon-shower"></span>2</li>
                                <li><span class="flaticon-king-size"></span>3</li>
                                <li><span class="flaticon-sun-umbrella"></span>Near Beach</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ftco-animate">
                    <div class="project-wrap">
                        <a href="#" class="img"
                            style="background-image: url(/landing/travel/images/destination-5.jpg);">
                            <span class="price">$550/person</span>
                        </a>
                        <div class="text p-4">
                            <span class="days">10 Days Tour</span>
                            <h3><a href="#">Banaue Rice Terraces</a></h3>
                            <p class="location"><span class="fa fa-map-marker"></span> Banaue, Ifugao, Philippines</p>
                            <ul>
                                <li><span class="flaticon-shower"></span>2</li>
                                <li><span class="flaticon-king-size"></span>3</li>
                                <li><span class="flaticon-sun-umbrella"></span>Near Beach</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ftco-animate">
                    <div class="project-wrap">
                        <a href="#" class="img"
                            style="background-image: url(/landing/travel/images/destination-6.jpg);">
                            <span class="price">$550/person</span>
                        </a>
                        <div class="text p-4">
                            <span class="days">7 Days Tour</span>
                            <h3><a href="#">Banaue Rice Terraces</a></h3>
                            <p class="location"><span class="fa fa-map-marker"></span> Banaue, Ifugao, Philippines</p>
                            <ul>
                                <li><span class="flaticon-shower"></span>2</li>
                                <li><span class="flaticon-king-size"></span>3</li>
                                <li><span class="flaticon-sun-umbrella"></span>Near Beach</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- <section class="ftco-section ftco-about img"style="background-image: url(/landing/travel/images/bg_4.jpg);">
        <div class="overlay"></div>
        <div class="container py-md-5">
            <div class="row py-md-5">
                <div class="col-md d-flex align-items-center justify-content-center">
                    <a href="https://vimeo.com/45830194"
                        class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
                        <span class="fa fa-play"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-about ftco-no-pt img">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-12 about-intro">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div class="img d-flex w-100 align-items-center justify-content-center"
                                style="background-image:url(/landing/travel/images/about-1.jpg);">
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-5 py-5">
                            <div class="row justify-content-start pb-3">
                                <div class="col-md-12 heading-section ftco-animate">
                                    <span class="subheading">About Us</span>
                                    <h2 class="mb-4">Make Your Tour Memorable and Safe With Us</h2>
                                    <p>Far far away, behind the word mountains, far from the countries Vokalia and
                                        Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right
                                        at the coast of the Semantics, a large language ocean.</p>
                                    <p><a href="#" class="btn btn-primary">Book Your Destination</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- <section class="ftco-section testimony-section bg-bottom"
        style="background-image: url(/landing/travel/images/bg_1.jpg);">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center pb-4">
                <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
                    <span class="subheading">Testimonial</span>
                    <h2 class="mb-4">Tourist Feedback</h2>
                </div>
            </div>
            <div class="row ftco-animate">
                <div class="col-md-12">
                    <div class="carousel-testimony owl-carousel">
                        <div class="item">
                            <div class="testimony-wrap py-4">
                                <div class="text">
                                    <p class="star">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                    </p>
                                    <p class="mb-4">Far far away, behind the word mountains, far from the countries
                                        Vokalia and Consonantia, there live the blind texts.</p>
                                    <div class="d-flex align-items-center">
                                        <div class="user-img"
                                            style="background-image: url(/landing/travel/images/person_1.jpg)"></div>
                                        <div class="pl-3">
                                            <p class="name">Roger Scott</p>
                                            <span class="position">Marketing Manager</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimony-wrap py-4">
                                <div class="text">
                                    <p class="star">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                    </p>
                                    <p class="mb-4">Far far away, behind the word mountains, far from the countries
                                        Vokalia and Consonantia, there live the blind texts.</p>
                                    <div class="d-flex align-items-center">
                                        <div class="user-img"
                                            style="background-image: url(/landing/travel/images/person_2.jpg)"></div>
                                        <div class="pl-3">
                                            <p class="name">Roger Scott</p>
                                            <span class="position">Marketing Manager</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimony-wrap py-4">
                                <div class="text">
                                    <p class="star">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                    </p>
                                    <p class="mb-4">Far far away, behind the word mountains, far from the countries
                                        Vokalia and Consonantia, there live the blind texts.</p>
                                    <div class="d-flex align-items-center">
                                        <div class="user-img"
                                            style="background-image: url(/landing/travel/images/person_3.jpg)"></div>
                                        <div class="pl-3">
                                            <p class="name">Roger Scott</p>
                                            <span class="position">Marketing Manager</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimony-wrap py-4">
                                <div class="text">
                                    <p class="star">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                    </p>
                                    <p class="mb-4">Far far away, behind the word mountains, far from the countries
                                        Vokalia and Consonantia, there live the blind texts.</p>
                                    <div class="d-flex align-items-center">
                                        <div class="user-img"
                                            style="background-image: url(/landing/travel/images/person_1.jpg)"></div>
                                        <div class="pl-3">
                                            <p class="name">Roger Scott</p>
                                            <span class="position">Marketing Manager</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimony-wrap py-4">
                                <div class="text">
                                    <p class="star">
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                    </p>
                                    <p class="mb-4">Far far away, behind the word mountains, far from the countries
                                        Vokalia and Consonantia, there live the blind texts.</p>
                                    <div class="d-flex align-items-center">
                                        <div class="user-img"
                                            style="background-image: url(/landing/travel/images/person_2.jpg)"></div>
                                        <div class="pl-3">
                                            <p class="name">Roger Scott</p>
                                            <span class="position">Marketing Manager</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 --}}

    {{-- <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center pb-4">
                <div class="col-md-12 heading-section text-center ftco-animate">
                    <span class="subheading">Our Blog</span>
                    <h2 class="mb-4">Recent Post</h2>
                </div>
            </div>
            <div class="row d-flex">
                <div class="col-md-4 d-flex ftco-animate">
                    <div class="blog-entry justify-content-end">
                        <a href="blog-single.html" class="block-20" style="background-image: url('/landing/travel/images/image_1.jpg');">
                        </a>
                        <div class="text">
                            <div class="d-flex align-items-center mb-4 topp">
                                <div class="one">
                                    <span class="day">11</span>
                                </div>
                                <div class="two">
                                    <span class="yr">2020</span>
                                    <span class="mos">September</span>
                                </div>
                            </div>
                            <h3 class="heading"><a href="#">Most Popular Place In This World</a></h3>
                            <!-- <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p> -->
                            <p><a href="#" class="btn btn-primary">Read more</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex ftco-animate">
                    <div class="blog-entry justify-content-end">
                        <a href="blog-single.html" class="block-20" style="background-image: url('/landing/travel/images/image_2.jpg');">
                        </a>
                        <div class="text">
                            <div class="d-flex align-items-center mb-4 topp">
                                <div class="one">
                                    <span class="day">11</span>
                                </div>
                                <div class="two">
                                    <span class="yr">2020</span>
                                    <span class="mos">September</span>
                                </div>
                            </div>
                            <h3 class="heading"><a href="#">Most Popular Place In This World</a></h3>
                            <!-- <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p> -->
                            <p><a href="#" class="btn btn-primary">Read more</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex ftco-animate">
                    <div class="blog-entry">
                        <a href="blog-single.html" class="block-20" style="background-image: url('/landing/travel/images/image_3.jpg');">
                        </a>
                        <div class="text">
                            <div class="d-flex align-items-center mb-4 topp">
                                <div class="one">
                                    <span class="day">11</span>
                                </div>
                                <div class="two">
                                    <span class="yr">2020</span>
                                    <span class="mos">September</span>
                                </div>
                            </div>
                            <h3 class="heading"><a href="#">Most Popular Place In This World</a></h3>
                            <!-- <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p> -->
                            <p><a href="#" class="btn btn-primary">Read more</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- <section class="ftco-intro ftco-section ftco-no-pt mt-5">
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
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        // Next/previous controls
        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        // Thumbnail image controls
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }

    </script>
    <script>

  //function untuk tanggal checkin

  function setCheckInDateRestriction() {
               // Ambil elemen input checkin dan checkout
        var checkinInput = document.querySelector('.checkindate');
        var checkoutInput = document.querySelector('.checkoutdate');

        // Dapatkan tanggal sekarang
        var currentDate = new Date();

        // Format tanggal sebagai "YYYY-MM-DD"
        var year = currentDate.getFullYear();
        var month = String(currentDate.getMonth() + 1).padStart(2, '0');
        var day = String(currentDate.getDate()).padStart(2, '0');
        var formattedDate = year + '-' + month + '-' + day;

        // Set nilai dan batasan min pada input checkin
        checkinInput.value = formattedDate;
        checkinInput.min = formattedDate;

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
    </script>
@endsection
