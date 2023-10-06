@extends('layouts.admin')
@section('title', 'Reports Page')
@section('content')
<section class="mt-3">
    <div class="container">
        <h1>Reports</h1>
          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
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
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item->vendor->vendor_name }}</td>
                                            <td>{{ $item->users->first_name }} {{ $item->users->last_name }}</td>
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
@endsection