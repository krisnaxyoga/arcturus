@extends('affiliate.layout.app')
@section('title', 'Reports Page')
@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>



<section class="mt-3">
    <div class="container">
        <h1>Reports</h1>
          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header">
                        Stay
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <form action="{{route('auth.affiliatorreport.index',['code'=>$code,'id'=>$id])}}" class="d-flex" method="get">
                                <select name="hotel" id="" class="form-control mr-2">
                                    <option value="">-select hotel-</option>
                                    @foreach ($hotels as $itemhotel)
                                    <option value="{{$itemhotel->vendor_name}}" @if($hotel_select == $itemhotel->vendor_name) selected @endif>{{$itemhotel->vendor_name}}</option>
                                    @endforeach
                                </select>
                                <input type="date" name="startdate" id="startdate" class="form-control mr-2" value="{{$startdate}}">
                                <input type="date" name="enddate" id="enddate" class="form-control mr-2" value="{{$enddate}}">
                                <button class="btn btn-primary mr-2" type="submit">filter</button>
                            </form>
                            <div class="buttons-excel mr-2">
                                <button class="dt-button btn btn-success">Excel</button>
                            </div>
                            <form action="{{route('auth.affiliatorreport.adminpdfreport',['code'=>$code,'id'=>$id])}}" method="get">
                                <input type="date" hidden value="{{$startdate}}" name="star_tdate">
                                <input type="date" hidden value="{{$enddate}}" name="end_date">
                                <input type="text" hidden value="{{$hotel_select}}" name="hotelselect">
                                <button class="btn btn-secondary">
                                    Pdf
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                       Made on
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <form action="{{route('auth.affiliatorreport.madeon',['code'=>$code,'id'=>$id])}}" class="d-flex" method="get">
                                <select name="hotel" id="" class="form-control mr-2">
                                    <option value="">-select hotel-</option>
                                    @foreach ($hotels as $itemhotel)
                                    <option value="{{$itemhotel->vendor_name}}" @if($hotel_select1 == $itemhotel->vendor_name) selected @endif>{{$itemhotel->vendor_name}}</option>
                                    @endforeach
                                </select>
                                <input type="date" name="startdate" id="startdate" class="form-control mr-2" value="{{$startdate1}}">
                                <input type="date" name="enddate" id="enddate" class="form-control mr-2" value="{{$enddate1}}">
                                <button class="btn btn-primary mr-2" type="submit">filter</button>
                            </form>
                            <div class="buttons-excel mr-2">
                                <button class="dt-button btn btn-success">Excel</button>
                            </div>
                            <form action="{{route('auth.affiliatorreport.madeonpdfreport',['code'=>$code,'id'=>$id])}}" method="get">
                                <input type="date" hidden value="{{$startdate1}}" name="star_tdate">
                                <input type="date" hidden value="{{$enddate1}}" name="end_date">
                                <input type="text" hidden value="{{$hotel_select1}}" name="hotelselect">
                                <button class="btn btn-secondary">
                                    Pdf
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        BOOKING REPORT
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>Hotel Name</th>
                                        <th>Guest Name</th>
                                        <th>Booking Date</th>
                                        <th>Check in</th>
                                        <th>Check out</th>
                                        <th>Total Guest</th>
                                        <th>Total Room</th>
                                        <th>Night</th>
                                        <th>Rate</th>
                                        <th>Total Room Revenue (contract)</th>
                                        <th>Total Room Revenue (selling)</th>
                                        <th>Commision</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            
                                            <td>{{ $item->vendor->vendor_name }}</td>
                                            <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->booking_date)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->checkin_date)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->checkout_date)) }}</td>
                                            <td>{{ $item->total_guests }}</td>
                                            <td>{{ $item->total_room }}</td>
                                            <td>{{ $item->night }}</td>
                                            <td>
                                                {{-- {{ ($item->pricenomarkup / $item->night)/$item->total_room }} --}}
                                                {{ ($item->total_room != 0) ? ($item->pricenomarkup / $item->night) / $item->total_room : 'Nilai Tidak Tersedia' }}

                                            </td>
                                            <td> 
                                                @if (is_null($item->pricenomarkup))
                                                    {{ $item->pricenomarkup }}
                                                @else
                                                    {{ $item->pricenomarkup }}
                                                @endif
                                            </td>
                                            <td>
                                                {{$item->price}}
                                            </td>
                                            <td> 
                                            @if (is_null($item->vendor->system_markup))
                                                {{ ($item->price - $item->pricenomarkup ) * 0.15 }}
                                            @else
                                                {{ ($item->price - $item->pricenomarkup  ) * 0.15 }}
                                            @endif
                                        </td>
                                        <td>
                                            {{$item->booking_status}}
                                        </td>

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
// Inisialisasi DataTables dan definisikan variabel table
$(document).ready(function () {
    var table = $('#dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
        ]
    });

    // Handle ekspor Excel
    $('.buttons-excel button').on('click', function () {
        // Export data yang telah difilter
        var filteredData = table.rows({ search: 'applied' }).data().toArray();
        var headerData = table.columns().header().toArray();

        var excelData = [headerData.map(function (th) { return th.textContent; })];
        excelData = excelData.concat(filteredData);

        // Hitung total dari kolom 'pricenomarkup'
        var columnIndex = 10; // Ganti dengan indeks kolom yang sesuai (dimulai dari 0)
        var totalPricenomarkup = 0;

        filteredData.forEach(function (rowData) {
            var pricenomarkup = parseFloat(rowData[columnIndex]);
            totalPricenomarkup += pricenomarkup;
        });

        // Tambahkan baris total
        excelData.push(['', '', '', '', '','','','','', 'Total', totalPricenomarkup]);

        var ws = XLSX.utils.aoa_to_sheet(excelData);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

        // Mengunduh file Excel
        XLSX.writeFile(wb, 'exported_data.xlsx');
    });
});

  </script>

<script>
    // Mengatur event listener untuk input startdate
    document.getElementById('startdate').addEventListener('change', function () {
        var startDateValue = this.value;
        if (startDateValue) {
            // Ubah startdate menjadi objek Date
            var startDate = new Date(startDateValue);

            // Tambahkan 1 hari ke startdate
            startDate.setDate(startDate.getDate() + 1);

            // Format tanggal dan isi input enddate
            var endDate = startDate.toISOString().slice(0, 10);
            document.getElementById('enddate').value = endDate;
        }
    });
</script>
@endsection
