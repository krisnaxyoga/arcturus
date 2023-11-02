@extends('layouts.admin')
@section('title', 'Reports Page')
@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>


<section class="mt-3">
    <div class="container">
        <h1>Reports</h1>
          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <form action="{{route('dashboard.report')}}" class="d-flex" method="get">
                                <input type="date" name="startdate" id="startdate" class="form-control mr-2" value="{{$startdate}}">
                                <input type="date" name="enddate" id="enddate" class="form-control mr-2" value="{{$enddate}}">
                                <button class="btn btn-primary mr-2" type="submit">filter</button>
                            </form>
                            <div class="buttons-excel">
                                <button class="dt-button btn btn-success">Excel</button>
                            </div>
                            
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
                                        <th>Total Room Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item->booking->vendor->vendor_name }}</td>
                                            <td>{{ $item->booking->first_name }} {{ $item->booking->last_name }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->booking->booking_date)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->checkin_date)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->checkout_date)) }}</td>
                                            <td>{{ $item->booking->total_guests }}</td>
                                            <td>{{ $item->total_room }}</td>
                                            <td>{{ $item->booking->night }}</td>
                                            <td>
                                                {{ ($item->booking->pricenomarkup / $item->booking->night)/$item->booking->total_room }}
                                            </td>
                                            <td> @if (is_null($item->total_ammount))
                                                    {{ $item->booking->pricenomarkup }}
                                                @else
                                                    {{ $item->total_ammount }}
                                                @endif
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
        var columnIndex = 9; // Ganti dengan indeks kolom yang sesuai (dimulai dari 0)
        var totalPricenomarkup = 0;

        filteredData.forEach(function (rowData) {
            var pricenomarkup = parseFloat(rowData[columnIndex]);
            totalPricenomarkup += pricenomarkup;
        });

        // Tambahkan baris total
        excelData.push(['', '', '', '', '','','','', 'Total', totalPricenomarkup]);

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