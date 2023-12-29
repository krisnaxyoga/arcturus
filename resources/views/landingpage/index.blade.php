@extends('layouts.landing')
@section('title', 'Home Page')
@section('contents')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{-- <div class="hero-wrap js-fullheight" style="background-image: url('/landing/travel/images/bg_5.jpg');"> --}}
    <div class="hero-wrap" style="height:600px">
        {{-- <div class="overlay"></div> --}}
        <div id="demo" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($slider as $index => $item)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" src="{{ $item->image }}" alt="{{ $item->image }}"
                            style="width:100%;height:600px; object-fit: cover;">
                    </div>
                @endforeach
                {{-- <div class="carousel-item">
                    <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" src="/landing/travel/images/bg_2.jpg" alt="Chicago" style="width:100%;height:600px; object-fit: cover;">
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


    @if (isset(Auth::user()->id) && Auth::user()->role_id == 3 && Auth::user()->is_active == 1)
        <section class="ftco-section ftco-no-pb ftco-no-pt">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ftco-search justify-content-center">
                            <div class="row">
                                <div class="col-md-12 nav-link-wrap">
                                    <div class="nav nav-pills text-center" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        {{-- <a class="nav-link active mr-md-1" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Search Tour</a> --}}

                                        <a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#"
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
                                                <div class="g-field-search">
                                                    <div class="row">
                                                        <div class="col-md d-flex">
                                                            <div class="form-group border-0 mb-3 mt-2 mx-2">
                                                                <label class="pl-3 mt-3" for="">Search</label>
                                                                <input type="text" name="search" class="form-control" placeholder="Search...">
                                                                {{-- <select name="country" id=""
                                                                class="form-control ">
                                                                <option value="">{{ __('-- Select --') }}
                                                                </option> --}}
                                                                {{-- @foreach (get_country_lists() as $id => $name)
                                                                    <option
                                                                        @if (($user->country ?? '') == $id) selected @endif
                                                                        value="{{ $name }}">
                                                                        {{ $name }}</option>
                                                                @endforeach --}}
                                                                {{-- @foreach ($country as $name)
                                                                <option
                                                                    @if (($user->country ?? '') == $name->country) selected @endif
                                                                    value="{{ $name->country }}">
                                                                    {{ $name->country }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-md d-flex">
                                                            <div class="form-group mb-3 mt-2 mx-2">
                                                                <label class="pl-3 mt-3" for="#">State</label>
                                                                <div class="form-field"> --}}
                                                                    {{-- <div class="icon"><span class="fa fa-search"></span></div> --}}
                                                                    {{-- <input type="text" name="state" class="form-control"
                                                                        placeholder="state...">
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-md d-flex">
                                                           
                                                            <div class="form-group mb-3 mt-2 mx-2">
                                                                <label class="pl-3 mt-3" for="">CheckIn - CheckOut</label>
                                                                <input class="form-control" type="text" name="dates" value="" />
                                                            </div>
                                                            <input value="" type="hidden" name="checkin"
                                                            class="form-control checkindate"
                                                            placeholder="Check In Date">
                                                            <input value="" type="hidden" name="checkout"
                                                            class="form-control checkoutdate"
                                                            placeholder="Check Out Date">
                                                        </div>
                                                        {{-- <div class="col-md d-flex">
                                                            <div class="form-group mb-3 mt-2 mx-2">
                                                                <label class="pl-3 mt-3" for="#">Check-in</label>
                                                                <div class="form-field">

                                                                    <input type="date" id="daterange" name="checkin" class="form-control checkindate"
                                                                        placeholder="Check In Date">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md d-flex">
                                                            <div class="form-group mb-3 mt-2 mx-2">
                                                                <label class="pl-3 mt-3" for="#">Check-out</label>
                                                                <div class="form-field">

                                                                    <input type="date" name="checkout"
                                                                        class="form-control checkoutdate"
                                                                        placeholder="Check Out Date">
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-md d-flex">
                                                            <div class="form-group mb-3 mt-2 mx-2">
                                                                <label class="pl-3 mt-3" for="#">Person</label>
                                                                <select name="person" id=""
                                                                        class="form-control">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
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
                                                            <button class="btn btn-primary w-100" type="submit">
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
                        </div>
                    </div>
                </div>
        </section>
    @endif

    <section class="ftco-section services-section">
        
        <div class="container">
            <div class="row d-flex justify-content-center mb-4">
                <div
                    class="col-md-4 ftco-animate">
                    <div class="card shadow border-0 mb-3" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5 class="text-center text-secondary font-weight-bold"><i class="fa fa-building"></i> Hotel</h5>
                            <h3 class="text-center text-secondary font-weight-bold">{{$hotel}}</h3>
                        </div>
                    </div>
                </div>
                <div
                    class="col-md-4 ftco-animate">
                    <div class="card shadow border-0 mb-3" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5 class="text-center text-secondary font-weight-bold"><i class="fa fa-users"></i> Agent</h5>
                            <h3 class="text-center text-secondary font-weight-bold">{{$agent}}</h3>
                        </div>
                    </div>
                </div>
                <div
                    class="col-md-4 ftco-animate">
                    <div class="card shadow border-0 mb-3 " style="border-radius: 20px;">
                        <div class="card-body">
                            <h5 class="text-center text-secondary font-weight-bold"><i class="fa fa-car"></i> Transport</h5>
                            <h3 class="text-center text-secondary font-weight-bold">2</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row d-flex mb-5">
                <div class="col-md-12 order-md-last heading-section pl-md-5 ftco-animate d-flex align-items-center">
                    <div class="w-100">
                         {{-- <span class="subheading">Welcome to Arcturus</span> --}}
                         <h2 class="subheading mb-4">Welcome to Arcturus</h2>
                         <p>ARCTURUS is a growing online travel marketplace that connects retailer agents with travel service
                             providers, especially hotels, boats, and tour operators. With one-time registration, ARCTURUS
                             helps retailer agents get access to the hotel’s dynamic rates and other special offers provided
                             by travel service providers. On the other hand, hotels and travel service providers can
                             efficiently reach and target their promotions directly to hundreds of retailer agents. </p>
                         <p>Our mission is to make it easier for retailer agents and hotels including travel services
                             providers to collaborate and support one another. The platform is built to enable properties
                             around the world to reach a global audience and grow their businesses by offering travellers an
                             extensive selection of hotels and activities at competitive rates.
                         </p>
                    </div>
                </div>
            </div>
            <h3 class="text-center mb-3 text-secondary font-weight-bold">How Rates Distributed</h3>
            <div class="row mb-3 justify-content-center">
                <div class="col-lg-4 mb-3 col-md-6">
                    <div class="cardx">
                        <p class="cardx-title text-center"> Hotel </p>
                        <div class="cardx-body">
                           <ul>
                            <li>Update Best AVAILABLE (BAR) in the system to make sure contract rate given is lower than website rates</li>
                            <li>provide contract rate for Arcturus with min 15% OFF BAR</li>
                            <li>provide monthly special offer or flash deal if available</li>
                           </ul>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 mb-3 col-md-6">
                    <div class="cardx">
                        <p class="cardx-title text-center">Arcturus</p>
                        <div class="cardx-body">
                          <ul>
                            <li>Arcturus will upload all rates with guarantee allotment minimum 1 room to be able displayed in Arcturus system</li>
                                
                                <li>To keep the rate COMPETITIVE with other channels, Arcturus only allows bank transfer due to if we allow credit card
                                or others, we have to pay bank fee about 2,5% from the total transaction which will be added on the top of rate
                                and automatically the rate become higher, no longer competitive and less profit for retail agent</li>
                          </ul>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 mb-3 col-md-6">
                    <div class="cardx">
                        <p class="cardx-title text-center">Retail Agent</p>
                        <div class="cardx-body">
                         <ul>
                            <li>JUST register in ARCTURUS then all will be displayed in your screen and ready to sell to your guests </li>

                                <li>Bank transfer need to verify by ADMIN maximum up to 3 hours depending on transaction traffic</li>
                                
                              <li>other payment option is E-WALLET, only need ADMIN verification when we do TOP UP and if your saldo 
                                is still available for the next transaction, no more ADMIN verification needed</li>
                         </ul>
                        </div>
                      </div>
                </div>
            </div>
            <h3 class="text-center mb-3 text-secondary font-weight-bold">Benefits For</h3>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-4 mb-3 col-md-6">
                    <div class="cardx">
                        <p class="cardx-title text-center">Hotel</p>
                        <div class="cardx-body">
                          <ul>
                            <li>Generated more hotel revenue due to hotel only pay 2.5% commission to system 
                                instead have to pay 25% up to 30% to others.</li>
                                  
                               <li> No risk since hotel will receives full payment upon bookings received.</li>
                          </ul>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 mb-3 col-md-6">
                    <div class="cardx">
                        <p class="cardx-title text-center">Arcturus</p>
                        <div class="cardx-body">
                         <ul>
                            <li>Arcturus will offers FREE registration for first 500 Indonesian agents and 500 Overseas agents 
                                afterward agent has to pay annual fee but for those first 1000 agents will be free forever</li>
                         </ul>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 mb-3 col-md-6">
                    <div class="cardx">
                        <p class="cardx-title text-center">Retail Agent</p>
                        <div class="cardx-body">
                            <ul>
                                <li>Automatically able to access ALL rates in Arcturus system without contacting any hotel sales to get 
                                    rate and room availability</li>
                                <li>Instant confirmation will be received with Agent logo and Hotel logo. print directly 
                                    from system or download and send via email to your guests</li>
                            </ul>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <p class="text-center"> For more information, please visit our instagram</p> <br>
            </div>
            <div class="row justify-content-center">
                <a href="https://www.instagram.com/arcturus.my.id?=NzZIODBkYWE4Ng==" target="_blank" class="instagram-button Btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 448 512" class="svgIcon"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg>
                  <span class="text-secondary font-weight-bold">@arcturus.my.id</span>
                </a>
            </div>
        </div>
             {{--
            <div class="row d-flex mb-5">
                <div class="col-md-12 order-md-last heading-section pl-md-5 ftco-animate d-flex align-items-center">
                    <div class="w-100">
                        {{-- <span class="subheading">Welcome to Arcturus</span> --}}
                        {{-- <h2 class="subheading mb-4">Welcome to Arcturus</h2>
                        <p>ARCTURUS is a growing online travel marketplace that connects retailer agents with travel service
                            providers, especially hotels, boats, and tour operators. With one-time registration, ARCTURUS
                            helps retailer agents get access to the hotel’s dynamic rates and other special offers provided
                            by travel service providers. On the other hand, hotels and travel service providers can
                            efficiently reach and target their promotions directly to hundreds of retailer agents. </p>
                        <p>Our mission is to make it easier for retailer agents and hotels including travel services
                            providers to collaborate and support one another. The platform is built to enable properties
                            around the world to reach a global audience and grow their businesses by offering travellers an
                            extensive selection of hotels and activities at competitive rates.
                        </p>
                        <h3>Partner with ARCTURUS</h3>
                        <p>ARCTURUS is growing and open to mutual collaboration to support your business development.
                        </p>
                        <p>Why You Should Collaborate with Us</p>
                        <h4>Benefits for the hotels</h4>
                        <ul>
                            <li>Hotels will not rely on wholesalers only.</li>
                            <li> Get direct connections with hundreds of retailer agents hence hotels can sell at better
                                prices.</li>
                            <li>Hotels can focus their promotion based on their target location. It will only be shown,
                                distributed, and visible to your target market.</li>
                            <li>Hotels will receive promo recommendations based on their target market and statistics.</li>
                            <li>Full payment will be received 1x24 hours after the booking is confirmed.</li> --}}
                            {{-- <li>Lower commission fee than other OTAs. Only 2,5%- 5%.</li> --}}
                        {{-- </ul>
                        <h4>Benefits for travel agents</h4>
                        <ul>
                            <li>FREE to join & FREE of charge.</li>
                            <li>Agents will automatically receive hotel contract rates with one-time registration without
                                even contacting the hotels.</li>
                            <li>Better profit since you receive direct offers from the hotel.</li>
                        </ul>


                    </div>
                </div>

            </div>
           
        </div> --}} 
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


  <section class="ftco-intro ftco-section ftco-no-pt mt-5">
    <div class="container">
        <a href="https://www.vibeshospitality.id/" target="_blank">
             <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <div class="img" style="background-image: url(/landing/travel/images/bg_2.jpg); border-radius: 29px">
                    <div class="overlay"></div>
                    <h2 style="text-transform: none;">ViBES HOSPITALITY</h2>
                    <!--<p>We can manage your dream building A small river named Duden flows by their place</p>-->
                    <!--<p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Ask For A Quote</a></p>-->
                </div>
            </div>
        </div>
        </a>
       
    </div>
</section>
    <script>
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
    </script>
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
    });
</script>
<script>
    // Inisialisasi Date Range Picker
    $('input[name="dates"]').daterangepicker({
        startDate: moment().format('YYYY-MM-DD'), // Tanggal checkin (hari ini)
        endDate: moment().add(1, 'days').format('YYYY-MM-DD'), // Tanggal checkout (besok)
        autoUpdateInput: false, // Menonaktifkan pembaruan otomatis
        locale: {
            format: 'YYYY-MM-DD', // Format tanggal yang diharapkan
        }
    });

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
    });
</script>
@endsection
