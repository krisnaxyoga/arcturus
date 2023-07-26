@extends('layouts.landing')
@section('title', 'Hotel')
@section('contents')

   
<section class="hero-wrap hero-wrap-2" style="background-image: url('/landing/travel/images/bg_1.jpg'); height:300px">
    <div class="overlay" style="height: 300px"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center" style="height:300px">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
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
                    <form action="{{route('hotel.homepage')}}" method="get" class="search-property-1">
                        @csrf
                        <div class="row no-gutters">
                            <div class="col-md d-flex">
                                <div class="form-group p-4 border-0">
                                    <label for="#">Country</label>
                                    <div class="form-field">
                                        <div class="select-wrap">
                                            <div class="icon"><span class="fa fa-chevron-down"></span></div>
                                             <select name="country" id=""  class="form-control">
                                            <option value="">{{ __('-- Select --') }}</option>
                                            @foreach (get_country_lists() as $id => $name)
                                                <option @if (($user->country ?? '') == $id) selected @endif value="{{ $name }}">{{ $name }}</option>
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
                                        <input type="text" name="state" class="form-control" placeholder="state...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group p-4 border-0">
                                    <label for="#">City</label>
                                    <div class="form-field">
                                        {{-- <div class="icon"><span class="fa fa-search"></span></div> --}}
                                        <input type="text" name="city" class="form-control" placeholder="city...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group p-4">
                                    <label for="#">Check-in</label>
                                    <div class="form-field">
                                        <div class="icon"><span class="fa fa-calendar"></span></div>
                                        <input type="text" name="checkin" class="form-control checkin_date" placeholder="Check In Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group p-4">
                                    <label for="#">Check-out</label>
                                    <div class="form-field">
                                        <div class="icon"><span class="fa fa-calendar"></span></div>
                                        <input type="text" name="checkout" class="form-control checkout_date" placeholder="Check Out Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md d-flex">
                                <div class="form-group p-4">
                                    <label for="#">Person</label>
                                    <div class="form-field">
                                        <div class="select-wrap">
                                            <div class="icon"><span class="fa fa-chevron-down"></span></div>
                                            <select name="person" id="" class="form-control">
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
                                        <input type="submit" value="Search" class="align-self-stretch form-control btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="ftco-section">
        <div class="container">
            <div class="row">
                @foreach ($data as $item)
                    <div class="col-md-4 ftco-animate">
                        <div class="project-wrap hotel">
                            <a href="{{route('hoteldetail.homepage',$item->contract_id)}}" class="img"
                                style="background-image: url(/landing/travel/images/hotel-resto-1.jpg);">
                                <span class="price">Rp. {{ number_format($item->price, 0, ',', '.')}}</span>
                            </a>
                            <div class="text p-4">
                                <p class="star mb-2">
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                </p>
                                <span class="days">min. {{$item->contractrate->min_stay}} night</span>
                                <h3><a href="{{route('hoteldetail.homepage',$item->contract_id)}}">{{$item->contractrate->vendors->vendor_name}}</a></h3>
                                <p class="location"><span class="fa fa-map-marker"></span> {{$item->contractrate->vendors->city}},{{$item->contractrate->vendors->state}}, {{$item->contractrate->vendors->country}}</p>
                                <ul>
                                    <li><span class="flaticon-381-user-7"><i class="fa fa-user"></i></span>{{$item->room->adults}}</li>
                                    {{-- <li><span class="flaticon-king-size"></span>3</li>
                                    <li><span class="flaticon-mountains"></span>Near Mountain</li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row mt-5">
                <div class="col text-center">
                    <div class="block-27">
                        {{$data->links()}}
                    </div>
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
@endsection
