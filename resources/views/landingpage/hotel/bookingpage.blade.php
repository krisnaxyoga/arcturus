@extends('layouts.landing')
@section('title', 'Hotel')
@section('contents')

<section class="hero-wrap hero-wrap-2" style="background-image: url('/landing/travel/images/bg_1.jpg'); height:300px">
    <div class="overlay" style="height: 300px"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center" style="height:300px">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="/">Home <i class="fa fa-chevron-right"></i></a>
                </span>
                <span><a href="{{ route('hotel.homepage') }}">Booking <i class="fa fa-chevron-right"></i></a></span></p>
            </div>
        </div>
    </div>
</section>
<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h4>Booking Submission</h4>
                <hr>
                <form action="{{route('booking.agent.store',$data->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                <div class="card">
                    <div class="card-body">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="firstname" placeholder="First Name" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Email <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="email" value="" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" placeholder="Phone" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Address Line 1</label>
                                        <input type="text" class="form-control" name="address1" placeholder="Address Line 1" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Address Line 2</label>
                                        <input type="text" class="form-control" name="address2" value="" placeholder="Address Line 2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">City</label>
                                        <input type="text" class="form-control" name="city" placeholder="City" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">State/Province/Region</label>
                                        <input type="text" class="form-control" name="state" placeholder="State/Province/Region" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">ZIP code/Postal code</label>
                                        <input type="text" class="form-control" name="zipcode" placeholder="ZIP code/Postal code" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="">Country <span class="text-danger">*</span></label>
                                        <select name="country" class="form-control" id="">
                                            <option value="">-select country-</option>
                                            @foreach (get_country_lists() as $id => $name)
                                                <option @if (($user->country ?? '') == $id) selected @endif value="{{ $name }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="">Special Requirements</label>
                                    <textarea class="form-control" name="special" id="" placeholder="Special Requirements"></textarea>
                                </div>
                            </div>
                    </div>
                </div>
                <input type="text" hidden value="1" name="paymentmethod">
                {{-- <div class="card mt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Select Payment Method</label>
                                <select name="paymentmethod" id="" class="form-control">
                                    <option value="1">payment offline</option>
                                    <option value="2">payment online</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="mt-4">
                    <input required type="checkbox" name="" id="" placeholder="I have read and accept the terms and conditions"> I have read and accept the terms and conditions
                    <div class="mt-4">
                        <button class="btn btn-primary">submit</button>
                    </div>
                </div>
            </form>
            </div>
            <div class="col-lg-4">
                <h4>Your Booking</h4>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <h3>{{$data->vendor->vendor_name}}</h3>
                        <p><i class="fa fa-map-marker"></i> {{$data->vendor->country}}</p>
                        <img class="img-fluid" style="width:100px" src="{{$data->vendor->logo_img}}" alt="">
                        <hr>
                        <ul class="p-0">
                            <li class="d-flex justify-content-between"><span>Check in :</span> <span>{{$data->checkin_date}}</span></li>
                            <li class="d-flex justify-content-between"><span>Check out :</span> <span>{{$data->checkout_date}}</span></li>
                            <li class="d-flex justify-content-between"><span>Night :</span> <span>{{$data->night}}</span></li>
                            <li class="d-flex justify-content-between"><span>Person:</span> <span>{{$data->total_guests}}</span></li>
                        </ul>
                        <hr>
                        <ul class="p-0">
                            @foreach ($hotelbooking as $item)
                                <li class="d-flex justify-content-between"><span> {{Str::limit($item->room->ratedesc,15)}} * {{$item->total_room}}</span><span>Rp. {{ number_format($item->price, 0, ',', '.')}}</span> </li>
                            @endforeach
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <p style="font-size:20px" class="text-dark">Total</p>
                            <p style="font-size:20px" class="text-primary">Rp. {{ number_format($data->price, 0, ',', '.')}}</p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
@endsection
