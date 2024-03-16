@extends('affiliate.layout.app')
@section('title','Hotel')
@section('content')
<section>
    <div class="container mt-4">
        <h1>
            @yield('title')
        </h1>
        <div class="row">
            <div class="col-lg-6">
                @foreach ($vendoraffiliate as $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <span>
                                <h3 class="m-0">{{$item->vendors->vendor_name}}</h3>
                                <hr>
                                <p class="m-0">{{$item->vendors->email}}</p>
                                <p class="m-0">{{$item->vendors->phone}}</p>
                                <p class="m-0">{{$item->vendors->address_line1}}</p>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection