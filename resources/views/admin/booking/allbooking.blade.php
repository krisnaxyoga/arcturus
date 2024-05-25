@extends('layouts.admin')
@section('title', 'Reports Page')
@section('content')
<section class="mt-3">
    <div class="container">
        <h1>TRANSFER BANK</h1>
          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        TRANSFER BANK CONFIRMATION
                    </div>
                    <div class="card-body">
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
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>confirmation</th>
                                        <th>Booking Status</th>
                                        <th>payment type</th>
                                        <th>Hotel Name</th>
                                        <th>Agent Name</th>
                                        <th>Guest Name</th>
                                        <th>Total Guest</th>
                                        <th>Price</th>
                                        <th>Guest Email</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key=>$item)
                                        <tr>
                                            <td>
                                                @if($item->booking_status != 'paid')
                                                <span class="badge badge-danger">
                                                    <i class="fa fa-clock"></i>
                                                </span>
                                                    @else
                                                    <ul>
                                                        <li style="list-style: none;">
                                                            <span class="btn-icon btn-transparent-dark mr-2">
                                                                <i data-feather="check"></i>
                                                            </span>
                                                        </li>
                                                        <li style="list-style: none;">
                                                            <button type="button" class="badge badge-warning border-0" data-toggle="modal" data-target="#sendEmailModal">
                                                                Sent email all
                                                            </button>
                                                        </li>
                                                        <li style="list-style: none;">
                                                            <button type="button" class="badge badge-primary border-0" data-toggle="modal" data-target="#sendEmailToAgentModal">
                                                                Sent email to agent
                                                            </button>
                                                        </li>
                                                        <li style="list-style: none;">
                                                            <button type="button" class="badge badge-secondary border-0" data-toggle="modal" data-target="#sendEmailToHotelModal">
                                                                Sent email to hotel
                                                            </button>
                                                        </li>
                                                    </ul>

                                                    <!-- Modal for sending email to all -->
                                                    <div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="sendEmailModalLabel">Sent Email to All</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to sent an email to all?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form class="d-inline" action="{{ route('admin.bookingall.confirmation', $item->id) }}" method="GET">
                                                                        @csrf
                                                                        @method('get')
                                                                        <button type="submit" class="btn btn-warning">Sent Email</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal for sending email to agent -->
                                                    <div class="modal fade" id="sendEmailToAgentModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailToAgentModalLabel" aria-hidden="true">
                                                        <!-- Include the same modal structure as above, just change the IDs, titles, and action URL accordingly -->
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="sendEmailModalLabel">Sent Email to Agent</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to sent an email to Agent?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form class="d-inline" action="{{ route('admin.bookingall.sendconfirmationtoagent',$item->id) }}" method="GET">
                                                                        @csrf
                                                                        @method('get')
                                                                        <button type="submit" class="btn btn-warning">Sent Email</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal for sending email to hotel -->
                                                    <div class="modal fade" id="sendEmailToHotelModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailToHotelModalLabel" aria-hidden="true">
                                                        <!-- Include the same modal structure as above, just change the IDs, titles, and action URL accordingly -->
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="sendEmailModalLabel">Sent Email to Hotel</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to sent an email to Hotel?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form class="d-inline" action="{{ route('admin.bookingall.sendconfirmationtohotel',$item->id) }}" method="GET">
                                                                        @csrf
                                                                        @method('get')
                                                                        <button type="submit" class="btn btn-warning">Sent Email</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script>
                                                        // Enable Bootstrap modal functionality
                                                        $(document).ready(function() {
                                                            $('#sendEmailModal').modal({
                                                                backdrop: 'static',
                                                                keyboard: false,
                                                                show: false
                                                            });

                                                            $('#sendEmailToAgentModal').modal({
                                                                backdrop: 'static',
                                                                keyboard: false,
                                                                show: false
                                                            });

                                                            $('#sendEmailToHotelModal').modal({
                                                                backdrop: 'static',
                                                                keyboard: false,
                                                                show: false
                                                            });
                                                        });
                                                    </script>

                                                @endif

                                            </td>
                                            <td> @if($item->booking_status != 'paid')
                                                <span class="badge badge-warning">{{ $item->booking_status }}</span>
                                                @else
                                                <span class="badge badge-success">{{ $item->booking_status }}</span>
                                                @endif
                                                </td>
                                            <td>
                                                @if($item->payment_method == 1)
                                                    <span class="badge badge-secondary">transfer bank</span>
                                                    @else
                                                    <span class="badge badge-secondary">Wallet</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->vendor->vendor_name }}</td>
                                            <td>{{ $item->users->first_name }} {{ $item->users->last_name }}</td>
                                            <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                            <td>{{ $item->total_guests }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->email }}</td>
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
