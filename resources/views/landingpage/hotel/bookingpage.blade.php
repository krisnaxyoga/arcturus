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
    <form action="{{route('booking.agent.store',$data->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
    <div class="container">

        <div class="row">
            <div class="col-lg-8">
                <h4>Booking Submission</h4>
                <hr>

                <div class="card border-0 shadow mb-5" style="border-radius: 25px !important">
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
                                <div class="col-lg-12 mb-3">
                                    <label for="">Special Requirements</label>
                                    <textarea class="form-control" name="special" id="" placeholder="Special Requirements"></textarea>
                                </div>
                                <div class="col-lg-12">
                                    <input required type="checkbox" name="" id="" placeholder="I have read and accept the terms and conditions"> I have read and accept the terms and conditions

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
                    <input type="hidden" id="totalPrice" name="totalPrice" value="{{ $data->price }}">

                    <input type="hidden" id="totalPriceInput" name="totalPriceInput" value="">

                    <input type="hidden" id="PriceInputTransport" name="PriceInputTransport" value="">
                    <input type="hidden" id="idtransport" name="idtransport" value="">

                </div>
            </div>
            <div class="col-lg-4">
                <h4>Your Booking</h4>
                <hr>
                <div class="card border-0 shadow mb-3" style="border-radius: 25px !important">
                    <div class="card-body">
                        <h3>{{$data->vendor->vendor_name}}</h3>
                        <p><i class="fa fa-map-marker"></i> {{$data->vendor->country}}</p>
                        <img onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" onerror="this.onerror=null; this.src='https://semantic-ui.com/images/wireframe/white-image.png';" class="img-fluid" style="width:100px" src="{{$data->vendor->logo_img}}" alt="">
                        <hr>
                        <ul class="p-0">
                            <li class="d-flex justify-content-between"><span>Check in :</span> <span>{{date('m/d/Y', strtotime($data->checkin_date))}}</span></li>
                            <li class="d-flex justify-content-between"><span>Check out :</span> <span>{{date('m/d/Y', strtotime($data->checkout_date))}}</span></li>
                            <li class="d-flex justify-content-between"><span>Night :</span> <span>{{$data->night}}</span></li>
                            <li class="d-flex justify-content-between"><span>Person:</span> <span>{{$data->total_guests}}</span></li>
                        </ul>
                        <hr>
                        <ul class="p-0">
                            @foreach ($hotelbooking as $item)
                                <li class="d-flex justify-content-between"><span> {{Str::limit($item->room->ratedesc,15)}} * {{$item->total_room}}</span><span>Rp. {{ number_format($item->price, 0, ',', '.')}}</span> </li>
                            @endforeach
                            <div id="transportnota" style="display:none">
                                <li class="d-flex justify-content-between"><span>transport</span><span id="pricetransport"></span> </li>
                            </div>
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <p style="font-size:20px" class="text-dark">Total</p>
                            <p style="font-size:20px" class="text-primary" id="pricetotal">Rp. {{ number_format($data->price, 0, ',', '.')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            {{-- {{dd($transport)}} --}}
            @if($transport->count() > 0)
            <div class="mb-3">
                <h4>Transport</h4>
                <hr>
                <div>
                    <div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card mb-2 border-0 shadow" style="border-radius: 10px;width:auto">
                                    <div class="card-body">
                                            <div class="form-goup mb-1">
                                                <label for="destination">Destination</label>
                                                <select class="form-control" name="destination" id="destination" onchange="filterTransport()">
                                                    <option value="0">all</option>
                                                    @foreach ($destination as $des)
                                                    <option value="{{$des->id}}">{{$des->destination}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="transport-cards">
                                    <div class="row">
                                        @foreach ($transport as $item)
                                        @php
                                            $price = $item->price + $item->agenttransport->markup
                                        @endphp
                                        @if($item->agenttransport->status == 1)
                                        <div class="card mx-3 border-0 rounded shadow" data-destination="{{$item->destination}}">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mx-3">
                                                        <label class="switch">
                                                            <input type="checkbox" class="transport-checkbox" data-idtransport="{{ $item->id }}" data-price="{{ $price }}">
                                                            <span class="slider"></span>
                                                        </label>
                                                    </div>
                                                    <div class="mx-3">
                                                        <p class="m-0" style="font-weight: 700">{{$item->type_car}}</p>
                                                        <p class="transport-price m-0">Rp. {{ number_format($price, 0, ',', '.')}}</p>
                                                        <p class="m-0">{{$item->transportdestination->destination}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        {{-- {{$item->agenttransport->status}} --}}

                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div id="transportform" style="display:none">
                    <div class="row" >
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="">Time pick up <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="timepickup" value="<?php echo date('H:i'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="">Pick up date<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="datepickup" value="{{$data->checkin_date}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="">Flight <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="flight" value="">
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="mt-4">
                    <button type="submit" class="btn btn-primary">submit</button>
                </div>
            </div>
        </div>


    </div>
</form>
</section>
<script>
    function filterTransport() {
        const destinationId = document.getElementById('destination').value;
        const transportCards = document.querySelectorAll('.transport-cards .card');

        transportCards.forEach(function(card) {
            const cardDestination = card.getAttribute('data-destination');
            if (destinationId !== "0" && cardDestination !== destinationId) {
                card.style.display = 'none';
            } else {
                card.style.display = 'block';
            }
        });
    }
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.transport-checkbox');
    const totalPriceInput = document.getElementById('totalPrice');
    const totalPriceDisplay = document.getElementById('pricetotal');
    const totalPriceforInput = document.getElementById('totalPriceInput');
    const idtransportinput = document.getElementById('idtransport');
    const transportPriceforInput = document.getElementById('PriceInputTransport');
    const pricetransport = document.getElementById('pricetransport');
    const transportform = document.getElementById('transportform');
    const transportnota = document.getElementById('transportnota');

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let price = parseFloat(this.getAttribute('data-price'));
            let idtransport = parseFloat(this.getAttribute('data-idtransport'));
            let totalPrice = parseFloat(totalPriceInput.value);

            if (this.checked) {
                checkboxes.forEach(function(cb) {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
                transportform.style.display = 'block';
                transportnota.style.display = 'block';
                totalPrice += price;
            } else {
                transportform.style.display = 'none';
                transportnota.style.display = 'none';
                totalPrice = totalPrice;
            }
            totalPriceforInput.value = totalPrice.toFixed(0);
            idtransportinput.value = idtransport;
            transportPriceforInput.value = price;
            totalPriceDisplay.textContent = 'Rp. ' + numberWithCommas(totalPrice.toFixed(0));
            pricetransport.textContent = 'Rp. ' + numberWithCommas(price.toFixed(0));
        });
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});



    // document.addEventListener('DOMContentLoaded', function () {
    //     const checkboxes = document.querySelectorAll('.transport-checkbox');
    //     const totalPriceInput = document.getElementById('totalPrice');
    //     const totalPriceDisplay = document.getElementById('pricetotal');

    //     checkboxes.forEach(function(checkbox) {
    //         checkbox.addEventListener('change', function() {
    //             let price = parseFloat(this.getAttribute('data-price'));
    //             let totalPrice = parseFloat(totalPriceInput.value);

    //             if (this.checked) {
    //                 totalPrice += price;
    //             } else {
    //                 totalPrice -= price;
    //             }

    //             totalPriceInput.value = totalPrice.toFixed(2);
    //             totalPriceDisplay.textContent = 'Rp. ' + numberWithCommas(totalPrice.toFixed(0));
    //         });
    //     });

    //     function numberWithCommas(x) {
    //         return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //     }
    // });
</script>


<style>
    .transport-cards {
        overflow-x: auto; /* Memberikan scrolling horizontal jika konten melebihi lebar */
        white-space: nowrap; /* Mencegah pemisahan baris */
        padding-bottom: 1rem;
    }

    .transport-cards .row {
        display: flex; /* Menjadikan item flex */
        flex-wrap: nowrap; /* Mencegah wrapping ke baris baru */
        gap: 0px; /* Jarak antar kartu */
    }

    .transport-cards .card {
        flex: 0 0 auto; /* Memastikan kartu tidak mengecil secara otomatis */
        width: auto; /* Atur lebar kartu */

    border-radius: 15px !important;
    }

    /* The switch - the box around the slider */
.switch {
  font-size: 17px;
  position: relative;
  display: inline-block;
  width: 3.5em;
  height: 2em;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgb(182, 182, 182);
  transition: .4s;
  border-radius: 10px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 1.4em;
  width: 1.4em;
  border-radius: 8px;
  left: 0.3em;
  bottom: 0.3em;
  transform: rotate(270deg);
  background-color: rgb(255, 255, 255);
  transition: .4s;
}

.switch input:checked + .slider {
  background-color: #00a6fb;
}

.switch input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

.switch input:checked + .slider:before {
  transform: translateX(1.5em);
}
</style>

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
