@extends('layouts.admin')
@section('title', 'Home Page')
@section('content')
<section class="mt-3">
    <div class="container">
        <h1>Welcome Admin!</h1>
          <!-- Content Row -->
          <div class="row">

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    total revenue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. {{ number_format($totaltransaction, 0, ',', '.')}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Hotel</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hotel }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Agent</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $agent }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">total paid
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalbooking }}</div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">total unpaid
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $unpaid }}</div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                @php
                                $grandTotal = 0; // Buat variabel untuk menyimpan total
                                @endphp
                                
                                @foreach ($roomhotel as $item)
                                   
                                        @php
                                            $grandTotal += $item->night * $item->total_room; // Tambahkan ke grand total
                                        @endphp
                                @endforeach
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    total room night</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$grandTotal}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-home fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Vendor register</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>action</th>
                                        <th>status</th>
                                        {{-- <th>Busisness Name</th> --}}
                                        <th>Vendor Name</th>
                                        <th>Type Vendor</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Country</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendor as $key=>$item)
                                        <tr>
                                            <td>
                                                @if($item->is_active == 1)
                                                <a href="{{route('dashboard.agent.unactive',$item->id)}}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i data-feather="x"></i></a>
                                                @else
                                                    <a href="{{route('dashboard.agent.active',$item->id)}}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i data-feather="check"></i></a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->is_active == 1)
                                                    <span class="badge badge-success">active</span>
                                                @else
                                                    <span class="badge badge-danger">no active</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $item->vendor_legal_name }}</td> --}}
                                            <td>{{ $item->vendor_name}}</td>
                                            <td>
                                                @if($item->type_vendor == 'hotel')
                                                    <span class="badge badge-secondary">HOTEL</span>
                                                    {{-- <span class="badge badge-success">markup : Rp. {{number_format($item->system_markup ? $item->system_markup : '0', 0, ',', '.')}}</span>
                                                    <button type="button" class="btn btn-datatable btn-icon btn-transparent-dark mr-2" onclick="showMarkupInput({{$key}})">
                                                        <i data-feather="edit"></i>
                                                    </button>
                                                    <div id="markupInputContainer{{$key}}" style="display: none;">
                                                        <form action="{{ route('dashboard.agent.markup',$item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="mb-3">
                                                                <label for="markupInput{{$key}}">Mark-up</label>
                                                                <input type="text" id="markupInput{{$key}}" name="markup" value="{{$item->system_markup ? $item->system_markup : '0'}}" class="form-control">
                                                            </div>
                                                            <div>
                                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> save</button>
                                                            </div>
                                                        </form>
                                                    </div> --}}
                                                @else
                                                <span class="badge badge-warning">AGENT</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->country }}</td>
                                           
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Booking Status</h6>
                        
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Paid
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-danger"></i> Unpaid
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-secondary"></i> Proccessing
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-warning"></i> Cancelled
                            </span>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Pie Chart -->
             <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Booking</h6>
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>Hotel Name</th>
                                        <th>Agent Name</th>
                                        <th>Total Guest</th>
                                        <th>Total Room</th>
                                        <th>Night</th>
                                        <th>Price</th>
                                        <th>Booking Status</th>
                                        <th>Guest Name</th>
                                        <th>Guest Email</th>
                                        <th>Country</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($booking as $item)
                                        <tr>
                                            <td>{{ $item->vendor->vendor_name }}</td>
                                            <td>  @if ($item->users)
        {{ $item->users->first_name }} {{ $item->users->last_name }}
    @else
        N/A
    @endif</td>
                                            <td>{{ $item->total_guests }}</td>
                                            <td>{{ $item->total_room }}</td>
                                            <td>{{ $item->night }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->booking_status }}</td>
                                            <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->country }}</td>
                                           

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Metropolis"),
'-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
    type: "doughnut",
    data: {
        labels: ["paid", "unpaid", "processing","cancelled"],
        datasets: [{
            data: [{{$paid}}, {{$unpaid}}, {{$process}},{{$cancelled}}],
            backgroundColor: [
                "rgba(0, 97, 242, 1)",
                "rgba(243, 76, 79)",
                "rgba(88, 0, 232, 1)",
                "rgb(244, 161, 0)",
            ],
            hoverBackgroundColor: [
                "rgba(0, 97, 242, 0.9)",
                "rgba(249, 87, 86)",
                "rgba(88, 0, 232, 0.9)",
                "rgba(254, 195, 5)",
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)"
        }]
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10
        },
        legend: {
            display: false
        },
        cutoutPercentage: 80
    }
});


// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Metropolis"),
'-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + "").replace(",", "").replace(" ", "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }
    return s.join(dec);
}

</script>
<script>
    function showMarkupInput(key) {
        var container = document.getElementById('markupInputContainer' + key);
        if (container.style.display === 'none') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    }
</script>
@endsection
